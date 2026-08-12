<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 border border-transparent rounded-lg font-semibold text-sm text-gray-900 hover:from-yellow-400 hover:to-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-500/50 focus:ring-offset-2 focus:ring-offset-black disabled:opacity-50 transition-all duration-300']) }}>
    {{ $slot }}
</button>
