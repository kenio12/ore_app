<?php

namespace App\Modules\AppV2\Filament\Resources\AppV2Resource\Pages;

use App\Modules\AppV2\Filament\Resources\AppV2Resource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;

class ListAppsV2 extends ListRecords
{
    protected static string $resource = AppV2Resource::class;

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->sortable(),
            Tables\Columns\TextColumn::make('title')
                ->label('タイトル')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->label('作成日')
                ->dateTime()
                ->sortable(),
        ];
    }
} 