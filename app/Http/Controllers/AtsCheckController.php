<?php

namespace App\Http\Controllers;

use App\Models\AtsCheck;
use App\Models\Resume;
use App\Services\AtsScorer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AtsCheckController extends Controller
{
    public function index(Request $request): View
    {
        $checks = $request->user()->atsChecks()->with('resume')->paginate(10);

        return view('ats.index', compact('checks'));
    }

    public function create(Request $request): View
    {
        $resumes = $request->user()->resumes()->get();

        return view('ats.create', [
            'resumes' => $resumes,
            'selectedResumeId' => (int) $request->query('resume', $resumes->first()->id ?? 0),
        ]);
    }

    public function store(Request $request, AtsScorer $scorer): RedirectResponse
    {
        $validated = $request->validate([
            'resume_id' => ['required', 'integer', 'exists:resumes,id'],
            'job_title' => ['nullable', 'string', 'max:160'],
            'job_description' => ['required', 'string', 'min:40', 'max:20000'],
        ]);

        /** @var Resume $resume */
        $resume = $request->user()->resumes()->findOrFail($validated['resume_id']);

        $result = $scorer->score($resume, $validated['job_description']);

        $check = AtsCheck::create([
            'user_id' => $request->user()->id,
            'resume_id' => $resume->id,
            'job_title' => $validated['job_title'] ?? null,
            'job_description' => $validated['job_description'],
            'score' => $result['score'],
            'matched_keywords' => $result['matched'],
            'missing_keywords' => $result['missing'],
            'suggestions' => $result['suggestions'],
        ]);

        return redirect()->route('ats.show', $check);
    }

    public function show(Request $request, AtsCheck $ats): View
    {
        abort_unless($ats->user_id === $request->user()->id, 403);

        $ats->load('resume');

        return view('ats.show', ['check' => $ats]);
    }

    public function destroy(Request $request, AtsCheck $ats): RedirectResponse
    {
        abort_unless($ats->user_id === $request->user()->id, 403);

        $ats->delete();

        return redirect()->route('ats.index')->with('status', 'ATS check deleted.');
    }
}
