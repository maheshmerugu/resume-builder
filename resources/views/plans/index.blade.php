<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Plans & Pricing</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if (session('status'))
                <div class="rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Current plan summary --}}
            @if ($currentPlan)
                <div class="bg-white rounded-xl shadow-sm p-6 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Your current plan</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $currentPlan->name }}
                            <span class="text-base font-normal text-gray-500">{{ $currentPlan->priceLabel() }}{{ $currentPlan->isFree() ? '' : $currentPlan->intervalLabel() }}</span>
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            @php $rr = auth()->user()->remainingResumes(); $rd = auth()->user()->remainingDownloads(); @endphp
                            Resumes left: <span class="font-semibold text-gray-700">{{ is_null($rr) ? 'Unlimited' : $rr }}</span>
                            &middot; Downloads left: <span class="font-semibold text-gray-700">{{ is_null($rd) ? 'Unlimited' : $rd }}</span>
                            @if ($subscription && $subscription->ends_at)
                                &middot; Renews/expires {{ $subscription->ends_at->format('M j, Y') }}
                            @endif
                        </p>
                    </div>
                    @if ($subscription)
                        <form method="POST" action="{{ route('plans.cancel') }}" onsubmit="return confirm('Cancel your subscription and move to Free?')">
                            @csrf
                            <button class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Cancel subscription</button>
                        </form>
                    @endif
                </div>
            @endif

            {{-- Plan cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($plans as $plan)
                    @php $isCurrent = $currentPlan && $currentPlan->id === $plan->id; @endphp
                    <div class="relative bg-white rounded-2xl shadow-sm border {{ $plan->is_featured ? 'border-indigo-500 ring-2 ring-indigo-200' : 'border-gray-100' }} p-6 flex flex-col">
                        @if ($plan->is_featured)
                            <span class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full bg-indigo-600 px-3 py-1 text-xs font-semibold text-white">Most Popular</span>
                        @endif

                        <h3 class="text-lg font-bold text-gray-800">{{ $plan->name }}</h3>
                        <p class="mt-1 text-sm text-gray-500 min-h-[2.5rem]">{{ $plan->description }}</p>

                        <div class="mt-4">
                            <span class="text-3xl font-extrabold text-gray-900">{{ $plan->priceLabel() }}</span>
                            @unless ($plan->isFree())
                                <span class="text-sm text-gray-500">{{ $plan->intervalLabel() }}</span>
                            @endunless
                        </div>

                        <ul class="mt-5 space-y-2 text-sm text-gray-600 flex-1">
                            @foreach (($plan->features ?? []) as $feature)
                                <li class="flex items-start gap-2">
                                    <svg class="mt-0.5 h-4 w-4 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="mt-6">
                            @if ($isCurrent)
                                <button disabled class="w-full rounded-md bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-500 cursor-default">Current plan</button>
                            @elseif ($plan->isFree())
                                <form method="GET" action="{{ route('plans.checkout', $plan) }}">
                                    <button class="w-full rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Switch to Free</button>
                                </form>
                            @else
                                <a href="{{ route('plans.checkout', $plan) }}"
                                   class="block w-full text-center rounded-md {{ $plan->is_featured ? 'bg-indigo-600 hover:bg-indigo-500' : 'bg-gray-800 hover:bg-gray-700' }} px-4 py-2 text-sm font-semibold text-white">
                                    Choose {{ $plan->name }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
