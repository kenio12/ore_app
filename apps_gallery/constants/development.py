"""
開発環境に関する定数
"""

# チームサイズ
TEAM_SIZES = {
    'solo': '1人',
    'duo': '2人チーム',
    'small': '3-5人チーム',
    'medium': '6-10人チーム',
    'large': '11-20人チーム',
    'enterprise': '21人以上'
}

# 仮想化ツール
VIRTUALIZATION_TOOLS = {
    'none': '使用していない',
    'docker': 'Docker',
    'docker_compose': 'Docker Compose',
    'kubernetes': 'Kubernetes',
    'vagrant': 'Vagrant',
    'virtualbox': 'VirtualBox',
    'vmware': 'VMware',
    'hyper_v': 'Hyper-V',
    'wsl': 'WSL (Windows Subsystem for Linux)',
    'other': 'その他'
}

# エディタ
EDITORS = {
    'none': '使用していない',
    'cursor': 'Cursor',
    'vscode': 'Visual Studio Code',
    'sublime': 'Sublime Text',
    'atom': 'Atom',
    'vim': 'Vim/Neovim',
    'emacs': 'Emacs',
    'intellij': 'IntelliJ IDEA',
    'pycharm': 'PyCharm',
    'webstorm': 'WebStorm',
    'phpstorm': 'PhpStorm',
    'eclipse': 'Eclipse',
    'netbeans': 'NetBeans',
    'xcode': 'Xcode',
    'android_studio': 'Android Studio',
    'notepad_plus': 'Notepad++',
    'other': 'その他'
}

# バージョン管理
VERSION_CONTROL = {
    'none': '使用していない',
    'git': 'Git',
    'github': 'GitHub',
    'gitlab': 'GitLab',
    'bitbucket': 'Bitbucket',
    'azure_devops': 'Azure DevOps',
    'svn': 'Subversion (SVN)',
    'mercurial': 'Mercurial',
    'perforce': 'Perforce',
    'other': 'その他'
}

# コミュニケーションツール
COMMUNICATION_TOOLS = {
    'none': '使用していない',
    'slack': 'Slack',
    'discord': 'Discord',
    'teams': 'Microsoft Teams',
    'zoom': 'Zoom',
    'meet': 'Google Meet',
    'chatwork': 'ChatWork',
    'line_works': 'LINE WORKS',
    'skype': 'Skype',
    'mattermost': 'Mattermost',
    'rocket_chat': 'Rocket.Chat',
    'other': 'その他'
}

# インフラストラクチャ
INFRASTRUCTURE = {
    'none': '使用していない',
    # クラウドプラットフォーム
    'aws': 'Amazon Web Services (AWS)',
    'gcp': 'Google Cloud Platform (GCP)',
    'azure': 'Microsoft Azure',
    'oracle': 'Oracle Cloud',
    'ibm': 'IBM Cloud',
    
    # PaaS
    'pythonanywhere': 'PythonAnywhere',
    'heroku': 'Heroku',
    'vercel': 'Vercel',
    'netlify': 'Netlify',
    'render': 'Render',
    'railway': 'Railway',
    'fly_io': 'Fly.io',
    'digital_ocean': 'DigitalOcean',
    'linode': 'Linode',
    'vultr': 'Vultr',
    
    # サーバーレス
    'lambda': 'AWS Lambda',
    'cloud_functions': 'Google Cloud Functions',
    'azure_functions': 'Azure Functions',
    
    # コンテナオーケストレーション
    'kubernetes': 'Kubernetes',
    'ecs': 'Amazon ECS',
    'gke': 'Google Kubernetes Engine',
    'aks': 'Azure Kubernetes Service',
    
    # その他
    'on_premise': 'オンプレミス',
    'shared_hosting': 'レンタルサーバー',
    'vps': 'VPS',
    'hybrid': 'ハイブリッド',
    'other': 'その他'
}

# CI/CD
CI_CD = {
    'none': '使用していない',
    'github_actions': 'GitHub Actions',
    'gitlab_ci': 'GitLab CI/CD',
    'jenkins': 'Jenkins',
    'circle_ci': 'CircleCI',
    'travis_ci': 'Travis CI',
    'azure_pipelines': 'Azure Pipelines',
    'teamcity': 'TeamCity',
    'bamboo': 'Bamboo',
    'drone': 'Drone CI',
    'codeship': 'Codeship',
    'other': 'その他'
}

# APIツール
API_TOOLS = {
    'none': '使用していない',
    # API開発・テスト
    'postman': 'Postman',
    'insomnia': 'Insomnia',
    'swagger': 'Swagger/OpenAPI',
    'curl': 'cURL',
    'hoppscotch': 'Hoppscotch',
    
    # APIゲートウェイ
    'kong': 'Kong',
    'aws_api_gateway': 'AWS API Gateway',
    'azure_api_management': 'Azure API Management',
    'apigee': 'Apigee',
    
    # APIドキュメント
    'redoc': 'ReDoc',
    'stoplight': 'Stoplight',
    'apiblueprint': 'API Blueprint',
    
    # GraphQL
    'graphql': 'GraphQL',
    'apollo': 'Apollo',
    'hasura': 'Hasura',
    
    # その他
    'other': 'その他'
}

# モニタリングツール
MONITORING_TOOLS = {
    'none': '使用していない',
    # APM（アプリケーションパフォーマンスモニタリング）
    'new_relic': 'New Relic',
    'datadog': 'Datadog',
    'dynatrace': 'Dynatrace',
    'app_dynamics': 'AppDynamics',
    
    # ログ管理
    'splunk': 'Splunk',
    'elastic_stack': 'Elastic Stack (ELK)',
    'graylog': 'Graylog',
    'papertrail': 'Papertrail',
    
    # メトリクス監視
    'prometheus': 'Prometheus',
    'grafana': 'Grafana',
    'nagios': 'Nagios',
    'zabbix': 'Zabbix',
    
    # エラー追跡
    'sentry': 'Sentry',
    'rollbar': 'Rollbar',
    'bugsnag': 'Bugsnag',
    
    # クラウドネイティブ監視
    'cloudwatch': 'AWS CloudWatch',
    'stackdriver': 'Google Cloud Monitoring',
    'azure_monitor': 'Azure Monitor',
    
    # その他
    'other': 'その他'
} 