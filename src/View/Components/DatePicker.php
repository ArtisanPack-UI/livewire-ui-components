<?php
/**
 * DatePicker
 *
 * This file contains the DatePicker class for the ArtisanPack UI Livewire UI Components package.
 *
 * @package    ArtisanPack\LivewireUiComponents\View
 * @subpackage Components
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 * @link       https://github.com/robsontenorio/mary Original MaryUI Repository
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */


namespace ArtisanPack\LivewireUiComponents\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use ArtisanPack\LivewireUiComponents\Styling\ColorGenerator;
/**
 * DatePicker Class
 *
 * Provides functionality for the DatePicker component.
 *
 * @since 1.0.0
 */

class DatePicker extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $icon = null,
        public ?string $iconRight = null,
        public ?string $hint = null,
        public ?string $hintClass = 'fieldset-label',
        public ?bool $inline = false,
        public ?array $config = [],

        // Theming
        public ?string $color = null,
        public ?string $colorAdjustment = null,
        public ?string $theme = 'artisanpack',
        public ?array $fontConfig = [],
        public ?bool $darkModeSupport = true,

        // Slots
        public mixed $prepend = null,
        public mixed $append = null,

        // Validations
        public ?string $errorField = null,
        public ?string $errorClass = 'text-error',
        public ?bool $omitError = false,
        public ?bool $firstErrorOnly = false,
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
    }

    public function modelName(): ?string
    {
        return $this->attributes->whereStartsWith('wire:model')->first();
    }

    public function errorFieldName(): ?string
    {
        return $this->errorField ?? $this->modelName();
    }

    public function isReadonly(): bool
    {
        return $this->attributes->has('readonly') && $this->attributes->get('readonly') == true;
    }

    public function isDisabled(): bool
    {
        return $this->attributes->has('disabled') && $this->attributes->get('disabled') == true;
    }

    public function setup(): string
    {
        // Handle `wire:model.live` for `range` dates
        if (isset($this->config["mode"]) && $this->config["mode"] == "range" && $this->attributes->wire('model')->hasModifier('live')) {
            $this->attributes->setAttributes([
                'wire:model' => $this->modelName(),
                'live' => true
            ]);
        }

        $config = json_encode(array_merge([
            'dateFormat' => 'Y-m-d H:i',
            'altInput' => true,
            'altInputClass' => ' ',
            'clickOpens' => ! $this->attributes->has('readonly') || $this->attributes->get('readonly') == false,
            'defaultDate' => '#model#',
            'plugins' => ['#plugins#'],
            'disable' => ['#disable#'],
        ], Arr::except($this->config, ["plugins"])));

        // Plugins
        $plugins = "";

        foreach (Arr::get($this->config, 'plugins', []) as $plugin) {
            $plugins .= "new " . key($plugin) . "( " . json_encode(current($plugin)) . " ),";
        }

        $config = str_replace('"#plugins#"', $plugins, $config);

        // Disables
        $disables = '';

        foreach (Arr::get($this->config, 'disable', []) as $disable) {
            $disables .= $disable . ',';
        }

        $config = str_replace('"#disable#"', $disables, $config);

        // Sets default date as current bound model
        $modelName = $this->modelName();
        if ($modelName) {
            $config = str_replace('"#model#"', '$wire.get("' . $modelName . '")', $config);
        } else {
            $config = str_replace('"#model#"', 'null', $config);
        }

        return $config;
    }

    /**
     * Get color-specific CSS classes using ColorGenerator.
     *
     * @return array
     * @since 1.1.0
     */
    public function getColorClasses(): array
    {
        if (!$this->color) {
            return [];
        }

        $colorGenerator = new ColorGenerator();
        return $colorGenerator->resolveComponentColor(
            $this->color, 
            $this->colorAdjustment, 
            'datepicker'
        );
    }

    /**
     * Get FlatPicker theme configuration.
     *
     * @return array
     * @since 1.1.0
     */
    public function getFlatPickrThemeConfig(): array
    {
        $config = [];

        if ($this->theme !== 'default') {
            $config['theme'] = $this->theme;
        }

        return $config;
    }

    /**
     * Generate CSS custom properties for theming.
     *
     * @return string
     * @since 1.1.0
     */
    public function getCustomCSSVariables(): string
    {
        $colorClasses = $this->getColorClasses();
        $fontClasses = $this->getFontClasses();
        $variables = [];

        // Color variables
        if (isset($colorClasses['style'])) {
            $variables[] = $colorClasses['style'];
        }

        // Font variables
        foreach ($fontClasses as $property => $value) {
            // Skip array values to prevent "Array to string conversion" errors
            if (is_scalar($value) && $value !== null) {
                $variables[] = "--artisanpack-datepicker-{$property}: {$value};";
            }
        }

        return implode(' ', $variables);
    }

    /**
     * Get font configuration classes.
     *
     * @return array
     * @since 1.1.0
     */
    public function getFontClasses(): array
    {
        return array_merge([
            'font-family' => 'inherit',
            'font-size' => '0.875rem',
            'font-weight' => '400',
            'line-height' => '1.25rem',
        ], $this->fontConfig);
    }

    /**
     * Get theme data attributes for the calendar.
     *
     * @return array
     * @since 1.1.0
     */
    public function getThemeAttributes(): array
    {
        $attributes = [
            'data-theme' => $this->theme,
        ];

        if ($this->darkModeSupport) {
            $attributes['data-dark-mode'] = 'supported';
        }

        return $attributes;
    }

    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.date-picker');
    }
}
