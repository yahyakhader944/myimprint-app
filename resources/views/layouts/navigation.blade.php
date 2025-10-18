<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white/70 backdrop-blur border-b border-gray-200 shadow-sm">

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center gap-3">
                    <a href="{{ url('/') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>

                    @hasrole('admin')
                    <span class="bg-gray-600 rounded-md mr-1.5 text-white px-1 uppercase"
                        style="font-size: 0.6rem;">{{ __('Admin') }}</span>
                    @endhasrole
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @hasrole('investor')
                    <x-nav-link :href="route('investor.workers.index')"
                        :active="request()->routeIs('investor.workers.index')">
                        {{ __('Search Workers') }}
                    </x-nav-link>

                    <x-nav-link :href="Auth::user()->investorProfile ? route('investor-profiles.show', Auth::user()->investorProfile) : route('investor-profiles.create')"
                        :active="request()->routeIs('investor-profiles.*')">
                        {{ __('Investor Profile') }}
                    </x-nav-link>
                    @endhasrole

                    @hasrole('worker')
                    <x-nav-link :href="Auth::user()->workerProfile ? route('worker-profiles.show', Auth::user()->workerProfile) : route('worker-profiles.create')"
                        :active="request()->routeIs('worker-profiles.*')">
                        {{ __('Worker Profile') }}
                    </x-nav-link>
                    @endhasrole

                    <!-- Admin Control Panel -->
                    @hasrole('admin')
                    <x-nav-link :href="route('admin.dashboard')"
                        :active="request()->routeIs('admin.dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.users.index')"
                        :active="request()->routeIs('admin.users.*')">
                        {{ __('Users') }}
                    </x-nav-link>

                    <x-nav-link :href="route('worker-profiles.index')"
                        :active="request()->routeIs('worker-profiles.*')">
                        {{ __('Worker Profiles') }}
                    </x-nav-link>

                    <x-nav-link :href="route('investor-profiles.index')"
                        :active="request()->routeIs('investor-profiles.*')">
                        {{ __('Investor Profiles') }}
                    </x-nav-link>
                    @endhasrole
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="flex items-center gap-2">
                    <!-- Avatar -->
                    @php
                        $user = Auth::user();
                        $avatar = null;

                        // Worker avatar
                        if ($user->hasRole('worker') && $user->workerProfile?->avatar) {
                            $avatar = asset('storage/' . $user->workerProfile->avatar);
                        }

                        // Investor avatar
                        if ($user->hasRole('investor') && $user->investorProfile?->avatar) {
                            $avatar = asset('storage/' . $user->investorProfile->avatar);
                        }
                    @endphp

                    @if($avatar)
                        <img src="{{ $avatar }}" alt="avatar" class="w-6 h-6 rounded-full object-cover border">
                    @else
                        <div
                            class="w-6 h-6 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 text-sm">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Settings') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>

                <!-- Messages -->
                <x-nav-link :href="route('conversations.index')" :active="request()->routeIs('conversations.*')"
                    title="{{ __('Conversations') }}"
                    class="relative flex items-center h-full px-2 text-gray-600 hover:text-blue-600">
                    <!-- Chat Icon -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.916L3 20l1.636-3.27A7.978 7.978 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <!-- Counter -->
                    @if($unreadMessagesCount > 0)
                        <span
                            class="absolute top-3 -right-1 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                            {{ $unreadMessagesCount }}
                        </span>
                    @endif
                </x-nav-link>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>


    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @hasrole('investor')
            <x-responsive-nav-link :href="route('investor.workers.index')"
                :active="request()->routeIs('investor.workers.index')">
                {{ __('Search Workers') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="Auth::user()->investorProfile ? route('investor-profiles.show', Auth::user()->investorProfile) : route('investor-profiles.create')"
                :active="request()->routeIs('investor-profiles.*')">
                {{ __('Investor Profile') }}
            </x-responsive-nav-link>
            @endhasrole

            @hasrole('worker')
            <x-responsive-nav-link :href="Auth::user()->workerProfile ? route('worker-profiles.show', Auth::user()->workerProfile) : route('worker-profiles.create')"
                :active="request()->routeIs('worker-profiles.*')">
                {{ __('Worker Profile') }}
            </x-responsive-nav-link>
            @endhasrole

            <!-- Admin Control Panel -->
            @hasrole('admin')
            <x-responsive-nav-link :href="route('admin.dashboard')"
                :active="request()->routeIs('admin.dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.users.index')"
                :active="request()->routeIs('admin.users.*')">
                {{ __('Users') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('worker-profiles.index')"
                :active="request()->routeIs('worker-profiles.*')">
                {{ __('Worker Profiles') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('investor-profiles.index')"
                :active="request()->routeIs('investor-profiles.*')">
                {{ __('Investor Profiles') }}
            </x-responsive-nav-link>
            @endhasrole

            <!-- Messages -->
            <x-responsive-nav-link :href="route('conversations.index')" :active="request()->routeIs('conversations.*')">
                {{ __('Messages') }}
                @if($unreadMessagesCount > 0)
                    <span class="ml-2 inline-block bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                        {{ $unreadMessagesCount }}
                    </span>
                @endif
            </x-responsive-nav-link>
        </div>


        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center gap-3 px-4">
                <div class="flex items-center gap-2">
                    <!-- Avatar -->
                    @php
                        $user = Auth::user();
                        $avatar = null;

                        // Worker avatar
                        if ($user->hasRole('worker') && $user->workerProfile?->avatar) {
                            $avatar = asset('storage/' . $user->workerProfile->avatar);
                        }

                        // Investor avatar
                        if ($user->hasRole('investor') && $user->investorProfile?->avatar) {
                            $avatar = asset('storage/' . $user->investorProfile->avatar);
                        }
                    @endphp

                    @if($avatar)
                        <img src="{{ $avatar }}" alt="avatar" class="w-6 h-6 rounded-full object-cover border">
                    @else
                        <div
                            class="w-6 h-6 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 text-sm">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Settings') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
