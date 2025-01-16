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
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('title')
                ->label('アプリ名')
                ->searchable()
                ->sortable(),
            TextColumn::make('status')
                ->label('公開状態'),
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