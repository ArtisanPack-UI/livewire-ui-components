<div
    class="hidden"
    x-init="steps.push({ step: '{{ $step }}', text: '{{ $text }}', classes: '{{ $stepClasses }}' @if($icon) , icon: {{ json_encode($iconHTML()) }}  @endif @if($dataContent), dataContent: '{{ $dataContent }}' @endif })"
></div>

<div x-show="current == '{{ $step }}'" {{ $attributes->class("px-1") }} >
    {{ $slot }}
</div>
