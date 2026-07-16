<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use App\Services\AtsScorer;
use App\Services\ResumeFromJobDescriptionGenerator;
use App\Support\ResumeThemes;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResumeFromJdController extends Controller
{
    public function __construct(
        protected ResumeFromJobDescriptionGenerator $generator,
        protected AtsScorer $scorer,
    ) {}

    public function create(Request $request): View|RedirectResponse
    {
        if (! $request->user()->hasPlanAccess()) {
            return $this->planLimitRedirect('Please subscribe to a plan to create resumes from job descriptions.');
        }

        if (! $request->user()->canCreateResume()) {
            return $this->planLimitRedirect(
                'You have reached the resume limit for your plan. Upgrade to create more.'
            );
        }

        return view('resumes.from-jd.create', [
            'resumes' => $request->user()->resumes()->get(),
            'themeCatalog' => ResumeThemes::catalog(),
            'selectedTemplate' => ResumeThemes::resolve($request->query('template', 'modern')),
            'prefillJd' => $request->query('jd', ''),
            'prefillTitle' => $request->query('title', ''),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (! $request->user()->hasPlanAccess()) {
            return $this->planLimitRedirect('Please subscribe to a plan to create resumes from job descriptions.');
        }

        if (! $request->user()->canCreateResume()) {
            return $this->planLimitRedirect(
                'You have reached the resume limit for your plan. Upgrade to create more.'
            );
        }

        $validated = $request->validate([
            'job_title' => ['nullable', 'string', 'max:160'],
            'job_description' => ['required', 'string', 'min:40', 'max:20000'],
            'template' => ['required', 'string', 'in:'.implode(',', ResumeThemes::ids())],
            'full_name' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:60'],
            'location' => ['nullable', 'string', 'max:120'],
            'years_experience' => ['nullable', 'integer', 'min:0', 'max:45'],
            'current_role' => ['nullable', 'string', 'max:160'],
            'background_notes' => ['nullable', 'string', 'max:3000'],
            'source_resume_id' => ['nullable', 'integer', 'exists:resumes,id'],
        ]);

        if (! empty($validated['source_resume_id'])) {
            abort_unless(
                $request->user()->resumes()->whereKey($validated['source_resume_id'])->exists(),
                403
            );
        }

        $payload = $this->generator->generate($request->user(), $validated);
        $payload['user_id'] = $request->user()->id;

        $resume = Resume::create($payload);

        $scorePreview = $this->scorer->score($resume, $validated['job_description']);

        return redirect()
            ->route('resumes.edit', $resume)
            ->with('status', 'Resume generated from job description. Estimated ATS match: '.$scorePreview['score'].'%. Review and personalize before applying.')
            ->with('jd_generated', true);
    }

    protected function planLimitRedirect(string $message): RedirectResponse
    {
        return redirect()->route('plans.index')->with('status', $message);
    }
}
