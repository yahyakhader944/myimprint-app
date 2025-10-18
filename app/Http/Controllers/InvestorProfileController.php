<?php

namespace App\Http\Controllers;

use App\Models\InvestorProfile;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvestorProfileController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of investor profiles with search and pagination
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', InvestorProfile::class);

        $query = InvestorProfile::with(['user']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('job_title', 'like', "%{$search}%")
                    ->orWhere('bio', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $investorProfiles = $query->paginate(12);

        return view('investors.index', compact('investorProfiles'));
    }

    /**
     * Show one investor profile.
     */
    public function show(InvestorProfile $investorProfile)
    {
        $investorProfile->load('user');
        return view('investors.show', compact('investorProfile'));
    }

    /**
     * Show the form for creating a new investor profile.
     */
    public function create()
    {
        $this->authorize('create', InvestorProfile::class);

        // $investorProfile = request()->user()->investorProfile;

        // if ($investorProfile) {
        //     return redirect()->route('investor-profiles.edit', $investorProfile->id);
        // }

        return view('investors.create');
    }

    /**
     * Store a newly created investor profile in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', InvestorProfile::class);

        $data = $request->validate([
            'job_title' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('investors', 'public');
        }

        $data['user_id'] = Auth::id();

        $profile = InvestorProfile::create($data);

        return redirect()->route('investor-profiles.show', $profile)
            ->with('status', 'Profile created successfully.');
    }

    /**
     * Show the form for editing the specified investor profile.
     */
    public function edit(InvestorProfile $investorProfile)
    {
        $this->authorize('update', $investorProfile);

        return view('investors.edit', compact('investorProfile'));
    }

    /**
     * Update the specified investor profile in storage.
     */
    public function update(Request $request, InvestorProfile $investorProfile)
    {
        $this->authorize('update', $investorProfile);

        $data = $request->validate([
            'job_title' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($investorProfile->avatar && Storage::disk('public')->exists($investorProfile->avatar)) {
                Storage::disk('public')->delete($investorProfile->avatar);
            }

            $data['avatar'] = $request->file('avatar')->store('investors', 'public');
        }

        $investorProfile->update($data);

        return redirect()->route('investor-profiles.show', $investorProfile)
            ->with('status', 'Profile updated successfully.');
    }

    /**
     * Delete investor profile
     */
    public function destroy(InvestorProfile $investorProfile)
    {
        $this->authorize('delete', $investorProfile);

        // Delete avatar if exists
        if ($investorProfile->avatar && Storage::disk('public')->exists($investorProfile->avatar)) {
            Storage::disk('public')->delete($investorProfile->avatar);
        }

        $investorProfile->delete();

        return redirect()->route('investor-profiles.index')
            ->with('status', 'Investor profile deleted successfully.');
    }
}
