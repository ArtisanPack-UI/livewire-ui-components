<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImageSlider Component Demo - ArtisanPack UI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/photoswipe@5/dist/photoswipe.css">
    <script type="module">
        import PhotoSwipeLightbox from 'https://unpkg.com/photoswipe@5/dist/photoswipe-lightbox.esm.js';
        
        const lightbox = new PhotoSwipeLightbox({
            gallery: '.image-slider',
            children: 'a',
            pswpModule: () => import('https://unpkg.com/photoswipe@5/dist/photoswipe.esm.js')
        });
        lightbox.init();
    </script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">ImageSlider Component</h1>
            <p class="text-lg text-gray-600">A powerful carousel/slider component for sequential image navigation with advanced features</p>
            <div class="mt-4">
                <a href="/components/image-gallery" class="text-blue-600 hover:text-blue-800 underline">
                    View ImageGallery Component →
                </a>
            </div>
        </div>

        <!-- Default Slider -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-4">Default Configuration</h2>
            <p class="text-gray-600 mb-6">Basic slider with navigation arrows and indicators enabled</p>
            
            <x-image-slider :images="$images" />
        </section>

        <!-- Navigation Variations -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-4">Navigation Controls</h2>
            
            <div class="space-y-8">
                <div>
                    <h3 class="text-lg font-medium mb-3">Without Arrows</h3>
                    <x-image-slider :images="$images" :with-arrows="false" />
                </div>

                <div>
                    <h3 class="text-lg font-medium mb-3">Without Indicators</h3>
                    <x-image-slider :images="$images" :with-indicators="false" />
                </div>

                <div>
                    <h3 class="text-lg font-medium mb-3">No Navigation</h3>
                    <x-image-slider :images="$images" :with-arrows="false" :with-indicators="false" />
                </div>
            </div>
        </section>

        <!-- Auto-play Features -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-4">Auto-play Configuration</h2>
            
            <div class="space-y-8">
                <div>
                    <h3 class="text-lg font-medium mb-3">Auto-play (5 second interval)</h3>
                    <p class="text-sm text-gray-500 mb-3">Automatically advances slides, pauses on hover</p>
                    <x-image-slider 
                        :images="$images" 
                        :auto-play="true" 
                        :auto-play-interval="5000"
                        :pause-on-hover="true"
                    />
                </div>

                <div>
                    <h3 class="text-lg font-medium mb-3">Fast Auto-play (2 second interval)</h3>
                    <p class="text-sm text-gray-500 mb-3">Quick succession, doesn't pause on hover</p>
                    <x-image-slider 
                        :images="$images" 
                        :auto-play="true" 
                        :auto-play-interval="2000"
                        :pause-on-hover="false"
                    />
                </div>
            </div>
        </section>

        <!-- Aspect Ratio Options -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-4">Aspect Ratio Variations</h2>
            
            <div class="space-y-8">
                <div>
                    <h3 class="text-lg font-medium mb-3">16:9 (Widescreen)</h3>
                    <x-image-slider :images="$images" aspect-ratio="16:9" />
                </div>

                <div>
                    <h3 class="text-lg font-medium mb-3">Square (1:1)</h3>
                    <x-image-slider :images="$images" aspect-ratio="square" />
                </div>

                <div>
                    <h3 class="text-lg font-medium mb-3">4:3 (Traditional)</h3>
                    <x-image-slider :images="$images" aspect-ratio="4:3" />
                </div>

                <div>
                    <h3 class="text-lg font-medium mb-3">3:4 (Portrait)</h3>
                    <x-image-slider :images="$images" aspect-ratio="3:4" />
                </div>
            </div>
        </section>

        <!-- Transition Effects -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-4">Transition Effects</h2>
            
            <div class="space-y-8">
                <div>
                    <h3 class="text-lg font-medium mb-3">Slide Transition</h3>
                    <x-image-slider :images="$images" transition="slide" />
                </div>

                <div>
                    <h3 class="text-lg font-medium mb-3">Fade Transition</h3>
                    <x-image-slider :images="$images" transition="fade" />
                </div>
            </div>
        </section>

        <!-- Custom ID Example -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-4">Custom ID</h2>
            <p class="text-gray-600 mb-6">Using a custom ID for the slider</p>
            
            <x-image-slider :images="$images" id="custom-slider" />
        </section>

        <!-- Advanced Configuration -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-4">Advanced Configuration</h2>
            <p class="text-gray-600 mb-6">Combining multiple features</p>
            
            <x-image-slider 
                :images="$images" 
                id="advanced-slider"
                :with-arrows="true"
                :with-indicators="true"
                :auto-play="true"
                :auto-play-interval="4000"
                :pause-on-hover="true"
                aspect-ratio="16:9"
                transition="slide"
            />
        </section>

        <!-- Usage Examples -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-4">Usage Examples</h2>
            
            <div class="bg-gray-800 text-gray-100 p-6 rounded-lg overflow-x-auto">
                <pre><code>{{-- Basic Usage --}}
&lt;x-image-slider :images="$images" /&gt;

{{-- With Navigation Controls --}}
&lt;x-image-slider 
    :images="$images" 
    :with-arrows="true" 
    :with-indicators="true" 
/&gt;

{{-- With Auto-play --}}
&lt;x-image-slider 
    :images="$images" 
    :auto-play="true" 
    :auto-play-interval="5000"
    :pause-on-hover="true"
/&gt;

{{-- Full Configuration --}}
&lt;x-image-slider 
    :images="$images" 
    id="my-slider"
    :with-arrows="true"
    :with-indicators="true"
    :auto-play="true"
    :auto-play-interval="4000"
    :pause-on-hover="true"
    aspect-ratio="16:9"
    transition="slide"
/&gt;

{{-- Available Props --}}
{{-- :images (required) - Array of image URLs --}}
{{-- :with-arrows (optional) - Show navigation arrows (default: true) --}}
{{-- :with-indicators (optional) - Show position indicators (default: true) --}}
{{-- :auto-play (optional) - Enable auto-progression (default: false) --}}
{{-- :auto-play-interval (optional) - Auto-play interval in ms (default: 5000) --}}
{{-- :pause-on-hover (optional) - Pause auto-play on hover (default: true) --}}
{{-- aspect-ratio (optional) - '16:9', 'square', '4:3', '3:4' (default: '16:9') --}}
{{-- transition (optional) - 'slide', 'fade' (default: 'slide') --}}
{{-- id (optional) - Custom ID for the slider --}}</code></pre>
            </div>
        </section>

        <!-- Features -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-4">Features</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="font-semibold mb-2">🏹 Navigation Controls</h3>
                    <p class="text-gray-600">Previous/Next arrows with proper accessibility</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="font-semibold mb-2">🎯 Position Indicators</h3>
                    <p class="text-gray-600">Dot indicators showing current position and total count</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="font-semibold mb-2">⏯️ Auto-play Support</h3>
                    <p class="text-gray-600">Optional automatic progression with configurable timing</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="font-semibold mb-2">📱 Touch/Swipe</h3>
                    <p class="text-gray-600">Mobile-friendly touch navigation</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="font-semibold mb-2">⌨️ Keyboard Navigation</h3>
                    <p class="text-gray-600">Arrow key support for accessibility</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="font-semibold mb-2">🔍 Lightbox Integration</h3>
                    <p class="text-gray-600">PhotoSwipe lightbox for full-screen viewing</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="font-semibold mb-2">🎨 Flexible Aspects</h3>
                    <p class="text-gray-600">Multiple aspect ratio options</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="font-semibold mb-2">✨ Smooth Transitions</h3>
                    <p class="text-gray-600">Slide and fade transition effects</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="font-semibold mb-2">♿ Accessibility</h3>
                    <p class="text-gray-600">WCAG compliant with proper ARIA labels</p>
                </div>
            </div>
        </section>
    </div>
</body>
</html>