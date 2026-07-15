<?php

namespace App\Http\Controllers;

use App\Models\AtsCheck;
use App\Models\Plan;
use App\Models\Resume;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $activeSubs = Subscription::where('status', 'active')
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>', now()));

        $revenueTotal = (int) Subscription::sum('amount_paid');
        $revenueMonth = (int) Subscription::where('created_at', '>=', now()->startOfMonth())->sum('amount_paid');
        $revenueLastMonth = (int) Subscription::whereBetween('created_at', [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth(),
        ])->sum('amount_paid');

        $paymentsCount = Subscription::where('amount_paid', '>', 0)->count();
        $avgPayment = $paymentsCount > 0 ? (int) round($revenueTotal / $paymentsCount) : 0;

        $revenueGrowth = $revenueLastMonth > 0
            ? round((($revenueMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1)
            : ($revenueMonth > 0 ? 100 : 0);

        $mrr = (int) Subscription::query()
            ->where('subscriptions.status', 'active')
            ->where(fn ($q) => $q->whereNull('subscriptions.ends_at')->orWhere('subscriptions.ends_at', '>', now()))
            ->join('plans', 'plans.id', '=', 'subscriptions.plan_id')
            ->where('plans.interval', 'monthly')
            ->sum('plans.price');

        $stats = [
            'users' => User::count(),
            'admins' => User::where('is_admin', true)->count(),
            'resumes' => Resume::count(),
            'checks' => AtsCheck::count(),
            'avg_score' => (int) round(AtsCheck::avg('score') ?? 0),
            'new_users_7d' => User::where('created_at', '>=', now()->subDays(7))->count(),
            'active_subs' => (clone $activeSubs)->count(),
            'revenue' => $revenueTotal,
            'revenue_month' => $revenueMonth,
            'revenue_last_month' => $revenueLastMonth,
            'revenue_growth' => $revenueGrowth,
            'payments_count' => $paymentsCount,
            'avg_payment' => $avgPayment,
            'mrr' => $mrr,
        ];

        $chartData = [
            'revenueByMonth' => $this->revenueByMonth(6),
            'revenueByPlan' => $this->revenueByPlan(),
            'userSignups' => $this->userSignupsByMonth(6),
            'subscriptionStatus' => $this->subscriptionStatusCounts(),
        ];

        $templateUsage = Resume::selectRaw('template, COUNT(*) as total')
            ->groupBy('template')
            ->pluck('total', 'template')
            ->all();

        $recentUsers = User::withCount(['resumes', 'atsChecks'])->latest()->take(8)->get();

        $recentChecks = AtsCheck::with(['user', 'resume'])->latest()->take(8)->get();

        $recentPayments = Subscription::with(['user', 'plan'])
            ->where('amount_paid', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        $topResumes = Resume::withCount('atsChecks')
            ->with('user')
            ->orderByDesc('ats_checks_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'chartData',
            'templateUsage',
            'recentUsers',
            'recentChecks',
            'recentPayments',
            'topResumes',
        ));
    }

    /**
     * @return array{labels: array<int, string>, values: array<int, int>}
     */
    protected function revenueByMonth(int $months = 6): array
    {
        $labels = [];
        $values = [];
        $keys = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key = $date->format('Y-m');
            $keys[] = $key;
            $labels[] = $date->format('M Y');
            $values[] = 0;
        }

        $rows = Subscription::query()
            ->where('created_at', '>=', now()->subMonths($months - 1)->startOfMonth())
            ->get(['amount_paid', 'created_at']);

        foreach ($rows as $row) {
            $key = Carbon::parse($row->created_at)->format('Y-m');
            $index = array_search($key, $keys, true);
            if ($index !== false) {
                $values[$index] += (int) $row->amount_paid;
            }
        }

        return compact('labels', 'values');
    }

    /**
     * @return array{labels: array<int, string>, values: array<int, int>}
     */
    protected function revenueByPlan(): array
    {
        $rows = Subscription::query()
            ->selectRaw('plan_id, SUM(amount_paid) as total')
            ->where('amount_paid', '>', 0)
            ->groupBy('plan_id')
            ->get();

        if ($rows->isEmpty()) {
            return ['labels' => ['No payments yet'], 'values' => [0]];
        }

        $planNames = Plan::whereIn('id', $rows->pluck('plan_id'))->pluck('name', 'id');

        return [
            'labels' => $rows->map(fn ($row) => $planNames[$row->plan_id] ?? 'Unknown')->values()->all(),
            'values' => $rows->map(fn ($row) => (int) $row->total)->values()->all(),
        ];
    }

    /**
     * @return array{labels: array<int, string>, values: array<int, int>}
     */
    protected function userSignupsByMonth(int $months = 6): array
    {
        $labels = [];
        $values = [];
        $keys = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key = $date->format('Y-m');
            $keys[] = $key;
            $labels[] = $date->format('M Y');
            $values[] = 0;
        }

        $rows = User::query()
            ->where('created_at', '>=', now()->subMonths($months - 1)->startOfMonth())
            ->get(['created_at']);

        foreach ($rows as $row) {
            $key = Carbon::parse($row->created_at)->format('Y-m');
            $index = array_search($key, $keys, true);
            if ($index !== false) {
                $values[$index]++;
            }
        }

        return compact('labels', 'values');
    }

    /**
     * @return array{labels: array<int, string>, values: array<int, int>}
     */
    protected function subscriptionStatusCounts(): array
    {
        $statuses = ['active', 'cancelled', 'expired'];
        $counts = [];

        foreach ($statuses as $status) {
            $counts[] = Subscription::where('status', $status)->count();
        }

        return [
            'labels' => ['Active', 'Cancelled', 'Expired'],
            'values' => $counts,
        ];
    }

    public function users(Request $request): View
    {
        $search = $request->string('q')->toString();

        $users = User::withCount(['resumes', 'atsChecks'])
            ->when($search, fn ($query) => $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
            }))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users', compact('users', 'search'));
    }

    public function showUser(User $user): View
    {
        $user->loadCount(['resumes', 'atsChecks']);
        $resumes = $user->resumes()->withCount('atsChecks')->get();
        $checks = $user->atsChecks()->with('resume')->take(10)->get();

        return view('admin.user-show', compact('user', 'resumes', 'checks'));
    }

    public function subscriptions(Request $request): View
    {
        $status = $request->string('status')->toString();

        $subscriptions = Subscription::with(['user', 'plan'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.subscriptions', compact('subscriptions', 'status'));
    }

    public function toggleAdmin(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('status', 'You cannot change your own admin status.');
        }

        $user->update(['is_admin' => ! $user->is_admin]);

        return back()->with('status', "Updated admin rights for {$user->name}.");
    }

    public function destroyUser(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('status', 'You cannot delete your own account here.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('status', 'User and their data deleted.');
    }
}
