<x-app-layout>
    <div class="max-w-6xl mx-auto p-6 space-y-6">

        <!-- Search Form -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Users Management</h1>

            <form method="GET" action="{{ route('admin.users.index') }}" class="relative">
                <div class="relative w-64">
                    <input type="text" name="search" placeholder="{{ __('Search users...') }}"
                        value="{{ request('search') }}"
                        class="w-full pl-4 pr-12 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">

                    <!-- Search and Clear Buttons -->
                    <div class="absolute inset-y-0 right-0 flex items-center">
                        <!-- Clear Button -->
                        @if(request('search'))
                            <a href="{{ route('admin.users.index') }}"
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

        <table class="w-full">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="p-2">ID</th>
                    <th class="p-2">Name</th>
                    <th class="p-2">Email</th>
                    <th class="p-2 text-center">Roles</th>
                    <th class="p-2 text-center">Status</th>
                    <th class="p-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="bg-white border-b shadow rounded">
                        <td class="p-2">{{ $user->id }}</td>
                        <td class="p-2">{{ $user->name }}</td>
                        <td class="p-2">{{ $user->email }}</td>
                        <td class="p-2 text-center">
                            {{ $user->roles->pluck('name')->join(', ') }}
                        </td>
                        <td class="p-2 text-center">
                            @if($user->is_blocked)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Blocked
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Active
                                </span>
                            @endif
                        </td>
                        <td class="p-2 text-center">
                            <a href="{{ route('admin.users.edit', $user) }}"
                                class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536M9 11l6.586-6.586a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H9v-2.414z" />
                                </svg>
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            @if(request('search'))
                                {{ __('No users found matching your search.') }}
                            @else
                                {{ __('No users found.') }}
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
