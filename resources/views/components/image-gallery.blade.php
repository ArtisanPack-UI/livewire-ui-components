<div class="w-full">
    <!-- Simple Gallery Grid -->
    <div class="grid {{ $getGapClass() }} {{ $getGridColumnClasses() }}">
        @foreach($images as $index => $image)
            <div class="group relative overflow-hidden rounded-lg {{ $getAspectRatioClass() }}">
                <img
                    src="{{ is_string($image) ? $image : ($image['url'] ?? $image['src'] ?? '') }}"
                    alt="Image {{ $index + 1 }}"
                    class="w-full h-full object-cover transition-all duration-300 group-hover:scale-105"
                />
            </div>
        @endforeach
    </div>
</div>