<?php

/**
 * Renders raw HTML through the allowlist.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Support\Markdown;

use League\CommonMark\Node\Node;
use League\CommonMark\Node\RawMarkupContainerInterface;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;

/**
 * Renders raw HTML blocks and inline tags with `sanitize` off: the
 * allowlisted formatting tags with safe attributes, every other tag escaped
 * ({@see MarkdownHtml::filter()}). Never script-capable markup.
 *
 * @since 3.0.0
 */
final class RawHtmlRenderer implements NodeRendererInterface
{
    /**
     * Renders the filtered markup.
     *
     * @since 3.0.0
     *
     * @param  Node  $node  An HtmlBlock or HtmlInline.
     * @param  ChildNodeRendererInterface  $childRenderer  Unused: raw HTML has no children.
     *
     * @return string The filtered HTML.
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
    {
        return $node instanceof RawMarkupContainerInterface ? MarkdownHtml::filter($node->getLiteral()) : '';
    }
}
