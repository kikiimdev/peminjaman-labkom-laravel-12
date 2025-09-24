<?php

namespace App\Policies;

use App\Models\Insiden;
use App\Models\User;

class InsidenPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // ADMIN can view all incidents, USER can view incidents they reported or are related to their schedules
        return $user->role === 'ADMIN' || $user->role === 'USER';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Insiden $insiden): bool
    {
        // ADMIN can view any incident, USER can view incidents they reported or are related to their schedules
        return $user->role === 'ADMIN' ||
               $insiden->pelapor_id === $user->id ||
               ($insiden->jadwal && $insiden->jadwal->peminjam_id === $user->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Both ADMIN and USER can create incidents
        return in_array($user->role, ['ADMIN', 'USER']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Insiden $insiden): bool
    {
        // ADMIN can update any incident, USER can only update incidents they reported
        return $user->role === 'ADMIN' || $insiden->pelapor_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Insiden $insiden): bool
    {
        // ADMIN can delete any incident, USER can only delete incidents they reported
        return $user->role === 'ADMIN' || $insiden->pelapor_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Insiden $insiden): bool
    {
        // Only ADMIN can restore incidents
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Insiden $insiden): bool
    {
        // Only ADMIN can permanently delete incidents
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can manage the incident (assign, close, etc).
     */
    public function manage(User $user, Insiden $insiden): bool
    {
        // Only ADMIN can manage incidents
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can assign themselves to handle an incident.
     */
    public function assign(User $user, Insiden $insiden): bool
    {
        // Only ADMIN can assign incident handlers
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can mark an incident as resolved.
     */
    public function resolve(User $user, Insiden $insiden): bool
    {
        // Only ADMIN can mark incidents as resolved
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can view incidents by room.
     */
    public function viewByRoom(User $user): bool
    {
        // ADMIN can view incidents by room, USER can only view incidents for rooms they have schedules in
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can view incident statistics.
     */
    public function viewStatistics(User $user): bool
    {
        // Only ADMIN can view incident statistics
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can export incident reports.
     */
    public function export(User $user): bool
    {
        // Only ADMIN can export incident reports
        return $user->role === 'ADMIN';
    }
}
