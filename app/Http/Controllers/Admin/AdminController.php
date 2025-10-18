<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkerProfile;
use App\Models\InvestorProfile;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        // عدد Worker Profiles
        $workersCount = WorkerProfile::count();

        // عدد Investor Profiles
        $investorsCount = InvestorProfile::count();

        // عدد الزوار خلال الأسبوع (نفترض أن الزوار هم المستخدمين النشطين)
        $weeklyVisitors = $this->getWeeklyVisitors();

        // إحصائيات إضافية يمكن إضافتها لاحقاً
        $recentUsers = User::with(['workerProfile', 'investorProfile'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'workersCount',
            'investorsCount',
            'weeklyVisitors',
            'recentUsers'
        ));
    }

    private function getWeeklyVisitors()
    {
        // نفترض أن الزوار هم المستخدمين الذين سجلوا دخولهم خلال الأسبوع
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        return User::whereBetween('last_login_at', [$startOfWeek, $endOfWeek])
            ->orWhere(function($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
            })
            ->distinct()
            ->count('id');
    }

    // يمكن إضافة المزيد من الإحصائيات لاحقاً
    private function getMonthlyGrowth()
    {
        // نمو المستخدمين خلال الشهر
        $currentMonth = User::whereMonth('created_at', now()->month)->count();
        $lastMonth = User::whereMonth('created_at', now()->subMonth()->month)->count();

        if ($lastMonth > 0) {
            return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 2);
        }

        return 0;
    }
}
