@php
    $settingsTableExists = \Illuminate\Support\Facades\Schema::hasTable('settings');
    $whatsappEnabled = $settingsTableExists && \App\Models\Setting::get('whatsapp_enabled', '0') === '1';
    $whatsappNumber = $whatsappEnabled ? \App\Models\Setting::get('whatsapp_number') : null;
    $whatsappPosition = $settingsTableExists ? \App\Models\Setting::get('whatsapp_position', 'right') : 'right';
@endphp

@if($whatsappNumber)
    @php
        $digitsOnly = preg_replace('/\D/', '', $whatsappNumber);
        $isLeft = $whatsappPosition === 'left';
    @endphp
    <a href="https://wa.me/{{ $digitsOnly }}" target="_blank" rel="noopener noreferrer"
        aria-label="Order on WhatsApp" title="Order on WhatsApp"
        class="fixed z-40 {{ $isLeft ? 'left-6 bottom-6 md:left-8 md:bottom-8' : 'right-6 bottom-24 md:right-8 md:bottom-28' }}
               group flex items-center justify-center w-14 h-14 rounded-full bg-[#25D366] text-white shadow-lg
               hover:scale-110 transition-transform duration-300">
        <span class="absolute inset-0 rounded-full bg-[#25D366] animate-ping opacity-40"></span>
        <i class="fab fa-whatsapp text-3xl relative"></i>
        <span
            class="pointer-events-none absolute {{ $isLeft ? 'left-full ml-3' : 'right-full mr-3' }} top-1/2 -translate-y-1/2 whitespace-nowrap px-3 py-1.5 rounded-lg bg-ink text-bone text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-200">
            Order on WhatsApp
        </span>
    </a>
@endif
