@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 placeholder:text-gray-400']) }}>
