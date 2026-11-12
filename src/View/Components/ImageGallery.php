<?php

declare(strict_types=1);
/**
 * ImageGallery
 *
 * This file contains the ImageGallery class for the ArtisanPack UI Livewire UI Components package.
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

/**
 * ImageGallery Class
 *
 * The design-system ImageGallery (#61): a grid of images whose buttons open a
 * lightbox, the `apuiLightbox` widget (a dialog with the image, a counter and
 * caption, previous and next buttons, thumbnails and arrow keys that follow
 * the text direction). `wire:model` binds the lightbox's open state.
 *
 * The 2.x PhotoSwipe integration is gone; `enable-lightbox` still turns the
 * lightbox off, and the other 2.x props (aspect-ratio, gap, layout, filters,
 * items-per-page, loading-style, show-captions, lazy-load) are accepted and
 * ignored, so old markup keeps rendering.
 *
 * @since 1.0.0
 */
class ImageGallery extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * The id prefix for this component's parts.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'image-gallery';

    /**
     * Props left out of the stable id, so a change to them keeps the ids.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['open', 'index', 'closeLabel', 'previousLabel', 'nextLabel', 'downloadLabel', 'counterLabel', 'thumbsLabel'];

    /**
     * Create a new component instance.
     *
     * @since 1.0.0
     *
     * @param  array  $images  The images: `{ src, alt, caption }`, the 2.x `url` key, or URL strings.
     * @param  array|int  $columns  The grid's columns. A 2.x breakpoint array uses its largest value.
     * @param  bool  $featured  The first image spans two columns and rows.
     * @param  bool  $lightbox  Each image opens the lightbox.
     * @param  bool  $open  Whether the lightbox is open (or bound with `wire:model`).
     * @param  int  $index  The image the lightbox shows, 0-based.
     * @param  bool  $downloadable  Adds a download link to the lightbox.
     * @param  bool  $modal  Traps focus and hides the page behind the lightbox.
     * @param  bool  $portal  Renders the lightbox at the end of the body.
     * @param  string|null  $closeLabel  The close button's name. Defaults to `__( 'Close' )`.
     * @param  string|null  $previousLabel  The previous button's name.
     * @param  string|null  $nextLabel  The next button's name.
     * @param  string|null  $downloadLabel  The download link's name.
     * @param  string|null  $counterLabel  The counter, with `{index}` and `{count}`.
     * @param  string|null  $thumbsLabel  Names the thumbnail strip.
     * @param  array  $classes  Extra classes per part.
     * @param  bool|null  $enableLightbox  Deprecated: use `lightbox`.
     */
    public function __construct(
        public array $images = [],
        public int|array $columns = 4,
        public bool $featured = false,
        public bool $lightbox = true,
        public bool $open = false,
        public int $index = 0,
        public bool $downloadable = false,
        public bool $modal = true,
        public bool $portal = true,
        public ?string $closeLabel = null,
        public ?string $previousLabel = null,
        public ?string $nextLabel = null,
        public ?string $downloadLabel = null,
        public ?string $counterLabel = null,
        public ?string $thumbsLabel = null,
        public array $classes = [],
        public ?bool $enableLightbox = null,
    ) {
        if (is_array($this->columns)) {
            $values        = array_filter(array_map('intval', $this->columns), static fn (int $value): bool => $value > 0);
            $this->columns = [] === $values ? 4 : max($values);
        }

        $this->columns = max(1, (int) $this->columns);

        if (null !== $this->enableLightbox) {
            $this->lightbox = $this->enableLightbox;
        }
    }

    /**
     * The images as `{ src, alt, caption }`, whatever shape they came in.
     *
     * @since 3.0.0
     *
     * @return list<array{src: string, alt: string, caption: string}> The images.
     */
    public function entries(): array
    {
        $entries = [];
        foreach ($this->images as $image) {
            if (is_string($image)) {
                $entries[] = ['src' => (string) SafeUrl::media($image), 'alt' => '', 'caption' => ''];

                continue;
            }

            if (! is_array($image)) {
                continue;
            }

            $entries[] = [
                'src'     => (string) SafeUrl::media((string) ($image['src'] ?? $image['url'] ?? '')),
                'alt'     => (string) ($image['alt'] ?? ''),
                'caption' => (string) ($image['caption'] ?? $image['title'] ?? ''),
            ];
        }

        return $entries;
    }

    /**
     * The index the lightbox starts on: the bound or given one, within the images.
     *
     * @since 3.0.0
     *
     * @return int The index.
     */
    public function currentIndex(): int
    {
        $count = count($this->entries());

        return $count > 0 ? min(max(0, $this->index), $count - 1) : 0;
    }

    /**
     * The lightbox's title for an image: the counter, then the caption.
     *
     * @since 3.0.0
     *
     * @param  int  $index  The image, 0-based.
     *
     * @return string The title.
     */
    public function titleFor(int $index): string
    {
        $entries = $this->entries();
        $counter = str_replace(['{index}', '{count}'], [(string) ($index + 1), (string) count($entries)], $this->counterTemplate());
        $caption = $entries[$index]['caption'] ?? '';

        return '' !== $caption ? $counter.' · '.$caption : $counter;
    }

    /**
     * The counter template, with `{index}` and `{count}` (Livewire's `__()` uses `:index` and `:count`).
     *
     * @since 3.0.0
     *
     * @return string The template.
     */
    public function counterTemplate(): string
    {
        return $this->filled($this->counterLabel)
            ? (string) $this->counterLabel
            : __(':index / :count', ['index' => '{index}', 'count' => '{count}']);
    }

    /**
     * A built-in label: the prop, else its translated default.
     *
     * @since 3.0.0
     *
     * @param  string  $name  The label: close, previous, next, download or thumbs.
     *
     * @return string The label.
     */
    public function label(string $name): string
    {
        $value = match ($name) {
            'close'    => $this->closeLabel,
            'previous' => $this->previousLabel,
            'next'     => $this->nextLabel,
            'download' => $this->downloadLabel,
            default    => $this->thumbsLabel,
        };

        if ($this->filled($value)) {
            return (string) $value;
        }

        return match ($name) {
            'close'    => __('Close'),
            'previous' => __('Previous photo'),
            'next'     => __('Next photo'),
            'download' => __('Download'),
            default    => __('Photos'),
        };
    }

    /**
     * Whether the lightbox starts open: the bound property, else `open`.
     *
     * @since 3.0.0
     *
     * @return bool Whether it's open.
     */
    public function startsOpen(): bool
    {
        return $this->lightbox && [] !== $this->entries() && $this->isOpen();
    }

    /**
     * The `x-data` expression that mounts the lightbox widget.
     *
     * @since 3.0.0
     *
     * @return string The expression.
     */
    public function alpineData(): string
    {
        $images = array_map(
            static fn (array $entry): array => ['src' => $entry['src'], 'alt' => $entry['alt'], 'caption' => $entry['caption']],
            $this->entries(),
        );

        return sprintf(
            'apuiLightbox({ id: %s, images: %s, open: %s, index: %d, modal: %s, labels: { counter: %s } })',
            Js::from($this->baseId())->toHtml(),
            Js::from($images)->toHtml(),
            $this->openExpression(),
            $this->currentIndex(),
            $this->modal ? 'true' : 'false',
            Js::from($this->counterTemplate())->toHtml(),
        );
    }

    /**
     * The root's attributes: everything but `wire:model`, which the widget
     * binds, `id` and `style`, which the view writes itself, and the ignored
     * 2.x props.
     *
     * @since 3.0.0
     *
     * @return \Illuminate\View\ComponentAttributeBag The attributes.
     */
    public function rootAttributes(): \Illuminate\View\ComponentAttributeBag
    {
        return $this->attributes->whereDoesntStartWith('wire:model')->except([
            'id', 'style', 'aspect-ratio', 'aspectRatio', 'gap', 'show-captions', 'showCaptions', 'layout',
            'lazy-load', 'lazyLoad', 'filters', 'items-per-page', 'itemsPerPage', 'loading-style', 'loadingStyle',
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
        return view('livewire-ui-components::components.image-gallery');
    }
}
