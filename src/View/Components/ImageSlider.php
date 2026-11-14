<?php

declare(strict_types=1);
/**
 * ImageSlider
 *
 * This file contains the ImageSlider class for the ArtisanPack UI Livewire UI Components package.
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\Support\SafeUrl;
use ArtisanPack\LivewireUiComponents\View\Concerns\HasStablePartIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Js;
use Illuminate\View\ComponentAttributeBag;

/**
 * ImageSlider Class
 *
 * The design-system ImageSlider (#61): one image per view on the carousel
 * machine (`apuiCarousel`), with a glass caption, a counter, previous and
 * next buttons, dots, optional thumbnails and autoplay with a pause button.
 * `wire:model` binds the slide shown.
 *
 * The 2.x props keep working where they still mean something: auto-play and
 * auto-play-interval are autoplay and interval, and the 2.x "16:9" ratios
 * become "16 / 9". The others (with-arrows, with-indicators, show-counter,
 * pause-on-hover, transition, transition-duration, enable-lightbox) are
 * accepted and ignored: the controls always show, and autoplay always
 * pauses on hover and focus.
 *
 * @since 1.0.0
 */
class ImageSlider extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * The id prefix for this component's parts.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'image-slider';

    /**
     * Props left out of the stable id, so a change to them keeps the ids.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['index', 'label', 'previousLabel', 'nextLabel', 'indicatorLabel', 'slideLabel', 'counterLabel', 'playLabel', 'pauseLabel'];

    /**
     * Create a new component instance.
     *
     * @since 1.0.0
     *
     * @param  array  $images  The slides: `{ src, alt, caption, description }`, the 2.x `url`/`title` keys, or URL strings.
     * @param  string  $aspectRatio  The frame's ratio, "21 / 9" or the 2.x "21:9".
     * @param  int  $index  The slide shown, 0-based (or bound with `wire:model`).
     * @param  bool  $loop  Wraps from the last slide to the first.
     * @param  bool  $autoplay  Advances every interval ms, with a pause button.
     * @param  int  $interval  Milliseconds between slides while autoplay runs.
     * @param  bool  $thumbnails  Shows a strip of thumbnails under the frame.
     * @param  string|null  $label  Names the region. Defaults to `__( 'Photos' )`.
     * @param  string|null  $previousLabel  The previous button's name.
     * @param  string|null  $nextLabel  The next button's name.
     * @param  string|null  $indicatorLabel  A dot's name, with `{index}`.
     * @param  string|null  $slideLabel  A slide's name, with `{index}` and `{count}`.
     * @param  string|null  $counterLabel  The counter, with `{index}` and `{count}`.
     * @param  string|null  $playLabel  The autoplay button while paused.
     * @param  string|null  $pauseLabel  The autoplay button while playing.
     * @param  array  $classes  Extra classes per part.
     * @param  bool|null  $autoPlay  Deprecated: use `autoplay`.
     * @param  int|null  $autoPlayInterval  Deprecated: use `interval`.
     */
    public function __construct(
        public array $images = [],
        public string $aspectRatio = '21 / 9',
        public int $index = 0,
        public bool $loop = true,
        public bool $autoplay = false,
        public int $interval = 5000,
        public bool $thumbnails = false,
        public ?string $label = null,
        public ?string $previousLabel = null,
        public ?string $nextLabel = null,
        public ?string $indicatorLabel = null,
        public ?string $slideLabel = null,
        public ?string $counterLabel = null,
        public ?string $playLabel = null,
        public ?string $pauseLabel = null,
        public array $classes = [],
        public ?bool $autoPlay = null,
        public ?int $autoPlayInterval = null,
    ) {
        if (null !== $this->autoPlay) {
            $this->autoplay = $this->autoPlay;
        }

        if (null !== $this->autoPlayInterval) {
            $this->interval = $this->autoPlayInterval;
        }

        $this->interval    = max(1000, $this->interval);
        $this->aspectRatio = $this->normalizeRatio($this->aspectRatio);
    }

    /**
     * The slides as `{ src, alt, caption, description }`, whatever shape they came in.
     *
     * @since 3.0.0
     *
     * @return list<array{src: string, alt: string, caption: string, description: string}> The slides.
     */
    public function entries(): array
    {
        $entries = [];
        foreach ($this->images as $image) {
            if (is_string($image)) {
                $entries[] = ['src' => (string) SafeUrl::media($image), 'alt' => '', 'caption' => '', 'description' => ''];

                continue;
            }

            if (! is_array($image)) {
                continue;
            }

            $entries[] = [
                'src'         => (string) SafeUrl::media((string) ($image['src'] ?? $image['url'] ?? '')),
                'alt'         => (string) ($image['alt'] ?? ''),
                'caption'     => (string) ($image['caption'] ?? $image['title'] ?? ''),
                'description' => (string) ($image['description'] ?? ''),
            ];
        }

        return $entries;
    }

    /**
     * The slide shown on the server: the bound property, else `index`, within the slides.
     *
     * @since 3.0.0
     *
     * @return int The index.
     */
    public function currentIndex(): int
    {
        $index = $this->index;
        $model = isset($this->attributes) ? $this->modelName() : null;
        if (null !== $model) {
            $component = $this->livewireComponent();
            $bound     = null !== $component ? data_get($component, $model) : null;
            if (is_numeric($bound)) {
                $index = (int) $bound;
            }
        }

        $count = count($this->entries());

        return $count > 0 ? min(max(0, $index), $count - 1) : 0;
    }

    /**
     * A built-in label: the prop, else its translated default. Templates keep
     * `{index}` and `{count}` for the widget; Livewire's `__()` strings use
     * `:index` and `:count`.
     *
     * @since 3.0.0
     *
     * @param  string  $name  The label: label, previous, next, indicator, slide, counter, play or pause.
     *
     * @return string The label.
     */
    public function text(string $name): string
    {
        $value = match ($name) {
            'label'     => $this->label,
            'previous'  => $this->previousLabel,
            'next'      => $this->nextLabel,
            'indicator' => $this->indicatorLabel,
            'slide'     => $this->slideLabel,
            'counter'   => $this->counterLabel,
            'play'      => $this->playLabel,
            default     => $this->pauseLabel,
        };

        if ($this->filled($value)) {
            return (string) $value;
        }

        $placeholders = ['index' => '{index}', 'count' => '{count}'];

        return match ($name) {
            'label'     => __('Photos'),
            'previous'  => __('Previous photo'),
            'next'      => __('Next photo'),
            'indicator' => __('Photo :index', $placeholders),
            'slide'     => __(':index of :count', $placeholders),
            'counter'   => __(':index / :count', $placeholders),
            'play'      => __('Start slide rotation'),
            default     => __('Stop slide rotation'),
        };
    }

    /**
     * A template with `{index}` (1-based) and `{count}` filled in.
     *
     * @since 3.0.0
     *
     * @param  string  $name  The label.
     * @param  int  $index  The slide, 0-based.
     *
     * @return string The text.
     */
    public function format(string $name, int $index): string
    {
        return str_replace(['{index}', '{count}'], [(string) ($index + 1), (string) count($this->entries())], $this->text($name));
    }

    /**
     * The `x-data` expression that mounts the carousel widget.
     *
     * @since 3.0.0
     *
     * @return string The expression.
     */
    public function alpineData(): string
    {
        $model = $this->modelName();
        if (null !== $model) {
            $live  = [] !== array_filter(
                array_keys($this->attributes->whereStartsWith('wire:model')->getAttributes()),
                static fn (string $key): bool => str_contains($key, '.live'),
            );
            $index = '$wire.entangle('.Js::from($model)->toHtml().')'.($live ? '.live' : '');
        } else {
            $index = (string) $this->currentIndex();
        }

        $labels = [];
        foreach (['previous', 'next', 'indicator', 'slide', 'play', 'pause'] as $name) {
            $labels[$name] = $this->text($name);
        }
        $labels['progress'] = $this->text('counter');

        return sprintf(
            "apuiCarousel({ id: %s, scope: 'image-slider', slideCount: %d, index: %s, loop: %s, autoplay: %s, interval: %d, labels: %s })",
            Js::from($this->baseId())->toHtml(),
            count($this->entries()),
            $index,
            $this->loop ? 'true' : 'false',
            $this->autoplay ? 'true' : 'false',
            $this->interval,
            Js::from($labels)->toHtml(),
        );
    }

    /**
     * The root's attributes: everything but `wire:model`, which the widget
     * binds, `id` and `style`, which the view writes itself, and the ignored
     * 2.x props.
     *
     * @since 3.0.0
     *
     * @return ComponentAttributeBag The attributes.
     */
    public function rootAttributes(): ComponentAttributeBag
    {
        return $this->attributes->whereDoesntStartWith('wire:model')->except([
            'id', 'style', 'with-arrows', 'withArrows', 'with-indicators', 'withIndicators', 'show-counter', 'showCounter',
            'pause-on-hover', 'pauseOnHover', 'transition', 'transition-duration', 'transitionDuration',
            'enable-lightbox', 'enableLightbox',
        ]);
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     *
     * @return View The view.
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.image-slider');
    }

    /**
     * A CSS aspect ratio from "21 / 9", the 2.x "21:9", or "square".
     *
     * @since 3.0.0
     *
     * @param  string  $ratio  The ratio as given.
     *
     * @return string The ratio as CSS takes it.
     */
    private function normalizeRatio(string $ratio): string
    {
        $ratio = trim($ratio);
        if ('square' === $ratio) {
            return '1 / 1';
        }

        if (1 === preg_match('/^(\d+(?:\.\d+)?)\s*[:\/]\s*(\d+(?:\.\d+)?)$/', $ratio, $matches)) {
            return $matches[1].' / '.$matches[2];
        }

        return '21 / 9';
    }
}
