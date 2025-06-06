<?php

declare(strict_types=1);

namespace App\Filament\Resources\PhieuNhapResource\Pages;

use App\Filament\EditAndRedirectToIndex;
use App\Filament\Resources\PhieuNhapResource;
use App\Models\chitietphieunhap;
use App\Models\kho;
use App\Models\phieunhap;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Livewire\Attributes\On;

class EditPhieuNhap extends EditAndRedirectToIndex
{
    protected static string $resource = PhieuNhapResource::class;

    protected static ?string $title = 'Sửa phiếu nhập';

    protected static ?string $breadcrumb = 'Sửa';

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

    public function getContentTabLabel(): ?string
    {
        return 'Sửa';
    }

    #[On('khoSelected')]
    public function handleKhoSelected($record): void
    {
        $state = $this->form->getRawState();

        $state['kho_id'] = $record['kho_id'];
        $state['TenKho'] = $record['TenKho'];

        $this->form->fill($state);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {

        $kho = kho::find($data['kho_id']);
        if ($kho) {
            $data['TenKho'] = $kho->TenKho;
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->hidden(fn ($record): bool => $record->TrangThai == 1 || $record->TrangThai == 2)
                ->requiresConfirmation()
                ->modalDescription('Xoá phiếu nhập sẽ xoá tất cả thông tin kèm theo. Bạn chắc chắn chưa?')
                ->action(
                    function ($data, $record): void {
                        chitietphieunhap::where('phieunhap_id', $record->id)->delete();

                        phieunhap::destroy($record->id);

                        Notification::make()
                            ->title('Đã xoá phiếu nhập thành công!')
                            ->success()
                            ->send();

                        redirect()->to(route('filament.admin.resources.phieunhap.index'));
                    }
                ),
        ];
    }
}
