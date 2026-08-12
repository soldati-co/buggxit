<button
    {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-6 py-3 border border-gray-700 rounded-lg font-semibold text-sm text-gray-300 hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-yellow-500/30 disabled:opacity-25 transition-all duration-200']) }}>
    {{ $slot }}
</button>
