@extends('layouts.admin')

@section('title', 'Edit Category: ' . $category->name . ' - BUGGXIT Admin')
@section('page-title', 'Edit Category')
@section('page-description', 'Update category details')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-bone-dim mb-2">Name <span
                                class="text-gold">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold/30 transition-all duration-200">
                        @error('name')
                            <p class="mt-2 text-sm text-bad">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-bone-dim mb-2">Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}"
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold/30 transition-all duration-200">
                        <p class="mt-1 text-xs text-bone-faint">Leave empty to auto-generate from name.</p>
                        @error('slug')
                            <p class="mt-2 text-sm text-bad">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-bone-dim mb-2">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold/30 transition-all duration-200">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-bad">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Parent Category -->
                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-bone-dim mb-2">Parent Category <span
                                class="text-bone-faint">(Optional)</span></label>
                        <select name="parent_id" id="parent_id"
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold/30 transition-all duration-200">
                            <option value="" class="bg-ink-raised2">None</option>
                            @foreach ($parents as $parent)
                                <option value="{{ $parent->id }}"
                                    {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}
                                    class="bg-ink-raised2">
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <p class="mt-2 text-sm text-bad">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div>
                        <label class="flex items-center cursor-pointer group">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                class="h-4 w-4 text-gold bg-ink-raised2 border-line rounded focus:ring-gold/30 focus:ring-offset-0">
                            <span class="ml-2 text-sm text-bone-dim group-hover:text-gold transition-colors">Active</span>
                        </label>
                        <p class="mt-1 text-xs text-bone-faint">Inactive categories won't appear in the admin dashboard.</p>
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-bone-dim mb-2">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order) }}"
                            min="0"
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold/30 transition-all duration-200">
                        <p class="mt-1 text-xs text-bone-faint">Lower numbers appear first.</p>
                        @error('sort_order')
                            <p class="mt-2 text-sm text-bad">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('admin.categories.index') }}"
                        class="px-6 py-3 border border-line rounded-lg text-bone-dim hover:text-bone hover:bg-ink-raised2/50 transition-all duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-gold to-gold-dim text-ink font-semibold rounded-lg hover:from-gold-bright hover:to-gold transition-all duration-300 shadow-lg shadow-gold/20">
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection