<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">{{ __('Edit Investor Profile') }}</h1>

        <form method="POST" action="{{ route('investor-profiles.update', $investorProfile) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Info -->
            <div class="w-full px-6 py-4 bg-white shadow rounded-lg grid gap-6 grid-cols-3">
                <div class="flex flex-col items-center">
                    <label for="avatar" class="font-semibold mb-2">{{ __('Avatar') }}</label>
                    <div class="grid items-center mt-3 h-full">
                        @if($investorProfile->avatar)
                            <img src="{{ asset('storage/'.$investorProfile->avatar) }}" id="avatar-preview"
                                 class="col-start-1 row-start-1 w-40 h-40 rounded-full object-cover border">
                        @else
                            <div id="avatar-placeholder" class="col-start-1 row-start-1 w-40 h-40 rounded-full bg-slate-100 border"></div>
                            <img id="avatar-preview" class="hidden col-start-1 row-start-1 w-40 h-40 rounded-full object-cover border">
                        @endif
                    </div>
                    <input type="file" id="avatar" name="avatar" accept="image/*" onchange="previewPhoto(event, 'avatar-preview')"
                           class="text-xs w-full border mt-3 p-2 rounded-lg shadow bg-blue-50 text-blue-700 cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                </div>
                <div class="grid gap-3 col-span-2">
                    <div>
                        <label for="job_title" class="block font-semibold mb-1">{{ __('Job Title') }} <span class="text-red-600">*</span></label>
                        <input type="text" id="job_title" name="job_title" value="{{ old('job_title', $investorProfile->job_title) }}" class="w-full border p-2 rounded" required>
                    </div>
                    <div>
                        <label for="bio" class="block font-semibold mb-1">{{ __('About Me') }}</label>
                        <textarea id="bio" name="bio" rows="4" class="w-full border p-2 rounded">{{ old('bio', $investorProfile->bio) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Save -->
            <div class="text-right">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white px-6 py-2 shadow rounded-lg transition duration-200">
                    {{ __('Save') }}
                </button>
            </div>
        </form>
    </div>

    <script>
        function previewPhoto(event, id) {
            const [file] = event.target.files;
            if (file) {
                const preview = document.getElementById(id);
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
            }
        }
    </script>
</x-app-layout>
