<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Exports\TaixeExporter;
use App\Filament\Resources\TaixeResource\Pages;
use App\Models\taixe;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class TaixeResource extends Resource implements HasShieldPermissions
{
    public static $trangthai = [
        0 => 'Đang giao',
        1 => 'Có sẵn',
        2 => 'Nghỉ',
    ];

    protected static ?string $model = taixe::class;

    protected static ?string $modelLabel = 'Tài xế';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $activeNavigationIcon = 'heroicon-s-user';

    protected static ?string $navigationLabel = 'Tài xế';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Quản lý vận chuyển';

    protected static ?string $slug = 'taixe';

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
        ];
    }

    public static function getBreadcrumb(): string
    {
        return 'Tài xế';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin tài xế')
                    ->description('Thông tin chi tiết về tài xế mới')
                    ->aside()
                    ->schema([
                        Radio::make('TrangThai')
                            ->visibleOn('edit')
                            ->inline()
                            ->live()
                            ->label('Trạng thái')
                            ->options(self::$trangthai),
                        TextInput::make('TenTaiXe')
                            ->label('Tên tài xế')
                            ->required(),
                        TextInput::make('Sdt')
                            ->label('Số điện thoại')
                            ->required()->unique(ignoreRecord: true)
                            ->prefix('+84')
                            ->regex('/^(0\d{9}|[1-9]\d{8})$/')
                            ->validationMessages([
                                'regex' => 'Số điện thoại sai quy cách.',
                            ])
                            ->validationMessages([
                                'unique' => 'Số điện thoại này đã tồn tại.',
                            ]),
                        TextInput::make('CCCD')
                            ->label('Số căn cước')
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->validationMessages([
                                'unique' => 'Số CCCD này đã tồn tại.',
                            ]),
                        TextInput::make('BangLai')
                            ->label('Bằng lái')
                            ->required(),
                        TextInput::make('DiaChi')
                            ->label('Địa chỉ'),
                        DatePicker::make('NamSinh')
                            ->label('Ngày sinh')
                            ->displayFormat('d/m/Y'),
                        Textarea::make('GhiChu')
                            ->label('Ghi chú'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Không có tài xế')
            ->emptyStateDescription('Vui lòng thêm dữ liệu hoặc thay đổi bộ lọc tìm kiếm.')
            ->columns([
                TextColumn::make('TenTaiXe')
                    ->label('Tên tài xế')
                    ->alignLeft()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('Sdt')
                    ->label('Số điện thoại')
                    ->alignCenter()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('CCCD')
                    ->label('Số căn cước')
                    ->alignCenter()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('BangLai')
                    ->label('Bằng lái')
                    ->alignCenter()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('DiaChi')
                    ->label('Địa chỉ')
                    ->alignLeft()
                    ->searchable()
                    ->limit(50)
                    ->tooltip(fn($record) => $record->DiaChi),
                TextColumn::make('NamSinh')
                    ->label('Năm sinh')
                    ->alignCenter()
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('GhiChu')
                    ->label('Ghi chú')
                    ->alignLeft()
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->limit(50)
                    ->tooltip(fn($record) => $record->GhiChu),
                TextColumn::make('TrangThai')
                    ->label('Trạng thái')
                    ->alignCenter()
                    ->icon(fn($record): string => match ($record->TrangThai) {
                        0 => 'heroicon-o-clock',
                        1 => 'heroicon-o-check-circle',
                        2 => 'heroicon-o-x-circle',
                        default => ''
                    })
                    ->formatStateUsing(fn($record) => match ($record->TrangThai) {
                        0 => 'Đang giao',
                        1 => 'Có sẵn',
                        2 => 'Nghỉ',
                        default => 'N/A'
                    })
                    ->badge()
                    ->color(fn($record): string => match ($record->TrangThai) {
                        0 => 'success',
                        1 => 'info',
                        2 => 'danger',
                        default => ''
                    })
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()->color('amber'),
                    DeleteAction::make()
                        ->action(
                            function ($record): void {
                                if ($record->phieuvanchuyen()->count() > 0) {
                                    Notification::make()
                                        ->danger()
                                        ->title('Xoá không thành công')
                                        ->body('Tài xế đang được sử dụng trong phiếu vận chuyển!')
                                        ->send();

                                    return;
                                }
                                $record->delete();
                                Notification::make()
                                    ->success()
                                    ->title('Xoá thành công')
                                    ->body('Tài xế đã xoá thành công!')
                                    ->send();
                            }
                        ),
                ])
            ])
            ->bulkActions([
                //
                ExportBulkAction::make()
                    ->exporter(TaixeExporter::class)
                    ->label('Xuất excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('primary'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTaixes::route('/'),
            'create' => Pages\CreateTaixe::route('/create'),
            'edit' => Pages\EditTaixe::route('/{record}/edit'),
        ];
    }
}
