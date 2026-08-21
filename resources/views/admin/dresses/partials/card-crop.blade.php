{{--
    Shared by create.blade.php and edit.blade.php. Lets an admin manually
    crop the dress's main image for use as its card/grid thumbnail — a
    separate 'card' image (Dress::cardImage()) from 'main', so the product
    detail page keeps showing the true, uncropped photo. Cropping is
    optional: a dress with no card crop just keeps showing the full main
    image (object-contain) on cards until one is set.

    Expects:
    - $existingImageUrl: the dress's current main image URL, or null (create
      page, or a dress with no real photo yet).
    - $existingCardImageUrl: the dress's current card-crop URL, or null.
--}}
<div class="mt-6 pt-6 border-t border-line"
    x-data="dressCardCropper({
        existingMainImageUrl: @js($existingImageUrl ?? null),
        existingCardImageUrl: @js($existingCardImageUrl ?? null),
    })"
    x-init="init()">

    <template x-if="!supported">
        <p class="text-xs text-bad mb-2">
            Card cropping isn't supported in this browser. Please use a recent version of Chrome, Edge, or Firefox.
        </p>
    </template>

    <div class="flex items-center justify-between mb-2">
        <label class="block text-sm font-medium text-bone-dim">
            Card Crop <span class="text-bone-faint">(Optional — controls how this dress looks in grid cards)</span>
        </label>
        <span class="text-xs" :class="hasCrop ? 'text-good' : 'text-bone-faint'"
            x-text="hasCrop ? 'Cropped for cards' : 'Using full image (may show empty space in cards)'"></span>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <button type="button" @click="openModal()" :disabled="!canCrop"
            class="px-4 py-2 bg-ink-raised2/50 border border-line rounded-lg text-sm text-bone-dim hover:text-gold hover:border-gold/50 transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
            <i class="fas fa-crop-alt mr-2"></i>
            <span x-text="hasCrop ? 'Edit Card Crop' : 'Crop for Card'"></span>
        </button>
        <button type="button" x-show="hasCrop" @click="clearCrop()"
            class="px-4 py-2 text-sm text-bad hover:text-bad/80 transition-colors">
            Remove Crop
        </button>
        <p x-show="!canCrop" class="text-xs text-bone-faint">Upload or keep a main image first.</p>
    </div>

    <div x-show="hasCrop" class="mt-3">
        <p class="text-sm text-bone-dim mb-2">Card preview:</p>
        <img :src="previewUrl" alt="Card crop preview" class="w-40 h-40 object-cover bg-ink-raised2/50 rounded-lg border border-line">
    </div>

    <input type="file" name="card_image" x-ref="cardImageInput" class="hidden">
    <input type="hidden" name="remove_card_image" x-ref="removeCardImageFlag" value="0">

    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-ink/80 backdrop-blur-sm"
        @keydown.escape.window="closeModal()">
        <div class="bg-ink-raised border border-line rounded-xl p-6 w-full max-w-2xl" @click.outside="closeModal()">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-bone font-semibold">Crop Card Image</h4>
                <div class="flex items-center gap-2">
                    <label class="text-xs text-bone-faint">Aspect</label>
                    <select x-model.number="aspectRatio" @change="setAspectRatio()"
                        class="bg-ink-raised2/50 border border-line rounded-lg text-sm text-bone px-2 py-1">
                        <option value="1.3333333333333333">4:3 (Recommended)</option>
                        <option value="1">1:1 (Square)</option>
                        <option value="1.5">3:2 (Wide)</option>
                    </select>
                </div>
            </div>
            <p class="text-xs text-bone-faint mb-3">Leave generous margin around the subject — narrow mobile screens crop a little tighter from the sides.</p>
            <div class="max-h-[60vh] overflow-hidden bg-ink rounded-lg">
                <img x-ref="cropperImage" alt="Image to crop" class="block max-w-full">
            </div>
            <div class="mt-4 flex justify-end gap-3">
                <button type="button" @click="closeModal()" class="px-4 py-2 border border-line rounded-lg text-bone-dim hover:text-bone">Cancel</button>
                <button type="button" @click="applyCrop()" class="px-4 py-2 bg-gradient-to-r from-gold to-gold-dim text-ink font-semibold rounded-lg">Apply Crop</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function dressCardCropper(options) {
            options = options || {};
            const existingMainImageUrl = options.existingMainImageUrl || null;
            const existingCardImageUrl = options.existingCardImageUrl || null;

            return {
                modalOpen: false,
                hasCrop: !!existingCardImageUrl,
                hadExistingCardImage: !!existingCardImageUrl,
                previewUrl: existingCardImageUrl || existingMainImageUrl || null,
                aspectRatio: 4 / 3,
                cropper: null,
                mainImageInput: null,
                supported: typeof window.DataTransfer !== 'undefined' && typeof window.Cropper !== 'undefined',

                init() {
                    this.mainImageInput = document.getElementById('main_image');
                    if (this.mainImageInput) {
                        // Picking a new main image invalidates any crop made
                        // against the old source, so the admin is forced to
                        // consciously re-crop rather than silently keeping a
                        // crop that no longer matches the photo.
                        this.mainImageInput.addEventListener('change', () => this.clearCrop());
                    }
                },

                get canCrop() {
                    return this.supported && !!((this.mainImageInput && this.mainImageInput.files && this.mainImageInput.files.length) || existingMainImageUrl);
                },

                currentSourceUrl() {
                    if (this.mainImageInput && this.mainImageInput.files && this.mainImageInput.files.length) {
                        return URL.createObjectURL(this.mainImageInput.files[0]);
                    }
                    return existingMainImageUrl;
                },

                openModal() {
                    if (!this.canCrop) return;
                    this.modalOpen = true;
                    this.$nextTick(() => {
                        const img = this.$refs.cropperImage;
                        img.onload = () => {
                            if (this.cropper) this.cropper.destroy();
                            this.cropper = new window.Cropper(img, {
                                aspectRatio: this.aspectRatio,
                                viewMode: 1,
                                autoCropArea: 1,
                                responsive: true,
                                background: false,
                                checkCrossOrigin: false,
                            });
                        };
                        img.src = this.currentSourceUrl();
                    });
                },

                setAspectRatio() {
                    if (this.cropper) this.cropper.setAspectRatio(Number(this.aspectRatio));
                },

                closeModal() {
                    this.modalOpen = false;
                    if (this.cropper) this.cropper.destroy();
                    this.cropper = null;
                },

                applyCrop() {
                    if (!this.cropper) return;
                    const canvas = this.cropper.getCroppedCanvas({
                        maxWidth: 1600,
                        maxHeight: 1600,
                        imageSmoothingQuality: 'high',
                        fillColor: '#ffffff',
                    });
                    canvas.toBlob((blob) => {
                        if (!blob) return;
                        const file = new File([blob], 'card-crop.jpg', { type: 'image/jpeg' });
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        this.$refs.cardImageInput.files = dt.files;

                        if (this.previewUrl && this.previewUrl.indexOf('blob:') === 0) {
                            URL.revokeObjectURL(this.previewUrl);
                        }
                        this.previewUrl = URL.createObjectURL(blob);
                        this.hasCrop = true;
                        if (this.$refs.removeCardImageFlag) this.$refs.removeCardImageFlag.value = '0';
                        this.closeModal();
                    }, 'image/jpeg', 0.9);
                },

                clearCrop() {
                    this.hasCrop = false;
                    if (this.$refs.cardImageInput) this.$refs.cardImageInput.value = '';
                    // Only tell the server to delete something if there WAS a
                    // persisted crop when the page loaded (edit page). On
                    // create, or when clearing a not-yet-submitted local
                    // crop, there's nothing server-side to remove.
                    if (this.hadExistingCardImage && this.$refs.removeCardImageFlag) {
                        this.$refs.removeCardImageFlag.value = '1';
                    }
                    this.previewUrl = null;
                },
            };
        }
    </script>
@endpush
