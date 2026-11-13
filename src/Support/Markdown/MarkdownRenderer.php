<?php

/**
 * Markdown rendering for the design-system Markdown component.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Support\Markdown;

use ArtisanPack\LivewireUiComponents\Support\SafeUrl;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\HtmlBlock;
use League\CommonMark\Extension\CommonMark\Node\Inline\HtmlInline;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\TaskList\TaskListItemMarker;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Node\Inline\Newline;
use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Node\Node;
use League\CommonMark\Node\NodeIterator;
use League\CommonMark\Node\StringContainerInterface;
use League\CommonMark\Util\RegexHelper;

/**
 * Renders GitHub-flavored Markdown (tables, task lists, strikethrough,
 * autolinks) with league/commonmark: the GFM extension, `html_input`
 * escape and `allow_unsafe_links` off. React and Vue write the same HTML
 * with `renderMarkdown` in `@artisanpack-ui/behavior` (`src/data/markdown.ts`);
 * the shared vectors in the design system's
 * `packages/behavior/test/fixtures/markdown-vectors.json` hold both to it.
 *
 * On top of commonmark's output, both stacks:
 *
 * - drop links and images whose URL isn't safe ({@see SafeUrl}), keeping
 *   their text;
 * - put a task item's checkbox and first line in a `<label>`, and mark the
 *   item `data-scope="markdown" data-part="task"`;
 * - write table alignment as `data-align` (start, center, end);
 * - with `sanitize` off, keep allowlisted raw HTML ({@see MarkdownHtml})
 *   instead of escaping it. Script-capable markup never renders.
 *
 * Untrusted source can't make the work unbounded: blocks nest at most
 * {@see self::MAX_NESTING} deep (deeper lines stay text) and a paragraph,
 * heading or cell keeps at most {@see self::MAX_DELIMITERS} emphasis
 * delimiter runs (the rest stay literal). `renderMarkdown` applies the same
 * limits (`MARKDOWN_MAX_NESTING`, `MARKDOWN_MAX_DELIMITERS`).
 *
 * @since 3.0.0
 */
final class MarkdownRenderer
{
    /**
     * How deep blocks nest (commonmark's `max_nesting_level`): a block quote
     * is one level, a list two.
     *
     * @since 3.0.0
     */
    public const MAX_NESTING = 100;

    /**
     * Delimiter runs per inline container (commonmark's `max_delimiters_per_line`).
     *
     * @since 3.0.0
     */
    public const MAX_DELIMITERS = 500;

    /**
     * One converter per sanitize setting.
     *
     * @var array<string, MarkdownConverter>
     */
    private static array $converters = [];

    /**
     * The HTML for Markdown source.
     *
     * @since 3.0.0
     *
     * @param  string|null  $source  The Markdown.
     * @param  bool  $sanitize  Escape raw HTML (true) or keep the allowlisted tags (false).
     *
     * @return string The HTML, ending in a newline unless it's empty.
     */
    public static function render(?string $source, bool $sanitize = true): string
    {
        if (null === $source || '' === $source) {
            return '';
        }

        return (string) self::converter($sanitize)->convert($source);
    }

    /**
     * Drops unsafe links and images, and labels task items' checkboxes.
     *
     * @since 3.0.0
     *
     * @param  DocumentParsedEvent  $event  The parsed document.
     */
    public static function adjust(DocumentParsedEvent $event): void
    {
        $links   = [];
        $images  = [];
        $markers = [];

        foreach ($event->getDocument()->iterator() as $node) {
            if ($node instanceof Link) {
                $links[] = $node;
            } elseif ($node instanceof Image) {
                $images[] = $node;
            } elseif ($node instanceof TaskListItemMarker) {
                $markers[] = $node;
            }
        }

        foreach ($images as $image) {
            if (null === SafeUrl::media($image->getUrl()) || RegexHelper::isLinkPotentiallyUnsafe($image->getUrl())) {
                $image->replaceWith(new Text(self::altText($image)));
            }
        }

        foreach ($links as $link) {
            if (null === SafeUrl::href($link->getUrl()) || RegexHelper::isLinkPotentiallyUnsafe($link->getUrl())) {
                foreach ($link->children() as $child) {
                    $link->insertBefore($child);
                }
                $link->detach();
            }
        }

        foreach ($markers as $marker) {
            self::label($marker);
        }
    }

    /**
     * The converter for a sanitize setting.
     *
     * @param  bool  $sanitize  Whether raw HTML is escaped.
     *
     * @return MarkdownConverter The converter.
     */
    private static function converter(bool $sanitize): MarkdownConverter
    {
        $key = $sanitize ? 'escape' : 'allow';

        if (isset(self::$converters[$key])) {
            return self::$converters[$key];
        }

        $environment = new Environment([
            'html_input'              => $key,
            'allow_unsafe_links'      => false,
            'max_nesting_level'       => self::MAX_NESTING,
            'max_delimiters_per_line' => self::MAX_DELIMITERS,
            'table'                   => [
                'alignment_attributes' => [
                    'left'   => ['data-align' => 'start'],
                    'center' => ['data-align' => 'center'],
                    'right'  => ['data-align' => 'end'],
                ],
            ],
        ]);

        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new GithubFlavoredMarkdownExtension);
        $environment->addRenderer(TaskLabel::class, new TaskLabelRenderer);
        $environment->addEventListener(DocumentParsedEvent::class, [self::class, 'adjust']);

        if (! $sanitize) {
            $environment->addRenderer(HtmlBlock::class, new RawHtmlRenderer, 100);
            $environment->addRenderer(HtmlInline::class, new RawHtmlRenderer, 100);
        }

        return self::$converters[$key] = new MarkdownConverter($environment);
    }

    /**
     * Moves a task marker and the rest of its paragraph into a label, and
     * marks the list item as the task part.
     *
     * @param  TaskListItemMarker  $marker  The checkbox.
     */
    private static function label(TaskListItemMarker $marker): void
    {
        $paragraph = $marker->parent();
        if (null === $paragraph) {
            return;
        }

        $label    = new TaskLabel;
        $siblings = [];
        for ($node = $marker; null !== $node; $node = $node->next()) {
            $siblings[] = $node;
        }

        $marker->insertBefore($label);
        foreach ($siblings as $sibling) {
            $label->appendChild($sibling);
        }

        $item = $paragraph->parent();
        if (null !== $item) {
            $item->data->set('attributes/data-scope', 'markdown');
            $item->data->set('attributes/data-part', 'task');
        }
    }

    /**
     * An image's alt text, as commonmark writes it.
     *
     * @param  Image  $image  The image.
     *
     * @return string The alt text.
     */
    private static function altText(Node $image): string
    {
        $alt = '';
        foreach (new NodeIterator($image) as $node) {
            if ($node instanceof StringContainerInterface) {
                $alt .= $node->getLiteral();
            } elseif ($node instanceof Newline) {
                $alt .= "\n";
            }
        }

        return $alt;
    }
}
