@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-gold text-sm font-medium leading-5 text-gold focus:outline-none focus:border-yellow-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-bone-dim hover:text-gold hover:border-gold-bright focus:outline-none focus:text-gold focus:border-gold-bright transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
