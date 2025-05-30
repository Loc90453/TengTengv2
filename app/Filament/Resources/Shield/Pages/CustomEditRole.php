<?php

declare(strict_types=1);

namespace App\Filament\Resources\Shield\Pages;

use App\Filament\Resources\CustomRoleResource;
use BezhanSalleh\FilamentShield\Resources\RoleResource\Pages\EditRole;
use Exception;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class CustomEditRole extends EditRole
{
    protected static string $resource = CustomRoleResource::class;

    protected static ?string $title = 'Sửa chức vụ';

    protected static ?string $breadcrumb = 'Sửa';

    public function shouldGetConfirm(): bool
    {
        try {
            $selectedAll = $this->form->getState()['select_all'] ?? false;
        } catch (Exception $e) {
            return false;
        }

        return $selectedAll;
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label('Lưu lại')
            ->requiresConfirmation(
                fn () => $this->shouldGetConfirm()
            )
            ->modalDescription(
                fn () => $this->shouldGetConfirm()
                ? 'Bạn có chắc chắn muốn sửa vai trò với toàn bộ quyền không?'
                : null
            )
            ->action(function () {
                $this->closeActionModal();
                $this->save();
            });

    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Sửa thành công')
            ->body('Đã sửa quyền hạn.');
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Hủy');
    }
}
