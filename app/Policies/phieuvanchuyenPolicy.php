<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\phieuvanchuyen;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class phieuvanchuyenPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_phieu::van::chuyen');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, phieuvanchuyen $phieuvanchuyen): bool
    {
        return $user->can('view_phieu::van::chuyen');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_phieu::van::chuyen');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, phieuvanchuyen $phieuvanchuyen): bool
    {
        return $user->can('update_phieu::van::chuyen');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, phieuvanchuyen $phieuvanchuyen): bool
    {
        return $user->can('delete_phieu::van::chuyen');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_phieu::van::chuyen');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, phieuvanchuyen $phieuvanchuyen): bool
    {
        return $user->can('{{ ForceDelete }}');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('{{ ForceDeleteAny }}');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, phieuvanchuyen $phieuvanchuyen): bool
    {
        return $user->can('{{ Restore }}');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('{{ RestoreAny }}');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, phieuvanchuyen $phieuvanchuyen): bool
    {
        return $user->can('{{ Replicate }}');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('{{ Reorder }}');
    }

    public function duyetphieuvanchuyen(User $user): bool
    {
        return $user->can('duyetphieuvanchuyen_phieu::van::chuyen');
    }
}
