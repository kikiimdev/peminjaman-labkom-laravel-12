<?php

namespace App\Policies;

use App\Models\LampiranJadwal;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LampiranJadwalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // ADMIN can view all attachments, USER can view attachments for reporting
        return in_array($user->role, ['ADMIN', 'USER']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LampiranJadwal $lampiranJadwal): bool
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
    public function update(User $user, LampiranJadwal $lampiranJadwal): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LampiranJadwal $lampiranJadwal): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LampiranJadwal $lampiranJadwal): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LampiranJadwal $lampiranJadwal): bool
    {
        return false;
    }
}
