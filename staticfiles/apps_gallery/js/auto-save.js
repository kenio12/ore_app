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

// 各タブのフォームからJSONデータを収集してメインフォームに追加する関数
function collectTabsFormData(formData) {
    console.log('タブフォームデータの収集を開始します');
    
    // 開発環境タブのフォームデータ収集
    const devEnvData = collectDevEnvFormData();
    if (devEnvData) {
        formData.append('development_environment', JSON.stringify(devEnvData));
        console.log('開発環境データを追加しました', devEnvData);
    }
    
    // バックエンドタブのフォームデータ収集
    const backendData = collectBackendFormData();
    if (backendData) {
        formData.append('backend', JSON.stringify(backendData));
        console.log('バックエンドデータを追加しました', backendData);
    }
    
    // フロントエンドタブのフォームデータ収集
    const frontendData = collectFrontendFormData();
    if (frontendData) {
        formData.append('frontend', JSON.stringify(frontendData));
        console.log('フロントエンドデータを追加しました', frontendData);
    }
    
    // ホスティングタブのフォームデータ収集
    const hostingData = collectHostingFormData();
    if (hostingData) {
        formData.append('hosting', JSON.stringify(hostingData));
        console.log('ホスティングデータを追加しました', hostingData);
    }
    
    // データベースタブのフォームデータ収集
    const databaseData = collectDatabaseFormData();
    if (databaseData) {
        formData.append('database', JSON.stringify(databaseData));
        console.log('データベースデータを追加しました', databaseData);
    }
    
    // セキュリティタブのフォームデータ収集
    const securityData = collectSecurityFormData();
    if (securityData) {
        formData.append('security', JSON.stringify(securityData));
        console.log('セキュリティデータを追加しました', securityData);
    }
    
    // アーキテクチャタブのフォームデータ収集
    const architectureData = collectArchitectureFormData();
    if (architectureData) {
        formData.append('architecture', JSON.stringify(architectureData));
        console.log('アーキテクチャデータを追加しました', architectureData);
    }
    
    // ハードウェアタブのフォームデータ収集
    const hardwareData = collectHardwareFormData();
    if (hardwareData) {
        console.log('ハードウェアデータを追加しました', hardwareData);
        // maker と model は個別のフォームフィールドとして送信
        if (hardwareData.maker) formData.append('maker', hardwareData.maker);
        if (hardwareData.model) formData.append('model', hardwareData.model);
        // 他のフィールドはJSON形式で送信
        if (hardwareData.pc_type) formData.append('pc_type', hardwareData.pc_type);
        if (hardwareData.device_type) formData.append('device_type', hardwareData.device_type);
        if (hardwareData.cpu_type) formData.append('cpu_type', hardwareData.cpu_type);
        if (hardwareData.memory_size) formData.append('memory_size', hardwareData.memory_size);
        if (hardwareData.storage_type) formData.append('storage_type', hardwareData.storage_type);
        if (hardwareData.monitor_count) formData.append('monitor_count', hardwareData.monitor_count);
        if (hardwareData.internet_type) formData.append('internet_type', hardwareData.internet_type);
    }
    
    // 開発ストーリータブのフォームデータ収集
    const developmentStoryData = collectDevelopmentStoryFormData();
    if (developmentStoryData) {
        formData.append('development_story', JSON.stringify(developmentStoryData));
        console.log('開発ストーリーデータを追加しました', developmentStoryData);
    }
    
    // デバッグ情報: フォームデータの内容を確認
    console.log('===== FormData内容確認 (collectTabsFormData後) =====');
    console.log('FormDataのキー:');
    for (let key of formData.keys()) {
        console.log('- ' + key);
    }
    
    // 実際にJSONフィールドが含まれているか確認
    console.log('JSONフィールドの確認:');
    const jsonFields = ['development_environment', 'architecture', 'backend', 'frontend', 'hosting', 'database', 'security', 'development_story'];
    jsonFields.forEach(field => {
        const value = formData.get(field);
        if (value) {
            console.log(`${field}が存在: 文字数=${value.length}`);
            try {
                const jsonData = JSON.parse(value);
                console.log(`${field}の内容:`, jsonData);
            } catch (e) {
                console.error(`${field}はJSON形式ではありません:`, e);
            }
        } else {
            console.warn(`${field}はFormDataに含まれていません`);
        }
    });
    console.log('============================================');
    
    return formData;
}

// 開発環境タブのフォームデータ収集
function collectDevEnvFormData() {
    console.log('開発環境タブのデータ収集を開始');
    
    // 開発環境タブが存在するか確認
    const devEnvTab = document.getElementById('dev_env');
    if (!devEnvTab) {
        console.warn('開発環境タブが見つかりません');
        return null;
    }
    
    console.log('開発環境タブが見つかりました:', devEnvTab);
    
    // 開発環境タブの内部構造を確認
    console.log('=== 開発環境タブの内部構造 ===');
    console.log('タブ内のフォームID:', devEnvTab.querySelector('form')?.id || 'フォームなし');
    console.log('タブ内の入力要素数:', devEnvTab.querySelectorAll('input').length);
    console.log('タブ内のチェックボックス数:', devEnvTab.querySelectorAll('input[type="checkbox"]').length);
    console.log('タブ内のラジオボタン数:', devEnvTab.querySelectorAll('input[type="radio"]').length);
    
    // タブ内のIDを持つ要素を表示
    const elementsWithId = devEnvTab.querySelectorAll('[id]');
    console.log('タブ内のIDを持つ要素:', Array.from(elementsWithId).map(el => el.id));
    
    const data = {};
    
    // エディタのチェックボックス
    data.editors = [];
    devEnvTab.querySelectorAll('input[name="development_environment_editors"]:checked').forEach(input => {
        data.editors.push(input.value);
    });
    
    // バージョン管理システム
    data.version_control = [];
    devEnvTab.querySelectorAll('input[name="development_environment_version_control"]:checked').forEach(input => {
        data.version_control.push(input.value);
    });
    
    // 仮想化ツール
    data.virtualization = [];
    devEnvTab.querySelectorAll('input[name="development_environment_virtualization"]:checked').forEach(input => {
        data.virtualization.push(input.value);
    });
    
    // チームサイズ
    const teamSizeInput = devEnvTab.querySelector('input[name="development_environment_team_size"]:checked');
    if (teamSizeInput) {
        data.team_size = teamSizeInput.value;
    }
    
    // コミュニケーションツール
    data.communication_tools = [];
    devEnvTab.querySelectorAll('input[name="development_environment_communication_tools"]:checked').forEach(input => {
        data.communication_tools.push(input.value);
    });
    
    // CI/CD
    data.ci_cd = [];
    devEnvTab.querySelectorAll('input[name="development_environment_ci_cd"]:checked').forEach(input => {
        data.ci_cd.push(input.value);
    });
    
    // APIツール
    data.api_tools = [];
    devEnvTab.querySelectorAll('input[name="development_environment_api_tools"]:checked').forEach(input => {
        data.api_tools.push(input.value);
    });
    
    // モニタリングツール
    data.monitoring_tools = [];
    devEnvTab.querySelectorAll('input[name="development_environment_monitoring_tools"]:checked').forEach(input => {
        data.monitoring_tools.push(input.value);
    });
    
    console.log('収集した開発環境データ:', data);
    return data;
}

// バックエンドタブのフォームデータ収集
function collectBackendFormData() {
    console.log('バックエンドタブのデータ収集を開始');
    
    // バックエンドタブが存在するか確認
    const backendTab = document.getElementById('backend');
    if (!backendTab) {
        console.warn('バックエンドタブが見つかりません');
        return null;
    }
    
    // 両方の形式で取得
    const data = {
        languages: [],
        frameworks: [],
        libraries: []
    };
    
    // メイン使用言語（ラジオボタン）
    const mainLanguageInput = backendTab.querySelector('input[name="backend_main_language"]:checked');
    if (mainLanguageInput) {
        data.main_language = mainLanguageInput.value;
        if (mainLanguageInput.value) {
            data.languages.push(mainLanguageInput.value);
        }
        console.log('メイン言語:', data.main_language);
    }
    
    // フレームワーク（ラジオボタン）
    const frameworkInput = backendTab.querySelector('input[name="backend_framework"]:checked');
    if (frameworkInput) {
        data.framework = frameworkInput.value;
        if (frameworkInput.value) {
            data.frameworks.push(frameworkInput.value);
        }
        console.log('フレームワーク:', data.framework);
    }
    
    // パッケージ（チェックボックス、複数選択可）
    data.packages = [];
    backendTab.querySelectorAll('input[name="backend_packages"]:checked').forEach(input => {
        data.packages.push(input.value);
        data.libraries.push(input.value);
    });
    console.log('パッケージ:', data.packages);
    
    console.log('収集したバックエンドデータ:', data);
    return data;
}

// フロントエンドタブのフォームデータ収集
function collectFrontendFormData() {
    console.log('フロントエンドタブのデータ収集を開始');
    
    // フロントエンドタブが存在するか確認
    const frontendTab = document.getElementById('frontend');
    if (!frontendTab) {
        console.warn('フロントエンドタブが見つかりません');
        return null;
    }
    
    const data = {};
    
    // マークアップ言語
    data.markup_languages = [];
    frontendTab.querySelectorAll('input[name="markup_languages"]:checked').forEach(input => {
        data.markup_languages.push(input.value);
    });
    
    // フロントエンド言語
    data.languages = [];
    frontendTab.querySelectorAll('input[name="frontend_languages"]:checked').forEach(input => {
        data.languages.push(input.value);
    });
    
    // フロントエンドフレームワーク
    data.frameworks = [];
    frontendTab.querySelectorAll('input[name="frontend_frameworks"]:checked').forEach(input => {
        data.frameworks.push(input.value);
    });
    
    // CSSフレームワーク
    data.css_frameworks = [];
    frontendTab.querySelectorAll('input[name="css_frameworks"]:checked').forEach(input => {
        data.css_frameworks.push(input.value);
    });
    
    // UIライブラリ
    data.ui_libraries = [];
    frontendTab.querySelectorAll('input[name="ui_libraries"]:checked').forEach(input => {
        data.ui_libraries.push(input.value);
    });
    
    // フロントエンドホスティング - ホスティングタブからデータを取得して統合
    const hostingTab = document.getElementById('hosting');
    if (hostingTab) {
        data.hosting = [];
        const inputs = hostingTab.querySelectorAll('input[name="frontend_hosting"]:checked');
        inputs.forEach(input => {
            data.hosting.push(input.value);
        });
        console.log('フロントエンドホスティングデータ (collectFrontendFormData内):', data.hosting);
    }
    
    console.log('収集したフロントエンドデータ:', data);
    return data;
}

// データベースタブのフォームデータ収集
function collectDatabaseFormData() {
    console.log('データベースタブのデータ収集を開始');
    
    // データベースタブが存在するか確認
    const databaseTab = document.getElementById('database-section');
    if (!databaseTab) {
        // 代替としてホスティングタブからのデータをチェック
        const hostingTab = document.getElementById('hosting');
        if (hostingTab) {
            const hostingData = {};
            hostingData.hosting = [];
            hostingTab.querySelectorAll('input[name="database_hosting"]:checked').forEach(input => {
                hostingData.hosting.push(input.value);
            });
            if (hostingData.hosting.length > 0) {
                console.log('ホスティングタブからデータベースホスティングデータを取得:', hostingData);
                return hostingData;
            }
        }
        console.warn('データベースタブが見つかりません');
        return null;
    }
    
    const data = {};
    
    // データベースタイプ
    data.types = [];
    databaseTab.querySelectorAll('input[name="database_types"]:checked').forEach(input => {
        data.types.push(input.value);
    });
    console.log('データベースタイプ:', data.types);
    
    // データベース製品
    data.products = [];
    databaseTab.querySelectorAll('input[name="database_products"]:checked').forEach(input => {
        data.products.push(input.value);
    });
    
    // データベースホスティング - ホスティングタブからも取得
    data.hosting = [];
    // データベースタブにホスティング設定がある場合
    databaseTab.querySelectorAll('input[name="database_hosting"]:checked').forEach(input => {
        data.hosting.push(input.value);
    });
    
    // ホスティングタブからもデータを取得して統合
    const hostingTab = document.getElementById('hosting');
    if (hostingTab) {
        hostingTab.querySelectorAll('input[name="database_hosting"]:checked').forEach(input => {
            if (!data.hosting.includes(input.value)) {
                data.hosting.push(input.value);
            }
        });
    }
    console.log('データベースホスティング:', data.hosting);
    
    // ORMツール
    data.orm_tools = [];
    databaseTab.querySelectorAll('input[name="orm_tools"]:checked').forEach(input => {
        data.orm_tools.push(input.value);
    });
    console.log('ORMツール:', data.orm_tools);
    
    console.log('収集したデータベースデータ:', data);
    return data;
}

// セキュリティタブのフォームデータ収集
function collectSecurityFormData() {
    console.log('セキュリティタブのデータ収集を開始');
    
    // セキュリティタブが存在するか確認
    const securityTab = document.getElementById('security-section');
    if (!securityTab) {
        console.warn('セキュリティタブが見つかりません');
        return null;
    }
    
    const data = {};
    
    // 認証方式
    data.auth_methods = [];
    securityTab.querySelectorAll('input[name="authentication_methods"]:checked').forEach(input => {
        data.auth_methods.push(input.value);
    });
    console.log('認証方式:', data.auth_methods);
    
    // セキュリティ対策
    data.measures = [];
    securityTab.querySelectorAll('input[name="security_measures"]:checked').forEach(input => {
        data.measures.push(input.value);
    });
    console.log('セキュリティ対策:', data.measures);
    
    // 空の配列を初期化して確実にデータが存在するようにする
    if (!data.auth_methods) data.auth_methods = [];
    if (!data.measures) data.measures = [];
    
    console.log('収集したセキュリティデータ:', data);
    return data;
}

// 開発ストーリータブのフォームデータ収集
function collectDevelopmentStoryFormData() {
    console.log('開発ストーリータブのデータ収集を開始');
    
    // 開発ストーリータブが存在するか確認
    const developmentTab = document.getElementById('development-section');
    if (!developmentTab) {
        console.warn('開発ストーリータブが見つかりません');
        return null;
    }
    
    const data = {};
    
    // 開発開始日
    const startDate = developmentTab.querySelector('input[name="development_start_date"]');
    if (startDate && startDate.value) {
        data.start_date = startDate.value;
        console.log('開発開始日:', data.start_date);
    }
    
    // 開発終了日
    const endDate = developmentTab.querySelector('input[name="development_end_date"]');
    if (endDate && endDate.value) {
        data.end_date = endDate.value;
        console.log('開発終了日:', data.end_date);
    }
    
    // 開発期間
    const duration = developmentTab.querySelector('input[name="development_duration"]');
    if (duration && duration.value) {
        data.duration = duration.value;
        console.log('開発期間:', data.duration);
    } else {
        // 隠しフィールドが見つからない場合、ID指定でも探す
        const durationHidden = document.getElementById('development_duration_hidden');
        if (durationHidden && durationHidden.value) {
            data.duration = durationHidden.value;
            console.log('開発期間(hidden):', data.duration);
        }
    }
    
    // 開発の動機
    const motivation = developmentTab.querySelector('textarea[name="development_motivation"]');
    if (motivation && motivation.value) {
        data.development_motivation = motivation.value;
        console.log('開発の動機を収集しました');
    }
    
    // 工夫したポイント
    const innovations = developmentTab.querySelector('textarea[name="development_innovations"]');
    if (innovations && innovations.value) {
        data.development_innovations = innovations.value;
        console.log('工夫した点を収集しました');
    }
    
    // 諦めた機能
    const abandoned = developmentTab.querySelector('textarea[name="development_abandoned"]');
    if (abandoned && abandoned.value) {
        data.development_abandoned = abandoned.value;
        console.log('断念した点を収集しました');
    }
    
    // 今後の予定
    const futurePlans = developmentTab.querySelector('textarea[name="development_future_plans"]');
    if (futurePlans && futurePlans.value) {
        data.development_future_plans = futurePlans.value;
        console.log('今後の予定を収集しました');
    }
    
    // 振り返り
    const reflections = developmentTab.querySelector('textarea[name="development_reflections"]');
    if (reflections && reflections.value) {
        data.development_reflections = reflections.value;
        console.log('振り返りを収集しました');
    }
    
    console.log('収集した開発ストーリーデータ:', data);
    return data;
}

// アーキテクチャタブのフォームデータ収集
function collectArchitectureFormData() {
    console.log('アーキテクチャタブのデータ収集を開始');
    
    // アーキテクチャタブが存在するか確認
    const architectureTab = document.getElementById('architecture');
    if (!architectureTab) {
        console.warn('アーキテクチャタブが見つかりません');
        return null;
    }
    
    const data = {};
    
    // アーキテクチャパターン
    data.patterns = [];
    architectureTab.querySelectorAll('input[name="architecture_patterns"]:checked').forEach(input => {
        data.patterns.push(input.value);
    });
    
    // デザインパターン
    data.design_patterns = [];
    architectureTab.querySelectorAll('input[name="architecture_design_patterns"]:checked').forEach(input => {
        data.design_patterns.push(input.value);
    });
    
    // アーキテクチャの説明
    const descriptionTextarea = architectureTab.querySelector('textarea[name="architecture_description"]');
    if (descriptionTextarea && descriptionTextarea.value.trim()) {
        data.description = descriptionTextarea.value.trim();
    }
    
    console.log('収集したアーキテクチャデータ:', data);
    return data;
}

// ホスティングタブのフォームデータ収集
function collectHostingFormData() {
    console.log('ホスティングタブのデータ収集を開始');
    
    // ホスティングタブが存在するか確認
    const hostingTab = document.getElementById('hosting');
    if (!hostingTab) {
        console.warn('ホスティングタブが見つかりません');
        return null;
    }
    
    const data = {};
    
    // Webアプリケーションホスティングサービス
    data.services = [];
    hostingTab.querySelectorAll('input[name="hosting_services"]:checked').forEach(input => {
        data.services.push(input.value);
    });
    console.log('ホスティングサービス:', data.services);
    
    // デプロイ方法
    data.deployment_methods = [];
    hostingTab.querySelectorAll('input[name="deployment_methods"]:checked').forEach(input => {
        data.deployment_methods.push(input.value);
    });
    console.log('デプロイ方法:', data.deployment_methods);
    
    // フロントエンドホスティング
    data.frontend_hosting = [];
    hostingTab.querySelectorAll('input[name="frontend_hosting"]:checked').forEach(input => {
        data.frontend_hosting.push(input.value);
    });
    console.log('フロントエンドホスティングデータ (ホスティングタブから):', data.frontend_hosting);
    
    // データベースホスティング（これはデータベースオブジェクトに入れるべきだが、移行期間は両方に入れる）
    data.database_hosting = [];
    hostingTab.querySelectorAll('input[name="database_hosting"]:checked').forEach(input => {
        data.database_hosting.push(input.value);
    });
    console.log('データベースホスティング:', data.database_hosting);
    
    // データベースオブジェクトのホスティングデータを同期
    const databaseData = collectDatabaseFormData();
    if (databaseData && data.database_hosting.length > 0) {
        databaseData.hosting = [...data.database_hosting];
        // JSONフィールドを更新
        const databaseJsonField = document.querySelector('input[name="database"]');
        if (databaseJsonField) {
            databaseJsonField.value = JSON.stringify(databaseData);
        }
    }
    
    // フロントエンドデータとの同期処理
    if (data.frontend_hosting && data.frontend_hosting.length > 0) {
        // frontend JSONフィールドを直接更新
        try {
            // フロントエンドJSONフィールドを取得
            const frontendJsonField = document.querySelector('input[name="frontend"]');
            if (frontendJsonField && frontendJsonField.value) {
                const frontendData = JSON.parse(frontendJsonField.value);
                // hostingプロパティを追加または更新
                frontendData.hosting = data.frontend_hosting;
                // 更新したJSONを書き戻す
                frontendJsonField.value = JSON.stringify(frontendData);
                console.log('フロントエンドJSONデータにホスティングを追加:', frontendData);
            } else {
                // frontend JSONフィールドがない場合、新しく作成
                const formElement = document.getElementById('appForm');
                if (formElement) {
                    const frontendInput = document.createElement('input');
                    frontendInput.type = 'hidden';
                    frontendInput.name = 'frontend';
                    const frontendData = {
                        hosting: data.frontend_hosting,
                        markup_languages: [],
                        languages: [],
                        frameworks: [],
                        css_frameworks: [],
                        ui_libraries: []
                    };
                    frontendInput.value = JSON.stringify(frontendData);
                    formElement.appendChild(frontendInput);
                    console.log('新しいフロントエンドJSONフィールドを作成:', frontendData);
                }
            }
        } catch (e) {
            console.error('フロントエンドJSONデータの更新エラー:', e);
        }
    }
    
    // 備考
    const notes = hostingTab.querySelector('textarea[name="hosting_notes"]');
    if (notes && notes.value) {
        data.notes = notes.value;
    }
    
    console.log('収集したホスティングデータ:', data);
    return data;
}

// ハードウェアタブのデータを収集する関数
function collectHardwareFormData() {
    console.log('ハードウェアデータの収集を開始します');
    
    // ハードウェアタブがなければ空を返す
    const hardwareTab = document.getElementById('hardware');
    if (!hardwareTab) {
        console.log('ハードウェアタブが見つかりません');
        return null;
    }
    
    // 収集するデータ
    const hardwareData = {};
    
    // メーカーと機種名
    const makerInput = document.querySelector('input[name="maker"]');
    const modelInput = document.querySelector('input[name="model"]');
    
    if (makerInput) hardwareData.maker = makerInput.value;
    if (modelInput) hardwareData.model = modelInput.value;
    
    // ラジオボタンで選択する項目
    const radioFields = ['pc_type', 'device_type', 'cpu_type', 'memory_size', 'storage_type', 'monitor_count', 'internet_type'];
    
    radioFields.forEach(fieldName => {
        const selectedRadio = document.querySelector(`input[name="${fieldName}"]:checked`);
        if (selectedRadio) {
            hardwareData[fieldName] = selectedRadio.value;
        }
    });
    
    console.log('収集したハードウェアデータ:', hardwareData);
    return hardwareData;
}

// フォームデータをシリアライズする関数
function serializeForm() {
    const form = document.getElementById('appForm');
    if (!form) return {};
    
    // FormData オブジェクトを作成
    let formData = new FormData(form);
    
    // 各タブのフォームデータを収集して追加
    formData = collectTabsFormData(formData);
    
    // シリアライズ
    const serialized = {};
    for (let [key, value] of formData.entries()) {
        if (value instanceof File) {
            serialized[key] = value.name || 'file-selected';
        } else {
            serialized[key] = value;
        }
    }
    
    console.log('シリアライズされたフォームデータ:', serialized);
    return serialized;
}

// 変更検知のリスナーを設定
function setupChangeListeners() {
    const formElements = document.querySelectorAll(
        '#appForm input:not([type=hidden]), #appForm textarea, #appForm select'
    );
    
    formElements.forEach(element => {
        element.addEventListener('change', handleFormChange);
        
        // テキスト入力要素の場合は入力イベントも監視
        if (element.tagName === 'TEXTAREA' || 
            (element.tagName === 'INPUT' && 
             ['text', 'email', 'url', 'number'].includes(element.type))) {
            element.addEventListener('input', handleFormChange);
        }
    });
    
    // 各タブの独立フォーム要素も監視
    const tabForms = document.querySelectorAll('.tab-pane form');
    tabForms.forEach(form => {
        const tabFormElements = form.querySelectorAll(
            'input:not([type=hidden]), textarea, select'
        );
        
        tabFormElements.forEach(element => {
            element.addEventListener('change', handleFormChange);
            
            // テキスト入力要素の場合は入力イベントも監視
            if (element.tagName === 'TEXTAREA' || 
                (element.tagName === 'INPUT' && 
                 ['text', 'email', 'url', 'number'].includes(element.type))) {
                element.addEventListener('input', handleFormChange);
            }
        });
    });
    
    // フォームの直接送信を防止
    const appForm = document.getElementById('appForm');
    if (appForm) {
        appForm.addEventListener('submit', function(event) {
            event.preventDefault();
            console.log('フォームの直接送信を防止しました - 自動保存が有効です');
        });
    }
}

// フォーム変更時の処理
function handleFormChange(event) {
    // 変更されたフォーム要素の情報をログ出力
    const element = event.target;
    console.log('=== フォーム要素が変更されました ===');
    console.log('変更要素:', element.tagName);
    console.log('要素ID:', element.id);
    console.log('要素名:', element.name);
    console.log('要素タイプ:', element.type);
    console.log('要素値:', element.value);
    
    // どのタブ内の要素か特定
    let tabElement = element.closest('.tab-pane');
    if (tabElement) {
        console.log('変更タブID:', tabElement.id);
    } else {
        console.log('タブ外の要素が変更されました');
    }
    
    // 変更フラグを設定
    if (!appState.formChanged) {
        appState.formChanged = true;
        logFormChangedState('handleFormChange', true);
        
        // 自動保存をスケジュール
        scheduleAutoSave();
        
        console.log('フォーム変更フラグをONにしました → 自動保存をスケジュールしました');
    } else {
        console.log('フォーム変更フラグは既にONです → 自動保存は既にスケジュール済み');
    }
    
    // 変更ボックスを表示（自作関数）
    const changedBox = document.createElement('div');
    changedBox.style.position = 'fixed';
    changedBox.style.bottom = '20px';
    changedBox.style.right = '20px';
    changedBox.style.padding = '10px';
    changedBox.style.backgroundColor = 'rgba(0, 255, 0, 0.7)';
    changedBox.style.borderRadius = '5px';
    changedBox.style.zIndex = '9999';
    changedBox.textContent = `変更検知: ${element.name || element.id || '名前なし要素'}`;
    document.body.appendChild(changedBox);
    
    // 3秒後に変更ボックスを削除
    setTimeout(() => {
        document.body.removeChild(changedBox);
    }, 3000);
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
    let formData = new FormData(form);
    
    // 各タブのフォームデータを収集して追加
    formData = collectTabsFormData(formData);
    
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

// 現在のタブ状態を保存する関数
function saveCurrentTab(tabId) {
    const appId = document.querySelector('#appForm').getAttribute('data-app-id');
    const storageKey = `active_tab_${appId || 'new'}`;
    localStorage.setItem(storageKey, tabId);
    console.log(`現在のタブ(${tabId})を保存しました`);
}

// 保存されたタブ状態を復元する関数
function restoreTabState() {
    const appId = document.querySelector('#appForm').getAttribute('data-app-id');
    const storageKey = `active_tab_${appId || 'new'}`;
    const savedTabId = localStorage.getItem(storageKey);
    
    if (savedTabId) {
        console.log(`保存されたタブ(${savedTabId})を復元します`);
        // タブエレメントを取得
        const tabElement = document.getElementById(savedTabId);
        
        if (tabElement) {
            // Bootstrapのタブを使って表示
            const tabInstance = new bootstrap.Tab(tabElement);
            tabInstance.show();
        }
    }
}

// タブ切り替え時のイベントリスナーを追加
document.addEventListener('shown.bs.tab', function(event) {
    // タブIDを取得してローカルストレージに保存
    const tabId = event.target.id;
    saveCurrentTab(tabId);
});

// セットアップ自動保存
function setupFormAutosave() {
    setupChangeListeners();
    
    // ページを離れる前の警告
    window.addEventListener('beforeunload', function(e) {
        if (formIsDirty && !justSaved) {
            const confirmationMessage = '変更が保存されていません。このページを離れますか？';
            e.returnValue = confirmationMessage;
            return confirmationMessage;
        }
    });
    
    console.log('フォーム自動保存が設定されました');
    showToastMessage('自動保存機能が有効です');
}

// 自動保存機能を初期化
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoadedイベント発生');
    
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
        } else {
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
            
            // 自動保存機能をセットアップ
            setupFormAutosave();
            
            // フォームにresetDirty関数を追加
            form.resetDirty = resetFormDirty;
            
            console.log('自動保存機能が初期化されました');
        }
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
    
    // 保存されたタブ状態を復元
    restoreTabState();
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