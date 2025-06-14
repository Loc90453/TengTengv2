<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\NhanVienResource\Pages;
use App\Models\nhanvien;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NhanVienResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = nhanvien::class;

    protected static ?string $modelLabel = 'Nhân viên';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $activeNavigationIcon = 'heroicon-s-users';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Nhân viên';

    protected static ?string $navigationGroup = 'Quản lý danh mục';

    protected static ?string $slug = 'nhanvien';

    public static function getBreadcrumb(): string
    {
        return 'Nhân viên';
    }

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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin nhân viên')
                    ->description('Thông tin chi tiết của nhân viên')
                    ->aside()
                    ->schema([
                        FileUpload::make('image')
                            ->label('Ảnh đại diện')
                            ->image()
                            ->imageEditor()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->directory('nhanvien')
                            ->visibility('public')
                            ->maxSize(5120) // 5MB
                            ->helperText('Định dạng: JPG, PNG. Kích thước tối đa: 5MB')
                            ->downloadable()
                            ->reorderable(false)
                            ->columnSpanFull(),
                        TextInput::make('name')
                            ->label('Tên nhân viên')
                            ->required(),
                        DatePicker::make('Birth')
                            ->label('Ngày sinh')
                            ->displayFormat('d/m/Y')
                            ->required(),
                        TextInput::make('Phone')
                            ->label('Số điện thoại')
                            ->required()
                            ->tel()
                            ->prefix('+84')
                            ->regex('/^(0\d{9}|[1-9]\d{8})$/')
                            ->validationMessages([
                                'regex' => 'Số điện thoại sai quy cách.',
                            ]),
                        TextInput::make('Address')
                            ->label('Địa chỉ')
                            ->required(),
                        TextInput::make('cccd')
                            ->label('Căn cước công dân')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->regex('/^\d{12}$/')
                            ->validationMessages([
                                'regex' => 'CCCD phải có đúng 12 chữ số.',
                                'unique' => 'CCCD này đã tồn tại trong hệ thống.',
                            ]),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Không có nhân viên')
            ->emptyStateDescription('Vui lòng thêm dữ liệu hoặc thay đổi bộ lọc tìm kiếm.')
            ->columns([
                ImageColumn::make('image')
                    ->alignCenter()
                    ->label('Ảnh')
                    ->circular()
                    ->defaultImageUrl(function (nhanvien $record): string {
                        $name = $record->name;

                        return "https://ui-avatars.com/api/?name={$name}";
                    }),
                TextColumn::make('name')
                    ->label('Tên nhân viên')
                    ->alignLeft()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('Birth')
                    ->label('Ngày sinh')
                    ->alignCenter()
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('Phone')
                    ->label('Số điện thoại')
                    ->alignLeft()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('Address')
                    ->label('Địa chỉ')
                    ->alignLeft()
                    ->searchable()
                    ->wrap(),
                TextColumn::make('cccd')
                    ->label('CCCD')
                    ->alignLeft()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->alignCenter()
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])->striped()
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('create_account')
                    ->visible(fn(nhanvien $record): bool => ! $record->user)
                    ->label('Tạo TK')
                    ->icon('heroicon-o-user-plus')
                    ->color('success')
                    ->url(fn(nhanvien $record): string => UserResource::getUrl('create', [
                        'nhanvien_id' => $record->id,
                    ])),
                ActionGroup::make([
                    ViewAction::make()->color('secondary'),
                    EditAction::make()->color('primary'),
                    DeleteAction::make()
                        ->action(
                            function ($record): void {
                                $hasRelatedRecords = false;
                                $relationshipMessages = [];

                                // Check if nhanvien has a linked user account
                                if ($record->user) {
                                    $hasRelatedRecords = true;
                                    $relationshipMessages[] = 'tài khoản người dùng';
                                }

                                // Check phieunhap relationship
                                if ($record->phieunhap()->count() > 0) {
                                    $hasRelatedRecords = true;
                                    $relationshipMessages[] = 'phiếu nhập';
                                }

                                // Check phieuxuat relationship
                                if ($record->phieuxuat()->count() > 0) {
                                    $hasRelatedRecords = true;
                                    $relationshipMessages[] = 'phiếu xuất';
                                }

                                if ($hasRelatedRecords) {
                                    Notification::make()
                                        ->danger()
                                        ->title('Xoá không thành công')
                                        ->body('Nhân viên đang được sử dụng trong: ' . implode(', ', $relationshipMessages) . '!')
                                        ->send();

                                    return;
                                }

                                $record->delete();
                                Notification::make()
                                    ->success()
                                    ->title('Xoá thành công')
                                    ->body('Nhân viên đã xoá thành công!')
                                    ->send();
                            }
                        ),
                ]),
            ])
            ->bulkActions([
                //
                ExportBulkAction::make()
                    ->exporter(\App\Filament\Exports\NhanvienExporter::class)
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
            'index' => Pages\ListNhanViens::route('/'),
            'create' => Pages\CreateNhanVien::route('/create'),
            'edit' => Pages\EditNhanVien::route('/{record}/edit'),
        ];
    }
}
