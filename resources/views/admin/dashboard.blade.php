<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Admin Dashboard') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Welcome to your administration panel') }}</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Workers Card -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">{{ __('Workers Profiles') }}</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $workersCount }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ __('Total registered workers') }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('worker-profiles.index') }}"
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center transition duration-200">
                        View all workers
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Investors Card -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">{{ __('Investors Profiles') }}</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $investorsCount }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ __('Total registered investors') }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('investor-profiles.index') }}"
                       class="text-green-600 hover:text-green-800 text-sm font-medium flex items-center transition duration-200">
                        View all investors
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Weekly Visitors Card -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">{{ __('Weekly Visitors') }}</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $weeklyVisitors }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ __('Active users this week') }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-purple-600 text-sm font-medium flex items-center">
                        {{ now()->startOfWeek()->format('M d') }} - {{ now()->endOfWeek()->format('M d') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="grid grid-cols-1 gap-8">
            <!-- Recent Users -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('Recent Users') }}</h2>
                    <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 text-sm transition duration-200">
                        {{ __('View all') }}
                    </a>
                </div>
                <div class="space-y-4">
                    @forelse($recentUsers as $user)
                        @php
                            $userAvatar = null;

                            if ($user->workerProfile) {
                                $userAvatar = $user->workerProfile->avatar;
                            }else if ($user->investorProfile) {
                                $userAvatar = $user->investorProfile->avatar;
                            }
                        @endphp
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                @if($userAvatar)
                                    <img src="{{ asset('storage/' . $userAvatar) }}" alt="{{ $user->name }}"
                                        class="w-10 h-10 rounded-full object-cover border-2 border-gray-200" loading="lazy">
                                @else
                                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                        <span class="text-gray-600 font-medium">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500">
                                        @if($user->workerProfile)
                                            <span class="text-blue-600">{{ __('Worker') }}</span>
                                        @elseif($user->investorProfile)
                                            <span class="text-green-600">{{ __('Investor') }}</span>
                                        @else
                                            <span class="text-gray-400">{{ __('No profile') }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-400">
                                {{ $user->created_at->diffForHumans() }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">{{ __('No recent users') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
