<?php

declare(strict_types=1);

namespace App\Filament\Resources\VattuResource\Pages;

use App\Filament\Resources\VattuResource;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListVattus extends ListRecords
{
    protected static string $resource = VattuResource::class;

    protected static ?string $title = 'Danh sách vật tư';

    protected static ?string $breadcrumb = 'Danh sách vật tư';

    public function getTabs(): array
    {
        $thanhphamCount = $this->getModel()::where('LaTP', 1)->count();
        $nguyenvatlieuCount = $this->getModel()::count() - $thanhphamCount;

        $tabs['all'] = Tab::make('All')
            ->badge($this->getModel()::count())
            ->badgeColor('gray');
        $tabs['tp'] = Tab::make('Thành phẩm')
            ->modifyQueryUsing(function ($query) {
                return $query->where('LaTP', 1);
            })
            ->badgeColor('success')
            ->badge($thanhphamCount);

        $tabs['nvl'] = Tab::make('Nguyên vật liệu')
            ->modifyQueryUsing(function ($query) {
                return $query->where('LaTP', 0);
            })
            ->badgeColor('info')
            ->badge($nguyenvatlieuCount);

        return $tabs;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus')
                ->label('Tạo mới'),

            ImportAction::make()
                ->importer(\App\Filament\Imports\VattuImporter::class)
                ->label('Nhập CSV')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('info'),

            Actions\ExportAction::make()
                ->exporter(\App\Filament\Exports\VattuExporter::class)
                ->label('Xuất excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success'),
        ];
    }
}
