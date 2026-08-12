@props(['href' => '#'])

@php
    $classes =
        'block w-full px-4 py-2 text-left text-sm leading-5 text-bone-dim hover:bg-ink-raised2 hover:text-gold focus:outline-none focus:bg-ink-raised2 focus:text-gold transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['href' => $href, 'class' => $classes]) }}>
    {{ $slot }}
</a>
