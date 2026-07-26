@php
    $slides = \App\Models\HeroSlide::where('is_active', true)
                ->orderBy('sort_order')
                ->get();
@endphp

@if($slides->count() > 0)
<div x-data="{
    activeSlide: 0,
    totalSlides: {{ $slides->count() }},
    autoplay: null,
    init() {
        this.startAutoplay();
    },
    startAutoplay() {
        this.autoplay = setInterval(() => {
            this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
        }, 5000);
    },
    stopAutoplay() {
        clearInterval(this.autoplay);
    },
    next() {
        this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
    },
    prev() {
        this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides;
    },
    goTo(index) {
        this.activeSlide = index;
    }
}" 
class="relative w-full h-[60vh] md:h-[85vh] min-h-[450px] overflow-hidden bg-gray-900"
@mouseenter="stopAutoplay()"
@mouseleave="startAutoplay()">

    {{-- Slides --}}
    @foreach($slides as $index => $slide)
    <div 
        x-show="activeSlide === {{ $index }}"
        x-transition:enter="transition ease-out duration-700"
        x-transition:enter-start="opacity-0 scale-105"
        x-transition:enter-end="opacity-100 scale-100"
        class="absolute inset-0 w-full h-full"
    >
        {{-- Background image using new API URL --}}
        <img src="{{ $slide->image_api_url }}?t={{ time() }}" 
             alt="{{ $slide->alt_text ?? $slide->headline ?? 'Hero slide' }}" 
             class="absolute inset-0 w-full h-full object-cover object-center"
             loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
             fetchpriority="{{ $index === 0 ? 'high' : 'auto' }}"
             onerror="this.style.display='none'; this.parentElement.querySelector('.bg-black\\/50')?.style?.display='block';">
        
        {{-- Dark overlay for readability --}}
        <div class="absolute inset-0 bg-black/50"></div>
        
        {{-- Text overlay --}}
        <div class="relative z-10 h-full flex flex-col justify-center items-start max-w-7xl mx-auto px-6 lg:px-16">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-4 max-w-2xl leading-tight">
                {{ $slide->headline ?? $slide->title }}
            </h1>
            @if($slide->subheading)
            <p class="text-lg md:text-xl text-gray-200 max-w-xl mb-8">
                {{ $slide->subheading }}
            </p>
            @endif
            @if($slide->cta_text && $slide->cta_url)
            <a href="{{ $slide->cta_url }}" 
               class="inline-block px-8 py-3 bg-yellow-500 text-gray-900 font-semibold rounded-lg hover:bg-yellow-400 transition">
                {{ $slide->cta_text }}
            </a>
            @endif
        </div>
    </div>
    @endforeach

    {{-- Navigation arrows --}}
    <button @click="prev()" 
            class="absolute left-4 top-1/2 -translate-y-1/2 z-20 p-3 rounded-full bg-black/40 text-white hover:bg-black/60 transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>
    <button @click="next()" 
            class="absolute right-4 top-1/2 -translate-y-1/2 z-20 p-3 rounded-full bg-black/40 text-white hover:bg-black/60 transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </button>

    {{-- Dot indicators --}}
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex space-x-2">
        @foreach($slides as $index => $slide)
        <button @click="goTo({{ $index }})" 
                :class="{ 'bg-yellow-500': activeSlide === {{ $index }}, 'bg-white/50': activeSlide !== {{ $index }} }"
                class="w-3 h-3 rounded-full transition-colors"></button>
        @endforeach
    </div>
</div>
@endif