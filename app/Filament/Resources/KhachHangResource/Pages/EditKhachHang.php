<?php

declare(strict_types=1);

namespace App\Filament\Resources\KhachHangResource\Pages;

use App\Filament\EditAndRedirectToIndex;
use App\Filament\Resources\KhachHangResource;
use Filament\Actions;
use Filament\Notifications\Notification;

class EditKhachHang extends EditAndRedirectToIndex
{
    protected static string $resource = KhachHangResource::class;

    protected static ?string $title = 'Sửa khách hàng';

    protected static ?string $breadcrumb = 'Sửa';

    protected function getHeaderActions(): array
    {
        return [
            //            Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Sửa thành công')
            ->body('Đã sửa thông tin khách hàng.');
    }
}
