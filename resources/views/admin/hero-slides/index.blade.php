@extends('layouts.admin')

@section('title', 'Hero Carousel - BUGGXIT Admin')

@section('content')
    <div class="mb-8">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('admin.dashboard') }}"
                class="text-gray-400 hover:text-yellow-500 transition-colors inline-flex items-center text-sm group">
                <svg class="w-4 h-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Dashboard
            </a>
        </div>

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-white">Hero Carousel Slides</h2>
            <a href="{{ route('admin.hero-slides.create') }}"
                class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-yellow-500 to-yellow-600 text-gray-900 font-semibold rounded-lg hover:from-yellow-400 hover:to-yellow-500 transition-all">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4" />
                </svg>
                Add New Slide
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-500/20 border border-green-500/50 text-green-400 p-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if ($slides->isEmpty())
            <div class="bg-black/90 border border-gray-800 rounded-xl p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-700 mb-4" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-gray-400 text-lg mb-4">No hero slides yet.</p>
                <a href="{{ route('admin.hero-slides.create') }}"
                    class="text-yellow-500 hover:text-yellow-400 font-medium">Upload your first slide →</a>
            </div>
        @else
            <div class="bg-black/90 border border-gray-800 rounded-xl overflow-hidden">
                <div class="p-4 border-b border-gray-800 text-sm text-gray-400 flex">
                    <span class="w-12">Order</span>
                    <span class="w-20">Image</span>
                    <span class="flex-1">Headline / Sub</span>
                    <span class="w-40">CTA</span>
                    <span class="w-24 text-center">Active</span>
                    <span class="w-32 text-right">Actions</span>
                </div>
                <div id="slides-sortable" class="divide-y divide-gray-800">
                    @foreach ($slides as $slide)
                        <div class="flex items-center p-4 hover:bg-gray-800/30 transition" data-slide-id="{{ $slide->id }}">
                            <div class="w-12 text-gray-500 cursor-move handle">☰</div>
                            <div class="w-20 flex-shrink-0 mr-4">
                                <div class="w-16 h-12 rounded overflow-hidden bg-gray-700 flex items-center justify-center">
                                    @if ($slide->image_path)
                                        <img src="{{ $slide->image_api_url }}?t={{ time() }}"
                                             alt="{{ $slide->alt_text }}"
                                             class="w-full h-full object-cover"
                                             onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'text-xs text-red-400\'>Broken</span>';">
                                    @else
                                        <span class="text-xs text-gray-400">No image</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-white font-medium truncate">{{ $slide->headline ?? $slide->title ?? 'No headline' }}</h4>
                                @if($slide->subheading)
                                    <p class="text-xs text-gray-400 truncate">{{ $slide->subheading }}</p>
                                @endif
                                <p class="text-xs text-gray-500">{{ $slide->alt_text }}</p>
                            </div>
                            <div class="w-40">
                                @if($slide->cta_text)
                                    <span class="inline-block text-xs bg-yellow-500/10 text-yellow-400 px-2 py-0.5 rounded">
                                        {{ $slide->cta_text }}
                                    </span>
                                    @if($slide->cta_url)
                                        <p class="text-xs text-gray-500 truncate">{{ $slide->cta_url }}</p>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-600">—</span>
                                @endif
                            </div>
                            <div class="w-24 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $slide->is_active ? 'bg-green-500/10 text-green-400 border border-green-500/30' : 'bg-gray-500/10 text-gray-400 border border-gray-500/30' }}">
                                    {{ $slide->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="w-32 text-right space-x-2">
                                <a href="{{ route('admin.hero-slides.edit', $slide) }}"
                                    class="p-1.5 text-gray-400 hover:text-yellow-500 hover:bg-gray-800/50 rounded-lg transition-all duration-200 inline-block" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.hero-slides.destroy', $slide) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this slide?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-all duration-200" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script>
            const sortable = document.getElementById('slides-sortable');
            if (sortable) {
                new Sortable(sortable, {
                    handle: '.handle',
                    animation: 150,
                    onEnd: function(evt) {
                        let order = [];
                        sortable.querySelectorAll('[data-slide-id]').forEach((el, index) => {
                            order.push({
                                id: el.dataset.slideId,
                                sort_order: index
                            });
                        });
                        fetch('{{ route('admin.hero-slides.update-order') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ order: order })
                        }).then(r => r.json()).then(data => console.log('Reordered'));
                    }
                });
            }
        </script>
    @endpush
@endsection