<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-slate-900 sm:text-2xl">Plans & Pricing</h1>
            <p class="text-sm text-slate-500">Choose the plan that fits your job search</p>
        </div>
    </x-slot>

    <div class="space-y-8">
        @include('partials.alert')

        @if ($currentPlan)
            <div class="admin-card flex flex-wrap items-center justify-between gap-4 p-6">
                <div>
                    <p class="text-sm text-slate-500">Your current plan</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $currentPlan->name }}
                        <span class="text-base font-normal text-slate-500">{{ $currentPlan->priceLabel() }}{{ $currentPlan->intervalLabel() }}</span>
                    </p>
                    <p class="mt-1 text-sm text-slate-500">
                        @php $rr = auth()->user()->remainingResumes(); $rd = auth()->user()->remainingDownloads(); @endphp
                        Resumes left: <span class="font-semibold text-slate-700">{{ is_null($rr) ? 'Unlimited' : $rr }}</span>
                        · Downloads left: <span class="font-semibold text-slate-700">{{ is_null($rd) ? 'Unlimited' : $rd }}</span>
                        @if ($subscription && $subscription->ends_at)
                            · Renews/expires {{ $subscription->ends_at->format('M j, Y') }}
                        @endif
                    </p>
                </div>
                @if ($subscription)
                    <form method="POST" action="{{ route('plans.cancel') }}" onsubmit="return confirm('Cancel your subscription? You will lose access to premium features.')">
                        @csrf
                        <button class="admin-btn-secondary">Cancel subscription</button>
                    </form>
                @endif
            </div>
        @else
            <div class="admin-card flex flex-wrap items-center justify-between gap-4 border-amber-200 bg-amber-50 p-6">
                <div>
                    <p class="text-sm font-semibold text-amber-800">No active subscription</p>
                    <p class="mt-1 text-sm text-amber-700">Choose a plan below to start building resumes, run ATS checks, and download PDFs.</p>
                </div>
                <a href="#plans" class="admin-btn-primary">View plans</a>
            </div>
        @endif

        <div id="plans" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($plans as $plan)
                @php $isCurrent = $currentPlan && $currentPlan->id === $plan->id; @endphp
                <div class="admin-card relative flex flex-col p-6 {{ $plan->is_featured ? 'ring-2 ring-indigo-200' : '' }}">
                    @if ($plan->is_featured)
                        <span class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full bg-indigo-600 px-3 py-1 text-xs font-semibold text-white">Most Popular</span>
                    @endif

                    <h3 class="text-lg font-bold text-slate-900">{{ $plan->name }}</h3>
                    <p class="mt-1 min-h-[2.5rem] text-sm text-slate-500">{{ $plan->description }}</p>

                    <div class="mt-4">
                        <span class="text-3xl font-extrabold text-slate-900">{{ $plan->priceLabel() }}</span>
                        <span class="text-sm text-slate-500">{{ $plan->intervalLabel() }}</span>
                    </div>

                    <ul class="mt-5 flex-1 space-y-2 text-sm text-slate-600">
                        @foreach (($plan->features ?? []) as $feature)
                            <li class="flex items-start gap-2">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-6">
                        @if ($isCurrent)
                            <button disabled class="w-full cursor-default rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-500">Current plan</button>
                        @else
                            <a href="{{ route('plans.checkout', $plan) }}"
                               class="block w-full rounded-xl px-4 py-2.5 text-center text-sm font-semibold text-white {{ $plan->is_featured ? 'bg-indigo-600 hover:bg-indigo-500' : 'bg-slate-800 hover:bg-slate-700' }}">
                                Choose {{ $plan->name }}
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
