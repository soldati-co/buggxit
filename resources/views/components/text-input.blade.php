@props(['disabled' => false])

<input @disabled($disabled)
    {{ $attributes->merge(['class' => 'bg-ink-raised2 border border-line rounded-lg px-4 py-3 text-bone placeholder-bone-faint focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold/30 transition-all duration-200']) }}>
