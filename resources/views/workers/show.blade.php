<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 space-y-8">

        <!-- Header / Intro -->
        <div class="w-full px-6 py-6 flex items-center gap-6">
            <!-- Avatar -->
            <div class="flex-shrink-0">
                @if($workerProfile->avatar)
                    <img src="{{ asset('storage/' . $workerProfile->avatar) }}"
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
                    {{ __('Hi, I’m') }} {{ $workerProfile->user->name }}
                </h1>
                <p class="text-xl text-gray-600">{{ $workerProfile->job_title }}</p>
            </div>

            <div class="flex gap-2">
                @if(auth()->id() === $workerProfile->user_id)
                    <!-- Edit Button -->
                    <a href="{{ route('worker-profiles.edit', $workerProfile) }}"
                        class="inline-flex items-center content-center bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 shadow rounded-lg transition duration-200">
                        <!-- Pencil Icon -->
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.232 5.232l3.536 3.536M9 11l6.586-6.586a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H9v-2.414z" />
                        </svg>
                        {{ __('Edit') }}
                    </a>
                @endif

                @if(auth()->user()->hasRole('investor'))
                    <!-- Message Button -->
                    <form method="POST" action="{{ route('conversations.store') }}">
                        @csrf
                        <input type="hidden" name="worker_id" value="{{ $workerProfile->user_id }}">
                        <input type="hidden" name="investor_id" value="{{ auth()->id() }}">

                        <button type="submit"
                            class="inline-flex items-center content-center bg-green-600 hover:bg-green-700 text-white px-3 py-2 shadow rounded-lg transition duration-200">
                            <!-- Chat Icon -->
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.916L3 20l1.636-3.27A7.978 7.978 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            {{ __('Message') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- About Me -->
        <div>
            <h2 class="font-bold text-xl mb-3">{{ __('About Me') }}</h2>
            <div class="w-full px-6 py-6 bg-white shadow rounded-lg">
                @if($workerProfile->bio_title)
                    <h3 class="font-semibold text-gray-800">{{ $workerProfile->bio_title }}</h3>
                @endif
                <p class="text-gray-700 leading-relaxed mt-2">{{ $workerProfile->bio }}</p>
            </div>
        </div>


        <!-- Skills -->
        <div>
            <h2 class="font-bold text-xl mb-3">{{ __('Skills') }}</h2>
            <div class="px-4 py-4 bg-white shadow rounded-lg">
                <div class="grid gap-3">
                    @forelse($workerProfile->skills as $skill)
                        <div class="p-3 border border-sky-200 bg-sky-50 rounded-lg">
                            <h3 class="font-semibold">{{ $skill->name }}</h3>
                            <p class="text-gray-700 text-sm mt-1">{{ $skill->description }}</p>
                        </div>
                    @empty
                        <p class="text-gray-400">{{ __('No skills added.') }}</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Services -->
        <div>
            <h2 class="font-bold text-xl mb-3">{{ __('Services') }}</h2>
            <div class="px-4 py-4 bg-white shadow rounded-lg">
                <div class="grid gap-3">
                    @forelse($workerProfile->services as $service)
                        <div class="p-3 border border-sky-200 bg-sky-50 rounded-lg">
                            <h3 class="font-semibold">{{ $service->name }}</h3>
                            <p class="text-gray-700 text-sm mt-1">{{ $service->description }}</p>
                        </div>
                    @empty
                        <p class="text-gray-400">{{ __('No services added.') }}</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Portfolio -->
        <div>
            <h2 class="font-bold text-xl mb-3">{{ __('Portfolio') }}</h2>
            <div class="grid md:grid-cols-3 gap-6">
                @forelse($workerProfile->portfolioItems as $item)
                    <div class="border rounded-lg bg-white shadow">
                        <div class="mb-3">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}"
                                    class="w-full h-40 rounded-t-lg object-cover border">
                            @else
                                <div
                                    class="w-full h-40 rounded-t-lg bg-slate-100 border flex items-center justify-center text-gray-400">
                                    {{ __('No Image') }}
                                </div>
                            @endif
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold">{{ $item->title }}</h3>
                            <p class="text-sm text-gray-500">{{ $item->subtitle }}</p>
                            <p class="text-gray-700 mt-2">{{ $item->description }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400">{{ __('No portfolio items added.') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
