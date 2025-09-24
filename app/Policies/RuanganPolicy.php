<?php

namespace App\Policies;

use App\Models\Ruangan;
use App\Models\User;

class RuanganPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Both ADMIN and USER can view all rooms
        return in_array($user->role, ['ADMIN', 'USER']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ruangan $ruangan): bool
    {
        // Both ADMIN and USER can view individual rooms
        return in_array($user->role, ['ADMIN', 'USER']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only ADMIN can create rooms
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ruangan $ruangan): bool
    {
        // Only ADMIN can update rooms
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ruangan $ruangan): bool
    {
        // Only ADMIN can delete rooms
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Ruangan $ruangan): bool
    {
        // Only ADMIN can restore rooms
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ruangan $ruangan): bool
    {
        // Only ADMIN can permanently delete rooms
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can manage room facilities.
     */
    public function manageFacilities(User $user): bool
    {
        // Only ADMIN can manage room facilities
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can manage room maintenance.
     */
    public function manageMaintenance(User $user): bool
    {
        // Only ADMIN can manage room maintenance
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can manage room inspections.
     */
    public function manageInspections(User $user): bool
    {
        // Only ADMIN can manage room inspections
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can assign room ownership.
     */
    public function assignOwnership(User $user): bool
    {
        // Only ADMIN can assign room ownership
        return $user->role === 'ADMIN';
    }
}
