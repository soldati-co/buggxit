@php
    $instagramFeedEnabled = \Illuminate\Support\Facades\Schema::hasTable('settings')
        ? \App\Models\Setting::get('instagram_feed_enabled', '1')
        : '1';
    $instagramWidgetId = \Illuminate\Support\Facades\Schema::hasTable('settings')
        ? \App\Models\Setting::get('instagram_widget_id')
        : null;
@endphp

@if($instagramFeedEnabled === '1')
    <section class="container-wide px-4 sm:px-6 lg:px-8 mx-auto mb-20 text-center">
        <p class="text-gold text-sm font-semibold uppercase tracking-wider">Follow Along</p>
        <h2 class="text-3xl md:text-4xl font-bold text-bone mt-2 mb-4">
            See how our customers wear <span class="text-gold">Buggxit</span>
        </h2>
        <p class="text-bone-dim text-lg mb-8 max-w-xl mx-auto">
            Tag us <a href="https://www.instagram.com/buggxit_couture/" target="_blank" rel="noopener noreferrer" class="text-gold hover:text-gold-bright transition-colors">@buggxit_couture</a>
        </p>

        @if($instagramWidgetId)
            <div class="max-w-4xl mx-auto rounded-2xl overflow-hidden border border-line">
                <div class="elfsight-app-{{ $instagramWidgetId }}" data-elfsight-app-lazy></div>
            </div>
            @once
                @push('scripts')
                    <script src="https://elfsightcdn.com/platform.js" async></script>
                @endpush
            @endonce
        @endif

        <a href="https://www.instagram.com/buggxit_couture/" target="_blank" rel="noopener noreferrer"
            class="inline-flex items-center mt-8 px-6 py-3 border border-line rounded-full text-bone hover:border-gold hover:text-gold hover:bg-gold/10 transition-all duration-300">
            <i class="fab fa-instagram mr-2"></i> Follow @buggxit_couture
        </a>
    </section>
@endif
