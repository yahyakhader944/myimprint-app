<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkerProfile;

class WorkerProfilePolicy
{
    /**
     * Determine whether the user can view any worker profiles.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the worker profile.
     */
    public function view(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create a worker profile.
     */
    public function create(User $user): bool
    {
        return $user->can('create worker-profile');
    }

    /**
     * Determine whether the user can update the worker profile.
     */
    public function update(User $user, WorkerProfile $profile): bool
    {
        return $user->can('edit worker-profile') && $user->id === $profile->user_id;
    }

    /**
     * Determine whether the user can delete the worker profile.
     */
    public function delete(User $user, WorkerProfile $profile): bool
    {
        return $user->can('delete worker-profile') && $user->id === $profile->user_id;
    }

    /**
     * Determine whether the user can restore the worker profile.
     */
    public function restore(User $user, WorkerProfile $profile): bool
    {
        return $this->update($user, $profile);
    }

    /**
     * Determine whether the user can permanently delete the worker profile.
     */
    public function forceDelete(User $user, WorkerProfile $profile): bool
    {
        return $this->delete($user, $profile);
    }
}
