<?php

namespace App\Filament\Resources\DonvitinhResource\Pages;

use App\Filament\EditAndRedirectToIndex;
use App\Filament\Resources\DonvitinhResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDonvitinh extends EditAndRedirectToIndex
{
    protected static string $resource = DonvitinhResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
