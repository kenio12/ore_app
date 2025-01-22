<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-bold mb-6">基本開発環境</h2>

    <!-- 開発人数 -->
    <div class="mb-6">
        <label for="dev_team_size" class="block text-sm font-medium text-gray-700">
            開発人数
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                '1' => '1人（個人）',
                '2-5' => '2-5人',
                '6-10' => '6-10人',
                '11-20' => '11-20人',
                '21-50' => '21-50人',
                '50+' => '50人以上'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="radio"
                        name="dev_team_size"
                        value="{{ $value }}"
                        {{ old('dev_team_size', $app->dev_team_size ?? '') == $value ? 'checked' : '' }}
                        class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        @error('dev_team_size')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- 開発環境の仮想化 -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            開発環境の仮想化
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @php
                // 最初に1回だけデコード
                $deviceTypes = $app->hardware ? json_decode($app->hardware->device_types ?? '[]', true) ?? [] : [];
                $storageTypes = $app->hardware ? json_decode($app->hardware->storage_types ?? '[]', true) ?? [] : [];
            @endphp

            @foreach([
                'docker' => 'Docker',
                'docker_compose' => 'Docker Compose',
                'kubernetes' => 'Kubernetes',
                'vagrant' => 'Vagrant',
                'virtual_box' => 'VirtualBox',
                'vmware' => 'VMware',
                'wsl' => 'WSL (Windows)',
                'none' => '使用していない',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="virtualization[]"
                        value="{{ $value }}"
                        @if(in_array($value, $deviceTypes)) checked @endif
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <!-- その他の場合の入力欄 -->
        <div class="mt-2">
            <input 
                type="text" 
                name="other_virtualization" 
                id="other_virtualization"
                value="{{ old('other_virtualization', $app->other_virtualization ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他の仮想化ツールを入力"
            >
        </div>
        @error('virtualization')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- OS -->
    <div class="mb-6">
        <label for="os" class="block text-sm font-medium text-gray-700">
            OS
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'windows' => 'Windows',
                'macos' => 'macOS',
                'linux' => 'Linux',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="radio"
                        name="os_type"
                        value="{{ $value }}"
                        {{ old('os_type', $app->os_type ?? '') == $value ? 'checked' : '' }}
                        class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <!-- その他の場合のバージョン入力 -->
        <div class="mt-2">
            <input 
                type="text" 
                name="os_version" 
                id="os_version"
                value="{{ old('os_version', $app->os_version ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="バージョンを入力（例：Windows 11、macOS Ventura 13.4）"
            >
        </div>
        @error('os_type')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
        @error('os_version')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- エディタ/IDE -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            エディタ/IDE
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'vscode' => 'VSCode',
                'cursor' => 'Cursor',
                'vim' => 'Vim',
                'emacs' => 'Emacs',
                'sublime' => 'Sublime Text',
                'atom' => 'Atom',
                'phpstorm' => 'PhpStorm',
                'intellij' => 'IntelliJ IDEA',
                'eclipse' => 'Eclipse',
                'xcode' => 'Xcode',
                'android_studio' => 'Android Studio',
                'notepad++' => 'Notepad++',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="editors[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('editors', $app->editors ?? [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <!-- その他の場合の入力欄 -->
        <div class="mt-2">
            <input 
                type="text" 
                name="other_editor" 
                id="other_editor"
                value="{{ old('other_editor', $app->other_editor ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他のエディタ/IDEを入力"
            >
        </div>
        @error('editors')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- バージョン管理 -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            バージョン管理
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                'git' => 'Git',
                'github' => 'GitHub',
                'gitlab' => 'GitLab',
                'bitbucket' => 'Bitbucket',
                'svn' => 'SVN',
                'other' => 'その他'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="version_control[]"
                        value="{{ $value }}"
                        {{ in_array($value, old('version_control', $app->version_control ?? [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <!-- その他の場合の入力欄 -->
        <div class="mt-2">
            <input 
                type="text" 
                name="other_version_control" 
                id="other_version_control"
                value="{{ old('other_version_control', $app->other_version_control ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="その他のバージョン管理を入力"
            >
        </div>
        @error('version_control')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- モニター環境 -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700">
            モニター環境
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
            @foreach([
                '1' => '1台',
                '2' => '2台',
                '3' => '3台',
                '4+' => '4台以上'
            ] as $value => $label)
                <label class="flex items-center gap-2">
                    <input
                        type="radio"
                        name="monitor_count"
                        value="{{ $value }}"
                        {{ old('monitor_count', $app->monitor_count ?? '') == $value ? 'checked' : '' }}
                        class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        @error('monitor_count')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

        <!-- メインモニター -->
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">メインモニター</label>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
                @foreach([
                    '24' => '24インチ以下',
                    '27' => '27インチ',
                    '32' => '32インチ',
                    '34' => '34インチウルトラワイド',
                    '38' => '38インチウルトラワイド',
                    '43+' => '43インチ以上',
                    'other' => 'その他'
                ] as $value => $label)
                    <label class="flex items-center gap-2">
                        <input
                            type="radio"
                            name="main_monitor_size"
                            value="{{ $value }}"
                            {{ old('main_monitor_size', $app->main_monitor_size ?? '') == $value ? 'checked' : '' }}
                            class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500"
                        >
                        <span class="text-gray-700">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            @error('main_monitor_size')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- 解像度 -->
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">解像度</label>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
                @foreach([
                    'fhd' => 'FHD (1920x1080)',
                    '2k' => '2K (2560x1440)',
                    '4k' => '4K (3840x2160)',
                    '5k' => '5K以上',
                    'other' => 'その他'
                ] as $value => $label)
                    <label class="flex items-center gap-2">
                        <input
                            type="radio"
                            name="main_monitor_resolution"
                            value="{{ $value }}"
                            {{ old('main_monitor_resolution', $app->main_monitor_resolution ?? '') == $value ? 'checked' : '' }}
                            class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500"
                        >
                        <span class="text-gray-700">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            @error('main_monitor_resolution')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- モニター詳細情報 -->
        <div class="mt-4">
            <label for="monitor_details" class="block text-sm font-medium text-gray-700">
                モニター詳細情報
            </label>
            <textarea 
                name="monitor_details" 
                id="monitor_details"
                rows="8"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="例：
- メイン：Dell U2720Q 4K 27インチ IPS
- サブ1：LG 34WN80C-B 34インチウルトラワイド
- サブ2：ASUS ProArt PA278CV 27インチ
- モニターアーム：エルゴトロン LX デュアル"
            >{{ old('monitor_details', $app->monitor_details ?? '') }}</textarea>
            @error('monitor_details')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- 補足情報 -->
    <div class="mb-6">
        <label for="dev_env_notes" class="block text-sm font-medium text-gray-700">
            補足情報
        </label>
        <textarea 
            name="dev_env_notes" 
            id="dev_env_notes"
            rows="8"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            placeholder="その他の開発環境に関する補足情報があれば入力してください"
        >{{ old('dev_env_notes', $app->dev_env_notes ?? '') }}</textarea>
        @error('dev_env_notes')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div> 