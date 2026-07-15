<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-slate-900 sm:text-2xl">Checkout</h1>
            <p class="text-sm text-slate-500">Complete your subscription payment</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-xl space-y-4">
        @include('partials.alert')

        <div class="admin-card p-8">
            <h3 class="text-lg font-bold text-slate-900">You're subscribing to {{ $plan->name }}</h3>
            <p class="mt-1 text-sm text-slate-500">{{ $plan->description }}</p>

            <div class="mt-6 rounded-xl bg-slate-50 p-5">
                <div class="flex items-center justify-between">
                    <span class="text-slate-600">{{ $plan->name }} plan</span>
                    <span class="font-semibold text-slate-800">{{ $plan->priceLabel() }}{{ $plan->intervalLabel() }}</span>
                </div>
                <div class="mt-3 flex items-center justify-between border-t border-slate-200 pt-3">
                    <span class="font-semibold text-slate-800">Total due today</span>
                    <span class="text-xl font-extrabold text-slate-900">{{ $plan->priceLabel() }}</span>
                </div>
            </div>

            <ul class="mt-6 space-y-2 text-sm text-slate-600">
                @foreach (($plan->features ?? []) as $feature)
                    <li class="flex items-start gap-2">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ $feature }}</span>
                    </li>
                @endforeach
            </ul>

            @if ($razorpayOrder && $razorpayKeyId)
                <div class="mt-8">
                    <button id="rzp-pay-btn"
                            class="admin-btn-primary w-full py-3">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Pay {{ $plan->priceLabel() }} with Razorpay
                    </button>
                </div>

                <form id="razorpay-form" method="POST" action="{{ route('plans.verifyPayment', $plan) }}" class="hidden">
                    @csrf
                    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                    <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
                    <input type="hidden" name="razorpay_signature" id="razorpay_signature">
                </form>

                <div id="payment-processing" class="mt-8 hidden">
                    <div class="flex flex-col items-center gap-3 py-4">
                        <svg class="h-8 w-8 animate-spin text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <p class="text-sm font-medium text-slate-600">Verifying payment...</p>
                    </div>
                </div>

                <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                <script>
                    document.getElementById('rzp-pay-btn').addEventListener('click', function () {
                        var options = {
                            key: '{{ $razorpayKeyId }}',
                            amount: {{ $plan->price * 100 }},
                            currency: '{{ config('razorpay.currency', 'INR') }}',
                            name: '{{ config('app.name', 'AI Resume Builder') }}',
                            description: '{{ $plan->name }} Plan Subscription',
                            order_id: '{{ $razorpayOrder->id }}',
                            prefill: {
                                name: '{{ $userName }}',
                                email: '{{ $userEmail }}'
                            },
                            theme: {
                                color: '#4f46e5'
                            },
                            handler: function (response) {
                                document.getElementById('rzp-pay-btn').classList.add('hidden');
                                document.getElementById('payment-processing').classList.remove('hidden');

                                document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                                document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                                document.getElementById('razorpay_signature').value = response.razorpay_signature;
                                document.getElementById('razorpay-form').submit();
                            },
                            modal: {
                                ondismiss: function () {}
                            }
                        };

                        var rzp = new Razorpay(options);
                        rzp.on('payment.failed', function (response) {
                            alert('Payment failed: ' + response.error.description);
                        });
                        rzp.open();
                    });
                </script>
            @else
                <div class="mt-8 rounded-xl border border-amber-200 bg-amber-50 p-4">
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-amber-800">Payment gateway not configured</p>
                            <p class="mt-1 text-xs text-amber-700">
                                Add your Razorpay API keys in the <code class="rounded bg-amber-100 px-1">.env</code> file:
                                <code class="mt-1 block rounded bg-amber-100 px-2 py-1 text-[11px]">RAZORPAY_KEY_ID=rzp_test_your_key<br>RAZORPAY_KEY_SECRET=your_secret</code>
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <a href="{{ route('plans.index') }}" class="mt-4 block text-center text-sm font-semibold text-indigo-600 hover:text-indigo-500">&larr; Back to plans</a>
        </div>
    </div>
</x-app-layout>
