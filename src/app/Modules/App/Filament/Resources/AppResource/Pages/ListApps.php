<?php

namespace App\Modules\App\Filament\Resources\AppResource\Pages;

use App\Modules\App\Filament\Resources\AppResource;
use Filament\Resources\Pages\ListRecords;

class ListApps extends ListRecords
{
    protected static string $resource = AppResource::class;

    protected function getDefaultSortColumn(): ?string
    {
        return 'created_at';
    }

    protected function getDefaultSortDirection(): ?string
    {
        return 'desc';
    }
} 