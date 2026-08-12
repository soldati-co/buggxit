<button
    {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-6 py-3 border border-line rounded-lg font-semibold text-sm text-bone-dim hover:bg-ink-raised2 hover:text-bone focus:outline-none focus:ring-2 focus:ring-gold/30 disabled:opacity-25 transition-all duration-200']) }}>
    {{ $slot }}
</button>
