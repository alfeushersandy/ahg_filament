<?php

namespace App\Filament\Resources\GudangResource\Pages;

use App\Filament\Resources\GudangResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageGudangs extends ManageRecords
{
    protected static string $resource = GudangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
