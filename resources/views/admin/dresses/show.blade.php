@extends('layouts.admin')

@section('title', $dress->name . ' - BUGGXIT Admin')
@section('page-title', 'Dress Details')
@section('page-description', 'View dress information')

@section('content')
    <div class="max-w-6xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('admin.dresses.index') }}"
                class="text-gold hover:text-gold-bright transition-colors flex items-center text-sm group">
                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Dresses
            </a>
        </div>

        <!-- Dress Header -->
        <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-bone">{{ $dress->name }}</h1>
                    <div class="flex flex-wrap items-center mt-3 gap-3">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                        @if ($dress->status == 'active') bg-good/10 text-good border border-good/30
                        @elseif($dress->status == 'draft') bg-gold/10 text-gold-bright border border-gold/30
                        @else bg-bad/10 text-bad border border-bad/30 @endif">
                            {{ ucfirst(str_replace('_', ' ', $dress->status)) }}
                        </span>
                        @if ($dress->is_featured)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gold/10 text-gold-bright border border-gold/30">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                                Featured
                            </span>
                        @endif
                        <span class="text-lg font-bold text-gold font-numeric">R{{ number_format($dress->price, 2) }}</span>
                    </div>
                </div>
                <div class="flex mt-4 md:mt-0 space-x-3">
                    <a href="{{ route('admin.dresses.edit', $dress) }}"
                        class="inline-flex items-center px-4 py-2.5 bg-ink-raised2/50 border border-line rounded-lg text-bone-dim hover:text-bone hover:bg-ink-raised2 hover:border-gold/50 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                    <form action="{{ route('admin.dresses.destroy', $dress) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this dress?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2.5 bg-ink-raised2/50 border border-line rounded-lg text-bad hover:text-bad hover:bg-bad/20 hover:border-bad/50 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            <!-- SKU and Category -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 bg-ink-raised2/30 border border-line rounded-lg">
                    <p class="text-xs text-bone-faint uppercase tracking-wider">Categories</p>
                    <p class="mt-1 text-sm font-medium text-bone">
                        {{ $dress->categories->pluck('name')->join(', ') ?: 'No category' }}
                    </p>
                </div>
                <div class="p-4 bg-ink-raised2/30 border border-line rounded-lg">
                    <p class="text-xs text-bone-faint uppercase tracking-wider">SKU</p>
                    <p class="mt-1 text-sm font-medium text-bone">{{ $dress->display_sku }}</p>
                </div>
                <div class="p-4 bg-ink-raised2/30 border border-line rounded-lg">
                    <p class="text-xs text-bone-faint uppercase tracking-wider">Date Created</p>
                    <p class="mt-1 text-sm font-medium text-bone">{{ $dress->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Images -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Main Image -->
                <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl overflow-hidden">
                    @if ($dress->main_image_url)
                        <img src="{{ $dress->main_image_url }}?t={{ time() }}" alt="{{ $dress->name }}" class="w-full h-96 object-contain">
                    @else
                        <div class="w-full h-96 bg-ink-raised2 flex items-center justify-center">
                            <svg class="w-24 h-24 text-bone-faint" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2z" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Gallery Images -->
                @if (count($dress->gallery_image_urls) > 0)
                    <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-bone mb-4 flex items-center">
                            <svg class="w-5 h-5 text-gold mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4 5h16v14H4V5zm2 2v10h12V7H6z" />
                            </svg>
                            Gallery Images
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ($dress->gallery_image_urls as $image)
                                <div class="rounded-lg overflow-hidden border border-line bg-ink-raised2/50">
                                    <img src="{{ $image }}" alt="{{ $dress->name }}" class="w-full h-40 object-contain hover:scale-105 transition-transform duration-300">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column - Details -->
            <div class="space-y-6">
                <!-- Description -->
                <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-bone mb-4 flex items-center">
                        <svg class="w-5 h-5 text-gold mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 6h16v12H4V6zm2 2v8h12V8H6z" />
                        </svg>
                        Description
                    </h3>
                    <p class="text-bone-dim whitespace-pre-line text-sm leading-relaxed">{{ $dress->description }}</p>
                </div>

                <!-- Sizes & Colors -->
                <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-bone mb-4 flex items-center">
                        <svg class="w-5 h-5 text-gold mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7 11h10v2H7zM4 7h16v2H4zM6 15h12v2H6z" />
                        </svg>
                        Available Options
                    </h3>

                    <!-- Sizes -->
                    <div class="mb-5">
                        <p class="text-sm font-medium text-bone-dim mb-2">Sizes</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($dress->sizes ?? [] as $size)
                                <span
                                    class="px-3 py-1.5 bg-ink-raised2 text-bone-dim rounded-lg border border-line text-sm">
                                    {{ $size }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Colors -->
                    <div>
                        <p class="text-sm font-medium text-bone-dim mb-2">Colors</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($dress->display_colors as $color)
                                <span
                                    class="px-3 py-1.5 bg-ink-raised2 text-bone-dim rounded-lg border border-line text-sm flex items-center">
                                    <span class="w-3 h-3 rounded-full mr-2"
                                        style="background-color: {{ $color['hex'] ?? '#ffffff' }}"></span>
                                    {{ $color['name'] ?? 'Custom' }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Production & Delivery -->
                <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-bone mb-4 flex items-center">
                        <svg class="w-5 h-5 text-gold mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20 8h-2v2h2v6h-2v2h2a2 2 0 002-2v-6a2 2 0 00-2-2zM4 8h2v2H4v6h2v2H4a2 2 0 01-2-2v-6a2 2 0 012-2z" />
                        </svg>
                        Production & Delivery
                    </h3>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-2 border-b border-line/50">
                            <span class="text-sm text-bone-dim">Turnaround Time</span>
                            <span class="text-sm font-medium text-bone">{{ $dress->turnaround_time }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-2 border-b border-line/50">
                            <span class="text-sm text-bone-dim">Expected Delivery</span>
                            <span class="text-sm font-medium text-bone">{{ $dress->expected_delivery }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-bone-dim">Last Updated</span>
                            <span class="text-xs text-bone-faint">{{ $dress->updated_at->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection