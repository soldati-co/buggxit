@csrf
<div class="space-y-6">
    <!-- Title (admin label) -->
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">Title (admin label)</label>
        <input type="text" name="title" value="{{ old('title', $slide->title ?? '') }}"
            class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:border-yellow-500 focus:ring-yellow-500">
    </div>

    <!-- Headline -->
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">Headline</label>
        <input type="text" name="headline" value="{{ old('headline', $slide->headline ?? '') }}"
            class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:border-yellow-500 focus:ring-yellow-500">
    </div>

    <!-- Subheading -->
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">Subheading</label>
        <textarea name="subheading" rows="3"
            class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:border-yellow-500 focus:ring-yellow-500">{{ old('subheading', $slide->subheading ?? '') }}</textarea>
    </div>

    <!-- CTA Text -->
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">CTA Button Text</label>
        <input type="text" name="cta_text" value="{{ old('cta_text', $slide->cta_text ?? '') }}"
            class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:border-yellow-500 focus:ring-yellow-500">
    </div>

    <!-- CTA URL -->
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">CTA Button Link</label>
        <input type="url" name="cta_url" value="{{ old('cta_url', $slide->cta_url ?? '') }}"
            class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:border-yellow-500 focus:ring-yellow-500"
            placeholder="https://example.com/collection">
    </div>

    <!-- Image -->
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">Image</label>
        @if (isset($slide) && $slide->image_path)
            <div class="mb-3" id="current-image-container">
                <img src="{{ route('api.hero-slides.image', $slide->id) }}?t={{ time() }}"
                     id="current-image"
                     class="w-40 h-24 object-cover rounded-lg border border-gray-700"
                     onerror="this.style.display='none'; this.parentElement.innerHTML+='<p class=\'text-red-400 text-xs mt-1\'>Image not found</p>';">
                <p class="text-xs text-gray-500 mt-1">Current image</p>
            </div>
        @endif
        <input type="file" name="image" id="image-input" accept="image/*"
            class="w-full text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-yellow-500/10 file:text-yellow-500 hover:file:bg-yellow-500/20 transition">
        @error('image')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
        @enderror

        <!-- Live preview -->
        <div id="image-preview-container" class="mt-3 hidden">
            <p class="text-sm text-gray-400 mb-2">Preview:</p>
            <img id="image-preview" src="#" alt="Preview" class="w-40 h-24 object-cover rounded-lg border border-gray-700">
        </div>
    </div>

    <!-- Alt Text -->
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">Alt Text</label>
        <input type="text" name="alt_text" value="{{ old('alt_text', $slide->alt_text ?? '') }}"
            class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:border-yellow-500 focus:ring-yellow-500">
    </div>

    <!-- Sort Order -->
    <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $slide->sort_order ?? 0) }}"
            class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:border-yellow-500 focus:ring-yellow-500">
    </div>

    <!-- Active -->
    <div class="flex items-center">
        <input type="checkbox" name="is_active" value="1"
            {{ old('is_active', isset($slide) ? $slide->is_active : true) ? 'checked' : '' }}
            class="rounded bg-gray-800 border-gray-700 text-yellow-500 focus:ring-yellow-500">
        <span class="ml-2 text-sm text-gray-300">Active</span>
    </div>
</div>

<div class="mt-8 flex justify-end space-x-3">
    <a href="{{ route('admin.hero-slides.index') }}"
        class="px-6 py-2.5 border border-gray-700 rounded-lg text-gray-300 hover:bg-gray-800 transition">Cancel</a>
    <button type="submit"
        class="px-6 py-2.5 bg-gradient-to-r from-yellow-500 to-yellow-600 text-gray-900 font-semibold rounded-lg hover:from-yellow-400 hover:to-yellow-500 transition">
        {{ $submitLabel ?? 'Save Slide' }}
    </button>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image-input');
    const previewContainer = document.getElementById('image-preview-container');
    const previewImg = document.getElementById('image-preview');
    const currentImageContainer = document.getElementById('current-image-container');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewContainer.classList.remove('hidden');
                if (currentImageContainer) {
                    currentImageContainer.style.display = 'none';
                }
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.classList.add('hidden');
            previewImg.src = '#';
            if (currentImageContainer) {
                currentImageContainer.style.display = 'block';
            }
        }
    });
});
</script>
@endpush