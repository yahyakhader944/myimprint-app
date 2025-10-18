<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

trait AuthHelper
{
    /**
     * Redirect user to the correct page based on role.
     */
    public static function redirectUserByRole(User $user): RedirectResponse
    {
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');

        } elseif ($user->hasRole('worker')) {
           // if there no worker profile go to create new one
            if (!$user->workerProfile) {
                return redirect()->route('worker-profiles.create');
            }

            return redirect()->route('worker-profiles.show', $user->workerProfile->id);

        } elseif ($user->hasRole('investor')) {
            // if there no investor profile go to create new one
            if (!$user->investorProfile) {
                return redirect()->route('investor-profiles.create');
            }

            return redirect()->route('investor.workers.index');
        }

        // fallback
        return redirect(route('/', absolute: false));
    }
}
