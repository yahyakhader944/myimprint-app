<?php

namespace App\Http\Controllers;

use App\Models\WorkerProfile;
use Illuminate\Http\Request;

class InvestorSearchController extends Controller
{
    public function index(Request $request)
    {
        $workers = collect(); // empty by default

        // Only run search if at least one search/filter is present
        if ($request->anyFilled(['search', 'job_title', 'skill', 'service', 'portfolio'])) {
            $query = WorkerProfile::with('user')
                ->when(
                    $request->search,
                    fn($q) =>
                    $q->where('job_title', 'like', '%' . $request->search . '%')
                        ->orWhere('bio_title', 'like', '%' . $request->search . '%')
                        ->orWhere('bio', 'like', '%' . $request->search . '%')
                        ->whereHas(
                            'skills',
                            fn($s) =>
                            $s->orWhere('name', 'like', '%' . $request->search . '%')
                                ->orWhere('description', 'like', '%' . $request->search . '%')
                        )
                        ->whereHas(
                            'services',
                            fn($s) =>
                            $s->orWhere('name', 'like', '%' . $request->search . '%')
                                ->orWhere('description', 'like', '%' . $request->search . '%')
                        )
                        ->whereHas(
                            'portfolioItems',
                            callback: fn($s) =>
                            $s->orWhere('title', 'like', '%' . $request->search . '%')
                                ->orWhere('subtitle', 'like', '%' . $request->search . '%')
                                ->orWhere('description', 'like', '%' . $request->search . '%')
                        )
                )
                ->when(
                    $request->job_title,
                    fn($q, $values) =>
                    $q->whereIn('job_title', (array) $values)
                )
                ->when(
                    $request->skill,
                    fn($q, $values) =>
                    $q->whereHas('skills', fn($s) => $s->whereIn('name', (array) $values))
                )
                ->when(
                    $request->service,
                    fn($q, $values) =>
                    $q->whereHas('services', fn($s) => $s->whereIn('name', (array) $values))
                )
                ->when(
                    $request->portfolio,
                    fn($q, $values) =>
                    $q->whereHas('portfolioItems', fn($p) => $p->whereIn('title', (array) $values))
                );

            $workers = $query->paginate(10);
        }

        return view('investors.workers.index', compact('workers'));
    }
}
