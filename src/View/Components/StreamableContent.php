<?php

declare(strict_types=1);
/**
 * Streamable Content Component
 *
 * A component for displaying streaming content, such as AI-generated text.
 * This component supports Livewire 4's wire:stream directive for real-time
 * content streaming.
 *
 * @author     Jacob Martella
 * @copyright  2024 Jacob Martella
 * @license    MIT
 *
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      2.0.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\Support\LivewireHelper;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * StreamableContent Class
 *
 * Provides a container for streaming content with wire:stream support.
 * In Livewire 4+, content can be streamed in real-time (ideal for AI responses).
 * In Livewire 3, the content is displayed statically.
 *
 * @since 2.0.0
 */
class StreamableContent extends BaseComponent
{
    public string $uuid;

    /**
     * Create a new StreamableContent component instance.
     *
     * @since 2.0.0
     *
     * @param  string  $target  The wire:stream target identifier.
     * @param  string|null  $id  Optional custom ID for the element.
     * @param  string  $tag  The HTML tag to use (default: 'div').
     * @param  bool  $prose  Whether to apply prose styling for rich text.
     * @param  string|null  $placeholder  Placeholder text shown while waiting for content.
     * @param  bool  $showCursor  Whether to show a blinking cursor during streaming.
     */
    public function __construct(
        public string $target,
        public ?string $id = null,
        public string $tag = 'div',
        public bool $prose = false,
        public ?string $placeholder = null,
        public bool $showCursor = false,
    ) {
        $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
    }

    /**
     * Check if wire:stream is supported (Livewire 4+).
     *
     * @since 2.0.0
     *
     * @return bool True if streaming is supported.
     */
    public function supportsStreaming(): bool
    {
        return LivewireHelper::supportsWireStream();
    }

    /**
     * Render the component.
     *
     * @since 2.0.0
     */
    public function render(): View|Closure|string
    {
        return view('livewire-ui-components::components.streamable-content');
    }
}
