<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\WorkerProfile::class => \App\Policies\WorkerProfilePolicy::class,
        \App\Models\InvestorProfile::class => \App\Policies\InvestorProfilePolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\Conversation::class => \App\Policies\ConversationPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Admin has access to all
        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });

        // Unread conversations count
        view()->composer('*', function ($view) {
            $unreadCount = Auth::check() ? Auth::user()->unreadConversationsCount() : 0;
            $view->with('unreadMessagesCount', $unreadCount);
        });
    }
}
