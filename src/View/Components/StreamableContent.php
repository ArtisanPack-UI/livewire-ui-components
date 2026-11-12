<?php

/**
 * Streamable Content Component
 *
 * The design-system StreamableContent (#60): content that arrives over time
 * through wire:stream. Livewire only (D-003).
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      2.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\Support\LivewireHelper;
use ArtisanPack\LivewireUiComponents\View\Concerns\HasStablePartIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;

/**
 * Streamable Content Component Class
 *
 * Renders `.apui-streamable-content` with data-state and the status (with a
 * Stop button while streaming), content (the live region wire:stream writes
 * to), caret and actions parts. Stop dispatches `stop`.
 *
 * @since 2.0.0
 */
class StreamableContent extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'streamable-content';

    /**
     * Props that don't change the id: the state and text.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['state', 'status', 'stopLabel', 'actions'];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string  $target  The wire:stream target.
     * @param  string  $state  waiting, streaming, done, error or stopped.
     * @param  string|null  $status  The status line's text. A `status` slot overrides it.
     * @param  string|null  $stopLabel  The Stop button's text. Defaults to "Stop", translated.
     * @param  string|null  $actions  Buttons under the content. An `actions` slot overrides it.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public string $target,
        public string $state = 'streaming',
        public ?string $status = null,
        public ?string $stopLabel = null,
        public ?string $actions = null,
        public array $classes = [],
    ) {}

    /**
     * Whether wire:stream is available (Livewire 4).
     *
     * @since 2.0.0
     */
    public function supportsStreaming(): bool
    {
        return LivewireHelper::supportsWireStream();
    }

    /**
     * Whether the stream is still running: waiting or streaming.
     *
     * @since 3.0.0
     */
    public function isBusy(): bool
    {
        return in_array($this->state, ['waiting', 'streaming'], true);
    }

    /**
     * The status line's text: `status`, else the state's, translated.
     *
     * @since 3.0.0
     */
    public function statusText(): string
    {
        if (null !== $this->status && '' !== $this->status) {
            return $this->status;
        }

        return match ($this->state) {
            'waiting' => __('Waiting for a response…'),
            'done'    => __('Done'),
            'error'   => __('The response was interrupted.'),
            'stopped' => __('Stopped'),
            default   => __('Writing…'),
        };
    }

    /**
     * The status icon for a finished state, or null.
     *
     * @since 3.0.0
     */
    public function statusIcon(): ?string
    {
        return match ($this->state) {
            'done'    => 'circle-check',
            'error'   => 'circle-alert',
            'stopped' => 'square',
            default   => null,
        };
    }

    /**
     * The Stop button's text: `stopLabel`, else "Stop", translated.
     *
     * @since 3.0.0
     */
    public function stopText(): string
    {
        return null !== $this->stopLabel && '' !== $this->stopLabel ? $this->stopLabel : __('Stop');
    }

    /**
     * Get the view that represents the component.
     *
     * @since 2.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.streamable-content');
    }
}
