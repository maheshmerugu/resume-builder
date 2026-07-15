<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Checkout</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm p-8">
                <h3 class="text-lg font-bold text-gray-800">You're subscribing to {{ $plan->name }}</h3>
                <p class="text-sm text-gray-500 mt-1">{{ $plan->description }}</p>

                <div class="mt-6 rounded-lg bg-gray-50 p-5">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">{{ $plan->name }} plan</span>
                        <span class="font-semibold text-gray-800">{{ $plan->priceLabel() }}{{ $plan->intervalLabel() }}</span>
                    </div>
                    <div class="mt-3 border-t pt-3 flex items-center justify-between">
                        <span class="font-semibold text-gray-800">Total due today</span>
                        <span class="text-xl font-extrabold text-gray-900">{{ $plan->priceLabel() }}</span>
                    </div>
                </div>

                <ul class="mt-6 space-y-2 text-sm text-gray-600">
                    @foreach (($plan->features ?? []) as $feature)
                        <li class="flex items-start gap-2">
                            <svg class="mt-0.5 h-4 w-4 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ $feature }}</span>
                        </li>
                    @endforeach
                </ul>

                <form method="POST" action="{{ route('plans.subscribe', $plan) }}" class="mt-8">
                    @csrf
                    <button class="w-full rounded-md bg-indigo-600 px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-500">
                        Pay {{ $plan->priceLabel() }} & Activate
                    </button>
                </form>

                <p class="mt-3 text-center text-xs text-gray-400">
                    Demo checkout — payment is simulated. Connect a gateway (e.g. Razorpay) in <code>PlanController@subscribe</code>.
                </p>

                <a href="{{ route('plans.index') }}" class="mt-4 block text-center text-sm text-indigo-600 hover:underline">&larr; Back to plans</a>
            </div>
        </div>
    </div>
</x-app-layout>
