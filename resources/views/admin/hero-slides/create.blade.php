@extends('layouts.admin')

@section('title', 'Add Hero Slide - BUGGXIT Admin')
@section('page-title', 'Add New Hero Slide')
@section('page-description', 'Add a new slide to the homepage hero carousel')

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
            <form action="{{ route('admin.hero-slides.store') }}" method="POST" enctype="multipart/form-data">
                @include('admin.hero-slides._form', ['slide' => new \App\Models\HeroSlide()])
            </form>
        </div>
    </div>
@endsection