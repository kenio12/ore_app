<?php

namespace App\Modules\App\Filament\Resources;

use App\Modules\App\Models\App;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Repeater;
use App\Modules\App\Filament\Resources\AppResource\Pages;

class AppResource extends Resource
{
    protected static ?string $model = App::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'アプリ一覧';
    protected static ?string $modelLabel = 'アプリ';
    protected static ?string $pluralModelLabel = 'アプリ';
    protected static ?string $slug = 'apps';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // 基本情報セクション
            Forms\Components\Section::make('基本情報')
                ->schema([
                    TextInput::make('title')
                        ->label('アプリ名')
                        ->required()
                        ->maxLength(255),
                    
                    Textarea::make('description')
                        ->label('アプリの紹介')
                        ->required(),
                    
                    Select::make('status')
                        ->label('公開状態')
                        ->options([
                            'published' => '公開',
                            'draft' => '下書き'
                        ])
                        ->required(),
                    
                    TextInput::make('demo_url')
                        ->label('デモURL')
                        ->url(),

                    Select::make('app_status')
                        ->label('開発状態')
                        ->options(config('app-module.constants.app_status'))
                        ->required(),

                    TextInput::make('color')
                        ->label('カラー')
                        ->type('color'),
                ]),

            // スクリーンショットセクション
            Forms\Components\Section::make('スクリーンショット')
                ->schema([
                    Repeater::make('screenshots')
                        ->schema([
                            TextInput::make('url')
                                ->label('画像URL'),
                            TextInput::make('temp_public_id')
                                ->label('Cloudinary ID'),
                        ])
                        ->columns(2)
                ]),

            // アプリ種別セクション
            Forms\Components\Section::make('アプリの種類')
                ->schema([
                    CheckboxList::make('app_types')
                        ->label('アプリの種類（複数選択可）')
                        ->options([
                            'web_app' => 'Webアプリ',
                            'ios_app' => 'iOSアプリ',
                            'android_app' => 'Androidアプリ',
                            'windows_app' => 'Windowsアプリ',
                            'mac_app' => 'Macアプリ',
                            'linux_app' => 'Linuxアプリ',
                            'game' => 'ゲーム',
                            'other' => 'その他'
                        ])
                        ->required()
                        ->columnSpanFull(),
                    
                    CheckboxList::make('genres')
                        ->label('ジャンル（複数選択可）')
                        ->options([
                            'web' => 'Webアプリ',
                            'mobile' => 'モバイルアプリ',
                            'desktop' => 'デスクトップアプリ',
                            'game' => 'ゲーム',
                            'tool' => 'ツール',
                            'business' => 'ビジネス',
                            'education' => '教育',
                            'entertainment' => 'エンターテイメント',
                            'utility' => 'ユーティリティ',
                            'productivity' => '生産性',
                            'social' => 'ソーシャル',
                            'communication' => 'コミュニケーション',
                            'development' => '開発ツール',
                            'graphics' => 'グラフィックス',
                            'multimedia' => 'マルチメディア',
                            'security' => 'セキュリティ',
                            'system' => 'システム',
                            'network' => 'ネットワーク',
                            'database' => 'データベース',
                            'ai' => 'AI/機械学習',
                            'blockchain' => 'ブロックチェーン',
                            'iot' => 'IoT',
                            'ar_vr' => 'AR/VR',
                            'other' => 'その他'
                        ])
                        ->columnSpanFull(),
                ]),

            // 技術スタックセクション
            Forms\Components\Section::make('技術スタック')
                ->schema([
                    TextInput::make('github_url')
                        ->label('GitHubリポジトリURL')
                        ->url(),
                    
                    CheckboxList::make('tech_stack')
                        ->label('使用技術（複数選択可）')
                        ->options(config('app.tech_stack_labels', [])),
                ]),

            // 開発期間セクション
            Forms\Components\Section::make('開発期間')
                ->schema([
                    Forms\Components\Grid::make(2)
                        ->schema([
                            TextInput::make('development_period_years')
                                ->label('開発期間（年）')
                                ->type('number')
                                ->minValue(0)
                                ->maxValue(99)
                                ->default(0)
                                ->required()
                                ->integer()
                                ->step(1),

                            TextInput::make('development_period_months')
                                ->label('開発期間（月）')
                                ->type('number')
                                ->minValue(0)
                                ->maxValue(11)
                                ->default(0)
                                ->required()
                                ->integer()
                                ->step(1),
                        ]),

                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\DatePicker::make('development_start_date')
                                ->label('開発開始日')
                                ->format('Y-m-d')
                                ->required()
                                ->displayFormat('Y年m月d日'),

                            Forms\Components\DatePicker::make('development_end_date')
                                ->label('開発終了日')
                                ->format('Y-m-d')
                                ->nullable()
                                ->displayFormat('Y年m月d日'),
                        ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->label('アプリ名')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->label('作成日時')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApps::route('/'),
            'create' => Pages\CreateApp::route('/create'),
            'edit' => Pages\EditApp::route('/{record}/edit'),
        ];
    }
} 