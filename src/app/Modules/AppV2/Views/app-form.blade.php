<x-app-layout>
    <div x-data="appForm()">
        <div class="space-y-6">
            {{-- タブナビゲーション --}}
            <div class="flex space-x-4 border-b">
                <button 
                    @click="currentTab = 'basic'"
                    :class="{ 'border-b-2 border-blue-500': currentTab === 'basic' }"
                    class="px-4 py-2">
                    基本情報
                </button>
                {{-- 他のタブボタン... --}}
            </div>

            {{-- タブコンテンツ --}}
            <div x-show="currentTab === 'basic'">
                <textarea 
                    x-model="formData.basic.description" 
                    @input="handleInput()"
                    rows="8" 
                    class="mt-4 w-full rounded-lg border-2 border-gray-200 p-4 text-lg
                           focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20
                           hover:border-purple-300 transition-all duration-300" 
                    placeholder="あなたのアプリの特徴や魅力を説明してください...">
                </textarea>
            </div>

            @include('AppV2::tabs._01_basic-tab')
            @include('AppV2::tabs._02_screenshots-tab', [
                'app' => $app ?? null,
                'viewOnly' => false
            ])
            @include('AppV2::tabs._03_story-tab')
            @include('AppV2::tabs._04_hardware-tab')
            @include('AppV2::tabs._05_dev-env-tab')
            @include('AppV2::tabs._06_architecture-tab')
            @include('AppV2::tabs._07_frontend-tab')
            @include('AppV2::tabs._08_backend-tab')
            @include('AppV2::tabs._09_database-tab')
            @include('AppV2::tabs._10_security-tab')
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('appForm', () => ({
                formData: {
                    basic: {
                        title: '',
                        description: '',
                        genres: [],
                        types: []
                    },
                    architecture: {
                        design_patterns: [],
                        description: '',
                        patterns: []
                    },
                    frontend: {
                        framework: '',
                        ui_library: ''
                    },
                    hardware: {
                        storage: ''
                    },
                    dev_env: {
                        editor: '',
                        version_control: '',
                        ci_cd: ''
                    },
                    story: {
                        development_trigger: '',
                        development_hardship: '',
                        development_tearful: '',
                        development_enjoyable: '',
                        development_funny: '',
                        development_impression: '',
                        development_oneword: ''
                    },
                    backend: {
                        languages: [],
                        frameworks: [],
                        packages: []
                    },
                    database: {
                        type: '',
                        design: ''
                    },
                    security: {
                        auth: '',
                        measures: []
                    }
                },
                currentTab: 'basic',
                isEditMode: @json(isset($appId)),
                
                init() {
                    console.log('Form initialized with:', this.formData);
                },

                handleTitleBlur() {
                    if (!this.isEditMode && this.formData.basic.title) {
                        fetch('/apps-v2/create-with-title', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                title: this.formData.basic.title
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.id) {
                                window.location.href = `/apps-v2/edit/${data.id}`;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('アプリの作成に失敗しました。');
                        });
                    }
                },

                handleInput() {
                    if (this.isEditMode) {
                        const appId = window.location.pathname.split('/')[2];
                        fetch(`/apps-v2/${appId}/autosave`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(this.formData)
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Autosaved:', data);
                        })
                        .catch(error => {
                            console.error('Autosave failed:', error);
                        });
                    }
                },

                autoSave() {
                    // ... existing autoSave logic ...
                }
            }));
        });
    </script>
</x-app-layout> 