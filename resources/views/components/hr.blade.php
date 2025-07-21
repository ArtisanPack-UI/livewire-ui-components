<div class="h-[2px] border-t-[length:var(--border)] border-t-base-content/10 my-5">
    <progress
        class="progress progress-primary hidden h-[1px]"
        wire:loading.class="!h-[length:var(--border)] !block"

        @if($progressTarget())
            wire:target="{{ $progressTarget() }}"
        @endif></progress>
</div>
