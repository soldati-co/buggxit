@extends('layouts.admin')

@section('title', 'Edit Hero Slide')
@section('page-title', 'Edit Hero Slide')
@section('page-description', 'Update this hero carousel slide')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('admin.hero-slides.index') }}"
                class="text-bone-dim hover:text-gold transition-colors inline-flex items-center text-sm group">
                <svg class="w-4 h-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Hero Slides
            </a>
        </div>

        <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6">
            <form action="{{ route('admin.hero-slides.update', $heroSlide) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @include('admin.hero-slides._form', ['slide' => $heroSlide])
            </form>
        </div>
    </div>
@endsection