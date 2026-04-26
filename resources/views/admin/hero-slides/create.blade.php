{{-- resources/views/admin/hero-slides/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Add Hero Slide - BUGGXIT Admin')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-white">Add New Hero Slide</h2>
        <a href="{{ route('admin.hero-slides.index') }}" 
           class="text-sm text-gray-400 hover:text-yellow-500 transition">
            &larr; Back to slides
        </a>
    </div>

    <div class="bg-black/90 backdrop-blur-sm border border-gray-800 rounded-xl p-6 md:p-8">
        <form action="{{ route('admin.hero-slides.store') }}" method="POST" enctype="multipart/form-data">
            @include('admin.hero-slides._form')
        </form>
    </div>
</div>
@endsection