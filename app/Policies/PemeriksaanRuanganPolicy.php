<?php

namespace App\Policies;

use App\Models\PemeriksaanRuangan;
use App\Models\User;

class PemeriksaanRuanganPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // ADMIN can view all room inspections, USER can view all room inspections
        return in_array($user->role, ['ADMIN', 'USER']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PemeriksaanRuangan $pemeriksaanRuangan): bool
    {
        // ADMIN can view any inspection, USER can view inspections they performed
        return $user->role === 'ADMIN' || $pemeriksaanRuangan->petugas_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Both ADMIN and USER can create inspections
        return in_array($user->role, ['ADMIN', 'USER']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PemeriksaanRuangan $pemeriksaanRuangan): bool
    {
        // ADMIN can update any inspection, USER can only update their own inspections
        return $user->role === 'ADMIN' || $pemeriksaanRuangan->petugas_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PemeriksaanRuangan $pemeriksaanRuangan): bool
    {
        // ADMIN can delete any inspection, USER can only delete their own inspections
        return $user->role === 'ADMIN' || $pemeriksaanRuangan->petugas_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PemeriksaanRuangan $pemeriksaanRuangan): bool
    {
        // ADMIN can restore any inspection, USER can only restore their own inspections
        return $user->role === 'ADMIN' || $pemeriksaanRuangan->petugas_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PemeriksaanRuangan $pemeriksaanRuangan): bool
    {
        // Only ADMIN can permanently delete inspections
        return $user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can manage all inspections.
     */
    public function manage(User $user): bool
    {
        // Only ADMIN can manage all inspections
        return $user->role === 'ADMIN';
    }
}
