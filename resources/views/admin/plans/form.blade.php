<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $plan->exists ? 'Edit Plan: '.$plan->name : 'New Plan' }}</h2>
            <a href="{{ route('admin.plans.index') }}" class="text-sm text-indigo-600 hover:underline">&larr; Back to plans</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                @if ($errors->any())
                    <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ $plan->exists ? route('admin.plans.update', $plan) : route('admin.plans.store') }}" class="space-y-5">
                    @csrf
                    @if ($plan->exists) @method('PUT') @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input name="name" value="{{ old('name', $plan->name) }}" required
                                   class="mt-1 w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Slug <span class="text-gray-400">(auto if blank)</span></label>
                            <input name="slug" value="{{ old('slug', $plan->slug) }}"
                                   class="mt-1 w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <input name="description" value="{{ old('description', $plan->description) }}"
                               class="mt-1 w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Price</label>
                            <input type="number" name="price" min="0" value="{{ old('price', $plan->price ?? 0) }}" required
                                   class="mt-1 w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Currency</label>
                            <input name="currency" value="{{ old('currency', $plan->currency ?? 'INR') }}" required
                                   class="mt-1 w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Interval</label>
                            <select name="interval" class="mt-1 w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach (\App\Models\Plan::INTERVALS as $value => $label)
                                    <option value="{{ $value }}" @selected(old('interval', $plan->interval) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Period days <span class="text-gray-400">(opt)</span></label>
                            <input type="number" name="period_days" min="1" value="{{ old('period_days', $plan->period_days) }}"
                                   class="mt-1 w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Resume limit</label>
                            <input type="number" name="resume_limit" min="0" value="{{ old('resume_limit', $plan->resume_limit) }}" placeholder="∞"
                                   class="mt-1 w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-400">Blank = unlimited</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Download limit</label>
                            <input type="number" name="download_limit" min="0" value="{{ old('download_limit', $plan->download_limit) }}" placeholder="∞"
                                   class="mt-1 w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-400">Blank = unlimited</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Edit limit</label>
                            <input type="number" name="edit_limit" min="0" value="{{ old('edit_limit', $plan->edit_limit) }}" placeholder="∞"
                                   class="mt-1 w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-400">Blank = unlimited</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sort order</label>
                            <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $plan->sort_order ?? 0) }}"
                                   class="mt-1 w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Features <span class="text-gray-400">(one per line)</span></label>
                        <textarea name="features_raw" rows="5"
                                  class="mt-1 w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('features_raw', implode("\n", $plan->features ?? [])) }}</textarea>
                    </div>

                    <div class="flex flex-wrap gap-6">
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $plan->is_active ?? true)) class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Active (visible)
                        </label>
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $plan->is_featured)) class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Featured (Most Popular)
                        </label>
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="is_default" value="1" @checked(old('is_default', $plan->is_default)) class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Default (Free fallback)
                        </label>
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="watermark" value="1" @checked(old('watermark', $plan->watermark)) class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Watermark PDF
                        </label>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button class="rounded-md bg-indigo-600 px-5 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                            {{ $plan->exists ? 'Save Plan' : 'Create Plan' }}
                        </button>
                        <a href="{{ route('admin.plans.index') }}" class="text-sm text-gray-500 hover:underline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
