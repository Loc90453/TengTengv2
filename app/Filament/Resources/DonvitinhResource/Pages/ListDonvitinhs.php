<?php

declare(strict_types=1);

namespace App\Filament\Resources\DonvitinhResource\Pages;

use App\Filament\Exports\DonvitinhExporter;
use App\Filament\Resources\DonvitinhResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDonvitinhs extends ListRecords
{
    protected static string $resource = DonvitinhResource::class;

    protected static ?string $title = 'Đơn vị tính';

    protected static ?string $breadcrumb = 'Danh sách đơn vị tính';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus')
                ->label('Tạo mới'),

            Actions\ExportAction::make()
                ->exporter(DonvitinhExporter::class)
                ->label('Xuất excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success'),
        ];
    }
}
