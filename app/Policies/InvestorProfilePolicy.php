<?php

namespace App\Policies;

use App\Models\User;
use App\Models\InvestorProfile;

class InvestorProfilePolicy
{
    /**
     * Determine whether the user can view any investor profiles.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view investor-profile');
    }

    /**
     * Determine whether the user can view the investor profile.
     */
    public function view(User $user): bool
    {
        return $user->can('view investor-profile');
    }

    /**
     * Determine whether the user can create an investor profile.
     */
    public function create(User $user): bool
    {
        return $user->can('create investor-profile');
    }

    /**
     * Determine whether the user can update the investor profile.
     */
    public function update(User $user, InvestorProfile $profile): bool
    {
        return $user->can('edit investor-profile') && $user->id === $profile->user_id;
    }

    /**
     * Determine whether the user can delete the investor profile.
     */
    public function delete(User $user, InvestorProfile $profile): bool
    {
        return $user->can('delete investor-profile') && $user->id === $profile->user_id;
    }

    /**
     * Determine whether the user can restore the investor profile.
     */
    public function restore(User $user, InvestorProfile $profile): bool
    {
        return $this->update($user, $profile);
    }

    /**
     * Determine whether the user can permanently delete the investor profile.
     */
    public function forceDelete(User $user, InvestorProfile $profile): bool
    {
        return $this->delete($user, $profile);
    }
}
