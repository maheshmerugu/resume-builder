<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PlanController extends Controller
{
    public function index(Request $request): View
    {
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();
        $currentPlan = $request->user()->currentPlan();
        $subscription = $request->user()->activeSubscription;

        return view('plans.index', compact('plans', 'currentPlan', 'subscription'));
    }

    public function checkout(Request $request, Plan $plan): View|RedirectResponse
    {
        abort_unless($plan->is_active, 404);

        if ($plan->isFree()) {
            return $this->downgradeToFree($request);
        }

        return view('plans.checkout', ['plan' => $plan]);
    }

    /**
     * Activate a paid plan. Payment is simulated here — plug a gateway
     * (e.g. Razorpay) into this method and only continue on verified success.
     */
    public function subscribe(Request $request, Plan $plan): RedirectResponse
    {
        abort_unless($plan->is_active, 404);

        $user = $request->user();

        if ($plan->isFree()) {
            return $this->downgradeToFree($request);
        }

        // Expire any current active subscription before starting the new one.
        $user->subscriptions()->where('status', 'active')->update(['status' => 'cancelled']);

        $duration = $plan->durationDays();

        Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => $duration ? now()->addDays($duration) : null,
            'downloads_used' => 0,
            'amount_paid' => $plan->price,
            'payment_reference' => 'MOCK-' . strtoupper(Str::random(10)),
        ]);

        return redirect()
            ->route('plans.index')
            ->with('status', "You are now on the {$plan->name} plan. Enjoy!");
    }

    public function cancel(Request $request): RedirectResponse
    {
        $request->user()->subscriptions()->where('status', 'active')->update(['status' => 'cancelled']);

        return redirect()
            ->route('plans.index')
            ->with('status', 'Your subscription was cancelled. You are back on the Free plan.');
    }

    protected function downgradeToFree(Request $request): RedirectResponse
    {
        $request->user()->subscriptions()->where('status', 'active')->update(['status' => 'cancelled']);

        return redirect()
            ->route('plans.index')
            ->with('status', 'You are on the Free plan.');
    }
}
