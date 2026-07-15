<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $resumes = $user->resumes()->withCount('atsChecks')->get();
        $atsChecks = $user->atsChecks()->with('resume')->take(5)->get();

        $stats = [
            'resumes' => $resumes->count(),
            'checks' => $user->atsChecks()->count(),
            'best_score' => (int) ($user->atsChecks()->max('score') ?? 0),
            'avg_completeness' => $resumes->isNotEmpty()
                ? (int) round($resumes->avg(fn ($r) => $r->completeness()))
                : 0,
        ];

        $plan = $user->currentPlan();

        return view('dashboard', compact('resumes', 'atsChecks', 'stats', 'plan'));
    }
}
