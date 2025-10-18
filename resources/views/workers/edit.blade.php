<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">{{ __('Edit Profile') }}</h1>

        <form method="POST" action="{{ route('worker-profiles.update', $workerProfile) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Info -->
            <div class="w-full px-6 py-4 bg-white shadow rounded-lg grid gap-6 grid-cols-3">
                <input type="hidden" name="id" value="{{ $workerProfile->id }}">
                <div class="flex flex-col items-center">
                    <label for="avatar" class="font-semibold mb-2">{{ __('Avatar') }}</label>
                    <div class="grid items-center mt-3 h-full">
                        @if($workerProfile->avatar)
                            <img src="{{ asset('storage/'.$workerProfile->avatar) }}" id="avatar-preview"
                                 class="col-start-1 row-start-1 w-40 h-40 rounded-full object-cover border">
                        @else
                            <div id="avatar-placeholder" class="col-start-1 row-start-1 w-40 h-40 rounded-full bg-slate-100 border"></div>
                            <img id="avatar-preview" class="hidden col-start-1 row-start-1 w-40 h-40 rounded-full object-cover border">
                        @endif
                    </div>
                    <input type="file" id="avatar" name="avatar" accept="image/*" onchange="previewPhoto(event, 'avatar-preview')"
                           class="text-xs w-full border mt-3 p-2 rounded-lg shadow bg-blue-50 text-blue-700 cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                    @error('avatar')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="grid gap-3 col-span-2">
                    <div>
                        <label for="job_title" class="block font-semibold mb-1">{{ __('Job Title') }} <span class="text-red-600">*</span></label>
                        <input type="text" id="job_title" name="job_title" value="{{ old('job_title', $workerProfile->job_title) }}" class="w-full border p-2 rounded" required>
                    </div>
                    <div>
                        <label for="bio_title" class="block font-semibold mb-1">{{ __('About Title') }}</label>
                        <input type="text" id="bio_title" name="bio_title" value="{{ old('bio_title', $workerProfile->bio_title) }}" class="w-full border p-2 rounded">
                    </div>
                    <div>
                        <label for="bio" class="block font-semibold mb-1">{{ __('About Description') }} <span class="text-red-600">*</span></label>
                        <textarea id="bio" name="bio" rows="4" class="w-full border p-2 rounded" required>{{ old('bio', $workerProfile->bio) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Skills -->
            <div class="px-6 py-4 bg-white shadow rounded-lg">
                <h2 class="font-bold mb-3">{{ __('Skills') }}</h2>
                <div id="skills-wrapper" class="space-y-3">
                    @foreach($workerProfile->skills as $i => $skill)
                        <div class="p-3 border rounded-lg bg-slate-100 flex flex-col gap-2">
                            <input type="hidden" name="skills[{{ $i }}][id]" value="{{ $skill->id }}">
                            <div class="flex items-center gap-2">
                                <label class="w-28">{{ __('Skill Name') }} <span class="text-red-600">*</span></label>
                                <input type="text" name="skills[{{ $i }}][name]" value="{{ $skill->name }}" class="border p-2 rounded flex-1" required>
                            </div>
                            <div class="flex items-start gap-2">
                                <label class="w-28">{{ __('Description') }}</label>
                                <textarea name="skills[{{ $i }}][description]" rows="2" class="border p-2 rounded flex-1">{{ $skill->description }}</textarea>
                            </div>
                            <button type="button" onclick="this.parentElement.remove()" class="bg-red-600 hover:bg-red-700 active:bg-red-800 text-white px-3 py-1 rounded-lg shadow-md self-end">{{ __('Delete') }}</button>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="addSkill()" class="mt-3 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white px-3 py-1 rounded-lg shadow-md">+ {{ __('Add Skill') }}</button>
            </div>

            <!-- Services -->
            <div class="px-6 py-4 bg-white shadow rounded-lg">
                <h2 class="font-bold mb-3">{{ __('Services') }}</h2>
                <div id="services-wrapper" class="space-y-3">
                    @foreach($workerProfile->services as $i => $service)
                        <div class="p-3 border rounded-lg bg-slate-100 flex flex-col gap-2">
                            <input type="hidden" name="services[{{ $i }}][id]" value="{{ $service->id }}">
                            <div class="flex items-center gap-2">
                                <label class="w-28">{{ __('Service Name') }} <span class="text-red-600">*</span></label>
                                <input type="text" name="services[{{ $i }}][name]" value="{{ $service->name }}" class="border p-2 rounded flex-1" required>
                            </div>
                            <div class="flex items-start gap-2">
                                <label class="w-28">{{ __('Description') }}</label>
                                <textarea name="services[{{ $i }}][description]" rows="2" class="border p-2 rounded flex-1">{{ $service->description }}</textarea>
                            </div>
                            <button type="button" onclick="this.parentElement.remove()" class="bg-red-600 hover:bg-red-700 active:bg-red-800 text-white px-3 py-1 shadow rounded-lg transition duration-200 self-end">
                                {{ __('Delete') }}
                            </button>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="addService()" class="mt-3 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white px-3 py-1 shadow rounded-lg transition duration-200">
                    + {{ __('Add Service') }}
                </button>
            </div>

            <!-- Portfolio -->
            <div class="px-6 py-4 bg-white shadow rounded-lg">
                <h2 class="font-bold mb-3">{{ __('Portfolio') }}</h2>
                <div id="portfolio-wrapper" class="space-y-4">
                    @foreach($workerProfile->portfolioItems as $i => $item)
                        <div class="p-3 border rounded-lg bg-slate-100 grid gap-6 grid-cols-3">
                            <input type="hidden" name="portfolio[{{ $i }}][id]" value="{{ $item->id }}">
                            <div class="flex flex-col items-center">
                                <div class="grid items-center mt-3 h-full">
                                    @if($item->image)
                                        <img src="{{ asset('storage/'.$item->image) }}" id="photo-preview-{{ $i }}" class="col-start-1 row-start-1 w-32 h-32 rounded-lg object-cover border">
                                    @else
                                        <div class="col-start-1 row-start-1 w-32 h-32 rounded-lg bg-slate-200 border"></div>
                                        <img id="photo-preview-{{ $i }}" class="hidden col-start-1 row-start-1 w-32 h-32 rounded-lg object-cover border">
                                    @endif
                                </div>
                                <input type="file" name="portfolio[{{ $i }}][image]" accept="image/*" onchange="previewPhoto(event, 'photo-preview-{{ $i }}')"
                                       class="text-xs w-full border p-2 rounded-lg shadow bg-blue-50 text-blue-700 cursor-pointer mt-3 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                            </div>
                            <div class="flex flex-col gap-2 col-span-2">
                                <div class="flex items-center gap-2">
                                    <label class="w-28">{{ __('Title') }} <span class="text-red-600">*</span></label>
                                    <input type="text" name="portfolio[{{ $i }}][title]" value="{{ $item->title }}" class="border p-2 rounded flex-1" required>
                                </div>
                                <div class="flex items-center gap-2">
                                    <label class="w-28">{{ __('Subtitle') }}</label>
                                    <input type="text" name="portfolio[{{ $i }}][subtitle]" value="{{ $item->subtitle }}" class="border p-2 rounded flex-1">
                                </div>
                                <div class="flex items-start gap-2">
                                    <label class="w-28">{{ __('Description') }}</label>
                                    <textarea name="portfolio[{{ $i }}][description]" rows="2" class="border p-2 rounded flex-1">{{ $item->description }}</textarea>
                                </div>
                                <button type="button" onclick="this.closest('.p-3').remove()" class="bg-red-600 hover:bg-red-700 active:bg-red-800 text-white px-3 py-1 shadow rounded-lg transition duration-200 self-end">
                                    {{ __('Delete') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="addPortfolio()" class="mt-3 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white px-3 py-1 shadow rounded-lg transition duration-200">
                    + {{ __('Add Portfolio Item') }}
                </button>
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

        let skillIndex = {{ $workerProfile->skills->count() }};
        let serviceIndex = {{ $workerProfile->services->count() }};
        let portfolioIndex = {{ $workerProfile->portfolioItems->count() }};

        function addSkill() {
            document.getElementById('skills-wrapper').insertAdjacentHTML('beforeend', `
                <div class="p-3 border rounded-lg bg-slate-100 flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <label class="w-28">{{ __('Skill Name') }} <span class="text-red-600">*</span></label>
                        <input type="text" name="skills[${skillIndex}][name]" class="border p-2 rounded flex-1" required>
                    </div>
                    <div class="flex items-start gap-2">
                        <label class="w-28">{{ __('Description') }}</label>
                        <textarea name="skills[${skillIndex}][description]" rows="2" class="border p-2 rounded flex-1"></textarea>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="bg-red-600 hover:bg-red-700 active:bg-red-800 text-white px-3 py-1 shadow rounded-lg transition duration-200 self-end">
                        {{ __('Delete') }}
                    </button>
                </div>`);
            skillIndex++;
        }

        function addService() {
            document.getElementById('services-wrapper').insertAdjacentHTML('beforeend', `
                <div class="p-3 border rounded-lg bg-slate-100 flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <label class="w-28">{{ __('Service Name') }} <span class="text-red-600">*</span></label>
                        <input type="text" name="services[${serviceIndex}][name]" class="border p-2 rounded flex-1" required>
                    </div>
                    <div class="flex items-start gap-2">
                        <label class="w-28">{{ __('Description') }}</label>
                        <textarea name="services[${serviceIndex}][description]" rows="2" class="border p-2 rounded flex-1"></textarea>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="bg-red-600 hover:bg-red-700 active:bg-red-800 text-white px-3 py-1 shadow rounded-lg transition duration-200 self-end">
                        {{ __('Delete') }}
                    </button>
                </div>`);
            serviceIndex++;
        }

        function addPortfolio() {
            document.getElementById('portfolio-wrapper').insertAdjacentHTML('beforeend', `
                <div class="p-3 border rounded-lg bg-slate-100 grid gap-6 grid-cols-3">
                    <div class="flex flex-col items-center">
                        <div class="grid items-center mt-3 h-full">
                            <div class="col-start-1 row-start-1 w-32 h-32 rounded-lg bg-slate-200 border"></div>
                            <img id="photo-preview-${portfolioIndex}" class="hidden col-start-1 row-start-1 w-32 h-32 rounded-lg object-cover border">
                        </div>
                        <input type="file" name="portfolio[${portfolioIndex}][image]" accept="image/*" onchange="previewPhoto(event, 'photo-preview-${portfolioIndex}')"
                               class="text-xs w-full border p-2 rounded-lg shadow bg-blue-50 text-blue-700 cursor-pointer mt-3 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                    </div>
                    <div class="flex flex-col gap-2 col-span-2">
                        <div class="flex items-center gap-2">
                            <label class="w-28">{{ __('Title') }} <span class="text-red-600">*</span></label>
                            <input type="text" name="portfolio[${portfolioIndex}][title]" class="border p-2 rounded flex-1" required>
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="w-28">{{ __('Subtitle') }}</label>
                            <input type="text" name="portfolio[${portfolioIndex}][subtitle]" class="border p-2 rounded flex-1">
                        </div>
                        <div class="flex items-start gap-2">
                            <label class="w-28">{{ __('Description') }}</label>
                            <textarea name="portfolio[${portfolioIndex}][description]" rows="2" class="border p-2 rounded flex-1"></textarea>
                        </div>
                        <button type="button" onclick="this.closest('.p-3').remove()" class="bg-red-600 hover:bg-red-700 active:bg-red-800 text-white px-3 py-1 shadow rounded-lg transition duration-200 self-end">
                            {{ __('Delete') }}
                        </button>
                    </div>
                </div>`);
            portfolioIndex++;
        }
    </script>
</x-app-layout>
