<?php

namespace App\Policies;

use App\Models\Jadwal;
use App\Models\User;

class JadwalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // ADMIN can view all schedules, USER can view all schedules (for browsing available rooms)
        return in_array($user->role, ['ADMIN', 'USER']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Jadwal $jadwal): bool
    {
        // ADMIN can view any schedule, USER can view schedules they own
        return $user->role === 'ADMIN' || $jadwal->peminjam_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Both ADMIN and USER can create schedules
        return in_array($user->role, ['ADMIN', 'USER']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Jadwal $jadwal): bool
    {
        // ADMIN can update any schedule, USER can only update their own schedules
        return $user->role === 'ADMIN' || $jadwal->peminjam_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Jadwal $jadwal): bool
    {
        // ADMIN can delete any schedule, USER can only delete their own schedules
        return $user->role === 'ADMIN' || $jadwal->peminjam_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Jadwal $jadwal): bool
    {
        // ADMIN can restore any schedule, USER can only restore their own schedules
        return $user->role === 'ADMIN' || $jadwal->peminjam_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Jadwal $jadwal): bool
    {
        // Only ADMIN can permanently delete schedules
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can approve schedules.
     */
    public function approve(User $user): bool
    {
        // Only ADMIN can approve schedules
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can reject schedules.
     */
    public function reject(User $user): bool
    {
        // Only ADMIN can reject schedules
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can manage all schedules.
     */
    public function manage(User $user): bool
    {
        // Only ADMIN can manage all schedules
        return $user->role === 'ADMIN';
    }
}
