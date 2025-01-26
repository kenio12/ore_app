<?php

namespace App\Modules\AppV2\Filament\Resources;

use App\Modules\AppV2\Models\App;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use App\Modules\AppV2\Filament\Resources\AppV2Resource\Pages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AppV2Resource extends Resource
{
    protected static ?string $model = App::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Apps V2';
    protected static ?string $slug = 'apps-v2';
    protected static ?string $recordTitleAttribute = 'title';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppsV2::route('/'),
            'create' => Pages\CreateAppV2::route('/create'),
            'edit' => Pages\EditAppV2::route('/{record}/edit'),
        ];
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('id')
                ->label('ID')
                ->disabled(),
            Forms\Components\TextInput::make('user.name')
                ->label('作成者')
                ->disabled()
                ->dehydrated(false)
                ->formatStateUsing(fn ($record) => $record?->user?->name ?? '不明'),
            Forms\Components\Hidden::make('user_id')
                ->default(fn () => auth()->id())
                ->required(),
            Forms\Components\TextInput::make('title')->required(),
            Forms\Components\Textarea::make('description'),
            Forms\Components\Textarea::make('motivation')
                ->label('開発動機'),
            Forms\Components\Textarea::make('purpose')
                ->label('目的'),
            Forms\Components\TextInput::make('demo_url'),
            Forms\Components\TextInput::make('github_url'),
            Forms\Components\Select::make('status')
                ->options([
                    'draft' => 'Draft',
                    'published' => 'Published',
                ])->default('draft'),
            Forms\Components\Select::make('app_status')
                ->options([
                    'in_development' => '開発中',
                    'completed' => '完了',
                ])->default('in_development'),
            Forms\Components\TagsInput::make('app_types')
                ->separator(','),
            Forms\Components\TagsInput::make('genres')
                ->separator(','),
            Forms\Components\DatePicker::make('development_start_date'),
            Forms\Components\DatePicker::make('development_end_date'),
            Forms\Components\TextInput::make('development_period_years')
                ->numeric()
                ->default(0),
            Forms\Components\TextInput::make('development_period_months')
                ->numeric()
                ->default(0),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('作成者')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('アプリ名')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('更新日時')
                    ->dateTime('Y-m-d H:i:s')
                    ->timezone('Asia/Tokyo')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('作成日時')
                    ->dateTime('Y-m-d H:i:s')
                    ->timezone('Asia/Tokyo')
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->modifyQueryUsing(fn ($query) => $query->where('user_id', auth()->id()));
    }

    // レコード作成前に実行
    public static function beforeCreate(Model $record): void
    {
        $record->user_id = auth()->id();
        Log::debug('New app record:', [
            'app_id' => $record->id,
            'user_id' => $record->user_id,
            'user_name' => auth()->user()->name
        ]);
    }

    // デバッグ用のログを追加
    public static function beforeFill($record): void
    {
        Log::debug('Loading app record:', [
            'app_id' => $record->id,
            'user_id' => $record->user_id,
            'user' => $record->user,
            'user_name' => $record->user->name ?? 'null'
        ]);
    }

    // リレーションを明示的に読み込む
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::with('user')->count();
    }

    // モデルに関連するユーザーを必ず読み込む
    public static function getNavigationGroup(): ?string
    {
        return 'アプリ管理';
    }
} 