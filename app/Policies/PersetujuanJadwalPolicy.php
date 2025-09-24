<?php

namespace App\Policies;

use App\Models\PersetujuanJadwal;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PersetujuanJadwalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // ADMIN can view all approvals, USER can view approvals for reporting
        return in_array($user->role, ['ADMIN', 'USER']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PersetujuanJadwal $persetujuanJadwal): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PersetujuanJadwal $persetujuanJadwal): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PersetujuanJadwal $persetujuanJadwal): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PersetujuanJadwal $persetujuanJadwal): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PersetujuanJadwal $persetujuanJadwal): bool
    {
        return false;
    }
}
