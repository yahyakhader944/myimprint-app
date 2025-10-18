<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">
        <!-- Page Header with Search -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">{{ __('Conversations') }}</h1>

            <!-- Search Form -->
            <form method="GET" action="{{ route('conversations.index') }}" class="relative">
                <div class="relative w-64">
                    <input type="text" name="search" placeholder="{{ __('Search conversations...') }}"
                        value="{{ request('search') }}"
                        class="w-full pl-4 pr-12 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">

                    <!-- Search and Clear Buttons -->
                    <div class="absolute inset-y-0 right-0 flex items-center">
                        @if(request('search'))
                            <!-- Clear Button (X icon) -->
                            <a href="{{ route('conversations.index') }}"
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

        @if($conversations->isEmpty())
            <p class="text-gray-600 text-center py-8">
                @if(request('search'))
                    {{ __('No conversations found matching your search.') }}
                @else
                    {{ __('No conversations yet.') }}
                @endif
            </p>
        @else
            <div class="space-y-4">
                @foreach($conversations as $conversation)
                    @php
                        $otherUser = $conversation->worker_id === auth()->id()
                            ? $conversation->investor
                            : $conversation->worker;
                        $lastMessage = $conversation->messages->last();

                        $avatar = null;

                        if ($otherUser->workerProfile) {
                            $avatar = $otherUser->workerProfile->avatar;
                        }

                        if ($otherUser->investorProfile) {
                            $avatar = $otherUser->investorProfile->avatar;
                        }
                    @endphp

                    <a href="{{ route('conversations.show', $conversation) }}"
                        class="block bg-white shadow rounded-lg p-4 hover:bg-sky-100 transition-colors duration-300">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    @if($avatar)
                                        <img src="{{ asset('storage/' . $avatar) }}" alt="avatar"
                                            class="w-14 h-14 rounded-full object-cover border">
                                    @else
                                        <div
                                            class="w-14 h-14 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 text-sm">
                                            {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h2 class="font-semibold">{{ $otherUser->name }}</h2>
                                    <p class="text-sm text-gray-600">
                                        @if ($lastMessage?->body)
                                            {{ $lastMessage?->body }}
                                        @elseif ($lastMessage?->attachment)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                </svg>
                                                {{ __('Attachment') }}
                                            </span>
                                        @else
                                            {{ __('No messages yet') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @if($lastMessage)
                                <span class="text-xs text-gray-400">
                                    {{ $lastMessage->created_at->diffForHumans() }}
                                </span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
