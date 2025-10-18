<?php

namespace App\Http\Controllers;

use App\Models\WorkerProfile;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WorkerProfileController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of worker profiles with search and pagination
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', WorkerProfile::class);

        $query = WorkerProfile::with(['user', 'skills']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('job_title', 'like', "%{$search}%")
                    ->orWhere('bio_title', 'like', "%{$search}%")
                    ->orWhere('bio', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('skills', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $workerProfiles = $query->paginate(12);

        return view('workers.index', compact('workerProfiles'));
    }

    /**
     * Show one worker profile
     */
    public function show(WorkerProfile $workerProfile)
    {
        $this->authorize('view', WorkerProfile::class);

        if ($workerProfile == null) {
            $this->create();
        }

        $workerProfile->load(['skills', 'services', 'portfolioItems', 'user']);
        return view('workers.show', compact('workerProfile'));
    }

    /**
     * Create worker profile
     */
    public function create()
    {
        $this->authorize('create', WorkerProfile::class);

        $workerProfile = request()->user()->workerProfile;

        if ($workerProfile != null) {
            return redirect()->route('worker-profiles.edit', $workerProfile->id);
        }

        return view('workers.create');
    }

    /**
     * Save worker profile
     */
    public function store(Request $request)
    {
        $this->authorize('create', WorkerProfile::class);

        $data = $request->validate([
            'job_title' => 'required|string|max:255',
            'bio_title' => 'nullable|string|max:255',
            'bio' => 'required|string',
            'avatar' => 'nullable|image|max:2048',

            // Skills
            'skills.*.name' => 'required_with:skills|string|max:255',
            'skills.*.description' => 'nullable|string',

            // Services
            'services.*.name' => 'required_with:services|string|max:255',
            'services.*.description' => 'nullable|string',

            // Portfolios
            'portfolio.*.title' => 'required_with:portfolio|string|max:255',
            'portfolio.*.subtitle' => 'nullable|string|max:255',
            'portfolio.*.description' => 'nullable|string',
            'portfolio.*.image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $data['user_id'] = Auth::id();
        $profile = WorkerProfile::create($data);

        // Skills
        if ($request->filled('skills')) {
            foreach ($request->skills as $skill) {
                $profile->skills()->create($skill);
            }
        }

        // Services
        if ($request->filled('services')) {
            foreach ($request->services as $service) {
                $profile->services()->create($service);
            }
        }

        // Portfolios
        if ($request->filled('portfolio')) {
            foreach ($request->portfolio as $item) {
                if (isset($item['image']) && $item['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $item['image'] = $item['image']->store('portfolio', 'public');
                }
                $profile->portfolioItems()->create($item);
            }
        }

        return redirect()->route('worker-profiles.show', $profile)
            ->with('status', 'Profile created successfully.');
    }

    /**
     * Edit worker profile
     */
    public function edit(WorkerProfile $workerProfile)
    {
        $this->authorize('update', $workerProfile);
        return view('workers.edit', compact('workerProfile'));
    }

    /**
     * Update worker profile
     */
    public function update(Request $request, WorkerProfile $workerProfile)
    {
        $this->authorize('update', $workerProfile);

        $data = $request->validate([
            'job_title' => 'required|string|max:255',
            'bio_title' => 'nullable|string|max:255',
            'bio' => 'required|string',
            'avatar' => 'nullable|image|max:2048',

            // Skills
            'skills.*.id' => 'nullable|integer|exists:skills,id',
            'skills.*.name' => 'required_with:skills|string|max:255',
            'skills.*.description' => 'nullable|string',

            // Services
            'services.*.id' => 'nullable|integer|exists:services,id',
            'services.*.name' => 'required_with:services|string|max:255',
            'services.*.description' => 'nullable|string',

            // portfolios
            'portfolio.*.id' => 'nullable|integer|exists:portfolio_items,id',
            'portfolio.*.title' => 'required_with:portfolio|string|max:255',
            'portfolio.*.subtitle' => 'nullable|string|max:255',
            'portfolio.*.description' => 'nullable|string',
            'portfolio.*.image' => 'nullable|image|max:2048',
        ]);

        // Avatar Image
        if ($request->hasFile('avatar')) {
            if ($workerProfile->avatar && Storage::disk('public')->exists($workerProfile->avatar)) {
                Storage::disk('public')->delete($workerProfile->avatar);
            }

            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $workerProfile->update($data);

        // Sync Skills (Delete skills not used by the worker anymore)
        $skillIds = collect($request->input('skills', []))->pluck('id')->filter()->all();
        $workerProfile->skills()->whereNotIn('id', $skillIds)->delete();

        // Skills
        if ($request->filled('skills')) {
            foreach ($request->skills as $skillData) {
                $workerProfile->skills()->updateOrCreate(['id' => $skillData['id'] ?? null], $skillData);
            }
        }

        // Sync Services (Delete services not used by the worker anymore)
        $serviceIds = collect($request->input('services', []))->pluck('id')->filter()->all();
        $workerProfile->services()->whereNotIn('id', $serviceIds)->delete();

        // Services
        if ($request->filled('services')) {
            foreach ($request->services as $serviceData) {
                $workerProfile->services()->updateOrCreate(['id' => $serviceData['id'] ?? null], $serviceData);
            }
        }

        // Sync Portfolios (Delete portfolios not used by the worker anymore)
        $portfolioIds = collect($request->input('portfolio', []))->pluck('id')->filter()->all();
        $workerProfile->portfolioItems()->whereNotIn('id', $portfolioIds)->delete();

        // Portfolios
        if ($request->filled('portfolio')) {
            foreach ($request->portfolio as $i => $portfolioData) {
                if (!empty($portfolioData['id'])) {
                    $portfolio = $workerProfile->portfolioItems()->find($portfolioData['id']);

                    if ($portfolio) {
                        // Check for new image and if there any delete old one
                        if ($request->hasFile("portfolio.$i.image")) {
                            if ($portfolio->image && Storage::disk('public')->exists($portfolio->image)) {
                                Storage::disk('public')->delete($portfolio->image);
                            }
                        }
                    }
                }

                if ($request->hasFile("portfolio.$i.image")) {
                    $portfolioData['image'] = $request->file("portfolio.$i.image")->store('portfolio', 'public');
                }

                $workerProfile->portfolioItems()->updateOrCreate(['id' => $portfolioData['id'] ?? null], $portfolioData);
            }
        }

        return redirect()->route('worker-profiles.show', $workerProfile)
            ->with('status', 'Profile updated successfully.');
    }

    /**
     * Delete worker profile
     */
    public function destroy(WorkerProfile $workerProfile)
    {
        $this->authorize('delete', $workerProfile);

        // Delete avatar if exists
        if ($workerProfile->avatar && Storage::disk('public')->exists($workerProfile->avatar)) {
            Storage::disk('public')->delete($workerProfile->avatar);
        }

        $workerProfile->delete();

        return redirect()->route('worker-profiles.index')
            ->with('status', 'Worker profile deleted successfully.');
    }

}
