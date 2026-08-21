@props(['variant' => 'footer'])

@php
    $platformIcons = [
        'instagram' => 'fa-instagram',
        'facebook' => 'fa-facebook',
        'twitter' => 'fa-twitter',
        'tiktok' => 'fa-tiktok',
    ];

    $settingsTableExists = \Illuminate\Support\Facades\Schema::hasTable('settings');

    $links = [];
    if ($settingsTableExists) {
        foreach ($platformIcons as $platform => $icon) {
            $url = \App\Models\Setting::get("social_{$platform}_url");
            $enabled = \App\Models\Setting::get("social_{$platform}_enabled", '0') === '1';

            if ($enabled && $url) {
                $links[$platform] = ['icon' => $icon, 'url' => $url];
            }
        }
    }

    $linkClass = $variant === 'contact'
        ? 'w-12 h-12 bg-ink-raised/50 border border-line rounded-full flex items-center justify-center hover:border-gold hover:bg-gold/10 transition-all duration-300 group'
        : 'p-2.5 bg-ink-raised2/50 rounded-full text-bone-dim hover:text-gold hover:bg-ink-raised2/80 transition-all duration-300 group';

    $iconClass = $variant === 'contact'
        ? 'fab %s text-bone-dim group-hover:text-gold text-lg'
        : 'fab %s text-lg group-hover:scale-110';

    $wrapperClass = $variant === 'contact' ? 'flex gap-4' : 'flex flex-wrap gap-3';
@endphp

@if (count($links))
    <div {{ $attributes->merge(['class' => $wrapperClass]) }}>
        @foreach ($links as $platform => $link)
            <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ ucfirst($platform) }}"
                class="{{ $linkClass }}">
                <i class="{{ sprintf($iconClass, $link['icon']) }}"></i>
            </a>
        @endforeach
    </div>
@endif
