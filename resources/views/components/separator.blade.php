@if($vertical)
    {{-- Vertical Separator --}}
    <div class="flex flex-col items-center h-full">
        @if($image)
            <div class="flex-1 w-[2px] {{ $getColorClasses() }}"
                 @if(str_starts_with($color ?? '', '#')) style="border-color: {{ $color }}" @endif></div>
            <div class="flex-shrink-0 px-2">
                <img src="{{ $image }}" alt="" class="w-8 h-8 object-contain">
            </div>
            <div class="flex-1 w-[2px] border-l-[length:var(--border)] {{ $getColorClasses() }}"
                 @if(str_starts_with($color ?? '', '#')) style="border-color: {{ $color }}" @endif></div>
        @else
            <div class="w-[2px] h-full border-l-[length:var(--border)] {{ $getColorClasses() }}"
                 @if(str_starts_with($color ?? '', '#')) style="border-color: {{ $color }}" @endif></div>
        @endif

        @if($progress)
            <progress
                class="progress {{ $getProgressColorClasses() }} hidden w-[1px] absolute z-10"
                wire:loading.class="!w-[length:var(--border)] !block"
                @if($progressTarget())
                    wire:target="{{ $progressTarget() }}"
                @endif></progress>
        @endif
    </div>
@else
    {{-- Horizontal Separator --}}
    <div class="relative my-5">
        @if($image)
            <div class="flex items-center">
                <div class="flex-1 h-[2px] border-t-[length:var(--border)] {{ $getColorClasses() }}"
                     @if(str_starts_with($color ?? '', '#')) style="border-color: {{ $color }}" @endif></div>
                <div class="flex-shrink-0 px-4">
                    <img src="{{ $image }}" alt="" class="w-8 h-8 object-contain">
                </div>
                <div class="flex-1 h-[2px] border-t-[length:var(--border)] {{ $getColorClasses() }}"
                     @if(str_starts_with($color ?? '', '#')) style="border-color: {{ $color }}" @endif></div>
            </div>
        @else
            <div class="h-[2px] border-t-[length:var(--border)] {{ $getColorClasses() }}"
                 @if(str_starts_with($color ?? '', '#')) style="border-color: {{ $color }}" @endif></div>
        @endif

        @if($progress)
            <progress
                class="progress {{ $getProgressColorClasses() }} hidden h-[1px] absolute top-0 left-0 w-full z-10"
                wire:loading.class="!h-[length:var(--border)] !block"
                @if($progressTarget())
                    wire:target="{{ $progressTarget() }}"
                @endif></progress>
        @endif
    </div>
@endif
