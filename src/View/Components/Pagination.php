<?php

/**
 * Pagination Component
 *
 * The design-system Pagination (#56): previous, the pages and next in a nav
 * landmark, from explicit numbers or a Laravel paginator.
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

use ArtisanPack\LivewireUiComponents\Support\SafeUrl;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\View\View;
use Livewire\Livewire;
use Throwable;

/**
 * Pagination Component Class
 *
 * Renders `.apui-pagination` (a `nav`) with the summary, previous, page,
 * ellipsis and next parts. With explicit numbers every control is a button
 * that dispatches `page-change`; with a paginator in `rows` they are links
 * to its URLs, which call `gotoPage()` instead inside a Livewire component
 * using WithPagination. Every attribute lands on the root.
 *
 * @since 1.0.0
 */
class Pagination extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  int  $page  The current page, from 1.
     * @param  int|null  $pageCount  How many pages there are. Defaults to total / perPage.
     * @param  int|null  $total  How many items the list has.
     * @param  int  $perPage  How many items a page holds.
     * @param  int  $siblings  Pages shown on each side of the current one.
     * @param  bool  $summary  Shows the summary before the pages.
     * @param  mixed  $rows  A Laravel paginator. It supplies the numbers and the page URLs.
     * @param  string|null  $label  The nav landmark's name. Defaults to __( 'Pagination' ).
     * @param  string|null  $previousLabel  The previous button's name. Defaults to __( 'Previous page' ).
     * @param  string|null  $nextLabel  The next button's name. Defaults to __( 'Next page' ).
     * @param  string|null  $pageLabel  Each page's name, with {page}. Defaults to __( 'Page {page}' ).
     * @param  string|null  $summaryLabel  The summary, with {from}, {to}, {total}, {page} and {pageCount}.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public int $page = 1,
        public ?int $pageCount = null,
        public ?int $total = null,
        public int $perPage = 10,
        public int $siblings = 1,
        public bool $summary = false,
        public mixed $rows = null,
        public ?string $label = null,
        public ?string $previousLabel = null,
        public ?string $nextLabel = null,
        public ?string $pageLabel = null,
        public ?string $summaryLabel = null,
        public array $classes = [],
    ) {
        // `Pagination` alone can resolve to Laravel's pagination.php group on
        // a case-insensitive filesystem, so only a string translation counts.
        $label               = __('Pagination');
        $this->label ??= is_string($label) ? $label : 'Pagination';
        $this->previousLabel ??= __('Previous page');
        $this->nextLabel ??= __('Next page');
        $this->pageLabel ??= __('Page {page}');
    }

    /**
     * The current page, clamped to the page count.
     *
     * @since 3.0.0
     *
     * @return int The page.
     */
    public function currentPage(): int
    {
        $page  = $this->rows instanceof Paginator ? $this->rows->currentPage() : $this->page;
        $count = $this->totalPages();

        return max(1, null === $count ? $page : min($page, $count));
    }

    /**
     * How many pages there are, or null when the paginator doesn't know
     * (a simple or cursor paginator).
     *
     * @since 3.0.0
     *
     * @return int|null The count, at least 1.
     */
    public function totalPages(): ?int
    {
        if ($this->rows instanceof LengthAwarePaginator) {
            return max(1, $this->rows->lastPage());
        }

        if ($this->rows instanceof Paginator || $this->rows instanceof CursorPaginator) {
            return null;
        }

        if (null !== $this->pageCount) {
            return max(1, $this->pageCount);
        }

        if (null !== $this->total) {
            return max(1, (int) ceil($this->total / max(1, $this->perPage)));
        }

        return 1;
    }

    /**
     * The pages and ellipses between previous and next: the first and last
     * pages, `siblings` pages around the current one, and an ellipsis for
     * each gap, keeping the count constant as the page moves.
     *
     * @since 3.0.0
     *
     * @return list<int|null> Page numbers, with null for an ellipsis.
     */
    public function pageItems(): array
    {
        $count = $this->totalPages();

        return null === $count ? [] : self::range($this->currentPage(), $count, max(0, $this->siblings));
    }

    /**
     * A page control's attributes: the tag, href, disabled state and the
     * Livewire call.
     *
     * @since 3.0.0
     *
     * @param  string  $kind  previous, page or next.
     * @param  int|null  $page  The page it goes to (page controls).
     *
     * @return array{tag: string, href: string|null, page: int|null, disabled: bool, current: bool, wireClick: string|null} The control.
     */
    public function control(string $kind, ?int $page = null): array
    {
        $current  = $this->currentPage();
        $count    = $this->totalPages();
        $target   = match ($kind) {
            'previous' => $current - 1,
            'next'     => $current + 1,
            default    => (int) $page,
        };
        $disabled = match ($kind) {
            'previous' => $this->rows instanceof CursorPaginator ? null === $this->rows->previousPageUrl() : $current <= 1,
            'next'     => $this->rows instanceof CursorPaginator || (null === $count && $this->rows instanceof Paginator)
                ? ! $this->rows->hasMorePages()
                : $current >= (int) $count,
            default    => false,
        };
        $isCurrent = 'page' === $kind && $target === $current;

        if (! ($this->rows instanceof Paginator || $this->rows instanceof CursorPaginator)) {
            return ['tag' => 'button', 'href' => null, 'page' => $target, 'disabled' => $disabled, 'current' => $isCurrent, 'wireClick' => null];
        }

        $href = null;
        if (! $disabled) {
            $href = match (true) {
                'previous' === $kind && $this->rows instanceof CursorPaginator => $this->rows->previousPageUrl(),
                'next' === $kind && $this->rows instanceof CursorPaginator     => $this->rows->nextPageUrl(),
                $this->rows instanceof Paginator                               => $this->rows->url($target),
                default                                                        => null,
            };
        }

        // Sanitize before choosing the tag: an unsafe URL renders the button
        // form, never an `<a href="">` that reloads the page.
        $href = SafeUrl::href($href);

        return [
            'tag'       => null === $href ? 'button' : 'a',
            'href'      => $href,
            'page'      => $this->rows instanceof CursorPaginator ? null : $target,
            'disabled'  => $disabled,
            'current'   => $isCurrent,
            'wireClick' => $disabled || $this->rows instanceof CursorPaginator ? null : $this->wireClick($target),
        ];
    }

    /**
     * The control's accessible name for a page.
     *
     * @since 3.0.0
     *
     * @param  int  $page  The page.
     *
     * @return string The name.
     */
    public function pageName(int $page): string
    {
        return str_replace('{page}', (string) $page, (string) $this->pageLabel);
    }

    /**
     * The summary text, or null when it's off or the paginator has no page
     * count.
     *
     * @since 3.0.0
     *
     * @return string|null The summary.
     */
    public function summaryText(): ?string
    {
        $count = $this->totalPages();
        if (! $this->summary || null === $count) {
            return null;
        }

        $page  = $this->currentPage();
        $total = $this->rows instanceof LengthAwarePaginator ? $this->rows->total() : $this->total;
        $per   = $this->rows instanceof Paginator ? $this->rows->perPage() : max(1, $this->perPage);

        if (null === $total) {
            $template = $this->summaryLabel ?? __('Page {page} of {pageCount}');
            $from     = ($page - 1) * $per + 1;
            $to       = $page * $per;
        } else {
            $template = $this->summaryLabel ?? __('Showing {from}–{to} of {total}');
            $from     = 0 === $total ? 0 : min($total, ($page - 1) * $per + 1);
            $to       = min($total, $page * $per);
        }

        return strtr($template, [
            '{from}'      => (string) $from,
            '{to}'        => (string) $to,
            '{total}'     => (string) ($total ?? ''),
            '{page}'      => (string) $page,
            '{pageCount}' => (string) $count,
        ]);
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.pagination');
    }

    /**
     * The page list for a page count: MUI's algorithm with one boundary page.
     *
     * @since 3.0.0
     *
     * @param  int  $page  The current page.
     * @param  int  $count  The page count.
     * @param  int  $siblings  Pages on each side of the current one.
     *
     * @return list<int|null> Page numbers, with null for an ellipsis.
     */
    public static function range(int $page, int $count, int $siblings): array
    {
        if ($count <= 2 * $siblings + 5) {
            return range(1, $count);
        }

        $start   = max(min($page - $siblings, $count - 2 * $siblings - 2), 3);
        $end     = min(max($page + $siblings, 2 * $siblings + 3), $count - 2);
        $items   = [1];
        $items[] = $start > 3 ? null : 2;
        array_push($items, ...range($start, $end));
        $items[] = $end < $count - 2 ? null : $count - 1;
        $items[] = $count;

        return $items;
    }

    /**
     * The Livewire call that goes to a page, when this renders inside a
     * component using WithPagination.
     *
     * @param  int  $page  The page.
     *
     * @return string|null The `wire:click` expression.
     */
    private function wireClick(int $page): ?string
    {
        try {
            $component = class_exists(Livewire::class) ? Livewire::current() : null;
        } catch (Throwable) {
            return null;
        }

        if (! is_object($component) || ! method_exists($component, 'gotoPage') || ! $this->rows instanceof Paginator) {
            return null;
        }

        return sprintf('gotoPage(%d, %s)', $page, json_encode($this->rows->getPageName(), JSON_HEX_APOS | JSON_HEX_QUOT));
    }
}
