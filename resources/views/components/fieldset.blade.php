@props([
    'title' => null,
    'subtitle' => null,
    'icon' => null,
    'separator' => true,
    'bgColor' => 'bg-base-100',
    'textColor' => null,
    'border' => 'border border-base-300',
    'middle' => null,
    'actions' => null,
])

<div {{ $attributes->class([
        'rounded-lg shadow-sm',
        $bgColor,
        $textColor,
        $border
    ]) }}>

	{{-- Render the header only if a title, subtitle, or slot is provided --}}
	@if($title || $subtitle || $middle || $actions)
		<div class="p-4 sm:p-6">
			<x-artisanpack-header
				:title="$title"
				:subtitle="$subtitle"
				:icon="$icon"
				:separator="$separator"
				:middle="$middle"
				:actions="$actions"
				class="mb-0!" {{-- Remove default bottom margin from header --}}
			/>
		</div>
	@endif

	{{-- Render the slot content (form fields) --}}
	<div @class([
        'p-4 sm:p-6',
        'pt-0' => ($title || $subtitle || $middle || $actions) && $separator, // Remove top padding if header exists
    ])>
		{{ $slot }}
	</div>
</div>