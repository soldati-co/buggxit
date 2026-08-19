@extends('layouts.admin')

@section('title', 'Hero Carousel - BUGGXIT Admin')
@section('page-title', 'Hero Carousel Slides')
@section('page-description', 'Manage the homepage hero carousel')

@section('header-actions')
    <div class="mt-4 md:mt-0">
        <a href="{{ route('admin.hero-slides.create') }}"
            class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-gold to-gold-dim text-ink font-semibold rounded-lg hover:from-gold-bright hover:to-gold transition-all duration-300 text-sm shadow-lg shadow-gold/20">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 4v16m8-8H4" />
            </svg>
            Add New Slide
        </a>
    </div>
@endsection

@section('content')
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('admin.dashboard') }}"
            class="text-bone-dim hover:text-gold transition-colors inline-flex items-center text-sm group">
            <svg class="w-4 h-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Dashboard
        </a>
    </div>

    <div class="mb-8">
        @if ($slides->isEmpty())
            <div class="bg-ink-raised/90 border border-line rounded-xl p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-bone-faint mb-4" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-bone-dim text-lg mb-4">No hero slides yet.</p>
                <a href="{{ route('admin.hero-slides.create') }}"
                    class="text-gold hover:text-gold-bright font-medium">Upload your first slide →</a>
            </div>
        @else
            <div class="bg-ink-raised/90 border border-line rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <div class="min-w-[640px]">
                        <div class="px-6 py-4 border-b border-line text-xs font-medium text-bone-dim uppercase tracking-wider flex">
                            <span class="w-12">Order</span>
                            <span class="w-20">Image</span>
                            <span class="flex-1">Headline / Sub</span>
                            <span class="w-40">CTA</span>
                            <span class="w-24 text-center">Active</span>
                            <span class="w-32 text-right">Actions</span>
                        </div>
                        <div id="slides-sortable" class="divide-y divide-line">
                            @foreach ($slides as $slide)
                                <div class="flex items-center px-6 py-4 hover:bg-ink-raised2/30 transition" data-slide-id="{{ $slide->id }}">
                            <div class="w-12 text-bone-faint cursor-move handle">☰</div>
                            <div class="w-20 flex-shrink-0 mr-4">
                                <div class="w-16 h-12 rounded overflow-hidden bg-ink-raised2 flex items-center justify-center">
                                    @if ($slide->image_api_url)
                                        <img src="{{ $slide->image_api_url }}?t={{ time() }}"
                                             alt="{{ $slide->alt_text }}"
                                             class="w-full h-full object-cover"
                                             onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'text-xs text-bad\'>Broken</span>';">
                                    @else
                                        <span class="text-xs text-bone-dim">No image</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-bone font-medium truncate">{{ $slide->headline ?? $slide->title ?? 'No headline' }}</h4>
                                @if($slide->subheading)
                                    <p class="text-xs text-bone-dim truncate">{{ $slide->subheading }}</p>
                                @endif
                                <p class="text-xs text-bone-faint">{{ $slide->alt_text }}</p>
                            </div>
                            <div class="w-40">
                                @if($slide->cta_text)
                                    <span class="inline-block text-xs bg-gold/10 text-gold-bright px-2 py-0.5 rounded">
                                        {{ $slide->cta_text }}
                                    </span>
                                    @if($slide->cta_url)
                                        <p class="text-xs text-bone-faint truncate">{{ $slide->cta_url }}</p>
                                    @endif
                                @else
                                    <span class="text-xs text-bone-faint">—</span>
                                @endif
                            </div>
                            <div class="w-24 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $slide->is_active ? 'bg-good/10 text-good border border-good/30' : 'bg-bone-faint/10 text-bone-dim border border-bone-faint/30' }}">
                                    {{ $slide->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="w-32 text-right space-x-2">
                                <a href="{{ route('admin.hero-slides.edit', $slide) }}"
                                    class="p-1.5 text-bone-dim hover:text-gold hover:bg-ink-raised2/50 rounded-lg transition-all duration-200 inline-block" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.hero-slides.destroy', $slide) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this slide?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-bone-dim hover:text-bad hover:bg-bad/10 rounded-lg transition-all duration-200" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const sortable = document.getElementById('slides-sortable');
                if (!sortable || typeof Sortable === 'undefined') {
                    return;
                }

                new Sortable(sortable, {
                    handle: '.handle',
                    animation: 150,
                    onEnd: function () {
                        let order = [];
                        sortable.querySelectorAll('[data-slide-id]').forEach((el, index) => {
                            order.push({
                                id: el.dataset.slideId,
                                sort_order: index,
                            });
                        });

                        fetch('{{ route('admin.hero-slides.update-order') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({ order: order }),
                        }).then((r) => r.json()).then(() => console.log('Reordered'));
                    },
                });
            });
        </script>
    @endpush
@endsection