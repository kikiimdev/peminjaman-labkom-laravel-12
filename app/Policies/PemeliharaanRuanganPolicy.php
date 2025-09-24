<?php

namespace App\Policies;

use App\Models\PemeliharaanRuangan;
use App\Models\User;

class PemeliharaanRuanganPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Both ADMIN and USER can view maintenance records
        return in_array($user->role, ['ADMIN', 'USER']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PemeliharaanRuangan $pemeliharaanRuangan): bool
    {
        // Both ADMIN and USER can view individual maintenance records
        return in_array($user->role, ['ADMIN', 'USER']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only ADMIN can create maintenance records
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PemeliharaanRuangan $pemeliharaanRuangan): bool
    {
        // Only ADMIN can update maintenance records
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PemeliharaanRuangan $pemeliharaanRuangan): bool
    {
        // Only ADMIN can delete maintenance records
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PemeliharaanRuangan $pemeliharaanRuangan): bool
    {
        // Only ADMIN can restore maintenance records
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PemeliharaanRuangan $pemeliharaanRuangan): bool
    {
        // Only ADMIN can permanently delete maintenance records
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can manage maintenance schedules.
     */
    public function manageSchedules(User $user): bool
    {
        // Only ADMIN can manage maintenance schedules
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can update maintenance status.
     */
    public function updateStatus(User $user, PemeliharaanRuangan $pemeliharaanRuangan): bool
    {
        // Only ADMIN can update maintenance status
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can mark maintenance as completed.
     */
    public function markAsCompleted(User $user, PemeliharaanRuangan $pemeliharaanRuangan): bool
    {
        // Only ADMIN can mark maintenance as completed
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can cancel maintenance.
     */
    public function cancel(User $user, PemeliharaanRuangan $pemeliharaanRuangan): bool
    {
        // Only ADMIN can cancel maintenance
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can manage maintenance costs.
     */
    public function manageCosts(User $user): bool
    {
        // Only ADMIN can manage maintenance costs
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can view maintenance history.
     */
    public function viewHistory(User $user): bool
    {
        // Both ADMIN and USER can view maintenance history
        return in_array($user->role, ['ADMIN', 'USER']);
    }

    /**
     * Determine whether the user can view maintenance statistics.
     */
    public function viewStatistics(User $user): bool
    {
        // Only ADMIN can view maintenance statistics
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can export maintenance reports.
     */
    public function export(User $user): bool
    {
        // Only ADMIN can export maintenance reports
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can schedule maintenance for a room.
     */
    public function scheduleForRoom(User $user): bool
    {
        // Only ADMIN can schedule maintenance for rooms
        return $user->role === 'ADMIN';
    }
}
