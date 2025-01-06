<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-bold mb-6">基本開発環境</h2>

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