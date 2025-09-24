<?php

namespace App\Policies;

use App\Models\Fasilitas;
use App\Models\User;

class FasilitasPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Both ADMIN and USER can view all facilities
        return in_array($user->role, ['ADMIN', 'USER']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Fasilitas $fasilitas): bool
    {
        // Both ADMIN and USER can view individual facilities
        return in_array($user->role, ['ADMIN', 'USER']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only ADMIN can create facilities
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Fasilitas $fasilitas): bool
    {
        // Only ADMIN can update facilities
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Fasilitas $fasilitas): bool
    {
        // Only ADMIN can delete facilities
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Fasilitas $fasilitas): bool
    {
        // Only ADMIN can restore facilities
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Fasilitas $fasilitas): bool
    {
        // Only ADMIN can permanently delete facilities
        return $user->role === 'ADMIN';
    }
}
