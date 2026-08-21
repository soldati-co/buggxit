<button
    id="back-to-top-btn"
    type="button"
    onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
    aria-label="Back to top"
    class="fixed bottom-[calc(1.5rem+env(safe-area-inset-bottom))] right-[calc(1.5rem+env(safe-area-inset-right))] md:bottom-[calc(2rem+env(safe-area-inset-bottom))] md:right-[calc(2rem+env(safe-area-inset-right))] p-3 bg-ink-raised/80 backdrop-blur-sm border border-line rounded-full text-bone-dim hover:text-gold hover:border-gold transition-all duration-300 z-40 shadow-lg group opacity-0 translate-y-2 pointer-events-none">
    <i class="fas fa-arrow-up text-lg group-hover:-translate-y-1 transition-transform duration-300"></i>
</button>

@push('scripts')
    <script>
        (function () {
            const btn = document.getElementById('back-to-top-btn');
            if (!btn) return;

            const footer = document.querySelector('footer');
            let footerVisible = false;

            const update = () => {
                const shouldShow = window.scrollY > 400 && !footerVisible;
                btn.classList.toggle('opacity-0', !shouldShow);
                btn.classList.toggle('translate-y-2', !shouldShow);
                btn.classList.toggle('pointer-events-none', !shouldShow);
            };

            // Hide the button once the footer scrolls into view so it never
            // sits on top of the footer's own text/links at any screen size.
            if (footer && 'IntersectionObserver' in window) {
                new IntersectionObserver((entries) => {
                    footerVisible = entries[0].isIntersecting;
                    update();
                }).observe(footer);
            }

            window.addEventListener('scroll', update, { passive: true });
            update();
        })();
    </script>
@endpush
