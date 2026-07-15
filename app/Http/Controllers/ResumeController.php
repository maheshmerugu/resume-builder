<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use App\Support\ResumeThemes;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ResumeController extends Controller
{
    public function index(Request $request): View
    {
        $resumes = $request->user()->resumes()->get();

        return view('resumes.index', compact('resumes'));
    }

    public function create(Request $request): View|RedirectResponse
    {
        if (! $request->user()->hasPlanAccess()) {
            return $this->planLimitRedirect('Please subscribe to a plan to create resumes.');
        }

        if (! $request->user()->canCreateResume()) {
            return $this->planLimitRedirect(
                'You have reached the resume limit for your plan. Upgrade to create more.'
            );
        }

        $template = ResumeThemes::resolve($request->query('template', 'modern'));

        $resume = new Resume([
            'title' => 'Untitled Resume',
            'template' => $template,
        ]);

        return view('resumes.create', [
            'resume' => $resume,
            'templates' => Resume::templates(),
            'themeCatalog' => ResumeThemes::catalog(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (! $request->user()->hasPlanAccess()) {
            return $this->planLimitRedirect('Please subscribe to a plan to create resumes.');
        }

        if (! $request->user()->canCreateResume()) {
            return $this->planLimitRedirect(
                'You have reached the resume limit for your plan. Upgrade to create more.'
            );
        }

        $data = $this->validated($request);
        $data['user_id'] = $request->user()->id;

        $resume = Resume::create($data);

        return redirect()
            ->route('resumes.edit', $resume)
            ->with('status', 'Resume created. Keep editing or preview it.');
    }

    public function show(Resume $resume): View
    {
        $this->authorizeResume($resume);

        return view('resumes.show', [
            'resume' => $resume,
            'template' => 'resumes.templates.themed',
        ]);
    }

    public function edit(Resume $resume): View
    {
        $this->authorizeResume($resume);

        return view('resumes.edit', [
            'resume' => $resume,
            'templates' => Resume::templates(),
            'themeCatalog' => ResumeThemes::catalog(),
        ]);
    }

    public function update(Request $request, Resume $resume): RedirectResponse
    {
        $this->authorizeResume($resume);

        $resume->update($this->validated($request));

        return redirect()
            ->route('resumes.edit', $resume)
            ->with('status', 'Resume saved.');
    }

    public function destroy(Resume $resume): RedirectResponse
    {
        $this->authorizeResume($resume);

        $resume->delete();

        return redirect()
            ->route('resumes.index')
            ->with('status', 'Resume deleted.');
    }

    public function duplicate(Request $request, Resume $resume): RedirectResponse
    {
        $this->authorizeResume($resume);

        if (! $request->user()->hasPlanAccess()) {
            return $this->planLimitRedirect('Please subscribe to a plan to duplicate resumes.');
        }

        if (! $request->user()->canCreateResume()) {
            return $this->planLimitRedirect(
                'You have reached the resume limit for your plan. Upgrade to duplicate resumes.'
            );
        }

        $copy = $resume->replicate();
        $copy->title = $resume->title . ' (Copy)';
        $copy->save();

        return redirect()
            ->route('resumes.edit', $copy)
            ->with('status', 'Resume duplicated.');
    }

    public function pdf(Request $request, Resume $resume): Response|RedirectResponse
    {
        $this->authorizeResume($resume);

        $user = $request->user();

        if (! $user->hasPlanAccess()) {
            return $this->planLimitRedirect('Please subscribe to a plan to download PDFs.');
        }

        if (! $user->canDownload()) {
            return $this->planLimitRedirect(
                'You have used all PDF downloads for your plan this period. Upgrade for unlimited downloads.'
            );
        }

        $pdf = Pdf::loadView('resumes.pdf', [
            'resume' => $resume,
            'template' => 'resumes.templates.themed',
            'watermark' => $user->billingEnabled() && (bool) ($user->currentPlan()?->watermark),
        ])->setPaper('a4');

        if ($user->billingEnabled()) {
            $user->recordDownload();
        }

        $filename = Str::slug($resume->full_name ?: $resume->title ?: 'resume') . '.pdf';

        return $pdf->download($filename);
    }

    protected function planLimitRedirect(string $message): RedirectResponse
    {
        return redirect()->route('plans.index')->with('status', $message);
    }

    /**
     * @return array<string, mixed>
     */
    protected function validated(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'template' => ['required', 'string', 'in:' . implode(',', ResumeThemes::ids())],
            'full_name' => ['nullable', 'string', 'max:120'],
            'headline' => ['nullable', 'string', 'max:160'],
            'email' => ['nullable', 'string', 'max:160'],
            'phone' => ['nullable', 'string', 'max:60'],
            'location' => ['nullable', 'string', 'max:120'],
            'linkedin' => ['nullable', 'string', 'max:200'],
            'website' => ['nullable', 'string', 'max:200'],
            'summary' => ['nullable', 'string', 'max:2000'],

            'experience' => ['nullable', 'array'],
            'experience.*.role' => ['nullable', 'string', 'max:160'],
            'experience.*.company' => ['nullable', 'string', 'max:160'],
            'experience.*.location' => ['nullable', 'string', 'max:120'],
            'experience.*.start' => ['nullable', 'string', 'max:40'],
            'experience.*.end' => ['nullable', 'string', 'max:40'],
            'experience.*.bullets' => ['nullable', 'string', 'max:3000'],

            'education' => ['nullable', 'array'],
            'education.*.degree' => ['nullable', 'string', 'max:160'],
            'education.*.school' => ['nullable', 'string', 'max:160'],
            'education.*.field' => ['nullable', 'string', 'max:160'],
            'education.*.start' => ['nullable', 'string', 'max:40'],
            'education.*.end' => ['nullable', 'string', 'max:40'],

            'projects' => ['nullable', 'array'],
            'projects.*.name' => ['nullable', 'string', 'max:160'],
            'projects.*.tech' => ['nullable', 'string', 'max:200'],
            'projects.*.description' => ['nullable', 'string', 'max:1000'],

            'certifications' => ['nullable', 'array'],
            'certifications.*.name' => ['nullable', 'string', 'max:200'],
            'certifications.*.issuer' => ['nullable', 'string', 'max:160'],
            'certifications.*.year' => ['nullable', 'string', 'max:20'],

            'skills_raw' => ['nullable', 'string', 'max:1500'],
            'languages_raw' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['experience'] = $this->cleanRepeater($request->input('experience', []), ['role', 'company']);
        $validated['education'] = $this->cleanRepeater($request->input('education', []), ['degree', 'school']);
        $validated['projects'] = $this->cleanRepeater($request->input('projects', []), ['name']);
        $validated['certifications'] = $this->cleanRepeater($request->input('certifications', []), ['name']);

        $validated['skills'] = $this->splitList($request->input('skills_raw'));
        $validated['languages'] = $this->splitList($request->input('languages_raw'));

        unset($validated['skills_raw'], $validated['languages_raw']);

        return $validated;
    }

    /**
     * Remove empty rows from a repeater, keeping rows that have at least one required field.
     *
     * @param  array<int, array<string, mixed>>  $rows
     * @param  array<int, string>  $requiredAny
     * @return array<int, array<string, mixed>>
     */
    protected function cleanRepeater(array $rows, array $requiredAny): array
    {
        $clean = [];

        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }

            $hasContent = false;
            foreach ($requiredAny as $field) {
                if (filled($row[$field] ?? null)) {
                    $hasContent = true;
                    break;
                }
            }

            if ($hasContent) {
                $clean[] = array_map(fn ($v) => is_string($v) ? trim($v) : $v, $row);
            }
        }

        return array_values($clean);
    }

    /**
     * @return array<int, string>
     */
    protected function splitList(?string $value): array
    {
        if (blank($value)) {
            return [];
        }

        return collect(preg_split('/[,\n]+/', $value))
            ->map(fn ($v) => trim($v))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    protected function safeTemplate(?string $template): string
    {
        return ResumeThemes::resolve($template ?? 'modern');
    }

    protected function authorizeResume(Resume $resume): void
    {
        abort_unless($resume->user_id === request()->user()->id, 403);
    }
}
