@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-semibold bg-indigo-50 text-indigo-700 transition'
            : 'inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
