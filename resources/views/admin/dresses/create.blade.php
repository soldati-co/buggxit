@extends('layouts.admin')

@section('title', 'Create New Dress')
@section('page-title', 'Add New Dress')
@section('page-description', 'Add a new traditional ceremony dress to your collection')

@section('content')
    <div class="max-w-4xl mx-auto">
        <form action="{{ route('admin.dresses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            @if ($errors->has('error'))
                <div class="p-4 bg-bad/10 border border-bad/30 rounded-lg flex items-start gap-3">
                    <i class="fas fa-triangle-exclamation text-bad mt-0.5"></i>
                    <p class="text-bad text-sm">{{ $errors->first('error') }}</p>
                </div>
            @endif

            <!-- Basic Information Card -->
            <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6">
                <h3 class="text-lg font-semibold text-bone mb-4 flex items-center">
                    <svg class="w-5 h-5 text-gold mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4 6h16v12H4V6zm2 2v8h12V8H6z" />
                    </svg>
                    Basic Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Dress Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-bone-dim mb-2">Dress Name <span
                                class="text-gold">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold/30 transition-all duration-200"
                            placeholder="e.g., Traditional Zulu Wedding Dress">
                        @error('name')
                            <p class="mt-2 text-sm text-bad">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SKU -->
                    <div>
                        <label for="sku" class="block text-sm font-medium text-bone-dim mb-2">SKU <span
                                class="text-gold">*</span></label>
                        <input type="text" name="sku" id="sku" value="{{ old('sku') }}" required
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold/30 transition-all duration-200"
                            placeholder="e.g., SLMK-001">
                        @error('sku')
                            <p class="mt-2 text-sm text-bad">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-bone-dim mb-2">Price (R) <span
                                class="text-gold">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-bone-faint text-sm">R</span>
                            </div>
                            <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01"
                                min="0" required
                                class="pl-8 w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold/30 transition-all duration-200"
                                placeholder="0.00">
                        </div>
                        @error('price')
                            <p class="mt-2 text-sm text-bad">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-bone-dim mb-2">Status <span
                                class="text-gold">*</span></label>
                        <select name="status" id="status" required
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold/30 transition-all duration-200">
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }} class="bg-ink-raised2">
                                Draft</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }} class="bg-ink-raised2">
                                Active</option>
                            <option value="out_of_stock" {{ old('status') == 'out_of_stock' ? 'selected' : '' }}
                                class="bg-ink-raised2">Out of Stock</option>
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-bad">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Categories (checkboxes) -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-bone-dim mb-3">Categories <span
                            class="text-gold">*</span></label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach ($categories as $category)
                            <label
                                class="flex items-center p-3 bg-ink-raised2/30 border border-line rounded-lg hover:border-gold/50 cursor-pointer transition-colors">
                                <input type="checkbox" name="category_ids[]" value="{{ $category->id }}"
                                    {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}
                                    class="h-4 w-4 text-gold bg-ink-raised2 border-line rounded focus:ring-gold/30 focus:ring-offset-0">
                                <span class="ml-2 text-sm text-bone-dim">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('category_ids')
                        <p class="mt-2 text-sm text-bad">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-bone-dim mb-2">Description <span
                            class="text-gold">*</span></label>
                    <textarea name="description" id="description" rows="4" required
                        class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold/30 transition-all duration-200"
                        placeholder="Describe the dress in detail...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-bad">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Sizes & Colors Card -->
            <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6">
                <h3 class="text-lg font-semibold text-bone mb-4 flex items-center">
                    <svg class="w-5 h-5 text-gold mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M7 11h10v2H7zM4 7h16v2H4zM6 15h12v2H6z" />
                    </svg>
                    Sizes & Colors
                </h3>

                <!-- Sizes -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-bone-dim mb-3">Available Sizes <span
                            class="text-gold">*</span></label>
                    <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
                        @foreach ($availableSizes as $size)
                            <label
                                class="flex items-center p-3 bg-ink-raised2/30 border border-line rounded-lg hover:border-gold/50 cursor-pointer transition-colors">
                                <input type="checkbox" name="sizes[]" value="{{ $size }}"
                                    {{ in_array($size, old('sizes', [])) ? 'checked' : '' }}
                                    class="h-4 w-4 text-gold bg-ink-raised2 border-line rounded focus:ring-gold/30 focus:ring-offset-0">
                                <span class="ml-2 text-sm text-bone-dim">Size {{ $size }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('sizes')
                        <p class="mt-2 text-sm text-bad">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Colors -->
                <div>
                    <label class="block text-sm font-medium text-bone-dim mb-3">Available Colors <span
                            class="text-gold">*</span></label>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
                        @foreach ($availableColors as $value => $label)
                            <label
                                class="flex items-center p-3 bg-ink-raised2/30 border border-line rounded-lg hover:border-gold/50 cursor-pointer transition-colors">
                                <input type="checkbox" name="colors[]" value="{{ $value }}"
                                    {{ in_array($value, old('colors', [])) ? 'checked' : '' }}
                                    class="h-4 w-4 text-gold bg-ink-raised2 border-line rounded focus:ring-gold/30 focus:ring-offset-0">
                                <span class="ml-2 text-sm text-bone-dim">{{ $label }}</span>
                                @if ($value != 'multi')
                                    <div class="ml-auto w-4 h-4 rounded-full"
                                        style="background-color: {{ $value }}; border: 1px solid rgba(255,255,255,0.2);">
                                    </div>
                                @else
                                    <div
                                        class="ml-auto w-4 h-4 rounded-full bg-gradient-to-r from-bad via-info to-gold-bright border border-white/20">
                                    </div>
                                @endif
                            </label>
                        @endforeach
                    </div>
                    @error('colors')
                        <p class="mt-2 text-sm text-bad">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Production & Delivery Card -->
            <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6">
                <h3 class="text-lg font-semibold text-bone mb-4 flex items-center">
                    <svg class="w-5 h-5 text-gold mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M20 8h-2v2h2v6h-2v2h2a2 2 0 002-2v-6a2 2 0 00-2-2zM4 8h2v2H4v6h2v2H4a2 2 0 01-2-2v-6a2 2 0 012-2z" />
                    </svg>
                    Production & Delivery
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Turnaround Time -->
                    <div>
                        <label for="turnaround_time" class="block text-sm font-medium text-bone-dim mb-2">Turnaround Time
                            <span class="text-gold">*</span></label>
                        <input type="text" name="turnaround_time" id="turnaround_time"
                            value="{{ old('turnaround_time') }}" required
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold/30 transition-all duration-200"
                            placeholder="e.g., 2-3 weeks">
                        <p class="mt-2 text-xs text-bone-faint">Estimated time to make the dress</p>
                        @error('turnaround_time')
                            <p class="mt-2 text-sm text-bad">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Expected Delivery -->
                    <div>
                        <label for="expected_delivery" class="block text-sm font-medium text-bone-dim mb-2">Expected
                            Delivery <span class="text-gold">*</span></label>
                        <input type="text" name="expected_delivery" id="expected_delivery"
                            value="{{ old('expected_delivery') }}" required
                            class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold/30 transition-all duration-200"
                            placeholder="e.g., To the nearest Courier">
                        <p class="mt-2 text-xs text-bone-faint">Delivery method or location</p>
                        @error('expected_delivery')
                            <p class="mt-2 text-sm text-bad">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Featured -->
                <div class="mt-6">
                    <label class="flex items-center cursor-pointer group">
                        <input type="checkbox" name="is_featured" value="1"
                            {{ old('is_featured') ? 'checked' : '' }}
                            class="h-4 w-4 text-gold bg-ink-raised2 border-line rounded focus:ring-gold/30 focus:ring-offset-0">
                        <span class="ml-2 text-sm text-bone-dim group-hover:text-gold transition-colors">Mark as
                            featured dress</span>
                    </label>
                </div>
            </div>

            <!-- Images Card with Previews & Detailed Errors -->
            <div class="bg-ink-raised/90 backdrop-blur-sm border border-line rounded-xl p-6">
                <h3 class="text-lg font-semibold text-bone mb-4 flex items-center">
                    <svg class="w-5 h-5 text-gold mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4 5h16v14H4V5zm2 2v10h12V7H6z" />
                    </svg>
                    Images
                </h3>

                <!-- Main Image -->
                <div class="mb-6">
                    <label for="main_image" class="block text-sm font-medium text-bone-dim mb-2">Main Image <span
                            class="text-gold">*</span></label>
                    <div
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-line border-dashed rounded-lg bg-ink-raised2/20">
                        <div class="space-y-2 text-center">
                            <svg class="mx-auto h-12 w-12 text-bone-faint" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-bone-dim">
                                <label for="main_image"
                                    class="relative cursor-pointer bg-ink-raised2/50 rounded-md font-medium text-gold hover:text-gold-bright focus-within:outline-none px-3 py-2 border border-line hover:border-gold/50 transition-colors">
                                    <span>Upload a file</span>
                                    <input id="main_image" name="main_image" type="file" class="sr-only"
                                        accept="image/*">
                                </label>
                                <p class="pl-2 pt-2">or drag and drop</p>
                            </div>
                            <p class="text-xs text-bone-faint">PNG, JPG, GIF up to 9MB</p>
                        </div>
                    </div>
                    
                    <!-- Detailed Main Image Error -->
                    @error('main_image')
                        <div class="mt-3 p-3 bg-bad/10 border border-bad/30 rounded-lg">
                            <p class="text-bad text-sm flex items-center">
                                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </p>
                        </div>
                    @enderror

                    <!-- Main Image Preview -->
                    <div id="main-image-preview" class="mt-3 hidden">
                        <p class="text-sm text-bone-dim mb-2">Preview:</p>
                        <img id="main-image-thumb" src="#" alt="Main image preview"
                            class="w-48 h-48 object-contain bg-ink-raised2/50 rounded-lg border border-line">
                    </div>
                </div>

                <!-- Gallery Images -->
                <div>
                    <label for="gallery_images" class="block text-sm font-medium text-bone-dim mb-2">Gallery Images <span
                            class="text-bone-faint">(Optional)</span></label>
                    <input type="file" name="gallery_images[]" id="gallery_images" multiple accept="image/*"
                        class="w-full px-4 py-3 bg-ink-raised2/50 border border-line rounded-lg text-bone text-sm focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold/30 transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gold/10 file:text-gold hover:file:bg-gold/20">
                    <p class="mt-2 text-xs text-bone-faint">Upload multiple images to show different angles</p>
                    
                    <!-- Detailed Gallery Images Errors -->
                    @error('gallery_images')
                        <div class="mt-3 p-3 bg-bad/10 border border-bad/30 rounded-lg">
                            <p class="text-bad text-sm flex items-center">
                                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </p>
                        </div>
                    @enderror
                    @error('gallery_images.*')
                        <div class="mt-3 p-3 bg-bad/10 border border-bad/30 rounded-lg">
                            <p class="text-bad text-sm flex items-center">
                                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </p>
                        </div>
                    @enderror

                    <!-- Gallery Images Preview -->
                    <div id="gallery-preview" class="mt-3 grid grid-cols-2 sm:grid-cols-4 gap-3 hidden">
                        <!-- Thumbnails will be injected here -->
                    </div>
                </div>

                <!-- General Error -->
                @error('error')
                    <div class="mt-3 p-3 bg-bad/10 border border-bad/30 rounded-lg">
                        <p class="text-bad text-sm flex items-center">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </p>
                    </div>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="mt-2 flex justify-end space-x-4">
                <a href="{{ route('admin.dresses.index') }}"
                    class="px-6 py-3 border border-line rounded-lg text-bone-dim hover:text-bone hover:bg-ink-raised2/50 transition-all duration-200">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-gold to-gold-dim text-ink font-semibold rounded-lg hover:from-gold-bright hover:to-gold transition-all duration-300 shadow-lg shadow-gold/20">
                    Save Dress
                </button>
            </div>
        </form>
    </div>
@endsection