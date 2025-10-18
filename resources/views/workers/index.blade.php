<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 space-y-8">

        <!-- Page Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('Worker Profiles') }}</h1>

            <!-- Search Form -->
            <form method="GET" action="{{ route('worker-profiles.index') }}" class="relative">
                <div class="relative w-64">
                    <input type="text" name="search" placeholder="{{ __('Search workers...') }}"
                        value="{{ request('search') }}"
                        class="w-full pl-4 pr-12 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">

                    <!-- Search and Clear Buttons -->
                    <div class="absolute inset-y-0 right-0 flex items-center">
                        <!-- Clear Button -->
                        @if(request('search'))
                            <a href="{{ route('worker-profiles.index') }}"
                                class="p-1 text-gray-400 hover:text-red-500 transition duration-200 mr-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        @endif

                        <!-- Search Button -->
                        <button type="submit"
                            class="p-1 text-gray-400 hover:text-blue-600 transition duration-200 mr-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Workers List -->
        <div class="space-y-4">
            @forelse($workerProfiles as $worker)
                <div class="bg-white rounded-lg shadow py-3 px-5">
                    <div class="flex items-center justify-between">
                        <!-- Left Section: Avatar and Info -->
                        <div class="flex items-center gap-4 flex-1">
                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                @if($worker->avatar)
                                    <img src="{{ asset('storage/' . $worker->avatar) }}" alt="{{ $worker->user->name }}"
                                        class="w-16 h-16 rounded-full object-cover border-2 border-gray-200" loading="lazy">
                                @else
                                    <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center">
                                        <span class="text-gray-600 font-medium">
                                            {{ strtoupper(substr($worker->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Name and Job Title -->
                            <div class="flex-1">
                                <h2 class="text-xl font-bold text-gray-900">
                                    {{ $worker->user->name }}
                                </h2>
                                <p class="text-gray-600">{{ $worker->job_title }}</p>
                            </div>
                        </div>

                        <!-- Right Section: Buttons -->
                        <div class="flex items-center gap-2">
                            <!-- View Button -->
                            <a href="{{ route('worker-profiles.show', $worker) }}"
                                class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg transition duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{ __('View') }}
                            </a>

                            <!-- Edit Button -->
                            @auth
                                @if(auth()->id() === $worker->user->id || auth()->user()->hasRole('admin'))
                                    <a href="{{ route('worker-profiles.edit', $worker) }}"
                                        class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg transition duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536M9 11l6.586-6.586a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H9v-2.414z" />
                                        </svg>
                                        {{ __('Edit') }}
                                    </a>
                                @endif
                            @endauth

                            <!-- Delete Button (Admin only) -->
                            @if(auth()->check() && auth()->user()->hasRole('admin'))
                                <form action="{{ route('worker-profiles.destroy', $worker) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg transition duration-200"
                                            onclick="return confirm('Are you sure you want to delete this worker profile?')">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        {{ __('Delete') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-500 mb-2">{{ __('No workers found') }}</h3>
                    <p class="text-gray-500">
                        @if(request('search'))
                            {{ __('Try adjusting your search words.') }}
                        @else
                            {{ __('No worker profiles available at the moment.') }}
                        @endif
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($workerProfiles->hasPages())
            <div class="mt-8">
                {{ $workerProfiles->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
