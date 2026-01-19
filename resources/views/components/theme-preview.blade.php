<div
    x-data="{
        copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                $dispatch('notify', { message: '{{ __( 'Copied to clipboard!' ) }}', type: 'success' });
            });
        }
    }"
    @copy-to-clipboard.window="copyToClipboard($event.detail.text)"
    class="min-h-screen"
    :class="$wire.darkMode ? 'bg-gray-900 text-white' : 'bg-gray-50 text-gray-900'"
>
    {{-- Live Preview CSS Variables --}}
    <style>
        {!! $this->previewCssVariables !!}
    </style>

    {{-- Header --}}
    <header class="border-b" :class="$wire.darkMode ? 'border-gray-700 bg-gray-800' : 'border-gray-200 bg-white'">
        <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold">{{ __( 'Theme Preview Tool' ) }}</h1>
                    <p class="mt-1 text-sm" :class="$wire.darkMode ? 'text-gray-400' : 'text-gray-500'">
                        {{ __( 'Customize and preview your theme in real-time' ) }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Dark Mode Toggle --}}
                    <label class="flex items-center gap-2 cursor-pointer">
                        <span class="text-sm">{{ __( 'Dark Mode' ) }}</span>
                        <input
                            type="checkbox"
                            wire:model.live="darkMode"
                            class="toggle toggle-sm"
                        />
                    </label>

                    {{-- Reset Button --}}
                    <button
                        wire:click="resetTheme"
                        class="btn btn-sm btn-outline"
                        :class="$wire.darkMode ? 'btn-ghost' : ''"
                    >
                        {{ __( 'Reset' ) }}
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Panel: Controls --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Section Navigation --}}
                <div class="tabs tabs-boxed" :class="$wire.darkMode ? 'bg-gray-800' : 'bg-gray-200'">
                    <button
                        wire:click="$set('activeSection', 'colors')"
                        @class(['tab', 'tab-active' => $activeSection === 'colors'])
                    >
                        {{ __( 'Colors' ) }}
                    </button>
                    <button
                        wire:click="$set('activeSection', 'glass')"
                        @class(['tab', 'tab-active' => $activeSection === 'glass'])
                    >
                        {{ __( 'Glass' ) }}
                    </button>
                    <button
                        wire:click="$set('activeSection', 'accessibility')"
                        @class(['tab', 'tab-active' => $activeSection === 'accessibility'])
                    >
                        {{ __( 'A11y' ) }}
                    </button>
                    <button
                        wire:click="$set('activeSection', 'export')"
                        @class(['tab', 'tab-active' => $activeSection === 'export'])
                    >
                        {{ __( 'Export' ) }}
                    </button>
                </div>

                {{-- Colors Section --}}
                @if($activeSection === 'colors')
                    <div class="card" :class="$wire.darkMode ? 'bg-gray-800' : 'bg-white shadow-sm'">
                        <div class="card-body">
                            <h2 class="card-title text-lg">{{ __( 'Color Selection' ) }}</h2>

                            {{-- Use Custom Colors Toggle --}}
                            <label class="flex items-center gap-2 cursor-pointer mb-4">
                                <input
                                    type="checkbox"
                                    wire:model.live="useCustomColors"
                                    class="checkbox checkbox-sm checkbox-primary"
                                />
                                <span class="text-sm">{{ __( 'Use Custom Hex Colors' ) }}</span>
                            </label>

                            @if($useCustomColors)
                                {{-- Custom Hex Color Inputs --}}
                                <div class="space-y-4">
                                    <div>
                                        <label class="label">
                                            <span class="label-text">{{ __( 'Primary Color' ) }}</span>
                                        </label>
                                        <div class="flex gap-2">
                                            <input
                                                type="color"
                                                wire:model.live.debounce.300ms="primaryHex"
                                                class="w-12 h-10 rounded cursor-pointer"
                                            />
                                            <input
                                                type="text"
                                                wire:model.live.debounce.300ms="primaryHex"
                                                placeholder="#0ea5e9"
                                                class="input input-bordered input-sm flex-1"
                                                :class="$wire.darkMode ? 'bg-gray-700' : ''"
                                            />
                                        </div>
                                    </div>

                                    <div>
                                        <label class="label">
                                            <span class="label-text">{{ __( 'Secondary Color' ) }}</span>
                                        </label>
                                        <div class="flex gap-2">
                                            <input
                                                type="color"
                                                wire:model.live.debounce.300ms="secondaryHex"
                                                class="w-12 h-10 rounded cursor-pointer"
                                            />
                                            <input
                                                type="text"
                                                wire:model.live.debounce.300ms="secondaryHex"
                                                placeholder="#64748b"
                                                class="input input-bordered input-sm flex-1"
                                                :class="$wire.darkMode ? 'bg-gray-700' : ''"
                                            />
                                        </div>
                                    </div>

                                    <div>
                                        <label class="label">
                                            <span class="label-text">{{ __( 'Accent Color' ) }}</span>
                                        </label>
                                        <div class="flex gap-2">
                                            <input
                                                type="color"
                                                wire:model.live.debounce.300ms="accentHex"
                                                class="w-12 h-10 rounded cursor-pointer"
                                            />
                                            <input
                                                type="text"
                                                wire:model.live.debounce.300ms="accentHex"
                                                placeholder="#f59e0b"
                                                class="input input-bordered input-sm flex-1"
                                                :class="$wire.darkMode ? 'bg-gray-700' : ''"
                                            />
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{-- Tailwind Color Selection --}}
                                <div class="space-y-4">
                                    @foreach(['primary' => 'Primary', 'secondary' => 'Secondary', 'accent' => 'Accent'] as $key => $label)
                                        <div>
                                            <label class="label">
                                                <span class="label-text">{{ __( $label . ' Color' ) }}</span>
                                            </label>
                                            <div class="grid grid-cols-7 gap-1">
                                                @foreach($tailwindColors as $name => $hex)
                                                    <button
                                                        wire:click="$set('{{ $key }}Color', '{{ $name }}')"
                                                        class="w-8 h-8 rounded-md border-2 transition-transform hover:scale-110"
                                                        style="background-color: {{ $hex }}"
                                                        :class="{
                                                            'border-white ring-2 ring-offset-2 ring-primary': $wire.{{ $key }}Color === '{{ $name }}',
                                                            'border-transparent': $wire.{{ $key }}Color !== '{{ $name }}'
                                                        }"
                                                        title="{{ ucfirst($name) }}"
                                                    ></button>
                                                @endforeach
                                            </div>
                                            <p class="text-xs mt-1" :class="$wire.darkMode ? 'text-gray-400' : 'text-gray-500'">
                                                {{ __( 'Selected:' ) }} {{ ucfirst(${$key.'Color'}) }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Color Preview Swatches --}}
                            <div class="mt-6 pt-4 border-t" :class="$wire.darkMode ? 'border-gray-700' : 'border-gray-200'">
                                <h3 class="text-sm font-medium mb-3">{{ __( 'Color Preview' ) }}</h3>
                                <div class="flex gap-2">
                                    <div class="flex-1 text-center">
                                        <div
                                            class="h-12 rounded-lg mb-1"
                                            style="background-color: {{ $this->effectivePrimaryColor }}"
                                        ></div>
                                        <span class="text-xs">{{ __( 'Primary' ) }}</span>
                                    </div>
                                    <div class="flex-1 text-center">
                                        <div
                                            class="h-12 rounded-lg mb-1"
                                            style="background-color: {{ $this->effectiveSecondaryColor }}"
                                        ></div>
                                        <span class="text-xs">{{ __( 'Secondary' ) }}</span>
                                    </div>
                                    <div class="flex-1 text-center">
                                        <div
                                            class="h-12 rounded-lg mb-1"
                                            style="background-color: {{ $this->effectiveAccentColor }}"
                                        ></div>
                                        <span class="text-xs">{{ __( 'Accent' ) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Glass Section --}}
                @if($activeSection === 'glass')
                    <div class="card" :class="$wire.darkMode ? 'bg-gray-800' : 'bg-white shadow-sm'">
                        <div class="card-body">
                            <h2 class="card-title text-lg">{{ __( 'Glass Effects' ) }}</h2>

                            {{-- Glass Preset --}}
                            <div>
                                <label class="label">
                                    <span class="label-text">{{ __( 'Glass Preset' ) }}</span>
                                </label>
                                <select
                                    wire:model.live="glassPreset"
                                    class="select select-bordered select-sm w-full"
                                    :class="$wire.darkMode ? 'bg-gray-700' : ''"
                                >
                                    @foreach($this->glassPresets as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tint Color --}}
                            <div class="mt-4">
                                <label class="label">
                                    <span class="label-text">{{ __( 'Tint Color' ) }}</span>
                                </label>
                                <select
                                    wire:model.live="tintColor"
                                    class="select select-bordered select-sm w-full"
                                    :class="$wire.darkMode ? 'bg-gray-700' : ''"
                                >
                                    @foreach($this->tintColorOptions as $option)
                                        <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tint Intensity --}}
                            @if($tintColor !== 'transparent')
                                <div class="mt-4">
                                    <label class="label">
                                        <span class="label-text">{{ __( 'Tint Intensity' ) }}: {{ number_format($tintIntensity * 100, 0) }}%</span>
                                    </label>
                                    <input
                                        type="range"
                                        wire:model.live="tintIntensity"
                                        min="0"
                                        max="1"
                                        step="0.05"
                                        class="range range-sm range-primary"
                                    />
                                </div>
                            @endif

                            {{-- Glass Preview --}}
                            <div class="mt-6 pt-4 border-t" :class="$wire.darkMode ? 'border-gray-700' : 'border-gray-200'">
                                <h3 class="text-sm font-medium mb-3">{{ __( 'Glass Preview' ) }}</h3>
                                <div
                                    class="relative h-32 rounded-lg overflow-hidden"
                                    style="background: linear-gradient(135deg, {{ $this->effectivePrimaryColor }}, {{ $this->effectiveAccentColor }})"
                                >
                                    <div
                                        class="absolute inset-4 rounded-lg backdrop-blur-md flex items-center justify-center"
                                        style="
                                            background: rgba(255, 255, 255, 0.1);
                                            border: 1px solid rgba(255, 255, 255, 0.2);
                                            @if($tintColor !== 'transparent')
                                                background-color: {{ $tailwindColors[$tintColor] ?? $tintColor }}{{ sprintf('%02x', intval($tintIntensity * 255)) }};
                                            @endif
                                        "
                                    >
                                        <span class="text-white font-medium">{{ __( 'Glass Card' ) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Accessibility Section --}}
                @if($activeSection === 'accessibility')
                    <div class="card" :class="$wire.darkMode ? 'bg-gray-800' : 'bg-white shadow-sm'">
                        <div class="card-body">
                            <h2 class="card-title text-lg">{{ __( 'Accessibility' ) }}</h2>

                            <div>
                                <label class="label">
                                    <span class="label-text">{{ __( 'Accessibility Preset' ) }}</span>
                                </label>
                                <select
                                    wire:model.live="accessibilityPreset"
                                    class="select select-bordered select-sm w-full"
                                    :class="$wire.darkMode ? 'bg-gray-700' : ''"
                                >
                                    @foreach($this->accessibilityPresets as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            @if(!empty($accessibilityPreset))
                                <div class="alert alert-info mt-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <div class="font-bold">{{ __( 'WCAG Compliance' ) }}</div>
                                        <div class="text-sm">
                                            {{ __( 'This preset meets WCAG accessibility standards for contrast and readability.' ) }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Export Section --}}
                @if($activeSection === 'export')
                    <div class="card" :class="$wire.darkMode ? 'bg-gray-800' : 'bg-white shadow-sm'">
                        <div class="card-body">
                            <h2 class="card-title text-lg">{{ __( 'Export Theme' ) }}</h2>

                            <div class="space-y-3">
                                <button
                                    wire:click="downloadCss"
                                    class="btn btn-primary btn-sm w-full"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    {{ __( 'Download CSS' ) }}
                                </button>

                                <button
                                    wire:click="downloadJson"
                                    class="btn btn-secondary btn-sm w-full"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    {{ __( 'Download JSON' ) }}
                                </button>
                            </div>

                            {{-- Shareable URL --}}
                            <div class="mt-6 pt-4 border-t" :class="$wire.darkMode ? 'border-gray-700' : 'border-gray-200'">
                                <h3 class="text-sm font-medium mb-2">{{ __( 'Shareable URL' ) }}</h3>
                                <div class="flex gap-2">
                                    <input
                                        type="text"
                                        value="{{ $this->shareableUrl }}"
                                        readonly
                                        class="input input-bordered input-sm flex-1 text-xs"
                                        :class="$wire.darkMode ? 'bg-gray-700' : ''"
                                    />
                                    <button
                                        wire:click="copyShareableUrl"
                                        class="btn btn-sm btn-ghost"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Artisan Command --}}
                            <div class="mt-4">
                                <h3 class="text-sm font-medium mb-2">{{ __( 'Artisan Command' ) }}</h3>
                                <div class="relative">
                                    <pre class="text-xs p-3 rounded-lg overflow-x-auto" :class="$wire.darkMode ? 'bg-gray-900' : 'bg-gray-100'"><code>{{ $this->artisanCommand }}</code></pre>
                                    <button
                                        @click="copyToClipboard('{{ $this->artisanCommand }}')"
                                        class="absolute top-2 right-2 btn btn-xs btn-ghost"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Right Panel: Preview --}}
            <div class="lg:col-span-2">
                <div class="card" :class="$wire.darkMode ? 'bg-gray-800' : 'bg-white shadow-sm'">
                    <div class="card-body">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                            <h2 class="card-title text-lg">{{ __( 'Component Preview' ) }}</h2>

                            {{-- Component Category Selector --}}
                            <div class="flex flex-wrap gap-2">
                                @foreach(array_keys($componentCategories) as $category)
                                    <button
                                        wire:click="$set('selectedComponent', '{{ $componentCategories[$category][0] ?? 'button' }}')"
                                        class="btn btn-xs"
                                        :class="{
                                            'btn-primary': {{ json_encode(in_array($selectedComponent, $componentCategories[$category])) }},
                                            'btn-ghost': {{ json_encode(!in_array($selectedComponent, $componentCategories[$category])) }}
                                        }"
                                    >
                                        {{ ucfirst($category) }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Component Grid --}}
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 mb-6">
                            @foreach($componentCategories as $category => $components)
                                @foreach($components as $component)
                                    <button
                                        wire:click="$set('selectedComponent', '{{ $component }}')"
                                        @class([
                                            'btn btn-sm',
                                            'btn-primary' => $selectedComponent === $component,
                                            'btn-outline' => $selectedComponent !== $component,
                                        ])
                                    >
                                        {{ ucfirst($component) }}
                                    </button>
                                @endforeach
                            @endforeach
                        </div>

                        {{-- Preview Area --}}
                        <div
                            class="rounded-xl p-8 min-h-[400px]"
                            :class="$wire.darkMode ? 'bg-gray-900' : 'bg-gray-100'"
                            style="
                                --primary: {{ $this->effectivePrimaryColor }};
                                --secondary: {{ $this->effectiveSecondaryColor }};
                                --accent: {{ $this->effectiveAccentColor }};
                            "
                        >
                            {{-- Button Preview --}}
                            @if($selectedComponent === 'button')
                                <div class="space-y-6">
                                    <h3 class="font-semibold text-lg mb-4">{{ __( 'Button Variants' ) }}</h3>
                                    <div class="flex flex-wrap gap-3">
                                        <button class="btn" style="background-color: var(--primary); color: white;">{{ __( 'Primary' ) }}</button>
                                        <button class="btn" style="background-color: var(--secondary); color: white;">{{ __( 'Secondary' ) }}</button>
                                        <button class="btn" style="background-color: var(--accent); color: white;">{{ __( 'Accent' ) }}</button>
                                        <button class="btn btn-outline" style="border-color: var(--primary); color: var(--primary);">{{ __( 'Outline' ) }}</button>
                                        <button class="btn btn-ghost">{{ __( 'Ghost' ) }}</button>
                                    </div>
                                    <div class="flex flex-wrap gap-3">
                                        <button class="btn btn-sm" style="background-color: var(--primary); color: white;">{{ __( 'Small' ) }}</button>
                                        <button class="btn" style="background-color: var(--primary); color: white;">{{ __( 'Normal' ) }}</button>
                                        <button class="btn btn-lg" style="background-color: var(--primary); color: white;">{{ __( 'Large' ) }}</button>
                                    </div>
                                </div>
                            @endif

                            {{-- Input Preview --}}
                            @if($selectedComponent === 'input')
                                <div class="space-y-6 max-w-md">
                                    <h3 class="font-semibold text-lg mb-4">{{ __( 'Input Fields' ) }}</h3>
                                    <div class="form-control">
                                        <label class="label"><span class="label-text">{{ __( 'Default Input' ) }}</span></label>
                                        <input type="text" placeholder="{{ __( 'Type here...' ) }}" class="input input-bordered" />
                                    </div>
                                    <div class="form-control">
                                        <label class="label"><span class="label-text">{{ __( 'Primary Border' ) }}</span></label>
                                        <input type="text" placeholder="{{ __( 'Type here...' ) }}" class="input input-bordered" style="border-color: var(--primary);" />
                                    </div>
                                    <div class="form-control">
                                        <label class="label"><span class="label-text">{{ __( 'With Error' ) }}</span></label>
                                        <input type="text" placeholder="{{ __( 'Error state' ) }}" class="input input-bordered input-error" />
                                        <label class="label"><span class="label-text-alt text-error">{{ __( 'This field is required' ) }}</span></label>
                                    </div>
                                </div>
                            @endif

                            {{-- Checkbox Preview --}}
                            @if($selectedComponent === 'checkbox')
                                <div class="space-y-4">
                                    <h3 class="font-semibold text-lg mb-4">{{ __( 'Checkboxes' ) }}</h3>
                                    <div class="form-control">
                                        <label class="label cursor-pointer justify-start gap-3">
                                            <input type="checkbox" checked class="checkbox" style="--chkbg: var(--primary); --chkfg: white;" />
                                            <span class="label-text">{{ __( 'Primary checkbox' ) }}</span>
                                        </label>
                                    </div>
                                    <div class="form-control">
                                        <label class="label cursor-pointer justify-start gap-3">
                                            <input type="checkbox" checked class="checkbox" style="--chkbg: var(--secondary); --chkfg: white;" />
                                            <span class="label-text">{{ __( 'Secondary checkbox' ) }}</span>
                                        </label>
                                    </div>
                                    <div class="form-control">
                                        <label class="label cursor-pointer justify-start gap-3">
                                            <input type="checkbox" checked class="checkbox" style="--chkbg: var(--accent); --chkfg: white;" />
                                            <span class="label-text">{{ __( 'Accent checkbox' ) }}</span>
                                        </label>
                                    </div>
                                </div>
                            @endif

                            {{-- Select Preview --}}
                            @if($selectedComponent === 'select')
                                <div class="space-y-6 max-w-md">
                                    <h3 class="font-semibold text-lg mb-4">{{ __( 'Select Dropdowns' ) }}</h3>
                                    <div class="form-control">
                                        <label class="label"><span class="label-text">{{ __( 'Default Select' ) }}</span></label>
                                        <select class="select select-bordered">
                                            <option disabled selected>{{ __( 'Pick one' ) }}</option>
                                            <option>{{ __( 'Option 1' ) }}</option>
                                            <option>{{ __( 'Option 2' ) }}</option>
                                            <option>{{ __( 'Option 3' ) }}</option>
                                        </select>
                                    </div>
                                    <div class="form-control">
                                        <label class="label"><span class="label-text">{{ __( 'Primary Border' ) }}</span></label>
                                        <select class="select select-bordered" style="border-color: var(--primary);">
                                            <option disabled selected>{{ __( 'Pick one' ) }}</option>
                                            <option>{{ __( 'Option 1' ) }}</option>
                                            <option>{{ __( 'Option 2' ) }}</option>
                                        </select>
                                    </div>
                                </div>
                            @endif

                            {{-- Toggle Preview --}}
                            @if($selectedComponent === 'toggle')
                                <div class="space-y-4">
                                    <h3 class="font-semibold text-lg mb-4">{{ __( 'Toggles' ) }}</h3>
                                    <div class="form-control">
                                        <label class="label cursor-pointer justify-start gap-3">
                                            <input type="checkbox" checked class="toggle" style="--tglbg: var(--primary);" />
                                            <span class="label-text">{{ __( 'Primary toggle' ) }}</span>
                                        </label>
                                    </div>
                                    <div class="form-control">
                                        <label class="label cursor-pointer justify-start gap-3">
                                            <input type="checkbox" checked class="toggle" style="--tglbg: var(--secondary);" />
                                            <span class="label-text">{{ __( 'Secondary toggle' ) }}</span>
                                        </label>
                                    </div>
                                    <div class="form-control">
                                        <label class="label cursor-pointer justify-start gap-3">
                                            <input type="checkbox" checked class="toggle" style="--tglbg: var(--accent);" />
                                            <span class="label-text">{{ __( 'Accent toggle' ) }}</span>
                                        </label>
                                    </div>
                                </div>
                            @endif

                            {{-- Alert Preview --}}
                            @if($selectedComponent === 'alert')
                                <div class="space-y-4">
                                    <h3 class="font-semibold text-lg mb-4">{{ __( 'Alerts' ) }}</h3>
                                    <div class="alert" style="background-color: {{ $this->effectivePrimaryColor }}20; border-color: var(--primary);">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6" style="color: var(--primary);">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>{{ __( 'This is an info alert with your primary color.' ) }}</span>
                                    </div>
                                    <div class="alert alert-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>{{ __( 'Your purchase has been confirmed!' ) }}</span>
                                    </div>
                                    <div class="alert alert-warning">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <span>{{ __( 'Warning: Invalid email address!' ) }}</span>
                                    </div>
                                    <div class="alert alert-error">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>{{ __( 'Error! Task failed successfully.' ) }}</span>
                                    </div>
                                </div>
                            @endif

                            {{-- Badge Preview --}}
                            @if($selectedComponent === 'badge')
                                <div class="space-y-6">
                                    <h3 class="font-semibold text-lg mb-4">{{ __( 'Badges' ) }}</h3>
                                    <div class="flex flex-wrap gap-3">
                                        <span class="badge" style="background-color: var(--primary); color: white;">{{ __( 'Primary' ) }}</span>
                                        <span class="badge" style="background-color: var(--secondary); color: white;">{{ __( 'Secondary' ) }}</span>
                                        <span class="badge" style="background-color: var(--accent); color: white;">{{ __( 'Accent' ) }}</span>
                                        <span class="badge badge-success">{{ __( 'Success' ) }}</span>
                                        <span class="badge badge-warning">{{ __( 'Warning' ) }}</span>
                                        <span class="badge badge-error">{{ __( 'Error' ) }}</span>
                                    </div>
                                    <div class="flex flex-wrap gap-3">
                                        <span class="badge badge-outline" style="border-color: var(--primary); color: var(--primary);">{{ __( 'Outline Primary' ) }}</span>
                                        <span class="badge badge-outline" style="border-color: var(--secondary); color: var(--secondary);">{{ __( 'Outline Secondary' ) }}</span>
                                        <span class="badge badge-outline" style="border-color: var(--accent); color: var(--accent);">{{ __( 'Outline Accent' ) }}</span>
                                    </div>
                                </div>
                            @endif

                            {{-- Progress Preview --}}
                            @if($selectedComponent === 'progress')
                                <div class="space-y-6 max-w-lg">
                                    <h3 class="font-semibold text-lg mb-4">{{ __( 'Progress Bars' ) }}</h3>
                                    <div>
                                        <label class="label"><span class="label-text">{{ __( 'Primary' ) }} (70%)</span></label>
                                        <progress class="progress w-full" value="70" max="100" style="--progress-bg: {{ $this->effectivePrimaryColor }}20; --progress-value: var(--primary);"></progress>
                                    </div>
                                    <div>
                                        <label class="label"><span class="label-text">{{ __( 'Secondary' ) }} (40%)</span></label>
                                        <progress class="progress w-full" value="40" max="100" style="--progress-bg: {{ $this->effectiveSecondaryColor }}20; --progress-value: var(--secondary);"></progress>
                                    </div>
                                    <div>
                                        <label class="label"><span class="label-text">{{ __( 'Accent' ) }} (90%)</span></label>
                                        <progress class="progress w-full" value="90" max="100" style="--progress-bg: {{ $this->effectiveAccentColor }}20; --progress-value: var(--accent);"></progress>
                                    </div>
                                </div>
                            @endif

                            {{-- Card Preview --}}
                            @if($selectedComponent === 'card')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <h3 class="font-semibold text-lg mb-4 col-span-full">{{ __( 'Cards' ) }}</h3>
                                    <div class="card bg-base-100 shadow-xl">
                                        <div class="card-body">
                                            <h2 class="card-title">{{ __( 'Card Title' ) }}</h2>
                                            <p>{{ __( 'This is a basic card with your theme colors applied.' ) }}</p>
                                            <div class="card-actions justify-end">
                                                <button class="btn btn-sm" style="background-color: var(--primary); color: white;">{{ __( 'Action' ) }}</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card bg-base-100 shadow-xl" style="border-left: 4px solid var(--accent);">
                                        <div class="card-body">
                                            <h2 class="card-title" style="color: var(--accent);">{{ __( 'Accent Card' ) }}</h2>
                                            <p>{{ __( 'A card with accent color border and title.' ) }}</p>
                                            <div class="card-actions justify-end">
                                                <button class="btn btn-sm" style="background-color: var(--accent); color: white;">{{ __( 'Learn More' ) }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Modal Preview --}}
                            @if($selectedComponent === 'modal')
                                <div class="space-y-4">
                                    <h3 class="font-semibold text-lg mb-4">{{ __( 'Modal Preview' ) }}</h3>
                                    <div class="mockup-window border" :class="$wire.darkMode ? 'border-gray-700 bg-gray-800' : 'border-gray-300 bg-gray-200'">
                                        <div class="flex justify-center items-center p-8" :class="$wire.darkMode ? 'bg-gray-900' : 'bg-gray-100'">
                                            <div class="card bg-base-100 shadow-2xl w-80">
                                                <div class="card-body">
                                                    <h2 class="card-title">{{ __( 'Confirm Action' ) }}</h2>
                                                    <p>{{ __( 'Are you sure you want to proceed with this action?' ) }}</p>
                                                    <div class="card-actions justify-end mt-4">
                                                        <button class="btn btn-ghost btn-sm">{{ __( 'Cancel' ) }}</button>
                                                        <button class="btn btn-sm" style="background-color: var(--primary); color: white;">{{ __( 'Confirm' ) }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Table Preview --}}
                            @if($selectedComponent === 'table')
                                <div class="space-y-4">
                                    <h3 class="font-semibold text-lg mb-4">{{ __( 'Table' ) }}</h3>
                                    <div class="overflow-x-auto">
                                        <table class="table">
                                            <thead>
                                                <tr style="background-color: {{ $this->effectivePrimaryColor }}10;">
                                                    <th>{{ __( 'Name' ) }}</th>
                                                    <th>{{ __( 'Job' ) }}</th>
                                                    <th>{{ __( 'Status' ) }}</th>
                                                    <th>{{ __( 'Actions' ) }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>John Doe</td>
                                                    <td>Developer</td>
                                                    <td><span class="badge badge-sm" style="background-color: var(--primary); color: white;">{{ __( 'Active' ) }}</span></td>
                                                    <td><button class="btn btn-xs btn-ghost">{{ __( 'Edit' ) }}</button></td>
                                                </tr>
                                                <tr>
                                                    <td>Jane Smith</td>
                                                    <td>Designer</td>
                                                    <td><span class="badge badge-sm" style="background-color: var(--accent); color: white;">{{ __( 'Pending' ) }}</span></td>
                                                    <td><button class="btn btn-xs btn-ghost">{{ __( 'Edit' ) }}</button></td>
                                                </tr>
                                                <tr>
                                                    <td>Bob Johnson</td>
                                                    <td>Manager</td>
                                                    <td><span class="badge badge-sm badge-ghost">{{ __( 'Inactive' ) }}</span></td>
                                                    <td><button class="btn btn-xs btn-ghost">{{ __( 'Edit' ) }}</button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            {{-- Stat Preview --}}
                            @if($selectedComponent === 'stat')
                                <div class="space-y-4">
                                    <h3 class="font-semibold text-lg mb-4">{{ __( 'Stats' ) }}</h3>
                                    <div class="stats shadow w-full">
                                        <div class="stat">
                                            <div class="stat-figure" style="color: var(--primary);">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-8 h-8 stroke-current">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="stat-title">{{ __( 'Downloads' ) }}</div>
                                            <div class="stat-value" style="color: var(--primary);">31K</div>
                                            <div class="stat-desc">{{ __( 'Jan 1st - Feb 1st' ) }}</div>
                                        </div>
                                        <div class="stat">
                                            <div class="stat-figure" style="color: var(--secondary);">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-8 h-8 stroke-current">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                                </svg>
                                            </div>
                                            <div class="stat-title">{{ __( 'New Users' ) }}</div>
                                            <div class="stat-value" style="color: var(--secondary);">4,200</div>
                                            <div class="stat-desc">{{ __( '↗︎ 400 (22%)' ) }}</div>
                                        </div>
                                        <div class="stat">
                                            <div class="stat-figure" style="color: var(--accent);">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-8 h-8 stroke-current">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                                </svg>
                                            </div>
                                            <div class="stat-title">{{ __( 'Revenue' ) }}</div>
                                            <div class="stat-value" style="color: var(--accent);">$89K</div>
                                            <div class="stat-desc">{{ __( '↗︎ 14% more than last month' ) }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Generated CSS Preview --}}
                <div class="card mt-6" :class="$wire.darkMode ? 'bg-gray-800' : 'bg-white shadow-sm'">
                    <div class="card-body">
                        <h2 class="card-title text-lg">{{ __( 'Generated CSS' ) }}</h2>
                        <div class="mockup-code text-xs max-h-64 overflow-y-auto">
                            <pre><code>{{ $this->generatedCss }}</code></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
