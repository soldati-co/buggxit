@csrf
<div class="space-y-6">
    <!-- Title (admin label) -->
    <div>
        <label class="block text-sm font-medium text-bone-dim mb-1">Title (admin label)</label>
        <input type="text" name="title" value="{{ old('title', $slide->title ?? '') }}"
            class="w-full bg-ink-raised2 border border-line rounded-lg px-4 py-3 text-bone focus:border-gold focus:ring-gold">
    </div>

    <!-- Headline -->
    <div>
        <label class="block text-sm font-medium text-bone-dim mb-1">Headline</label>
        <input type="text" name="headline" value="{{ old('headline', $slide->headline ?? '') }}"
            class="w-full bg-ink-raised2 border border-line rounded-lg px-4 py-3 text-bone focus:border-gold focus:ring-gold">
    </div>

    <!-- Subheading -->
    <div>
        <label class="block text-sm font-medium text-bone-dim mb-1">Subheading</label>
        <textarea name="subheading" rows="3"
            class="w-full bg-ink-raised2 border border-line rounded-lg px-4 py-3 text-bone focus:border-gold focus:ring-gold">{{ old('subheading', $slide->subheading ?? '') }}</textarea>
    </div>

    <!-- CTA Text -->
    <div>
        <label class="block text-sm font-medium text-bone-dim mb-1">CTA Button Text</label>
        <input type="text" name="cta_text" value="{{ old('cta_text', $slide->cta_text ?? '') }}"
            class="w-full bg-ink-raised2 border border-line rounded-lg px-4 py-3 text-bone focus:border-gold focus:ring-gold">
    </div>

    <!-- CTA URL -->
    <div>
        <label class="block text-sm font-medium text-bone-dim mb-1">CTA Button Link</label>
        <input type="url" name="cta_url" value="{{ old('cta_url', $slide->cta_url ?? '') }}"
            class="w-full bg-ink-raised2 border border-line rounded-lg px-4 py-3 text-bone focus:border-gold focus:ring-gold"
            placeholder="https://example.com/collection">
    </div>

    <!-- Image -->
    <div>
        <label class="block text-sm font-medium text-bone-dim mb-1">Image</label>
        @if (isset($slide) && $slide->image_api_url)
            <div class="mb-3" id="current-image-container">
                <img src="{{ $slide->image_api_url }}?t={{ time() }}"
                     id="current-image"
                     class="w-40 h-24 object-cover rounded-lg border border-line"
                     onerror="this.style.display='none'; this.parentElement.innerHTML+='<p class=\'text-bad text-xs mt-1\'>Image not found</p>';">
                <p class="text-xs text-bone-faint mt-1">Current image</p>
            </div>
        @endif
        <input type="file" name="image" id="image-input" accept="image/*"
            class="w-full text-bone-dim file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gold/10 file:text-gold hover:file:bg-gold/20 transition">
        @error('image')
            <p class="text-bad text-xs mt-1">{{ $message }}</p>
        @enderror

        <!-- Live preview -->
        <div id="image-preview-container" class="mt-3 hidden">
            <p class="text-sm text-bone-dim mb-2">Preview:</p>
            <img id="image-preview" src="#" alt="Preview" class="w-40 h-24 object-cover rounded-lg border border-line">
        </div>
    </div>

    <!-- Alt Text -->
    <div>
        <label class="block text-sm font-medium text-bone-dim mb-1">Alt Text</label>
        <input type="text" name="alt_text" value="{{ old('alt_text', $slide->alt_text ?? '') }}"
            class="w-full bg-ink-raised2 border border-line rounded-lg px-4 py-3 text-bone focus:border-gold focus:ring-gold">
    </div>

    <!-- Sort Order -->
    <div>
        <label class="block text-sm font-medium text-bone-dim mb-1">Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $slide->sort_order ?? 0) }}"
            class="w-full bg-ink-raised2 border border-line rounded-lg px-4 py-3 text-bone focus:border-gold focus:ring-gold">
    </div>

    <!-- Active -->
    <div class="flex items-center">
        <input type="checkbox" name="is_active" value="1"
            {{ old('is_active', isset($slide) ? $slide->is_active : true) ? 'checked' : '' }}
            class="rounded bg-ink-raised2 border-line text-gold focus:ring-gold">
        <span class="ml-2 text-sm text-bone-dim">Active</span>
    </div>
</div>

<div class="mt-8 flex justify-end space-x-4">
    <a href="{{ route('admin.hero-slides.index') }}"
        class="px-6 py-3 border border-line rounded-lg text-bone-dim hover:bg-ink-raised2 transition">Cancel</a>
    <button type="submit"
        class="px-6 py-3 bg-gradient-to-r from-gold to-gold-dim text-ink font-semibold rounded-lg hover:from-gold-bright hover:to-gold transition">
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