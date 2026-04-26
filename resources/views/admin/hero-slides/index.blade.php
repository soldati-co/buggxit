@extends('layouts.admin')

@section('title', 'Hero Carousel - BUGGXIT Admin')

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-white">Hero Carousel Slides</h2>
        <a href="{{ route('admin.hero-slides.create') }}" 
           class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-yellow-500 to-yellow-600 text-gray-900 font-semibold rounded-lg hover:from-yellow-400 hover:to-yellow-500 transition-all">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 4v16m8-8H4"/>
            </svg>
            Add New Slide
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-500/20 border border-green-500/50 text-green-400 p-4 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($slides->isEmpty())
        <div class="bg-black/90 border border-gray-800 rounded-xl p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-700 mb-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-gray-400 text-lg mb-4">No hero slides yet.</p>
            <a href="{{ route('admin.hero-slides.create') }}" class="text-yellow-500 hover:text-yellow-400 font-medium">Upload your first slide →</a>
        </div>
    @else
        <div class="bg-black/90 border border-gray-800 rounded-xl overflow-hidden">
            <div class="p-4 border-b border-gray-800 text-sm text-gray-400 flex">
                <span class="w-16">Order</span>
                <span class="flex-1">Slide</span>
                <span class="w-24 text-center">Active</span>
                <span class="w-32 text-right">Actions</span>
            </div>
            <div id="slides-sortable" class="divide-y divide-gray-800">
                @foreach($slides as $slide)
                <div class="flex items-center p-4 hover:bg-gray-800/30 transition" data-slide-id="{{ $slide->id }}">
                    <div class="w-16 text-gray-500 cursor-move handle">☰</div>
                    <div class="flex-1 flex items-center space-x-4">
                        <div class="w-24 h-16 rounded-lg overflow-hidden bg-gray-700 flex-shrink-0">
                            <img src="{{ $slide->image_url }}" alt="{{ $slide->alt_text }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="text-white font-medium">{{ $slide->title ?? 'Untitled' }}</h4>
                            <p class="text-xs text-gray-500">{{ $slide->alt_text }}</p>
                        </div>
                    </div>
                    <div class="w-24 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $slide->is_active ? 'bg-green-500/10 text-green-400 border border-green-500/30' : 'bg-gray-500/10 text-gray-400 border border-gray-500/30' }}">
                            {{ $slide->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="w-32 text-right space-x-2">
                        <a href="{{ route('admin.hero-slides.edit', $slide) }}" class="text-yellow-500 hover:text-yellow-400 text-sm">Edit</a>
                        <form action="{{ route('admin.hero-slides.destroy', $slide) }}" method="POST" class="inline" onsubmit="return confirm('Delete this slide?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 text-sm">Delete</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Simple drag reorder (can be replaced with SortableJS)
    const sortable = document.getElementById('slides-sortable');
    if (sortable) {
        new Sortable(sortable, {
            handle: '.handle',
            animation: 150,
            onEnd: function (evt) {
                let order = [];
                sortable.querySelectorAll('[data-slide-id]').forEach((el, index) => {
                    order.push({ id: el.dataset.slideId, sort_order: index });
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
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
@endpush
@endsection