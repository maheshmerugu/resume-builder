@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full px-3 py-2.5 rounded-lg text-start text-sm font-semibold text-indigo-700 bg-indigo-50 transition'
            : 'block w-full px-3 py-2.5 rounded-lg text-start text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
