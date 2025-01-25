<?php

namespace App\Modules\AppV2\Filament\Resources;

use App\Modules\AppV2\Models\App;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use App\Modules\AppV2\Filament\Resources\AppV2Resource\Pages;
use Illuminate\Database\Eloquent\Model;

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
            Forms\Components\Hidden::make('user_id')
                ->default(fn () => auth()->id()),
            Forms\Components\TextInput::make('title')->required(),
            Forms\Components\Textarea::make('description'),
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
                Tables\Columns\TextColumn::make('title')
                    ->label('アプリ名')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('公開状態')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('更新日時')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('作成日時')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->modifyQueryUsing(fn ($query) => $query->where('user_id', auth()->id()));
    }

    // レコード作成前に実行
    public static function beforeCreate(Model $record): void
    {
        $record->user_id = auth()->id();
    }
} 