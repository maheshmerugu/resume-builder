<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminPlanController extends Controller
{
    public function index(): View
    {
        $plans = Plan::withCount(['subscriptions' => fn ($q) => $q->where('status', 'active')])
            ->orderBy('sort_order')
            ->get();

        return view('admin.plans.index', compact('plans'));
    }

    public function create(): View
    {
        return view('admin.plans.form', ['plan' => new Plan(['interval' => 'monthly', 'currency' => 'INR', 'is_active' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        Plan::create($data);

        return redirect()->route('admin.plans.index')->with('status', 'Plan created.');
    }

    public function edit(Plan $plan): View
    {
        return view('admin.plans.form', compact('plan'));
    }

    public function update(Request $request, Plan $plan): RedirectResponse
    {
        $data = $this->validated($request, $plan);

        $plan->update($data);

        return redirect()->route('admin.plans.index')->with('status', 'Plan updated.');
    }

    public function destroy(Plan $plan): RedirectResponse
    {
        if ($plan->subscriptions()->where('status', 'active')->exists()) {
            return back()->with('status', 'Cannot delete a plan with active subscriptions.');
        }

        $plan->delete();

        return redirect()->route('admin.plans.index')->with('status', 'Plan deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validated(Request $request, ?Plan $plan = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'slug' => ['nullable', 'string', 'max:80', Rule::unique('plans', 'slug')->ignore($plan?->id)],
            'description' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:1'],
            'currency' => ['required', 'string', 'max:8'],
            'interval' => ['required', Rule::in(array_keys(Plan::INTERVALS))],
            'period_days' => ['nullable', 'integer', 'min:1', 'max:3650'],
            'resume_limit' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'download_limit' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'edit_limit' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'features_raw' => ['nullable', 'string', 'max:2000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:1000'],
        ]);

        $validated['slug'] = Str::slug($validated['slug'] ?: $validated['name']);
        $validated['watermark'] = $request->boolean('watermark');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_default'] = false;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $validated['features'] = collect(preg_split('/\r?\n/', (string) $request->input('features_raw')))
            ->map(fn ($v) => trim($v))
            ->filter()
            ->values()
            ->all();

        unset($validated['features_raw']);

        return $validated;
    }
}
