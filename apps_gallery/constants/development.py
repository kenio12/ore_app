"""
開発環境に関する定数
"""

# チームサイズ
TEAM_SIZES = {
    '1': '1人（個人）',
    '2-5': '2-5人',
    '6-10': '6-10人',
    '11-20': '11-20人',
    '21-50': '21-50人',
    '50+': '50人以上'
}

# 仮想化ツール
VIRTUALIZATION_TOOLS = {
    'docker': 'Docker',
    'vagrant': 'Vagrant',
    'virtualbox': 'VirtualBox',
    'vmware': 'VMware',
    'wsl': 'WSL',
    'other': 'その他'
}

# エディタ
EDITORS = {
    'vscode': 'Visual Studio Code',
    'phpstorm': 'PhpStorm',
    'sublime': 'Sublime Text',
    'vim': 'Vim',
    'atom': 'Atom',
    'other': 'その他'
}

# バージョン管理
VERSION_CONTROL = {
    'git': 'Git',
    'github': 'GitHub',
    'gitlab': 'GitLab',
    'bitbucket': 'Bitbucket',
    'svn': 'SVN',
    'other': 'その他'
}

# コミュニケーションツール
COMMUNICATION_TOOLS = {
    'slack': 'Slack',
    'discord': 'Discord',
    'teams': 'Microsoft Teams',
    'zoom': 'Zoom',
    'meet': 'Google Meet',
    'chatwork': 'Chatwork',
    'skype': 'Skype',
    'other': 'その他'
}

# インフラストラクチャ
INFRASTRUCTURE = {
    'aws': 'AWS',
    'gcp': 'Google Cloud',
    'azure': 'Microsoft Azure',
    'heroku': 'Heroku',
    'vercel': 'Vercel',
    'digitalocean': 'DigitalOcean',
    'firebase': 'Firebase',
    'cloudflare': 'Cloudflare',
    'other': 'その他'
}

# CI/CD
CI_CD = {
    'github_actions': 'GitHub Actions',
    'gitlab_ci': 'GitLab CI',
    'jenkins': 'Jenkins',
    'circle_ci': 'CircleCI',
    'travis_ci': 'Travis CI',
    'azure_pipelines': 'Azure Pipelines',
    'other': 'その他'
}

# APIツール
API_TOOLS = {
    'postman': 'Postman',
    'swagger': 'Swagger/OpenAPI',
    'insomnia': 'Insomnia',
    'curl': 'cURL',
    'graphql': 'GraphQL',
    'other': 'その他'
}

# モニタリングツール
MONITORING_TOOLS = {
    'datadog': 'Datadog',
    'newrelic': 'New Relic',
    'grafana': 'Grafana',
    'prometheus': 'Prometheus',
    'sentry': 'Sentry',
    'cloudwatch': 'AWS CloudWatch',
    'stackdriver': 'Google Cloud Monitoring',
    'other': 'その他'
} 