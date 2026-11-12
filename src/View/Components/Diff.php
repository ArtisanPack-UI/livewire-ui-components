<?php

/**
 * Diff Component
 *
 * The design-system Diff (#61): the changes between two versions of a text.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      1.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\Support\DiffBuilder;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;

/**
 * Diff Component Class
 *
 * Renders `.apui-diff` with data-view (unified or split): the header (icon,
 * file, counts, actions) and the table of hunks and lines, computed on the
 * server by DiffBuilder, the same rows React and Vue render. Changed words
 * are `ins` and `del`; every line has a sign. The table stays LTR (D-004).
 * The `actions` slot holds header controls. Every attribute lands on the
 * root.
 *
 * 2.x `old` and `new` still work, as `oldContent` and `newContent`. The
 * 2.x diff2html `config` is gone.
 *
 * @since 1.0.0
 */
class Diff extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * The 2.x props read from the attributes, then left off the root.
     *
     * @since 3.0.0
     */
    public const DEPRECATED_ATTRIBUTES = ['old', 'new', 'config'];

    /**
     * Create a new component instance.
     *
     * @since 1.0.0
     *
     * @param  string|null  $oldContent  The earlier version.
     * @param  string|null  $newContent  The later version.
     * @param  string|null  $fileName  What changed, in the header.
     * @param  string  $view  unified or split.
     * @param  bool  $wordLevel  Mark the changed words.
     * @param  int  $context  Unchanged lines around each change.
     * @param  string|null  $countsLabel  The counts' text for assistive technology.
     * @param  string|null  $emptyLabel  Shown when nothing changed.
     * @param  string|null  $actions  Header controls. The `actions` slot replaces it.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public ?string $oldContent = null,
        public ?string $newContent = null,
        public ?string $fileName = null,
        public string $view = 'unified',
        public bool $wordLevel = true,
        public int $context = 3,
        public ?string $countsLabel = null,
        public ?string $emptyLabel = null,
        public ?string $actions = null,
        public array $classes = [],
    ) {
        $this->countsLabel ??= __('{additions} additions, {deletions} deletions');
        $this->emptyLabel ??= __('No changes');
    }

    /**
     * The view, falling back to `unified`.
     *
     * @since 3.0.0
     */
    public function viewValue(): string
    {
        return 'split' === $this->view ? 'split' : 'unified';
    }

    /**
     * The diff, with the 2.x `old` and `new` as fallbacks.
     *
     * @since 3.0.0
     *
     * @return array{hunks: list<array{header: string, lines: list<array>}>, additions: int, deletions: int}
     */
    public function result(): array
    {
        $old = $this->oldContent ?? $this->attributes->get('old');
        $new = $this->newContent ?? $this->attributes->get('new');

        return DiffBuilder::diff(
            is_scalar($old) ? (string) $old : null,
            is_scalar($new) ? (string) $new : null,
            $this->context,
            $this->wordLevel,
        );
    }

    /**
     * A hunk's lines as split-view rows.
     *
     * @since 3.0.0
     *
     * @return list<array{old: array, new: array}>
     */
    public function splitRows(array $lines): array
    {
        return DiffBuilder::splitRows($lines);
    }

    /**
     * The counts label with the numbers filled in.
     *
     * @since 3.0.0
     */
    public function countsText(int $additions, int $deletions): string
    {
        return strtr((string) $this->countsLabel, [
            '{additions}' => (string) $additions,
            '{deletions}' => (string) $deletions,
        ]);
    }

    /**
     * A side's text, with changed words in `del` (deletions) or `ins`
     * (additions), escaped and on one line so `pre-wrap` keeps it exact.
     *
     * @since 3.0.0
     *
     * @param  list<array{text: string, changed: bool}>  $segments  The line's runs.
     * @param  string  $type  The side's type: ctx, add, del or empty.
     */
    public function codeHtml(array $segments, string $type): HtmlString
    {
        $tag  = 'del' === $type ? 'del' : 'ins';
        $html = '';
        foreach ($segments as $segment) {
            $text  = e($segment['text']);
            $html .= $segment['changed'] && in_array($type, ['add', 'del'], true)
                ? '<'.$tag.' data-scope="diff" data-part="'.$tag.'"'.$this->partClass($tag)->toHtml().'>'.$text.'</'.$tag.'>'
                : $text;
        }

        return new HtmlString($html);
    }

    /**
     * A cell's sign: + for an addition, − for a deletion, a space otherwise.
     *
     * @since 3.0.0
     */
    public function sign(string $type): string
    {
        return match ($type) {
            'add'   => '+',
            'del'   => "\u{2212}",
            'ctx'   => ' ',
            default => '',
        };
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.diff');
    }
}
