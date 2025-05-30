<?php

declare(strict_types=1);

namespace App\Filament\Resources\PhieuSuCoResource\Pages;

use App\Filament\EditAndRedirectToIndex;
use App\Filament\Resources\PhieuSuCoResource;
use App\Models\phieuvanchuyen;
use App\Models\phieuxuat;
use Filament\Actions;
use Filament\Notifications\Notification;
use Livewire\Attributes\On;

class EditPhieuSuCo extends EditAndRedirectToIndex
{
    protected static string $resource = PhieuSuCoResource::class;

    public function getTitle(): string
    {
        return 'Chỉnh sửa phiếu sự cố';
    }

    #[On('phieuvanchuyenSelected')]
    public function onPhieuvanchuyenSelected($data): void
    {
        $this->data['phieuvanchuyen_id'] = $data['phieuvanchuyen_id'];

        $phieuvanchuyen = phieuvanchuyen::find($data['phieuvanchuyen_id']);

        if ($phieuvanchuyen && ! empty($phieuvanchuyen->phieuxuat_id)) {
            $this->data['phieuxuat_id'] = $phieuvanchuyen->phieuxuat_id;
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Xóa'),
        ];
    }

    // #[On('phieuxuatSelected')]
    // public function onPhieuxuatSelected($data): void
    // {
    //     $this->data['phieuxuat_id'] = $data['phieuxuat_id'];

    //     // Notification::make()
    //     //     ->title('Đã chọn phiếu xuất')
    //     //     ->success()
    //     //     ->duration(1500)
    //     //     ->send();
    // }
}
