<div
    x-data="imageGallery({
        layout: '{{ $layout }}',
        lazyLoad: {{ $lazyLoad ? 'true' : 'false' }},
        lightbox: {{ $enableLightbox ? 'true' : 'false' }},
        filters: @js($filters),
        images: @js($images),
        uuid: '{{ $uuid }}',
        itemsPerPage: {{ $itemsPerPage }},
        loadingStyle: '{{ $loadingStyle }}',
        showCaptions: {{ $showCaptions ? 'true' : 'false' }}
    })"
    x-init="init()"
    {{ $attributes->class("w-full") }}
>
    <!-- Filters (if enabled) -->
    @if($filters)
        <div class="mb-6">
            <div class="flex flex-wrap gap-2">
                <button
                    @click="setActiveFilter(null)"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200"
                    :class="activeFilter === null ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600'"
                >
                    All
                </button>
                @foreach($filters as $filter)
                    <button
                        @click="setActiveFilter('{{ $filter }}')"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 capitalize"
                        :class="activeFilter === '{{ $filter }}' ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600'"
                    >
                        {{ $filter }}
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Gallery Grid -->
    <div
        class="grid {{ $getGapClass() }} {{ $getGridColumnClasses() }} @if($layout === 'masonry') auto-rows-max @endif"
        id="gallery-{{ $uuid }}"
    >
        <template x-for="(image, index) in displayedImages" :key="index">
            <div
                class="group relative overflow-hidden rounded-lg @if($aspectRatio !== 'auto') {{ $getAspectRatioClass() }} @endif"
                x-show="!loading || index < 12"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
            >
                <!-- Loading Placeholder -->
                <div
                    x-show="!imageLoaded[index]"
                    class="absolute inset-0 {{ $getLoadingClass() }} flex items-center justify-center"
                >
                    @if($loadingStyle === 'spinner')
                        <div class="w-6 h-6 border-2 border-gray-300 border-t-blue-500 rounded-full animate-spin"></div>
                    @elseif($loadingStyle === 'skeleton')
                        <div class="w-full h-full bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200 dark:from-gray-700 dark:via-gray-600 dark:to-gray-700 animate-pulse"></div>
                    @endif
                </div>

                <!-- Image -->
                <img
                    :src="typeof image === 'string' ? image : (image.url || image.src || '')"
                    :alt="typeof image === 'string' ? `Image ${index + 1}` : (image.alt || `Image ${index + 1}`)"
                    class="w-full h-full object-cover transition-all duration-300 group-hover:scale-105"
                    :loading="index > 8 && lazyLoad ? 'lazy' : 'eager'"
                    @load="imageLoaded[index] = true"
                    @error="imageLoadError(index)"
                    x-show="imageLoaded[index]"
                />

                <!-- Overlay -->
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300">
                    <!-- Lightbox trigger -->
                    <template x-if="lightbox">
                        <button
                            @click="openLightbox(index)"
                            class="absolute inset-0 w-full h-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset"
                            :aria-label="`View full size image ${index + 1}`"
                        >
                            <span class="sr-only" x-text="`Open image ${index + 1} in lightbox`"></span>
                            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                </svg>
                            </div>
                        </button>
                    </template>
                </div>

                <!-- Caption -->
                <template x-if="showCaptions && typeof image === 'object' && image.caption">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/75 to-transparent text-white p-3">
                        <p class="text-sm font-medium" x-text="image.caption"></p>
                    </div>
                </template>
            </div>
        </template>
    </div>

    <!-- Empty State -->
    <div x-show="displayedImages.length === 0" class="text-center py-12">
        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <p class="text-gray-500 dark:text-gray-400">No images found matching the current filter.</p>
    </div>

    <!-- Pagination (if enabled) -->
    @if($itemsPerPage > 0)
        <div x-show="totalPages > 1" class="mt-8 flex justify-center">
            <nav class="flex items-center space-x-2" aria-label="Pagination">
                <button
                    @click="currentPage > 1 ? setPage(currentPage - 1) : null"
                    :disabled="currentPage === 1"
                    class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                >
                    Previous
                </button>

                <template x-for="page in visiblePages" :key="page">
                    <button
                        @click="setPage(page)"
                        class="px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200"
                        :class="page === currentPage ? 'bg-blue-500 text-white' : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-50 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white'"
                        x-text="page"
                    ></button>
                </template>

                <button
                    @click="currentPage < totalPages ? setPage(currentPage + 1) : null"
                    :disabled="currentPage === totalPages"
                    class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                >
                    Next
                </button>
            </nav>
        </div>
    @endif

    <!-- PhotoSwipe Lightbox Container -->
    <template x-if="lightbox">
        <div
            :id="`lightbox-${uuid}`"
            class="pswp-gallery hidden"
        >
            <template x-for="(image, index) in displayedImages" :key="index">
                <a
                    :href="typeof image === 'string' ? image : (image.url || image.src || '')"
                    :data-pswp-width="typeof image === 'object' ? (image.width || '1200') : '1200'"
                    :data-pswp-height="typeof image === 'object' ? (image.height || '800') : '800'"
                    target="_blank"
                >
                    <img :src="typeof image === 'string' ? image : (image.url || image.src || '')" :alt="typeof image === 'string' ? `Image ${index + 1}` : (image.alt || `Image ${index + 1}`)" style="display: none;">
                </a>
            </template>
        </div>
    </template>
</div>

<script>
function imageGallery(config) {
    return {
        // Configuration
        layout: config.layout || 'grid',
        lazyLoad: config.lazyLoad !== false,
        lightbox: config.lightbox !== false,
        filters: config.filters || null,
        images: config.images || [],
        uuid: config.uuid || '',
        itemsPerPage: config.itemsPerPage || 0,
        loadingStyle: config.loadingStyle || 'skeleton',
        showCaptions: config.showCaptions || false,

        // State
        loading: true,
        imageLoaded: {},
        activeFilter: null,
        currentPage: 1,
        lightboxInstance: null,

        // Computed properties
        get filteredImages() {
            if (!this.activeFilter || !this.filters) {
                return this.images;
            }

            return this.images.filter(image => {
                if (typeof image === 'object' && image.category) {
                    return image.category === this.activeFilter;
                }
                if (typeof image === 'object' && image.tags) {
                    return image.tags.includes(this.activeFilter);
                }
                return true;
            });
        },

        get displayedImages() {
            if (this.itemsPerPage === 0) {
                return this.filteredImages;
            }

            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.filteredImages.slice(start, end);
        },

        get totalPages() {
            if (this.itemsPerPage === 0) {
                return 1;
            }
            return Math.ceil(this.filteredImages.length / this.itemsPerPage);
        },

        get visiblePages() {
            const pages = [];
            const total = this.totalPages;
            const current = this.currentPage;

            if (total <= 7) {
                for (let i = 1; i <= total; i++) {
                    pages.push(i);
                }
            } else {
                pages.push(1);

                if (current > 4) {
                    pages.push('...');
                }

                const start = Math.max(2, current - 2);
                const end = Math.min(total - 1, current + 2);

                for (let i = start; i <= end; i++) {
                    pages.push(i);
                }

                if (current < total - 3) {
                    pages.push('...');
                }

                if (total > 1) {
                    pages.push(total);
                }
            }

            return pages.filter(p => p !== '...' || true);
        },

        init() {
            // Initialize image loading state
            this.images.forEach((image, index) => {
                this.imageLoaded[index] = false;
            });

            this.loading = false;

            // Initialize PhotoSwipe if available
            if (this.lightbox && typeof PhotoSwipeLightbox !== 'undefined') {
                this.$nextTick(() => {
                    this.initLightbox();
                });
            }

            // Initialize lazy loading intersection observer
            if (this.lazyLoad && 'IntersectionObserver' in window) {
                this.initIntersectionObserver();
            }
        },

        setActiveFilter(filter) {
            this.activeFilter = filter;
            this.currentPage = 1; // Reset to first page when filtering
        },

        setPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
            }
        },

        imageLoadError(index) {
            console.warn(`Failed to load image at index ${index}`);
            this.imageLoaded[index] = true; // Show placeholder
        },

        initLightbox() {
            try {
                this.lightboxInstance = new PhotoSwipeLightbox({
                    gallery: `#lightbox-${this.uuid}`,
                    children: 'a',
                    showHideAnimationType: 'fade',
                    pswpModule: PhotoSwipe
                });

                this.lightboxInstance.init();
            } catch (error) {
                console.warn('PhotoSwipe not available:', error);
                this.lightbox = false;
            }
        },

        openLightbox(index) {
            if (this.lightboxInstance && this.lightbox) {
                const items = document.querySelectorAll(`#lightbox-${this.uuid} a`);
                if (items[index]) {
                    items[index].click();
                }
            }
        },

        initIntersectionObserver() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                            observer.unobserve(img);
                        }
                    }
                });
            }, {
                rootMargin: '50px'
            });

            // Observe lazy loading images
            this.$nextTick(() => {
                const lazyImages = document.querySelectorAll(`#gallery-${this.uuid} img[loading="lazy"]`);
                lazyImages.forEach(img => observer.observe(img));
            });
        }
    }
}
</script>
