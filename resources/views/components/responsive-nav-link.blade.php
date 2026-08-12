@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'block pl-3 pr-4 py-2 border-l-4 border-gold text-base font-medium text-gold bg-gold/10 focus:outline-none focus:text-gold-dim focus:bg-gold/20 focus:border-gold-dim transition duration-150 ease-in-out'
            : 'block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-bone-dim hover:text-gray-100 hover:bg-ink-raised2 hover:border-line focus:outline-none focus:text-gray-100 focus:bg-ink-raised2 focus:border-line transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
