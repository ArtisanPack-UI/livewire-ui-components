<div
    x-data="{
        currentIndex: 0,
        images: @js($images),
        autoPlay: {{ $autoPlay ? 'true' : 'false' }},
        interval: {{ $autoPlayInterval }},
        loop: {{ $loop ? 'true' : 'false' }},
        pauseOnHover: {{ $pauseOnHover ? 'true' : 'false' }},
        transition: '{{ $transition }}',
        duration: {{ $transitionDuration }},
        enableLightbox: {{ $enableLightbox ? 'true' : 'false' }},
        uuid: '{{ $uuid }}',
        
        // State
        loading: true,
        autoPlayTimer: null,
        isPaused: false,
        touchStartX: 0,
        touchEndX: 0,
        lightbox: null,

        init() {
            this.loading = false;

            if (this.autoPlay) {
                this.startAutoPlay();
            }

            if (this.enableLightbox && typeof PhotoSwipeLightbox !== 'undefined') {
                this.initLightbox();
            }

            // Preload next images
            this.$nextTick(() => {
                this.preloadImages();
            });
        },

        next() {
            if (this.currentIndex < this.images.length - 1) {
                this.currentIndex++;
            } else if (this.loop) {
                this.currentIndex = 0;
            }
        },

        previous() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
            } else if (this.loop) {
                this.currentIndex = this.images.length - 1;
            }
        },

        goToSlide(index) {
            this.currentIndex = index;
        },

        startAutoPlay() {
            if (!this.autoPlay || this.images.length <= 1) return;

            this.autoPlayTimer = setInterval(() => {
                if (!this.isPaused) {
                    this.next();
                }
            }, this.interval);
        },

        stopAutoPlay() {
            if (this.autoPlayTimer) {
                clearInterval(this.autoPlayTimer);
                this.autoPlayTimer = null;
            }
        },

        pauseAutoPlay() {
            if (this.pauseOnHover) {
                this.isPaused = true;
            }
        },

        resumeAutoPlay() {
            if (this.pauseOnHover) {
                this.isPaused = false;
            }
        },

        handleTouchStart(e) {
            this.touchStartX = e.touches[0].clientX;
        },

        handleTouchMove(e) {
            this.touchEndX = e.touches[0].clientX;
        },

        handleTouchEnd(e) {
            const threshold = 50;
            const diff = this.touchStartX - this.touchEndX;

            if (Math.abs(diff) > threshold) {
                if (diff > 0) {
                    this.next();
                } else {
                    this.previous();
                }
            }
        },

        preloadImages() {
            // Preload next and previous images for smoother transitions
            const nextIndex = (this.currentIndex + 1) % this.images.length;
            const prevIndex = this.currentIndex === 0 ? this.images.length - 1 : this.currentIndex - 1;

            [nextIndex, prevIndex].forEach(index => {
                const img = new Image();
                const imageData = this.images[index];
                img.src = typeof imageData === 'string' ? imageData : (imageData.url || imageData.src || '');
            });
        },

        initLightbox() {
            try {
                this.lightbox = new PhotoSwipeLightbox({
                    gallery: `#lightbox-${this.uuid}`,
                    children: 'a',
                    showHideAnimationType: 'fade',
                    pswpModule: PhotoSwipe
                });

                this.lightbox.init();
            } catch (error) {
                console.warn('PhotoSwipe not available:', error);
                this.enableLightbox = false;
            }
        },

        openLightbox(index) {
            if (this.lightbox && this.enableLightbox) {
                // Navigate to specific image in lightbox
                const items = document.querySelectorAll(`#lightbox-${this.uuid} a`);
                if (items[index]) {
                    items[index].click();
                }
            }
        }
    }"
    x-init="init()"
    {{ $attributes->class("relative w-full select-none") }}
    @keydown.arrow-left="previous()"
    @keydown.arrow-right="next()"
    @mouseenter="pauseAutoPlay()"
    @mouseleave="resumeAutoPlay()"
    tabindex="0"
    role="region"
    aria-label="Image slider with {{ count($images) }} images"
>
    <!-- Loading State -->
    <div x-show="loading" class="absolute inset-0 bg-gray-100 dark:bg-gray-800 animate-pulse rounded-lg flex items-center justify-center">
        <div class="w-8 h-8 border-2 border-gray-300 border-t-blue-500 rounded-full animate-spin"></div>
    </div>

    <!-- Image Container -->
    <div class="relative overflow-hidden rounded-lg {{ $getAspectRatioClass() }}">
        <!-- Images Track -->
        <div
            class="flex {{ $getTransitionClass() }} ease-in-out"
            :class="transition === 'slide' ? 'duration-{{ $transitionDuration }}' : ''"
            :style="transition === 'slide' ? 'transform: translateX(-' + (currentIndex * 100) + '%)' : ''"
        >
            @foreach($images as $index => $image)
                <div
                    class="w-full flex-shrink-0 relative"
                    :class="transition === 'fade' ? (currentIndex === {{ $index }} ? 'opacity-100 duration-{{ $transitionDuration }}' : 'opacity-0 absolute inset-0 duration-{{ $transitionDuration }}') : ''"
                >
                    <img
                        src="{{ is_array($image) ? $image['url'] ?? $image['src'] ?? '' : $image }}"
                        alt="{{ is_array($image) ? ($image['alt'] ?? 'Image ' . ($index + 1)) : 'Image ' . ($index + 1) }}"
                        class="w-full h-full object-cover"
                        @if($index > 0)
                            loading="lazy"
                        @endif
                        onload="this.parentNode.parentNode.parentNode.parentNode.querySelector('[x-show=loading]').style.display = 'none'"
                    />

                    @if($enableLightbox)
                        <button
                            @click="openLightbox({{ $index }})"
                            class="absolute inset-0 w-full h-full bg-black/0 hover:bg-black/10 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset"
                            aria-label="View full size image {{ $index + 1 }}"
                        >
                            <span class="sr-only">Open image {{ $index + 1 }} in lightbox</span>
                        </button>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Navigation Arrows -->
        @if($withArrows && count($images) > 1)
            <button
                @click="previous()"
                class="absolute left-2 top-1/2 -translate-y-1/2 z-10 bg-white/80 dark:bg-gray-800/80 hover:bg-white hover:scale-105 dark:hover:bg-gray-800 p-2 rounded-full shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
                :disabled="!loop && currentIndex === 0"
                aria-label="Previous image"
            >
                <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            <button
                @click="next()"
                class="absolute right-2 top-1/2 -translate-y-1/2 z-10 bg-white/80 dark:bg-gray-800/80 hover:bg-white hover:scale-105 dark:hover:bg-gray-800 p-2 rounded-full shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
                :disabled="!loop && currentIndex === images.length - 1"
                aria-label="Next image"
            >
                <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        @endif
    </div>

    <!-- Indicators -->
    @if($withIndicators && count($images) > 1)
        <div class="flex justify-center space-x-2 mt-4" role="tablist" aria-label="Image indicators">
            @foreach($images as $index => $image)
                <button
                    @click="goToSlide({{ $index }})"
                    class="w-3 h-3 rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    :class="currentIndex === {{ $index }} ? 'bg-blue-500' : 'bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500'"
                    role="tab"
                    :aria-selected="currentIndex === {{ $index }}"
                    aria-label="Go to image {{ $index + 1 }}"
                ></button>
            @endforeach
        </div>
    @endif

    <!-- Counter -->
    @if($showCounter && count($images) > 1)
        <div class="absolute top-2 right-2 bg-black/50 text-white px-2 py-1 rounded text-sm font-medium">
            <span x-text="currentIndex + 1"></span>/<span>{{ count($images) }}</span>
        </div>
    @endif

    <!-- Touch/Swipe Areas -->
    <div
        class="absolute inset-0 touch-pan-y"
        x-on:touchstart="handleTouchStart($event)"
        x-on:touchmove="handleTouchMove($event)"
        x-on:touchend="handleTouchEnd($event)"
    ></div>

    <!-- PhotoSwipe Lightbox Container -->
    @if($enableLightbox)
        <div
            id="lightbox-{{ $uuid }}"
            class="pswp-gallery pswp-gallery--single-column hidden"
        >
            @foreach($images as $index => $image)
                <a
                    href="{{ is_array($image) ? $image['url'] ?? $image['src'] ?? '' : $image }}"
                    data-pswp-width="{{ is_array($image) ? ($image['width'] ?? '1200') : '1200' }}"
                    data-pswp-height="{{ is_array($image) ? ($image['height'] ?? '800') : '800' }}"
                    target="_blank"
                >
                    <img src="{{ is_array($image) ? $image['url'] ?? $image['src'] ?? '' : $image }}" alt="{{ is_array($image) ? ($image['alt'] ?? 'Image ' . ($index + 1)) : 'Image ' . ($index + 1) }}" style="display: none;">
                </a>
            @endforeach
        </div>
    @endif
</div>

