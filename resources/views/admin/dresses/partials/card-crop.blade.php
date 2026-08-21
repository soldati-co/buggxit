{{--
    Shared by create.blade.php and edit.blade.php. Lets an admin manually
    crop the dress's main image for display in three INDEPENDENT contexts —
    the "Card Grid" (products listing + homepage featured, which share a box
    shape), the homepage "New Arrivals" section (a different box shape), and
    the product detail page's large hero image (a true 1:1 square). One crop
    can't serve all three well. Each is separate from 'main'
    (Dress::cardImage() / cardImageNewArrivals() / detailImage()), so the
    product detail page's gallery still shows true, uncropped photos.
    Cropping is optional for each: an uncropped target falls back to the
    full main image — New Arrivals falls back further to the Card Grid crop
    if only that one has been set, since a deliberate crop is still better
    than none.

    Card Grid and New Arrivals always render with object-contain (see
    products/index.blade.php, pages/landing.blade.php) — nothing is ever
    cut off there regardless of a crop's shape; cropping just chooses what's
    zoomed in on. The Product Page image (products/show.blade.php) still
    switches to object-cover when a crop exists, so its crop shape matters
    more — see the mismatch warning in applyCrop() below.

    Expects:
    - $existingImageUrl: the dress's current main image URL, or null.
    - $existingCardImageUrl: the dress's current Card Grid crop URL, or null.
    - $existingCardImageNewArrivalsUrl: the dress's current dedicated New
      Arrivals crop URL, or null (may still show a preview via fallback).
    - $existingDetailImageUrl: the dress's current Product Detail crop URL,
      or null.
--}}
<div class="mt-6 pt-6 border-t border-line"
    x-data="dressCardCropper({
        existingMainImageUrl: @js($existingImageUrl ?? null),
        existingCardImageUrl: @js($existingCardImageUrl ?? null),
        existingCardImageNewArrivalsUrl: @js($existingCardImageNewArrivalsUrl ?? null),
        existingDetailImageUrl: @js($existingDetailImageUrl ?? null),
    })"
    x-init="init()">

    <template x-if="!supported">
        <p class="text-xs text-bad mb-2">
            Card cropping isn't supported in this browser. Please use a recent version of Chrome, Edge, or Firefox.
        </p>
    </template>

    <label class="block text-sm font-medium text-bone-dim mb-1">
        Image Crops <span class="text-bone-faint">(Optional — controls how this dress looks in cards and on its own page)</span>
    </label>
    <p class="text-xs text-bone-faint mb-3">
        <i class="fas fa-circle-info mr-1"></i>
        For the best fit, upload a square main photo (1:1) around 1200–1600px, with the dress centered and some
        space around it — that matches the Card Grid and Product Page directly, and crops cleanly for the
        New Arrivals section (4:3) too.
    </p>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <template x-for="key in ['grid', 'newArrivals', 'detail']" :key="key">
            <div class="border border-line rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-bone font-medium" x-text="targets[key].label"></span>
                    <span class="text-xs" :class="targets[key].hasCrop ? 'text-good' : 'text-bone-faint'"
                        x-text="statusText(key)"></span>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" @click="openModal(key)" :disabled="!canCrop"
                        class="px-3 py-1.5 bg-ink-raised2/50 border border-line rounded-lg text-xs text-bone-dim hover:text-gold hover:border-gold/50 transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                        <i class="fas fa-crop-alt mr-1"></i>
                        <span x-text="targets[key].hasCrop ? 'Edit Crop' : 'Crop Image'"></span>
                    </button>
                    <button type="button" x-show="targets[key].hasCrop" @click="clearCrop(key)"
                        class="text-xs text-bad hover:text-bad/80 transition-colors">
                        Remove
                    </button>
                </div>
                <p x-show="!canCrop" class="text-xs text-bone-faint mt-2">Upload or keep a main image first.</p>
                <img x-show="targets[key].previewUrl" :src="targets[key].previewUrl" alt="Crop preview"
                    class="mt-3 max-w-full max-h-32 object-contain bg-ink-raised2/50 rounded-lg border border-line">
            </div>
        </template>
    </div>

    <input type="file" name="card_image" x-ref="cardImageInput" class="hidden">
    <input type="hidden" name="remove_card_image" x-ref="removeCardImageFlag" value="0">
    <input type="file" name="card_image_new_arrivals" x-ref="cardImageNewArrivalsInput" class="hidden">
    <input type="hidden" name="remove_card_image_new_arrivals" x-ref="removeCardImageNewArrivalsFlag" value="0">
    <input type="file" name="detail_image" x-ref="detailImageInput" class="hidden">
    <input type="hidden" name="remove_detail_image" x-ref="removeDetailImageFlag" value="0">

    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-ink/80 backdrop-blur-sm"
        @keydown.escape.window="closeModal()">
        <div class="bg-ink-raised border border-line rounded-xl p-6 w-full max-w-2xl" @click.outside="closeModal()">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-bone font-semibold" x-text="activeTarget ? 'Crop for ' + targets[activeTarget].label : 'Crop Image'"></h4>
                <div class="flex items-center gap-2">
                    <label class="text-xs text-bone-faint">Snap to</label>
                    <select x-model="aspectRatio" @change="setAspectRatio()"
                        class="bg-ink-raised2/50 border border-line rounded-lg text-sm text-bone px-2 py-1">
                        <option value="1">Square (1:1)</option>
                        <option value="1.3333333333333333">Landscape (4:3)</option>
                        <option value="free">Free (not recommended)</option>
                    </select>
                </div>
            </div>
            <p class="text-xs text-bone-faint mb-3">Card Grid and New Arrivals always show the whole photo (nothing is ever cut off) — cropping here just zooms in on what should be featured. On the Product Page, a crop matching its shape also fills the image edge-to-edge. The panels on the right show exactly how this crop will look in each spot.</p>
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1 max-h-[60vh] overflow-hidden bg-ink rounded-lg">
                    <img x-ref="cropperImage" alt="Image to crop" class="block max-w-full">
                </div>
                <div class="flex sm:flex-col gap-3 shrink-0">
                    <div>
                        <p class="text-xs text-bone-faint mb-1">Card Grid</p>
                        <div class="w-24 h-24 overflow-hidden rounded-lg border border-line bg-ink-raised2/50" x-ref="previewGrid"></div>
                    </div>
                    <div>
                        <p class="text-xs text-bone-faint mb-1">New Arrivals</p>
                        <div class="w-24 h-[72px] overflow-hidden rounded-lg border border-line bg-ink-raised2/50" x-ref="previewNewArrivals"></div>
                    </div>
                    <div>
                        <p class="text-xs text-bone-faint mb-1">Product Page</p>
                        <div class="w-24 h-24 overflow-hidden rounded-lg border border-line bg-ink-raised2/50" x-ref="previewDetail"></div>
                    </div>
                </div>
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
            const existingCardImageNewArrivalsUrl = options.existingCardImageNewArrivalsUrl || null;
            const existingDetailImageUrl = options.existingDetailImageUrl || null;

            return {
                modalOpen: false,
                activeTarget: null,
                // Overwritten by openModal() to match whichever target is
                // being cropped — this initial value is never actually used.
                aspectRatio: '1',
                cropper: null,
                mainImageInput: null,
                supported: typeof window.DataTransfer !== 'undefined' && typeof window.Cropper !== 'undefined',

                targets: {
                    grid: {
                        label: 'Card Grid',
                        hasCrop: !!existingCardImageUrl,
                        hadExisting: !!existingCardImageUrl,
                        previewUrl: existingCardImageUrl || existingMainImageUrl || null,
                        fallbackLabel: null,
                        fileInputRef: 'cardImageInput',
                        removeFlagRef: 'removeCardImageFlag',
                    },
                    newArrivals: {
                        label: 'New Arrivals',
                        hasCrop: !!existingCardImageNewArrivalsUrl,
                        hadExisting: !!existingCardImageNewArrivalsUrl,
                        previewUrl: existingCardImageNewArrivalsUrl || existingCardImageUrl || existingMainImageUrl || null,
                        // Only used for the status label when this target has
                        // no dedicated crop of its own but Card Grid does.
                        fallbackLabel: (!existingCardImageNewArrivalsUrl && existingCardImageUrl) ? 'Using Card Grid crop' : null,
                        fileInputRef: 'cardImageNewArrivalsInput',
                        removeFlagRef: 'removeCardImageNewArrivalsFlag',
                    },
                    detail: {
                        label: 'Product Page',
                        hasCrop: !!existingDetailImageUrl,
                        hadExisting: !!existingDetailImageUrl,
                        previewUrl: existingDetailImageUrl || existingMainImageUrl || null,
                        fallbackLabel: null,
                        fileInputRef: 'detailImageInput',
                        removeFlagRef: 'removeDetailImageFlag',
                    },
                },

                init() {
                    this.mainImageInput = document.getElementById('main_image');
                    if (this.mainImageInput) {
                        // Picking a new main image invalidates any crop made
                        // against the old source, so the admin is forced to
                        // consciously re-crop rather than silently keeping
                        // crops that no longer match the photo.
                        this.mainImageInput.addEventListener('change', () => {
                            this.clearCrop('grid');
                            this.clearCrop('newArrivals');
                            this.clearCrop('detail');
                        });
                    }
                },

                statusText(key) {
                    const target = this.targets[key];
                    if (target.hasCrop) return 'Cropped';
                    if (target.fallbackLabel) return target.fallbackLabel;
                    return 'Using full image';
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

                openModal(targetKey) {
                    if (!this.canCrop) return;
                    this.activeTarget = targetKey;
                    // Card Grid/New Arrivals always show the full photo now
                    // (object-contain, never cropped by the browser), so a
                    // mismatched free-form crop there only affects framing —
                    // default to Free for those. The Product Page image
                    // still fills its box with object-cover when a crop
                    // exists, so a badly-shaped crop there can genuinely
                    // slice content off — keep that one locked by default.
                    this.aspectRatio = targetKey === 'detail' ? '1' : 'free';
                    this.modalOpen = true;
                    this.$nextTick(() => {
                        const img = this.$refs.cropperImage;
                        img.onload = () => {
                            if (this.cropper) this.cropper.destroy();
                            this.cropper = new window.Cropper(img, {
                                aspectRatio: this.numericAspectRatio(),
                                viewMode: 1,
                                autoCropArea: 1,
                                responsive: true,
                                background: false,
                                checkCrossOrigin: false,
                                preview: [this.$refs.previewGrid, this.$refs.previewNewArrivals, this.$refs.previewDetail],
                            });
                        };
                        img.src = this.currentSourceUrl();
                    });
                },

                // 'free' means no locked ratio (Cropper.js expects NaN for that).
                numericAspectRatio() {
                    return this.aspectRatio === 'free' ? NaN : Number(this.aspectRatio);
                },

                setAspectRatio() {
                    if (this.cropper) this.cropper.setAspectRatio(this.numericAspectRatio());
                },

                closeModal() {
                    this.modalOpen = false;
                    this.activeTarget = null;
                    if (this.cropper) this.cropper.destroy();
                    this.cropper = null;
                },

                applyCrop() {
                    if (!this.cropper || !this.activeTarget) return;
                    const target = this.targets[this.activeTarget];

                    // Card Grid and New Arrivals always show the whole photo
                    // now (object-contain, never cropped by the browser), so
                    // a shape mismatch there only affects composition. The
                    // Product Page image still fills its box with
                    // object-cover when a crop exists, so a badly-shaped
                    // Free crop there can still slice content off — warn
                    // before saving rather than finding out on the live site.
                    if (this.aspectRatio === 'free' && this.activeTarget === 'detail') {
                        const expectedRatio = 1;
                        const data = this.cropper.getData();
                        const actualRatio = data.width / data.height;
                        const mismatch = Math.abs(actualRatio - expectedRatio) / expectedRatio;
                        if (mismatch > 0.25) {
                            const proceed = confirm(
                                `This crop's shape doesn't closely match where it's displayed (${target.label}). ` +
                                `It may get zoomed in and cropped further to fit, cutting off part of the image. Use it anyway?`
                            );
                            if (!proceed) return;
                        }
                    }

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
                        this.$refs[target.fileInputRef].files = dt.files;

                        if (target.previewUrl && target.previewUrl.indexOf('blob:') === 0) {
                            URL.revokeObjectURL(target.previewUrl);
                        }
                        target.previewUrl = URL.createObjectURL(blob);
                        target.hasCrop = true;
                        target.fallbackLabel = null;
                        if (this.$refs[target.removeFlagRef]) this.$refs[target.removeFlagRef].value = '0';
                        this.closeModal();
                    }, 'image/jpeg', 0.9);
                },

                clearCrop(key) {
                    const target = this.targets[key];
                    target.hasCrop = false;
                    target.fallbackLabel = null;
                    if (this.$refs[target.fileInputRef]) this.$refs[target.fileInputRef].value = '';
                    // Only tell the server to delete something if there WAS a
                    // persisted crop when the page loaded (edit page). On
                    // create, or when clearing a not-yet-submitted local
                    // crop, there's nothing server-side to remove.
                    if (target.hadExisting && this.$refs[target.removeFlagRef]) {
                        this.$refs[target.removeFlagRef].value = '1';
                    }
                    target.previewUrl = null;
                },
            };
        }
    </script>
@endpush
