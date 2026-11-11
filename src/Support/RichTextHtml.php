<?php

/**
 * Rich text sanitizing for the RichTextEditor.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Support;

use DOMDocument;
use DOMElement;
use DOMNode;
use Illuminate\Support\Str;

/**
 * Keeps only the formats the RichTextEditor makes, mirroring
 * `sanitizeRichText()` in the design system's behavior engine: paragraphs,
 * headings, lists, quotes, code, links with safe URLs and images with safe
 * URLs. Other tags are unwrapped to their text; scripts, styles, embeds and
 * form controls are dropped with their content. Every attribute but `href`,
 * `src` and `alt` is dropped.
 *
 * @since 3.0.0
 */
final class RichTextHtml
{
    /**
     * Tags kept (attributes aside).
     *
     * @var list<string>
     */
    private const ALLOWED = ['p', 'br', 'strong', 'em', 'u', 's', 'h2', 'h3', 'h4', 'ul', 'ol', 'li', 'blockquote', 'code', 'pre', 'a', 'img', 'hr'];

    /**
     * Tags renamed to an allowed one.
     *
     * @var array<string, string>
     */
    private const RENAMED = ['b' => 'strong', 'i' => 'em', 'strike' => 's', 'del' => 's', 'h1' => 'h2', 'h5' => 'h4', 'h6' => 'h4', 'div' => 'p'];

    /**
     * Tags dropped with everything inside them.
     *
     * @var list<string>
     */
    private const DROPPED = ['script', 'style', 'template', 'iframe', 'object', 'embed', 'noscript', 'svg', 'math', 'head', 'title', 'meta', 'link', 'form', 'input', 'button', 'select', 'textarea'];

    /**
     * Sanitizes editor HTML.
     *
     * @since 3.0.0
     *
     * @param  string|null  $html  Untrusted HTML.
     *
     * @return string The editor's formats only.
     */
    public static function sanitize(?string $html): string
    {
        if (null === $html || '' === trim($html)) {
            return '';
        }

        $document = new DOMDocument;
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="utf-8"?><!doctype html><html><body>'.$html.'</body></html>', LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $body = $document->getElementsByTagName('body')->item(0);

        return null === $body ? '' : self::children($body);
    }

    /**
     * Reads a value in the output format as sanitized editor HTML.
     *
     * @since 3.0.0
     *
     * @param  string|null  $value  The value.
     * @param  string  $output  html or markdown.
     *
     * @return string The HTML.
     */
    public static function toHtml(?string $value, string $output = 'html'): string
    {
        if ('markdown' === $output) {
            return self::sanitize(Str::markdown((string) $value, ['html_input' => 'escape', 'allow_unsafe_links' => false]));
        }

        return self::sanitize($value);
    }

    /**
     * Serializes a node's children.
     */
    private static function children(DOMNode $node): string
    {
        $html = '';
        foreach ($node->childNodes as $child) {
            $html .= self::node($child);
        }

        return $html;
    }

    /**
     * Serializes one node.
     */
    private static function node(DOMNode $node): string
    {
        if (XML_TEXT_NODE === $node->nodeType) {
            return htmlspecialchars((string) $node->textContent, ENT_NOQUOTES | ENT_HTML5, 'UTF-8');
        }

        if (! $node instanceof DOMElement) {
            return '';
        }

        $name = strtolower($node->tagName);
        if (in_array($name, self::DROPPED, true)) {
            return '';
        }

        $tag = self::RENAMED[$name] ?? $name;
        if (! in_array($tag, self::ALLOWED, true)) {
            return self::children($node);
        }

        if ('br' === $tag || 'hr' === $tag) {
            return '<'.$tag.'>';
        }

        if ('img' === $tag) {
            $src = self::imageUrl($node->getAttribute('src'));

            return null === $src ? '' : '<img src="'.e($src).'" alt="'.e($node->getAttribute('alt')).'">';
        }

        $inner = self::children($node);
        if ('a' === $tag) {
            $href = self::linkUrl($node->getAttribute('href'));

            return null === $href ? $inner : '<a href="'.e($href).'">'.$inner.'</a>';
        }

        return '<'.$tag.'>'.$inner.'</'.$tag.'>';
    }

    /**
     * A link URL: relative, http, https, mailto or tel.
     */
    private static function linkUrl(string $url): ?string
    {
        return self::withScheme($url, ['http', 'https', 'mailto', 'tel']);
    }

    /**
     * An image URL: relative, http or https.
     */
    private static function imageUrl(string $url): ?string
    {
        return self::withScheme($url, ['http', 'https']);
    }

    /**
     * The URL when it's relative or its scheme is allowed, read the way
     * browsers read it (ignoring case, whitespace and control characters).
     *
     * @param  list<string>  $schemes  Allowed schemes.
     */
    private static function withScheme(string $url, array $schemes): ?string
    {
        $url = trim($url);
        if ('' === $url) {
            return null;
        }

        $clean = strtolower((string) preg_replace('/[\x00-\x20\x7f-\x9f]/u', '', $url));
        if (! preg_match('/^([a-z][a-z0-9+.-]*):/', $clean, $match)) {
            return $url;
        }

        return in_array($match[1], $schemes, true) ? $url : null;
    }
}
