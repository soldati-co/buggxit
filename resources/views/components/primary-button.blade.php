<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-gradient-to-r from-gold to-gold-dim border border-transparent rounded-lg font-semibold text-sm text-ink hover:from-gold-bright hover:to-gold focus:outline-none focus:ring-2 focus:ring-gold/50 focus:ring-offset-2 focus:ring-offset-ink disabled:opacity-50 transition-all duration-300']) }}>
    {{ $slot }}
</button>
