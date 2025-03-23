// ハンバーガーメニューボタンのクリックイベント
document.addEventListener('DOMContentLoaded', function() {
    const menuButton = document.getElementById('menu-button');
    const profileTabs = document.getElementById('profile-tabs');
    const profileTabClose = document.getElementById('profile-tab-close');
    
    if (menuButton && profileTabs && profileTabClose) {
        // メニューボタンクリックでタブを表示
        menuButton.addEventListener('click', function(e) {
            e.preventDefault();
            profileTabs.style.display = 'block';
        });
        
        // 閉じるボタンでタブを非表示
        profileTabClose.addEventListener('click', function() {
            profileTabs.style.display = 'none';
        });
        
        // タブ外クリックで非表示
        document.addEventListener('click', function(e) {
            if (!profileTabs.contains(e.target) && e.target !== menuButton && !menuButton.contains(e.target)) {
                profileTabs.style.display = 'none';
            }
        });
    }
}); 