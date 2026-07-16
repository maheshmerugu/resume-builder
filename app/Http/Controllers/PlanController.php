<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Notifications\SubscriptionConfirmedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Throwable;

class PlanController extends Controller
{
    public function index(Request $request): View
    {
        $plans = Plan::where('is_active', true)->paid()->orderBy('sort_order')->get();
        $currentPlan = $request->user()->currentPlan();
        $subscription = $request->user()->activeSubscription;

        return view('plans.index', compact('plans', 'currentPlan', 'subscription'));
    }

    public function checkout(Request $request, Plan $plan): View|RedirectResponse
    {
        abort_unless($plan->is_active && ! $plan->isFree(), 404);

        $razorpayKeyId = config('razorpay.key_id');
        $razorpayKeySecret = config('razorpay.key_secret');

        $razorpayOrder = null;

        if ($razorpayKeyId && $razorpayKeySecret && ! str_starts_with($razorpayKeyId, 'rzp_test_XXX')) {
            $api = new Api($razorpayKeyId, $razorpayKeySecret);

            $razorpayOrder = $api->order->create([
                'amount' => $plan->price * 100,
                'currency' => config('razorpay.currency', 'INR'),
                'receipt' => 'plan_' . $plan->id . '_user_' . $request->user()->id . '_' . time(),
                'notes' => [
                    'plan_id' => $plan->id,
                    'plan_name' => $plan->name,
                    'user_id' => $request->user()->id,
                ],
            ]);
        }

        return view('plans.checkout', [
            'plan' => $plan,
            'razorpayKeyId' => $razorpayKeyId,
            'razorpayOrder' => $razorpayOrder,
            'userName' => $request->user()->name,
            'userEmail' => $request->user()->email,
        ]);
    }

    public function subscribe(Request $request, Plan $plan): RedirectResponse
    {
        abort_unless($plan->is_active && ! $plan->isFree(), 404);

        return redirect()->route('plans.checkout', $plan);
    }

    /**
     * Verify Razorpay payment and activate subscription.
     */
    public function verifyPayment(Request $request, Plan $plan): RedirectResponse
    {
        abort_unless($plan->is_active && ! $plan->isFree(), 404);

        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $razorpayKeyId = config('razorpay.key_id');
        $razorpayKeySecret = config('razorpay.key_secret');

        try {
            $api = new Api($razorpayKeyId, $razorpayKeySecret);

            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ]);
        } catch (SignatureVerificationError $e) {
            return redirect()
                ->route('plans.checkout', $plan)
                ->with('error', 'Payment verification failed. Please try again.');
        }

        $user = $request->user();

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
            'payment_reference' => $request->razorpay_payment_id,
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature,
        ]);

        try {
            $user->notify(new SubscriptionConfirmedNotification($plan));
        } catch (Throwable $e) {
            Log::warning('Subscription confirmation email failed after payment.', [
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()
            ->route('plans.index')
            ->with('status', "Payment successful! You are now on the {$plan->name} plan.");
    }

    public function cancel(Request $request): RedirectResponse
    {
        $request->user()->subscriptions()->where('status', 'active')->update(['status' => 'cancelled']);

        return redirect()
            ->route('plans.index')
            ->with('status', 'Your subscription was cancelled. Subscribe again to continue using premium features.');
    }
}
