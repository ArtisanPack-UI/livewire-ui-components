<div
        x-data="{
                steps: [],
                current: @entangle($attributes->wire('model')),
                init() {
                    // Fix weird issue when navigating back
                    document.addEventListener('livewire:navigating', () => {
                        document.querySelectorAll('.step').forEach(el =>  el.remove());
                    });
                }
        }"
    >
        <!-- STEP LABELS -->
        <nav aria-label="Progress">
            <ul
                class="steps [&>*:nth-child(2)]:before:hidden {{ $stepperClasses }}"
                role="list"
            >
                <template x-for="(step, index) in steps" :key="index">
                    <li
                        class="step"
                        :data-content="!step.icon ? step.dataContent || (index + 1) : ''"
                        :class="(index + 1 <= current) && '{{ $stepsColor }} ' + step.classes"
                        :aria-current="(index + 1) === current ? 'step' : null"
                        role="listitem"
                    >
                            <template x-if="step.icon">
                                <span x-html="step.icon" class="step-icon" aria-hidden="true"></span>
                            </template>
                            <span x-html="step.text"></span>
                            <span class="sr-only" x-text="(index + 1) < current ? ' (completed)' : (index + 1) === current ? ' (current)' : ' (upcoming)'"></span>
                    </li>
                </template>
            </ul>
        </nav>

        <!-- STEP PANELS-->
        <div {{ $attributes->whereDoesntStartWith('wire') }}>
            {{ $slot }}
        </div>

        <!-- Force Tailwind compile steps color -->
        <span class="hidden step-primary step-error step-success step-neutral step-info step-accent"></span>
    </div>
