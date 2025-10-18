<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 space-y-8">

        <!-- Header / Intro -->
        <div class="w-full px-6 py-6 flex items-center gap-6">
            <!-- Avatar -->
            <div class="flex-shrink-0">
                @if($investorProfile->avatar)
                    <img src="{{ asset('storage/' . $investorProfile->avatar) }}"
                        class="w-32 h-32 rounded-full object-cover border shadow">
                @else
                    <div class="w-32 h-32 rounded-full bg-gray-300 border flex items-center justify-center text-gray-500">
                        {{ __('No Avatar') }}
                    </div>
                @endif
            </div>

            <!-- Name + Job Title -->
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900">
                    {{ $investorProfile->user->name }}
                </h1>
                <p class="text-xl text-gray-600">{{ $investorProfile->job_title }}</p>
            </div>

            <div class="flex gap-2">
                @if(auth()->id() === $investorProfile->user_id)
                    <!-- Edit Button -->
                    <a href="{{ route('investor-profiles.edit', $investorProfile) }}"
                        class="inline-flex items-center content-center bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 shadow rounded-lg transition duration-200">
                        <!-- Pencil Icon -->
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.232 5.232l3.536 3.536M9 11l6.586-6.586a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H9v-2.414z" />
                        </svg>
                        {{ __('Edit') }}
                    </a>
                @endif
            </div>
        </div>

        <!-- About Me -->
        <div>
            <h2 class="font-bold text-xl mb-3">{{ __('About Me') }}</h2>
            <div class="w-full px-6 py-6 bg-white shadow rounded-lg">
                <p class="text-gray-700 leading-relaxed mt-2">{{ $investorProfile->bio ?? __('No description available.') }}</p>
            </div>
        </div>

    </div>
</x-app-layout>
