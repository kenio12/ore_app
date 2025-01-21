<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-6">
    <h2 class="text-2xl font-bold text-gray-900">基本開発環境</h2>

    {{-- チーム規模 --}}
    @if($app->basicDevEnvironment && $app->basicDevEnvironment->dev_team_size)
        <div class="space-y-2">
            <h3 class="text-lg font-semibold">チーム規模</h3>
            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                {{ config('constants.basic_dev_env.team_sizes')[$app->basicDevEnvironment->dev_team_size] ?? $app->basicDevEnvironment->dev_team_size }}
            </span>
        </div>
    @endif

    {{-- 仮想化ツール --}}
    @if($app->basicDevEnvironment && $app->basicDevEnvironment->virtualization)
        <div class="space-y-2">
            <h3 class="text-lg font-semibold">仮想化ツール</h3>
            <div class="flex flex-wrap gap-2">
                @foreach(json_decode($app->basicDevEnvironment->virtualization) as $tool)
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                        {{ config('constants.basic_dev_env.virtualization_tools')[$tool] ?? $tool }}
                    </span>
                @endforeach
                @if($app->basicDevEnvironment->other_virtualization)
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                        {{ $app->basicDevEnvironment->other_virtualization }}
                    </span>
                @endif
            </div>
        </div>
    @endif

    {{-- OS情報 --}}
    @if($app->basicDevEnvironment && $app->basicDevEnvironment->os_type)
        <div class="space-y-2">
            <h3 class="text-lg font-semibold">OS環境</h3>
            <div class="flex flex-wrap gap-2">
                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">
                    {{ config('constants.basic_dev_env.os_types')[$app->basicDevEnvironment->os_type] ?? $app->basicDevEnvironment->os_type }}
                </span>
                @if($app->basicDevEnvironment->os_version)
                    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">
                        バージョン: {{ $app->basicDevEnvironment->os_version }}
                    </span>
                @endif
            </div>
        </div>
    @endif

    {{-- エディタ --}}
    @if($app->basicDevEnvironment && $app->basicDevEnvironment->editors)
        <div class="space-y-2">
            <h3 class="text-lg font-semibold">使用エディタ</h3>
            <div class="flex flex-wrap gap-2">
                @foreach(json_decode($app->basicDevEnvironment->editors) as $editor)
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                        {{ config('constants.basic_dev_env.editors')[$editor] ?? $editor }}
                    </span>
                @endforeach
                @if($app->basicDevEnvironment->other_editor)
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                        {{ $app->basicDevEnvironment->other_editor }}
                    </span>
                @endif
            </div>
        </div>
    @endif

    {{-- バージョン管理 --}}
    @if($app->basicDevEnvironment && $app->basicDevEnvironment->version_control)
        <div class="space-y-2">
            <h3 class="text-lg font-semibold">バージョン管理</h3>
            <div class="flex flex-wrap gap-2">
                @foreach(json_decode($app->basicDevEnvironment->version_control) as $tool)
                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">
                        {{ config('constants.basic_dev_env.version_control')[$tool] ?? $tool }}
                    </span>
                @endforeach
                @if($app->basicDevEnvironment->other_version_control)
                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">
                        {{ $app->basicDevEnvironment->other_version_control }}
                    </span>
                @endif
            </div>
        </div>
    @endif

    {{-- モニター環境 --}}
    @if($app->basicDevEnvironment && ($app->basicDevEnvironment->monitor_count || $app->basicDevEnvironment->main_monitor_size || $app->basicDevEnvironment->main_monitor_resolution))
        <div class="space-y-2">
            <h3 class="text-lg font-semibold">モニター環境</h3>
            <div class="flex flex-wrap gap-2">
                @if($app->basicDevEnvironment->monitor_count)
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm">
                        {{ config('constants.basic_dev_env.monitor_counts')[$app->basicDevEnvironment->monitor_count] ?? $app->basicDevEnvironment->monitor_count }}
                    </span>
                @endif
                @if($app->basicDevEnvironment->main_monitor_size)
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm">
                        {{ config('constants.basic_dev_env.monitor_sizes')[$app->basicDevEnvironment->main_monitor_size] ?? $app->basicDevEnvironment->main_monitor_size }}
                    </span>
                @endif
                @if($app->basicDevEnvironment->main_monitor_resolution)
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm">
                        {{ config('constants.basic_dev_env.monitor_resolutions')[$app->basicDevEnvironment->main_monitor_resolution] ?? $app->basicDevEnvironment->main_monitor_resolution }}
                    </span>
                @endif
            </div>
            @if($app->basicDevEnvironment->monitor_details)
                <p class="text-gray-600">{{ $app->basicDevEnvironment->monitor_details }}</p>
            @endif
        </div>
    @endif

    {{-- 追加の開発環境メモ --}}
    @if($app->basicDevEnvironment && $app->basicDevEnvironment->dev_env_notes)
        <div class="space-y-2">
            <h3 class="text-lg font-semibold">追加の開発環境メモ</h3>
            <p class="text-gray-600">{{ $app->basicDevEnvironment->dev_env_notes }}</p>
        </div>
    @endif
</div> 