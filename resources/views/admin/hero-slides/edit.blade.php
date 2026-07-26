@extends('layouts.admin')

@section('title', 'Edit Hero Slide')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('admin.hero-slides.index') }}"
                class="text-gray-400 hover:text-yellow-500 transition-colors inline-flex items-center text-sm group">
                <svg class="w-4 h-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Hero Slides
            </a>
        </div>

        <h2 class="text-2xl font-bold text-white mb-6">Edit Hero Slide</h2>

        <div class="bg-black/90 backdrop-blur-sm border border-gray-800 rounded-xl p-6 md:p-8">
            <form action="{{ route('admin.hero-slides.update', $heroSlide) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @include('admin.hero-slides._form', ['slide' => $heroSlide])
            </form>
        </div>
    </div>
@endsection