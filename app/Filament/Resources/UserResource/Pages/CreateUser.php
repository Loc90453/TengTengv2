<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\CreateAndRedirectToIndex;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateAndRedirectToIndex
{
    protected static string $resource = UserResource::class;
}
