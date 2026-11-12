<?php

declare(strict_types=1);
/**
 * Carousel
 *
 * This file contains the Carousel class for the ArtisanPack UI Livewire UI Components package.
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\View\Concerns\HasStablePartIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Js;
use Illuminate\View\ComponentAttributeBag;

/**
 * Carousel Class
 *
 * The design-system Carousel (#65) on the carousel machine ImageSlider uses
 * (`apuiCarousel`, scoped `carousel`, D-041): scroll-snapped slides with
 * previous and next buttons, dots, optional autoplay with a pause button,
 * and `per-view` slides at once with the next one peeking. A title puts the
 * buttons in a header; without one they overlay the slides. Each slide is a
 * Card by default; `@scope('slide', $slide)` renders it yourself, with
 * `$loop->index` as its index.
 * `wire:model` binds the position shown.
 *
 * The 2.x props keep working where they still mean something: slides keep
 * their image, alt, title and description keys (url becomes the Card's
 * link), and aria-label is label. The others (without-indicators,
 * without-arrows, next-arrow, previous-arrow, dots, aria-labelled-by,
 * respects-reduced-motion) are accepted and ignored: the controls always
 * show, and autoplay always respects reduced motion.
 *
 * @since 1.0.0
 */
class Carousel extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * The gap between slides, the machine's `spacing`.
     *
     * @since 3.0.0
     */
    public const SPACING = '12px';

    /**
     * How much of the next slide peeks above one per view. Mirrors
     * `CAROUSEL_PEEK` in `@artisanpack-ui/behavior`.
     *
     * @since 3.0.0
     */
    public const PEEK = 0.15;

    /**
     * The 2.x props accepted and left off the root.
     *
     * @since 3.0.0
     */
    public const IGNORED_ATTRIBUTES = [
        'without-indicators', 'withoutIndicators', 'without-arrows', 'withoutArrows', 'next-arrow', 'nextArrow',
        'previous-arrow', 'previousArrow', 'dots', 'aria-labelled-by', 'ariaLabelledBy', 'aria-labelledby',
        'respects-reduced-motion', 'respectsReducedMotion',
    ];

    /**
     * The id prefix for this component's parts.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'carousel';

    /**
     * Props left out of the stable id, so a change to them keeps the ids.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['index', 'label', 'previousLabel', 'nextLabel', 'indicatorLabel', 'slideLabel', 'playLabel', 'pauseLabel', 'ariaLabel'];

    /**
     * Create a new component instance.
     *
     * @since 1.0.0
     *
     * @param  array  $slides  The slides: `{ image, alt, title, description }` for the default Card, or anything for `@scope('slide')`.
     * @param  int  $perView  Slides in view at once.
     * @param  int  $index  The position shown, 0-based (or bound with `wire:model`).
     * @param  bool  $loop  Wraps from the last position to the first.
     * @param  bool  $autoplay  Advances every interval ms, with a pause button.
     * @param  int  $interval  Milliseconds between slides while autoplay runs.
     * @param  string|null  $title  The header's heading.
     * @param  int  $headingLevel  The title's heading level, 2 to 6.
     * @param  string|null  $label  Names the region. Defaults to the title, else `__( 'Carousel' )`.
     * @param  string|null  $previousLabel  The previous button's name.
     * @param  string|null  $nextLabel  The next button's name.
     * @param  string|null  $indicatorLabel  A dot's name, with `{index}`.
     * @param  string|null  $slideLabel  A slide's name, with `{index}` and `{count}`.
     * @param  string|null  $playLabel  The autoplay button while paused.
     * @param  string|null  $pauseLabel  The autoplay button while playing.
     * @param  array  $classes  Extra classes per part.
     * @param  string|null  $ariaLabel  Deprecated: use `label`.
     */
    public function __construct(
        public array $slides = [],
        public int $perView = 1,
        public int $index = 0,
        public bool $loop = false,
        public bool $autoplay = false,
        public int $interval = 5000,
        public ?string $title = null,
        public int $headingLevel = 3,
        public ?string $label = null,
        public ?string $previousLabel = null,
        public ?string $nextLabel = null,
        public ?string $indicatorLabel = null,
        public ?string $slideLabel = null,
        public ?string $playLabel = null,
        public ?string $pauseLabel = null,
        public array $classes = [],
        public ?string $ariaLabel = null,
    ) {
        if ((null === $this->label || '' === $this->label) && null !== $this->ariaLabel && '' !== $this->ariaLabel) {
            $this->label = $this->ariaLabel;
        }

        $this->slides       = array_values($this->slides);
        $this->perView      = max(1, $this->perView);
        $this->interval     = max(1000, $this->interval);
        $this->headingLevel = min(6, max(2, $this->headingLevel));
    }

    /**
     * The machine's `slidesPerPage`: `per-view`, plus the peek above one.
     *
     * @since 3.0.0
     *
     * @return float|int The slides per page.
     */
    public function slidesPerPage(): float|int
    {
        return $this->perView > 1 ? $this->perView + self::PEEK : 1;
    }

    /**
     * How many positions there are, one per slide that can sit at the start
     * of the view. Mirrors `carouselPageCount` in `@artisanpack-ui/behavior`.
     *
     * @since 3.0.0
     *
     * @return int The positions.
     */
    public function pageCount(): int
    {
        $count = count($this->slides);

        return $count <= $this->perView ? 1 : $count - $this->perView + 1;
    }

    /**
     * The position shown on the server: the bound property, else `index`, within the positions.
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

        return min(max(0, $index), $this->pageCount() - 1);
    }

    /**
     * Whether a slide is in view on the server render.
     *
     * @since 3.0.0
     *
     * @param  int  $slide  The slide, 0-based.
     *
     * @return bool True when it shows.
     */
    public function inView(int $slide): bool
    {
        $start = $this->currentIndex();

        return $slide >= $start && $slide < $start + $this->perView;
    }

    /**
     * The default Card's props for a slide: image, alt, title, description
     * and the 2.x url as its link.
     *
     * @since 3.0.0
     *
     * @param  mixed  $slide  The slide.
     *
     * @return array{media: string|null, mediaAlt: string, title: string|null, description: string|null, href: string|null} The props.
     */
    public function card(mixed $slide): array
    {
        $slide = is_array($slide) ? $slide : (is_object($slide) ? (array) $slide : []);
        $text  = static fn (string $key): ?string => isset($slide[$key]) && is_scalar($slide[$key]) && '' !== (string) $slide[$key] ? (string) $slide[$key] : null;

        return [
            'media'       => $text('image'),
            'mediaAlt'    => $text('alt') ?? '',
            'title'       => $text('title'),
            'description' => $text('description'),
            'href'        => $text('href') ?? $text('url'),
        ];
    }

    /**
     * A built-in label: the prop, else its translated default. Templates keep
     * `{index}` and `{count}` for the widget; Livewire's `__()` strings use
     * `:index` and `:count`.
     *
     * @since 3.0.0
     *
     * @param  string  $name  The label: label, previous, next, indicator, slide, play or pause.
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
            'play'      => $this->playLabel,
            default     => $this->pauseLabel,
        };

        if ($this->filled($value)) {
            return (string) $value;
        }

        $placeholders = ['index' => '{index}', 'count' => '{count}'];

        return match ($name) {
            'label'     => $this->filled($this->title) ? (string) $this->title : __('Carousel'),
            'previous'  => __('Previous slide'),
            'next'      => __('Next slide'),
            'indicator' => __('Go to slide :index', $placeholders),
            'slide'     => __(':index of :count', $placeholders),
            'play'      => __('Start slide rotation'),
            default     => __('Stop slide rotation'),
        };
    }

    /**
     * A template with `{index}` (1-based) and `{count}` (the slides) filled in.
     *
     * @since 3.0.0
     *
     * @param  string  $name  The label.
     * @param  int  $index  The slide or position, 0-based.
     *
     * @return string The text.
     */
    public function format(string $name, int $index): string
    {
        return str_replace(['{index}', '{count}'], [(string) ($index + 1), (string) count($this->slides)], $this->text($name));
    }

    /**
     * The machine's layout styles on the root, written on the server so the
     * track lays out before Alpine runs.
     *
     * @since 3.0.0
     *
     * @return string The declarations.
     */
    public function layoutStyle(): string
    {
        return sprintf(
            '--slides-per-page: %s; --slide-spacing: %s; --slide-item-size: calc(100%% / var(--slides-per-page) - var(--slide-spacing) * (var(--slides-per-page) - 1) / var(--slides-per-page))',
            (string) $this->slidesPerPage(),
            self::SPACING,
        );
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

        return sprintf(
            "apuiCarousel({ id: %s, scope: 'carousel', slideCount: %d, perView: %d, spacing: %s, index: %s, loop: %s, autoplay: %s, interval: %d, labels: %s })",
            Js::from($this->baseId())->toHtml(),
            count($this->slides),
            $this->perView,
            Js::from(self::SPACING)->toHtml(),
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
        return $this->attributes->whereDoesntStartWith('wire:model')->except(['id', 'style', ...self::IGNORED_ATTRIBUTES]);
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
        return view('livewire-ui-components::components.carousel');
    }
}
