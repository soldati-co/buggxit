@extends('layouts.admin')

@section('title', 'Edit Hero Slide')

@section('content')
<div class="max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold text-white mb-6">Edit Hero Slide</h2>
    <form action="{{ route('admin.hero-slides.update', $heroSlide) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.hero-slides._form')
    </form>
</div>
@endsection