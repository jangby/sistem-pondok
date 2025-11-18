@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 py-2 rounded-md text-sm font-medium leading-5 text-white bg-emerald-800 shadow-inner focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center px-3 py-2 rounded-md text-sm font-medium leading-5 text-emerald-100 hover:text-white hover:bg-emerald-500 focus:outline-none focus:text-white focus:bg-emerald-500 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>