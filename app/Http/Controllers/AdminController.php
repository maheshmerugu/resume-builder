<?php

namespace App\Http\Controllers;

use App\Models\AtsCheck;
use App\Models\Resume;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $activeSubs = Subscription::where('status', 'active')
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>', now()));

        $stats = [
            'users' => User::count(),
            'admins' => User::where('is_admin', true)->count(),
            'resumes' => Resume::count(),
            'checks' => AtsCheck::count(),
            'avg_score' => (int) round(AtsCheck::avg('score') ?? 0),
            'new_users_7d' => User::where('created_at', '>=', now()->subDays(7))->count(),
            'active_subs' => (clone $activeSubs)->count(),
            'revenue' => (int) Subscription::sum('amount_paid'),
        ];

        $templateUsage = Resume::selectRaw('template, COUNT(*) as total')
            ->groupBy('template')
            ->pluck('total', 'template')
            ->all();

        $recentUsers = User::withCount(['resumes', 'atsChecks'])->latest()->take(8)->get();

        $recentChecks = AtsCheck::with(['user', 'resume'])->latest()->take(8)->get();

        $topResumes = Resume::withCount('atsChecks')
            ->with('user')
            ->orderByDesc('ats_checks_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'templateUsage', 'recentUsers', 'recentChecks', 'topResumes'));
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
