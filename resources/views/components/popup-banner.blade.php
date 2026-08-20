@php
    $settingsTableExists = \Illuminate\Support\Facades\Schema::hasTable('settings');
    $popupBannerEnabled = $settingsTableExists && \App\Models\Setting::get('popup_banner_enabled', '0') === '1';
    $popupBannerText = $popupBannerEnabled ? \App\Models\Setting::get('popup_banner_text') : null;
@endphp

@if($popupBannerText)
    @php $bannerKey = 'popup-banner-dismissed-' . substr(md5($popupBannerText), 0, 8); @endphp
    <div id="popup-banner" data-banner-key="{{ $bannerKey }}"
        class="hidden relative z-30 bg-gold text-ink">
        <div class="container-wide px-4 sm:px-6 lg:px-8 mx-auto py-2.5 flex items-center justify-center gap-4 text-center">
            <p class="text-sm font-medium">
                <i class="fas fa-store mr-1.5"></i>{{ $popupBannerText }}
            </p>
            <a href="https://www.instagram.com/buggxit_couture/" target="_blank" rel="noopener noreferrer"
                class="hidden sm:inline-flex items-center flex-shrink-0 px-3 py-1 text-xs font-semibold bg-ink text-gold rounded-full hover:bg-ink-raised2 transition-colors">
                Follow on Instagram
            </a>
            <button type="button" id="popup-banner-dismiss" aria-label="Dismiss"
                class="flex-shrink-0 text-ink/70 hover:text-ink transition-colors">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                const banner = document.getElementById('popup-banner');
                if (!banner) return;
                const key = banner.dataset.bannerKey;

                if (!localStorage.getItem(key)) {
                    banner.classList.remove('hidden');
                }

                document.getElementById('popup-banner-dismiss')?.addEventListener('click', () => {
                    localStorage.setItem(key, '1');
                    banner.classList.add('hidden');
                });
            })();
        </script>
    @endpush
@endif
