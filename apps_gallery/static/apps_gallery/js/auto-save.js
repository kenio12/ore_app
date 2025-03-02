// ==================== 自動保存機能 ====================
// 自動保存機能のJavaScriptコード

// グローバル変数（appState）を定義
let appState = {
    formChanged: false,      // フォーム変更フラグ (明示的にfalseに設定)
    initialData: null,       // 初期データ（比較用）
    saveTimer: null,         // 保存タイマーID
    initialLoad: true,       // 初期ロードフラグ
    lastSaveTime: null,      // 最終保存時間
    saveInProgress: false,   // 保存処理中フラグ
    retryCount: 0,           // リトライカウンター
    errorState: false        // エラー状態
};

// デバッグ用のログ出力
console.log('auto-save.jsの読み込み完了 - 初期状態:', JSON.stringify(appState));

// デバッグ関数：フラグの変更を追跡
function logFormChangedState(source, value) {
    console.log(`[DEBUG] formChanged が ${source} によって ${value} に設定されました`);
    console.trace(); // スタックトレースを出力
}

// フォームデータをシリアライズする関数
function serializeForm() {
    const form = document.getElementById('appForm');
    if (!form) return {};
    
    const formData = new FormData(form);
    const serialized = {};
    
    for (let [key, value] of formData.entries()) {
        if (value instanceof File) {
            serialized[key] = value.name || 'file-selected';
        } else {
            serialized[key] = value;
        }
    }
    
    return serialized;
}

// 変更検知のリスナーを設定
function setupChangeListeners() {
    const formElements = document.querySelectorAll(
        '#appForm input:not([type=hidden]), #appForm textarea, #appForm select'
    );
    
    formElements.forEach(element => {
        element.addEventListener('change', handleFormChange);
        element.addEventListener('input', handleFormChange);
    });
    
    console.log(`${formElements.length}個の入力フィールドに変更リスナーを設定しました`);
}

// フォームの変更を処理する関数
function handleFormChange() {
    if (!appState.formChanged) {
        appState.formChanged = true;
        logFormChangedState('handleFormChange', true);
        
        // 保存状態表示を更新
        updateSaveStatus('unsaved', '未保存の変更があります');
    }
    
    // 自動保存をスケジュール
    scheduleAutoSave();
}

// 自動保存をスケジュールする関数
function scheduleAutoSave() {
    // 既存のタイマーをクリア
    if (appState.saveTimer) {
        clearTimeout(appState.saveTimer);
    }
    
    // 新しいタイマーを設定
    appState.saveTimer = setTimeout(performAutoSave, 3000);
}

// 自動保存を実行する関数
function performAutoSave() {
    // 保存が必要ない、または保存中なら何もしない
    if (!appState.formChanged || appState.saveInProgress) {
        return;
    }
    
    // フォーム要素を取得
    const form = document.getElementById('appForm');
    if (!form) {
        console.error('フォームが見つかりません');
        return;
    }
    
    // 読み取り専用モードではなく、data-readonlyが"false"の場合のみ自動保存を有効化
    const isReadOnly = form.getAttribute('data-readonly') === "true";
    if (isReadOnly) {
        console.log('読み取り専用モードのため自動保存は無効です');
        return;
    }
    
    // 保存中フラグを設定
    appState.saveInProgress = true;
    
    // 保存状態表示を更新
    updateSaveStatus('saving', '保存中...');
    
    // フォームデータを取得
    const formData = new FormData(form);
    
    // アプリIDを取得
    const appId = form.getAttribute('data-app-id') || '';
    
    // 自動保存フラグを追加
    formData.append('is_auto_save', 'true');
    
    // 保存先URLを構築
    const saveUrl = appId ? 
        `/apps_gallery/auto-save/${appId}/` : 
        '/apps_gallery/auto-save/';
    
    // Fetch APIで保存リクエスト
    fetch(saveUrl, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Auto-Save': 'true'
        }
    })
    .then(response => response.json())
    .then(data => {
        appState.saveInProgress = false;
        
        if (data.success) {
            // 保存成功
            appState.formChanged = false;
            logFormChangedState('performAutoSave-success', false);
            appState.lastSaveTime = new Date();
            appState.retryCount = 0;
            
            // アプリIDがない場合は設定（新規作成の場合）
            if (!appId && data.app_id) {
                // アプリIDをフォームに設定
                form.setAttribute('data-app-id', data.app_id);
                
                // hidden入力にもアプリIDを設定
                const appIdInput = document.createElement('input');
                appIdInput.type = 'hidden';
                appIdInput.name = 'app_id';
                appIdInput.value = data.app_id;
                form.appendChild(appIdInput);
                
                // URLがcreateの場合は、editに変更する
                if (window.location.pathname.includes('/create/')) {
                    // 現在のURLを取得
                    const currentPath = window.location.pathname;
                    // createをedit/{app_id}/に置き換え
                    const newPath = currentPath.replace('/create/', `/edit/${data.app_id}/`);
                    // URLを更新（履歴を置き換え）
                    window.history.replaceState({}, '', newPath);
                    console.log(`URLを更新しました: ${newPath}`);
                }
                
                // 新規アプリが作成された場合は特別なメッセージを表示
                updateSaveStatus('success', `新規アプリID: ${data.app_id}で保存しました`);
                
                // トーストでも通知
                showToastMessage(`新規アプリID: ${data.app_id}で保存しました`);
            } else {
                // 既存アプリの更新の場合はアプリIDを含むメッセージ
                const currentAppId = form.getAttribute('data-app-id') || appId;
                updateSaveStatus('success', `アプリID: ${currentAppId}を上書き保存しました`);
                
                // トーストでも通知
                showToastMessage(`アプリID: ${currentAppId}を上書き保存しました`);
            }
        } else {
            // 保存失敗
            updateSaveStatus('error', data.error || '保存に失敗しました');
            
            // 緊急措置：エラーが続くと画面遷移できなくなるため、変更フラグをリセット
            if (appState.retryCount >= 2) {
                console.warn('自動保存に繰り返し失敗しているため、変更フラグをリセットします');
                appState.formChanged = false;
                logFormChangedState('performAutoSave-errorReset', false);
            }
            
            // 再試行
            retryAutoSave();
        }
    })
    .catch(error => {
        console.error('自動保存エラー:', error);
        appState.saveInProgress = false;
        
        // 保存状態表示を更新
        updateSaveStatus('error', 'ネットワークエラー');
        
        // 緊急措置：エラーが続くと画面遷移できなくなるため、変更フラグをリセット
        if (appState.retryCount >= 2) {
            console.warn('自動保存に繰り返し失敗しているため、変更フラグをリセットします');
            appState.formChanged = false;
            logFormChangedState('performAutoSave-catchError', false);
        }
        
        // 再試行
        retryAutoSave();
    });
}

// 自動保存の再試行
function retryAutoSave() {
    appState.retryCount++;
    
    // 最大3回まで再試行
    if (appState.retryCount <= 3) {
        const retryDelay = appState.retryCount * 5000; // 再試行間隔を徐々に増やす
        console.log(`${appState.retryCount}回目のリトライを${retryDelay / 1000}秒後に実行します`);
        
        setTimeout(() => {
            if (appState.formChanged) {
                performAutoSave();
            }
        }, retryDelay);
    }
}

// 保存状態表示を更新する関数
function updateSaveStatus(status, message) {
    // ステータス表示要素を取得
    let container = document.getElementById('saveStatusContainer');
    
    if (container) {
        // 「準備完了」メッセージは表示しない（指示に従い）
        if (status === 'ready') {
            return;
        }
        
        // メッセージを更新
        const saveMsg = document.getElementById('saveStatusMsg');
        if (saveMsg) {
            saveMsg.textContent = message;
        }
        
        // タイムスタンプを更新
        if (status === 'success' && appState.lastSaveTime) {
            const timestamp = document.getElementById('saveTimestamp');
            if (timestamp) {
                timestamp.textContent = `最終保存: ${appState.lastSaveTime.toLocaleTimeString()}`;
            }
        }
        
        // ステータスに応じてクラスを設定
        container.className = `save-status-container ${status}`;
        
        // 成功・エラー時は一定時間後に表示を消す
        if (status === 'success' || status === 'error') {
            setTimeout(() => {
                container.classList.add('fade-out');
            }, 5000);
        } else {
            container.classList.remove('fade-out');
        }
    }
}

// フォームの変更フラグをリセットする関数（外部から呼び出し可能）
function resetFormDirty() {
    console.log('フォーム変更フラグをリセットします');
    
    // 変更フラグをリセット
    appState.formChanged = false;
    logFormChangedState('resetFormDirty', false);
    
    // 保存状態表示を更新
    updateSaveStatus('success', '保存済み');
    
    return true;
}

// トーストメッセージを表示する関数
function showToastMessage(message) {
    // すでに存在する場合は削除
    let existingToast = document.getElementById('autoSaveToast');
    if (existingToast) {
        existingToast.remove();
    }
    
    // アクティブなタブを検出し、対応する色を取得
    const activeTab = document.querySelector('.nav-link.active');
    let tabColor = '#00ff9f'; // デフォルトはサイバーグリーン
    
    // アクティブなタブのIDに基づいて色を設定
    if (activeTab) {
        const tabId = activeTab.id;
        
        // タブIDから対応するCSS変数を特定
        if (tabId === 'screenshots-tab') {
            tabColor = getComputedStyle(document.documentElement).getPropertyValue('--cyber-purple').trim();
        } else if (tabId === 'basic-tab') {
            tabColor = getComputedStyle(document.documentElement).getPropertyValue('--cyber-gold').trim();
        } else if (tabId === 'appeal-tab') {
            tabColor = getComputedStyle(document.documentElement).getPropertyValue('--cyber-green').trim();
        } else if (tabId === 'hardware-tab') {
            tabColor = getComputedStyle(document.documentElement).getPropertyValue('--cyber-blue').trim();
        } else if (tabId === 'dev-env-tab') {
            tabColor = getComputedStyle(document.documentElement).getPropertyValue('--cyber-orange').trim();
        } else if (tabId === 'architecture-tab') {
            tabColor = getComputedStyle(document.documentElement).getPropertyValue('--cyber-lime').trim();
        } else if (tabId === 'backend-tab') {
            tabColor = getComputedStyle(document.documentElement).getPropertyValue('--cyber-aqua').trim();
        } else if (tabId === 'frontend-tab') {
            tabColor = getComputedStyle(document.documentElement).getPropertyValue('--cyber-pink').trim();
        } else if (tabId === 'database-tab') {
            tabColor = getComputedStyle(document.documentElement).getPropertyValue('--cyber-emerald').trim();
        } else if (tabId === 'security-tab') {
            tabColor = getComputedStyle(document.documentElement).getPropertyValue('--cyber-red').trim();
        } else if (tabId === 'development-tab') {
            tabColor = getComputedStyle(document.documentElement).getPropertyValue('--cyber-yellow').trim();
        }
        
        // 色が取得できなかった場合のフォールバック
        if (!tabColor || tabColor === '') {
            tabColor = '#00ff9f'; // デフォルト色
        }
    }
    
    // トースト要素を作成
    const toastEl = document.createElement('div');
    toastEl.id = 'autoSaveToast';
    toastEl.className = 'toast position-fixed bottom-0 end-0 m-3';
    toastEl.setAttribute('role', 'alert');
    toastEl.setAttribute('aria-live', 'assertive');
    toastEl.setAttribute('aria-atomic', 'true');
    
    // サイバーパンクスタイルのCSS - 動的に色を反映
    toastEl.style.backgroundColor = 'rgba(18, 18, 24, 0.95)';
    toastEl.style.border = `2px solid ${tabColor}`;
    toastEl.style.boxShadow = `0 0 15px ${tabColor}80`; // 50%の透明度を追加
    
    // トースト内容を設定 - 動的に色を反映
    toastEl.innerHTML = `
        <div class="toast-header" style="background-color: rgba(0, 20, 50, 0.9); color: ${tabColor}; border-bottom: 1px solid ${tabColor};">
            <strong class="me-auto" style="color: ${tabColor}; text-shadow: 0 0 5px ${tabColor};">自動保存</strong>
            <small style="color: ${tabColor};">たった今</small>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="閉じる"></button>
        </div>
        <div class="toast-body" style="color: ${tabColor}; text-shadow: 0 0 5px ${tabColor}80;">${message}</div>
    `;
    
    // bodyに追加
    document.body.appendChild(toastEl);
    
    // Bootstrapトーストの初期化
    if (typeof bootstrap !== 'undefined') {
        const toast = new bootstrap.Toast(toastEl, {
            animation: true,
            autohide: true,
            delay: 3000
        });
        
        // トーストを表示
        toast.show();
        
        // 表示後に削除
        toastEl.addEventListener('hidden.bs.toast', function() {
            toastEl.remove();
        });
    }
}

// CSRFトークンを取得する関数
function getCookie(name) {
    let cookieValue = null;
    if (document.cookie && document.cookie !== '') {
        const cookies = document.cookie.split(';');
        for (let i = 0; i < cookies.length; i++) {
            const cookie = cookies[i].trim();
            if (cookie.substring(0, name.length + 1) === (name + '=')) {
                cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                break;
            }
        }
    }
    return cookieValue;
}

// ホームボタンクリック時の処理
function handleHomeClick(event) {
    console.log('[DEBUG] handleHomeClick が呼び出されました', event ? 'イベントあり' : 'イベントなし');
    
    // イベントがない場合は処理しない（自動的な呼び出しを防止）
    if (!event) {
        console.warn('イベントなしでhandleHomeClickが呼び出されました');
        return;
    }
    
    // イベントの発生源をログ出力
    if (event.target) {
        console.log('[DEBUG] イベント発生元:', event.target.id, event.target.tagName);
    }
    
    event.preventDefault();
    
    // 自動保存が有効なので、直接ホームに遷移する
    console.log('[DEBUG] 自動保存機能があるため、直接ホームへ遷移します');
    window.location.href = '/';
}

// ダイアログを閉じる処理
function closeHomeDialog() {
    console.log('[DEBUG] closeHomeDialog が呼び出されました');
    const homeDialog = document.getElementById('homeDialog');
    if (homeDialog) {
        homeDialog.style.display = 'none';
    }
}

// ホームへ移動用の新しい保存関数
function saveAndGoHome() {
    const form = document.getElementById('appForm');
    if (form) {
        // FormDataオブジェクトを作成
        const formData = new FormData(form);
        
        // Fetch APIでPOSTリクエストを送信
        fetch(form.action + '?redirect=home', {  // URLにパラメータを追加
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRFToken': getCookie('csrftoken')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // 保存成功後にホームページへリダイレクト
                window.location.href = '/';
            } else {
                alert('保存に失敗しました：' + (data.error || '不明なエラー'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('保存中にエラーが発生しました');
        });
    } else {
        console.error('Form not found');
        alert('フォームが見つかりません');
    }
}

// 自動保存機能を初期化
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoadedイベント発生');
    
    // 変更フラグを強制的にリセット
    appState.formChanged = false;
    logFormChangedState('DOMContentLoaded', false);
    
    // appStateの状態を確認
    console.log('appState初期状態:', JSON.stringify(appState));
    
    // ホームダイアログを完全に削除（不要になったため）
    const homeDialog = document.getElementById('homeDialog');
    if (homeDialog) {
        homeDialog.parentNode.removeChild(homeDialog);
        console.log('ホームダイアログを完全に削除しました');
    }

    // ホームボタンのイベントリスナーを設定
    const homeButton = document.getElementById('homeButton');
    if (homeButton) {
        // 既存のイベントリスナーをすべて削除（重複防止）
        homeButton.removeEventListener('click', handleHomeClick);
        // 新しいイベントリスナーを追加
        homeButton.addEventListener('click', handleHomeClick);
        console.log('ホームボタンのイベントリスナーを設定');
    } else {
        console.warn('[DEBUG] ホームボタンが見つかりません');
    }
    
    const form = document.getElementById('appForm');
    if (form) {
        console.log('[DEBUG] フォームが見つかりました', {
            action: form.action,
            method: form.method,
            readonly: form.getAttribute('data-readonly')
        });
        
        // 読み取り専用モードでは自動保存を初期化しない
        const isReadOnly = form.getAttribute('data-readonly') === "true";
        if (isReadOnly) {
            console.log('読み取り専用モードのため自動保存は無効です');
            return;
        }

        // URLチェック - アプリIDがあるのにURLがcreateの場合は修正する
        const appId = form.getAttribute('data-app-id');
        if (appId && window.location.pathname.includes('/create/')) {
            // 現在のURLを取得
            const currentPath = window.location.pathname;
            // createをedit/{app_id}/に置き換え
            const newPath = currentPath.replace('/create/', `/edit/${appId}/`);
            // URLを更新（履歴を置き換え）
            window.history.replaceState({}, '', newPath);
            console.log(`ページロード時にURLを更新しました: ${newPath}`);
        }

        // 初期データを保存
        appState.initialData = serializeForm();
        
        // 変更リスナーを設定
        setupChangeListeners();
        
        // フォームにresetDirty関数を追加
        form.resetDirty = resetFormDirty;
        
        console.log('自動保存機能が初期化されました');
    } else {
        console.warn('[DEBUG] フォームが見つかりません');
    }

    // その他の初期化処理（トーストメッセージなど）
    const toastEl = document.getElementById('liveToast');
    if (toastEl) {
        // トーストのオプションを設定
        const toastOptions = {
            animation: true,
            autohide: true,  // 自動で非表示
            delay: 2000      // 2秒後に消える
        };
        
        // Bootstrapトーストの初期化
        if (typeof bootstrap !== 'undefined') {
            const toast = new bootstrap.Toast(toastEl, toastOptions);
            
            // 成功メッセージのパラメータがURLにある場合のみトーストを表示
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success')) {
                // トーストを表示
                toast.show();
                
                // URLからsuccess=trueパラメータを削除
                window.history.replaceState({}, '', window.location.pathname);
                
                // 2秒後に強制的に非表示
                setTimeout(() => {
                    toast.hide();
                    // DOMからトースト要素を完全に削除
                    toastEl.remove();
                }, 2000);
            }
        }
    }

    // ページロード時にキャッシュをクリア
    if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
        window.location.reload(true);  // 強制的にページを再読み込み
    }
});

// バックフォワードキャッシュ対策を強化
window.addEventListener('pageshow', function(event) {
    console.log('pageshowイベント発生');
    
    // 変更フラグを強制的にリセット
    appState.formChanged = false;
    logFormChangedState('pageshow', false);
    console.log('pageshow時に変更フラグをリセット:', JSON.stringify(appState));
    
    // 強制的な対策：ホームダイアログを完全に削除して再作成
    const oldDialog = document.getElementById('homeDialog');
    if (oldDialog) {
        console.log('[DEBUG] ホームダイアログを完全に削除します');
        oldDialog.parentNode.removeChild(oldDialog);
    }

    // さらに徹底的にリセット
    setTimeout(() => {
        console.log('[DEBUG] 遅延チェック - appState:', JSON.stringify(appState));
        if (appState.formChanged) {
            console.warn('[DEBUG] 遅延チェックで formChanged が true になっていたのでリセットします');
            appState.formChanged = false;
            logFormChangedState('pageshow-timeout', false);
        }
        
        // 念のため、再度ダイアログをチェック
        const dialogAfterTimeout = document.getElementById('homeDialog');
        if (dialogAfterTimeout && dialogAfterTimeout.style.display !== 'none') {
            console.warn('[DEBUG] 遅延チェックでダイアログが表示されていたので削除します');
            dialogAfterTimeout.parentNode.removeChild(dialogAfterTimeout);
        }
    }, 500);

    if (event.persisted || 
        (window.performance && 
         window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD)) {
        window.location.reload(true);  // キャッシュを無視して再読み込み
    }
});

// ダイアログを再作成する関数（必要なときだけ呼び出す）
function recreateHomeDialog() {
    // 既存のダイアログを確認
    let homeDialog = document.getElementById('homeDialog');
    
    // ダイアログがなければ作成
    if (!homeDialog) {
        console.log('[DEBUG] ホームダイアログを作成します');
        homeDialog = document.createElement('div');
        homeDialog.id = 'homeDialog';
        homeDialog.style.display = 'none';
        
        // ダイアログの内容を作成
        homeDialog.innerHTML = `
            <div class="home-dialog-content">
                <h4>変更が保存されていません</h4>
                <p>保存せずにホームに戻りますか？未保存の変更は失われます。</p>
                <div class="home-dialog-buttons">
                    <button id="cancelHomeButton" class="btn btn-secondary">キャンセル</button>
                    <button id="discardButton" class="btn btn-danger">保存せずに戻る</button>
                    <button id="saveAndHomeButton" class="btn btn-primary">保存して戻る</button>
                </div>
            </div>
        `;
        
        // bodyに追加
        document.body.appendChild(homeDialog);
        
        // イベントリスナーを設定
        homeDialog.querySelector('#cancelHomeButton').addEventListener('click', closeHomeDialog);
        homeDialog.querySelector('#discardButton').addEventListener('click', function() {
            window.location.href = '/';
        });
        homeDialog.querySelector('#saveAndHomeButton').addEventListener('click', saveAndGoHome);
    }
    
    return homeDialog;
} 