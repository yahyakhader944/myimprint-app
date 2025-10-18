<x-app-layout>
    <div class="max-w-3xl mx-auto p-6 space-y-6">
        <h1 class="text-xl font-bold">Edit User</h1>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Info -->
            <div class="w-full px-6 py-4 bg-white shadow rounded-lg grid gap-6">
                <div>
                    <label class="block mb-1 font-semibold">{{ __('Name') }}</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="border p-2 w-full rounded">
                </div>
                <div>
                    <label class="block mb-1 font-semibold">{{ __('Email') }}</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="border p-2 w-full rounded">
                </div>
                <div>
                    <label class="block mb-1 font-semibold">{{ __('Roles') }}</label>
                    <select name="roles[]" multiple class="border p-2 w-full rounded">
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}"
                                @selected($user->roles->pluck('name')->contains($role->name))>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_blocked" @checked($user->is_blocked)>
                        <span class="ml-2">{{ __('Block User') }}</span>
                    </label>
                    <small class="text-gray-600">If checked, user will be prevented from logging in.</small>
                </div>
            </div>

            <!-- Save -->
            <div class="text-right">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white px-6 py-2 rounded-lg shadow-md">{{ __('Save') }}</button>
            </div>
        </form>
    </div>
</x-app-layout>
