<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-bad border border-transparent rounded-lg font-semibold text-sm text-bone hover:bg-bad active:bg-bad focus:outline-none focus:ring-2 focus:ring-bad focus:ring-offset-2 focus:ring-offset-ink disabled:opacity-25 transition-all duration-200']) }}>
    {{ $slot }}
</button>
