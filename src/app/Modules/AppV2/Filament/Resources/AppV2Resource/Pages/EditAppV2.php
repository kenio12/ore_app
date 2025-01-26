<?php

namespace App\Modules\AppV2\Filament\Resources\AppV2Resource\Pages;

use App\Modules\AppV2\Filament\Resources\AppV2Resource;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms;

class EditAppV2 extends EditRecord
{
    protected static string $resource = AppV2Resource::class;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Tabs::make('アプリ情報')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('基本情報')
                        ->schema([
                            Forms\Components\TextInput::make('id')
                                ->label('ID')
                                ->disabled(),
                            Forms\Components\TextInput::make('title')
                                ->label('タイトル')
                                ->required(),
                            Forms\Components\Textarea::make('description')
                                ->label('説明')
                                ->rows(3),
                            Forms\Components\Textarea::make('motivation')
                                ->label('開発動機')
                                ->rows(3),
                            Forms\Components\Textarea::make('purpose')
                                ->label('目的')
                                ->rows(3),
                            Forms\Components\TextInput::make('demo_url')
                                ->label('デモURL'),
                            Forms\Components\TextInput::make('github_url')
                                ->label('GitHubリポジトリ'),
                            Forms\Components\Select::make('status')
                                ->label('公開状態')
                                ->options([
                                    'draft' => '下書き',
                                    'published' => '公開',
                                ])
                        ]),
                    Forms\Components\Tabs\Tab::make('開発情報')
                        ->schema([
                            Forms\Components\Select::make('app_status')
                                ->label('開発状態')
                                ->options([
                                    'in_development' => '開発中',
                                    'completed' => '完了',
                                    'maintenance' => 'メンテナンス中',
                                ]),
                            Forms\Components\DatePicker::make('development_start_date')
                                ->label('開発開始日'),
                            Forms\Components\DatePicker::make('development_end_date')
                                ->label('開発終了日'),
                            Forms\Components\TextInput::make('development_period_years')
                                ->label('開発期間（年）')
                                ->numeric(),
                            Forms\Components\TextInput::make('development_period_months')
                                ->label('開発期間（月）')
                                ->numeric(),
                        ]),
                    Forms\Components\Tabs\Tab::make('技術情報')
                        ->schema([
                            Forms\Components\KeyValue::make('frontend_info')
                                ->label('フロントエンド情報'),
                            Forms\Components\KeyValue::make('backend_info')
                                ->label('バックエンド情報'),
                            Forms\Components\KeyValue::make('database_info')
                                ->label('データベース情報'),
                            Forms\Components\KeyValue::make('dev_env_info')
                                ->label('開発環境情報'),
                            Forms\Components\KeyValue::make('architecture_info')
                                ->label('アーキテクチャ情報'),
                            Forms\Components\KeyValue::make('security_info')
                                ->label('セキュリティ情報'),
                        ]),
                ])->columnSpan('full')
        ];
    }
} 