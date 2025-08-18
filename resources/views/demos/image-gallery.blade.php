<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImageGallery Component Demo - ArtisanPack UI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/photoswipe@5/dist/photoswipe.css">
    <script type="module">
        import PhotoSwipeLightbox from 'https://unpkg.com/photoswipe@5/dist/photoswipe-lightbox.esm.js';
        
        const lightbox = new PhotoSwipeLightbox({
            gallery: '.image-gallery',
            children: 'a',
            pswpModule: () => import('https://unpkg.com/photoswipe@5/dist/photoswipe.esm.js')
        });
        lightbox.init();
    </script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">ImageGallery Component</h1>
            <p class="text-lg text-gray-600">A responsive grid-based image gallery component for displaying collections of images</p>
            <div class="mt-4">
                <a href="/components/image-slider" class="text-blue-600 hover:text-blue-800 underline">
                    View ImageSlider Component →
                </a>
            </div>
        </div>

        <!-- Default Gallery -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-4">Default Configuration</h2>
            <p class="text-gray-600 mb-6">Basic grid layout with default settings (3 columns, medium gap, landscape aspect ratio)</p>
            
            <x-image-gallery :images="$images" />
        </section>

        <!-- Different Column Layouts -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-4">Column Variations</h2>
            
            <div class="space-y-8">
                <div>
                    <h3 class="text-lg font-medium mb-3">2 Columns</h3>
                    <x-image-gallery :images="array_slice($images, 0, 4)" :columns="2" />
                </div>

                <div>
                    <h3 class="text-lg font-medium mb-3">4 Columns</h3>
                    <x-image-gallery :images="$images" :columns="4" />
                </div>

                <div>
                    <h3 class="text-lg font-medium mb-3">5 Columns</h3>
                    <x-image-gallery :images="$images" :columns="5" />
                </div>
            </div>
        </section>

        <!-- Aspect Ratio Variations -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-4">Aspect Ratio Options</h2>
            
            <div class="space-y-8">
                <div>
                    <h3 class="text-lg font-medium mb-3">Square (1:1)</h3>
                    <x-image-gallery :images="array_slice($images, 0, 4)" aspect-ratio="square" />
                </div>

                <div>
                    <h3 class="text-lg font-medium mb-3">Portrait (3:4)</h3>
                    <x-image-gallery :images="array_slice($images, 0, 3)" aspect-ratio="portrait" />
                </div>

                <div>
                    <h3 class="text-lg font-medium mb-3">Auto (Natural Image Ratio)</h3>
                    <x-image-gallery :images="array_slice($images, 0, 6)" aspect-ratio="auto" />
                </div>
            </div>
        </section>

        <!-- Gap Variations -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-4">Gap Sizes</h2>
            
            <div class="space-y-8">
                <div>
                    <h3 class="text-lg font-medium mb-3">No Gap</h3>
                    <x-image-gallery :images="array_slice($images, 0, 6)" gap="none" />
                </div>

                <div>
                    <h3 class="text-lg font-medium mb-3">Small Gap</h3>
                    <x-image-gallery :images="array_slice($images, 0, 6)" gap="sm" />
                </div>

                <div>
                    <h3 class="text-lg font-medium mb-3">Large Gap</h3>
                    <x-image-gallery :images="array_slice($images, 0, 6)" gap="lg" />
                </div>

                <div>
                    <h3 class="text-lg font-medium mb-3">Extra Large Gap</h3>
                    <x-image-gallery :images="array_slice($images, 0, 6)" gap="xl" />
                </div>
            </div>
        </section>

        <!-- Custom ID Example -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-4">Custom ID</h2>
            <p class="text-gray-600 mb-6">Using a custom ID for the gallery</p>
            
            <x-image-gallery :images="array_slice($images, 0, 4)" id="custom-gallery" />
        </section>

        <!-- Usage Examples -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-4">Usage Examples</h2>
            
            <div class="bg-gray-800 text-gray-100 p-6 rounded-lg overflow-x-auto">
                <pre><code>{{-- Basic Usage --}}
&lt;x-image-gallery :images="$images" /&gt;

{{-- With Custom Configuration --}}
&lt;x-image-gallery 
    :images="$images" 
    :columns="4" 
    aspect-ratio="square" 
    gap="lg" 
    id="my-gallery" 
/&gt;

{{-- Available Props --}}
{{-- :images (required) - Array of image URLs --}}
{{-- :columns (optional) - Number of columns (1-6, default: 3) --}}
{{-- aspect-ratio (optional) - 'landscape', 'square', 'portrait', 'auto' (default: 'landscape') --}}
{{-- gap (optional) - 'none', 'sm', 'md', 'lg', 'xl' (default: 'md') --}}
{{-- id (optional) - Custom ID for the gallery --}}</code></pre>
            </div>
        </section>

        <!-- Features -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-4">Features</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="font-semibold mb-2">📱 Responsive Design</h3>
                    <p class="text-gray-600">Automatically adjusts columns for different screen sizes</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="font-semibold mb-2">🔍 Lightbox Integration</h3>
                    <p class="text-gray-600">PhotoSwipe lightbox for full-screen image viewing</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="font-semibold mb-2">🎨 Customizable Layout</h3>
                    <p class="text-gray-600">Flexible column count, aspect ratios, and spacing options</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="font-semibold mb-2">♿ Accessibility</h3>
                    <p class="text-gray-600">WCAG compliant with proper ARIA labels and keyboard navigation</p>
                </div>
            </div>
        </section>
    </div>
</body>
</html>