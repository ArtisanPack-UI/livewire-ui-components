@php
    $sizeClasses = $sizeClasses();
    $layoutClasses = $layoutClasses();
    $canAnimate = $canAnimate();
    $numericValue = $numericValue();
    $valueFormat = $valueFormat();
@endphp

<div
    {{ $attributes->class([
        "rounded-lg w-full",
        "bg-base-100" => !$glass,
        $sizeClasses['container'],
        "lg:tooltip $tooltipPosition" => $tooltip,
        $glassClasses() => $glass,
    ]) }}

    @if($tooltip)
        data-tip="{{ $tooltip }}"
    @endif

    @if($glassStyle())
        style="{{ $glassStyle() }}"
    @endif

    @if($canAnimate)
        x-data="{
            currentValue: {{ $numericValue }},
            targetValue: {{ $numericValue }},
            displayValue: '{{ $value }}',
            isAnimating: false,
            prefersReducedMotion: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
            duration: {{ $animateDuration }},
            prefix: '{{ addslashes($valueFormat['prefix']) }}',
            suffix: '{{ addslashes($valueFormat['suffix']) }}',
            decimals: {{ $valueFormat['decimals'] }},
            useCommas: {{ $valueFormat['useCommas'] ? 'true' : 'false' }},

            formatNumber(num) {
                let formatted = num.toFixed(this.decimals);
                if (this.useCommas) {
                    const parts = formatted.split('.');
                    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    formatted = parts.join('.');
                }
                return this.prefix + formatted + this.suffix;
            },

            animateValue(from, to) {
                if (this.prefersReducedMotion || from === to) {
                    this.currentValue = to;
                    this.displayValue = this.formatNumber(to);
                    return;
                }

                this.isAnimating = true;
                const startTime = performance.now();
                const diff = to - from;

                const easeOutQuart = (t) => 1 - Math.pow(1 - t, 4);

                const step = (currentTime) => {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / this.duration, 1);
                    const easedProgress = easeOutQuart(progress);

                    this.currentValue = from + (diff * easedProgress);
                    this.displayValue = this.formatNumber(this.currentValue);

                    if (progress < 1) {
                        requestAnimationFrame(step);
                    } else {
                        this.currentValue = to;
                        this.displayValue = this.formatNumber(to);
                        this.isAnimating = false;
                    }
                };

                requestAnimationFrame(step);
            },

            init() {
                this.displayValue = this.formatNumber(this.targetValue);

                // Listen for Livewire updates
                this.$watch('targetValue', (newVal, oldVal) => {
                    if (newVal !== oldVal) {
                        this.animateValue(oldVal, newVal);
                    }
                });

                // Listen for reduced motion preference changes
                window.matchMedia('(prefers-reduced-motion: reduce)').addEventListener('change', (e) => {
                    this.prefersReducedMotion = e.matches;
                });
            }
        }"
        x-init="init()"
        x-effect="targetValue = {{ $numericValue }}"
    @endif
>
    <div class="{{ $layoutClasses['container'] }}">
        {{-- Icon First (left/top) --}}
        @if($icon && $shouldRenderIconFirst())
            <div class="{{ $color }}">
                <x-artisanpack-icon :name="$icon" :class="$sizeClasses['icon']" />
            </div>
        @endif

        <div class="{{ $layoutClasses['content'] }}">
            {{-- Title First (top position) --}}
            @if($title && $shouldRenderTitleFirst())
                <div class="text-base-content/50 whitespace-nowrap {{ $sizeClasses['title'] }}">
                    {{ $title }}
                </div>
            @endif

            {{-- Value/Slot --}}
            <div class="font-black {{ $sizeClasses['value'] }}">
                @if($canAnimate)
                    <span x-text="displayValue"></span>
                @else
                    {{ $value ?? $slot }}
                @endif
            </div>

            {{-- Title Last (bottom position) --}}
            @if($title && !$shouldRenderTitleFirst())
                <div class="text-base-content/50 whitespace-nowrap {{ $sizeClasses['title'] }}">
                    {{ $title }}
                </div>
            @endif

            {{-- Description --}}
            @if($description)
                <div class="stat-desc {{ $sizeClasses['description'] }}">
                    {{ $description }}
                </div>
            @endif

            {{-- Trend Indicator --}}
            @if($hasChange())
                <div class="flex items-center gap-1 mt-1 {{ $changeColorClasses() }} {{ $sizeClasses['description'] }}">
                    <x-artisanpack-icon :name="$changeIcon()" class="w-4 h-4" />
                    <span class="font-medium">{{ $formattedChange() }}</span>
                    @if($changeLabel)
                        <span class="text-base-content/50">{{ $changeLabel }}</span>
                    @endif
                </div>
            @endif

            {{-- Sparkline --}}
            @if($hasSparkline())
                <div class="mt-2 w-full overflow-hidden">
                    <x-artisanpack-sparkline
                        :data="$sparklineData"
                        :type="$sparklineType"
                        :color="$sparklineColor"
                        :height="$sparklineHeight()"
                        width="100%"
                        class="w-full block"
                    />
                </div>
            @endif
        </div>

        {{-- Icon Last (right/bottom) --}}
        @if($icon && !$shouldRenderIconFirst())
            <div class="{{ $color }}">
                <x-artisanpack-icon :name="$icon" :class="$sizeClasses['icon']" />
            </div>
        @endif
    </div>
</div>
