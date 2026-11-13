<?php

/**
 * Renders a task label.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Support\Markdown;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use Stringable;

/**
 * Renders a {@see TaskLabel} as `<label>` around its children.
 *
 * @since 3.0.0
 */
final class TaskLabelRenderer implements NodeRendererInterface
{
    /**
     * Renders the label.
     *
     * @since 3.0.0
     *
     * @param  Node  $node  The task label.
     * @param  ChildNodeRendererInterface  $childRenderer  Renders the checkbox and text.
     *
     * @return Stringable The `<label>` element.
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): Stringable
    {
        TaskLabel::assertInstanceOf($node);

        return new HtmlElement('label', [], $childRenderer->renderNodes($node->children()));
    }
}
