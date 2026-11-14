<?php

/**
 * Spotlight Component
 *
 * The design-system Spotlight (#57): a command palette on the behavior
 * engine's dialog and combobox machines.
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
use ArtisanPack\LivewireUiComponents\View\Concerns\HasStablePartIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Js;
use Throwable;

/**
 * Spotlight Component Class
 *
 * Renders `apuiSpotlight` (D-019): an optional trigger, then the backdrop
 * and the positioner holding the `.apui-spotlight` panel (the dialog's
 * content part) with the search row, the listbox of grouped results and the
 * footer. `groups` render on the server and are filtered as the user types;
 * `url` fetches more as JSON. `wire:model` binds the open state through
 * `$wire.entangle()`. `class` lands on the panel; every other attribute
 * lands on the element that holds the Alpine component, where `open-change`,
 * `select` and `search-change` are dispatched.
 *
 * @since 1.0.0
 */
class Spotlight extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'spotlight';

    /**
     * Props that change between renders without changing the id: state, data and text.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = [
        'open', 'groups', 'loading', 'label', 'placeholder', 'searchText', 'emptyLabel', 'emptyText', 'noResultsText', 'loadingLabel', 'loadingText', 'recentLabel',
        'closeLabel', 'navigateLabel', 'selectLabel', 'trigger', 'footer',
    ];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  bool  $open  Whether it's open. `wire:model` binds it instead.
     * @param  array<int, array<string, mixed>>  $groups  The results: `[ 'id', 'label', 'items' => [ [ 'value', 'label', 'description', 'href', 'icon', 'color', 'shortcut', 'keywords', 'disabled' ] ] ]`.
     * @param  bool  $remote  The page searches: groups are the results for the query and aren't filtered here.
     * @param  string|null  $url  An endpoint returning results as JSON for `?search=…`. Defaults to the `artisanpack.spotlight` route when there are no groups.
     * @param  bool  $navigate  Adds `wire:navigate` to internal link items (default true). Off-site and `#` links never get it.
     * @param  bool  $loading  Shows the loading row and marks the results busy.
     * @param  string  $shortcut  The global shortcut, such as `mod+k`. Empty turns it off.
     * @param  bool  $recent  Lists the most recent choices first while the query is empty. Off by default: they're kept in the browser's localStorage.
     * @param  string|null  $label  The palette's accessible name. Defaults to "Search", translated.
     * @param  string|null  $placeholder  The query's placeholder. Defaults to "Search…", translated.
     * @param  string|null  $searchText  Deprecated: use `placeholder`.
     * @param  string|null  $emptyLabel  Shown when nothing matches. Defaults to "No results.", translated. An `empty` slot overrides it.
     * @param  string|null  $emptyText  Deprecated: use `emptyLabel`.
     * @param  string|null  $noResultsText  Deprecated: use `emptyLabel`.
     * @param  string|null  $loadingLabel  Shown while loading. Defaults to "Searching…", translated.
     * @param  string|null  $loadingText  Deprecated: use `loadingLabel`.
     * @param  string|null  $recentLabel  The Recent group's label. Defaults to "Recent", translated.
     * @param  string|null  $closeLabel  The Esc button's accessible name. Defaults to "Close", translated.
     * @param  string|null  $navigateLabel  The footer's hint beside the arrow keys. Defaults to "Navigate", translated.
     * @param  string|null  $selectLabel  The footer's hint beside the Enter key. Defaults to "Open", translated.
     * @param  string|null  $trigger  Content for a trigger button. A `trigger` slot overrides it.
     * @param  string|null  $footer  Replaces the footer's key hints. A `footer` slot overrides it.
     * @param  bool  $modal  Traps focus, locks scroll and hides the rest of the page from assistive technology.
     * @param  bool  $portal  Renders the palette at the end of the body (x-teleport). Off renders it in place.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public bool $open = false,
        public array $groups = [],
        public bool $remote = false,
        public ?string $url = null,
        public bool $navigate = true,
        public bool $loading = false,
        public string $shortcut = 'mod+k',
        public bool $recent = false,
        public ?string $label = null,
        public ?string $placeholder = null,
        public ?string $searchText = null,
        public ?string $emptyLabel = null,
        public ?string $emptyText = null,
        public ?string $noResultsText = null,
        public ?string $loadingLabel = null,
        public ?string $loadingText = null,
        public ?string $recentLabel = null,
        public ?string $closeLabel = null,
        public ?string $navigateLabel = null,
        public ?string $selectLabel = null,
        public ?string $trigger = null,
        public ?string $footer = null,
        public bool $modal = true,
        public bool $portal = true,
        public array $classes = [],
    ) {}

    /**
     * The `x-data` expression: `apuiSpotlight()` with the machine id, the
     * open state and the options the client needs.
     *
     * @since 3.0.0
     *
     * @return string The expression.
     */
    public function alpineData(): string
    {
        $options = [
            'id'           => $this->baseId(),
            'modal'        => $this->modal,
            'label'        => $this->labelText(),
            'shortcut'     => $this->normalizedShortcut(),
            'recent'       => $this->recent,
            'recentLabel'  => $this->text($this->recentLabel, 'Recent'),
            'remote'       => $this->remote,
            'url'          => $this->endpoint(),
            'loading'      => $this->loading,
            'translations' => [
                'resultsCount' => __(':count result|:count results'),
                'noResults'    => $this->emptyTextValue(),
                'loading'      => $this->loadingTextValue(),
            ],
        ];

        return sprintf(
            'apuiSpotlight(Object.assign(%s, { open: %s }))',
            Js::from(array_filter($options, static fn (mixed $value): bool => null !== $value))->toHtml(),
            $this->openExpression(),
        );
    }

    /**
     * The endpoint results are fetched from: `url`, else the package's
     * `artisanpack.spotlight` route when there are no groups and the page
     * doesn't search itself, as in 2.x.
     *
     * @since 3.0.0
     *
     * @return string|null The URL, or null for none.
     */
    public function endpoint(): ?string
    {
        if (null !== $this->url && '' !== $this->url) {
            return $this->url;
        }

        if ([] !== $this->groups || $this->remote) {
            return null;
        }

        try {
            return Route::has('artisanpack.spotlight') ? route('artisanpack.spotlight', absolute: false) : null;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * The groups to render: each with an id, an optional label and its items,
     * every item with a value (its own, else its href, else its label).
     * Items without a label are dropped.
     *
     * @since 3.0.0
     *
     * @return list<array{id: string, label: string|null, items: list<array<string, mixed>>}> The groups.
     */
    public function resultGroups(): array
    {
        $groups = [];
        foreach (array_values($this->groups) as $index => $group) {
            if (! is_array($group) || ! is_array($group['items'] ?? null)) {
                continue;
            }

            $id    = (string) ($group['id'] ?? 'group-'.$index);
            $items = [];
            foreach ($group['items'] as $item) {
                $label = is_array($item) ? ($item['label'] ?? $item['name'] ?? null) : null;
                if (! is_string($label) || '' === $label) {
                    continue;
                }

                $href  = $item['href'] ?? $item['link'] ?? null;
                $entry = array_filter([
                    'label'       => $label,
                    'description' => is_string($item['description'] ?? null) ? $item['description'] : null,
                    // Only relative, http(s), mailto, tel and sms links (never `javascript:`).
                    'href'        => is_string($href) ? SafeUrl::href($href) : null,
                    'icon'        => is_string($item['icon'] ?? null) ? $item['icon'] : null,
                    'color'       => is_string($item['color'] ?? null) ? $item['color'] : null,
                    'shortcut'    => $item['shortcut'] ?? null,
                    'keywords'    => is_array($item['keywords'] ?? null) ? array_values(array_map('strval', $item['keywords'])) : null,
                    'disabled'    => ! empty($item['disabled']) ? true : null,
                ], static fn (mixed $value): bool => null !== $value);

                $entry['value'] = (string) ($item['value'] ?? $entry['href'] ?? $label);
                $items[]        = $entry;
            }

            $groups[] = [
                'id'    => $id,
                'label' => is_string($group['label'] ?? null) && '' !== $group['label'] ? $group['label'] : null,
                'items' => $items,
            ];
        }

        return $groups;
    }

    /**
     * Whether any item renders on the server.
     *
     * @since 3.0.0
     *
     * @return bool True when there's at least one item.
     */
    public function hasItems(): bool
    {
        foreach ($this->resultGroups() as $group) {
            if ([] !== $group['items']) {
                return true;
            }
        }

        return false;
    }

    /**
     * The value of the result an open palette starts on: the first one that
     * isn't disabled, as the machine highlights it on open.
     *
     * @since 3.0.0
     *
     * @return string|null The value, or null when nothing can be active.
     */
    public function activeValue(): ?string
    {
        foreach ($this->resultGroups() as $group) {
            foreach ($group['items'] as $item) {
                if (empty($item['disabled'])) {
                    return $item['value'];
                }
            }
        }

        return null;
    }

    /**
     * An item's id: the combobox machine's own scheme for options, so the
     * server and the machine agree on it (and on `aria-activedescendant`).
     *
     * @since 3.0.0
     *
     * @param  string  $value  The item's value.
     *
     * @return string The id.
     */
    public function itemId(string $value): string
    {
        return 'combobox:'.$this->baseId().':option:'.$value;
    }

    /**
     * An item's display shortcut as keys: a list as given, a string split on spaces and `+`.
     *
     * @since 3.0.0
     *
     * @param  mixed  $shortcut  The item's shortcut.
     *
     * @return list<string> The keys.
     */
    public function itemKeys(mixed $shortcut): array
    {
        if (is_array($shortcut)) {
            return array_values(array_filter(array_map('strval', $shortcut), static fn (string $key): bool => '' !== $key));
        }

        if (! is_string($shortcut) || '' === $shortcut) {
            return [];
        }

        return array_values(array_filter(preg_split('/\s+|\+(?!$)/u', $shortcut) ?: [], static fn (string $key): bool => '' !== $key));
    }

    /**
     * The shortcut parsed: its key and modifiers, or null when it's off.
     *
     * @since 3.0.0
     *
     * @return array{key: string, mod: bool, ctrl: bool, meta: bool, alt: bool, shift: bool}|null The shortcut.
     */
    public function parsedShortcut(): ?array
    {
        $shortcut = $this->normalizedShortcut();
        if ('' === $shortcut) {
            return null;
        }

        $parts = array_values(array_filter(array_map(static fn (string $part): string => strtolower(trim($part)), explode('+', $shortcut)), static fn (string $part): bool => '' !== $part));
        $key   = str_ends_with($shortcut, '++') ? '+' : array_pop($parts);
        if (null === $key || '' === $key) {
            return null;
        }

        $has = static fn (string ...$names): bool => [] !== array_intersect($parts, $names);

        return [
            'key'   => $key,
            'mod'   => $has('mod'),
            'ctrl'  => $has('ctrl', 'control'),
            'meta'  => $has('meta', 'cmd', 'command'),
            'alt'   => $has('alt', 'option'),
            'shift' => $has('shift'),
        ];
    }

    /**
     * The shortcut's keys for the trigger's hint, as the server renders
     * them (Ctrl for `mod`); the client shows ⌘ on Apple platforms.
     *
     * @since 3.0.0
     *
     * @return list<string> The keys.
     */
    public function shortcutKeys(): array
    {
        $parsed = $this->parsedShortcut();
        if (null === $parsed) {
            return [];
        }

        $keys = [];
        if ($parsed['ctrl'] || $parsed['mod']) {
            $keys[] = 'Ctrl';
        }
        if ($parsed['meta']) {
            $keys[] = 'Meta';
        }
        if ($parsed['alt']) {
            $keys[] = 'Alt';
        }
        if ($parsed['shift']) {
            $keys[] = 'Shift';
        }
        $keys[] = 1 === mb_strlen($parsed['key']) ? mb_strtoupper($parsed['key']) : $parsed['key'];

        return $keys;
    }

    /**
     * The shortcut as `aria-keyshortcuts`: `mod+k` is `Meta+K Control+K`.
     *
     * @since 3.0.0
     *
     * @return string|null The value, or null when it's off.
     */
    public function ariaKeyShortcuts(): ?string
    {
        $parsed = $this->parsedShortcut();
        if (null === $parsed) {
            return null;
        }

        $names = [' ' => 'Space', 'space' => 'Space', 'enter' => 'Enter', 'escape' => 'Escape', 'esc' => 'Escape'];
        $key   = $names[$parsed['key']] ?? (1 === mb_strlen($parsed['key']) ? mb_strtoupper($parsed['key']) : $parsed['key']);
        $tail  = array_values(array_filter([$parsed['alt'] ? 'Alt' : null, $parsed['shift'] ? 'Shift' : null, $key]));
        $combo = static fn (?string ...$modifiers): string => implode('+', [...array_filter($modifiers), ...$tail]);

        if ($parsed['mod']) {
            return $combo('Meta').' '.$combo('Control');
        }

        return $combo($parsed['ctrl'] ? 'Control' : null, $parsed['meta'] ? 'Meta' : null);
    }

    /**
     * The shortcut in this version's spelling. The 2.x spelling (Alpine key
     * modifiers joined with dots: `meta.g`, `ctrl.k`) still reads, with
     * `meta` and `cmd` as `mod`, so it follows the platform.
     *
     * @since 3.0.0
     *
     * @return string The shortcut, such as `mod+k`; empty when it's off.
     */
    public function normalizedShortcut(): string
    {
        $shortcut = trim($this->shortcut);
        if ('' === $shortcut || str_contains($shortcut, '+') || ! str_contains($shortcut, '.')) {
            return $shortcut;
        }

        $parts = array_map(
            static fn (string $part): string => in_array(strtolower($part), ['meta', 'cmd', 'super'], true) ? 'mod' : strtolower($part),
            array_values(array_filter(explode('.', $shortcut), static fn (string $part): bool => '' !== $part)),
        );

        return implode('+', $parts);
    }

    /**
     * The query's placeholder: `placeholder`, the deprecated `searchText`,
     * else "Search…", translated.
     *
     * @since 3.0.0
     *
     * @return string The placeholder.
     */
    public function placeholderText(): string
    {
        return $this->text($this->placeholder ?? $this->searchText, 'Search…');
    }

    /**
     * The empty state's text: `emptyLabel`, the deprecated `emptyText` or
     * `noResultsText`, else "No results.", translated.
     *
     * @since 3.0.0
     *
     * @return string The text.
     */
    public function emptyTextValue(): string
    {
        return $this->text($this->emptyLabel ?? $this->emptyText ?? $this->noResultsText, 'No results.');
    }

    /**
     * The loading row's text: `loadingLabel`, the deprecated `loadingText`,
     * else "Searching…", translated.
     *
     * @since 3.0.0
     *
     * @return string The text.
     */
    public function loadingTextValue(): string
    {
        return $this->text($this->loadingLabel ?? $this->loadingText, 'Searching…');
    }

    /**
     * The Esc button's accessible name: its visible key, then `closeLabel`
     * (else "Close"), through one translatable string so a locale can reorder it.
     *
     * @since 3.0.0
     *
     * @return string The name, such as "Esc, Close".
     */
    public function closeTriggerLabel(): string
    {
        return __('Esc, :label', ['label' => $this->text($this->closeLabel, 'Close')]);
    }

    /**
     * The palette's accessible name: `label`, else "Search", translated.
     *
     * @since 3.0.0
     *
     * @return string The name.
     */
    public function labelText(): string
    {
        return $this->text($this->label, 'Search');
    }

    /**
     * A built-in string: the prop when it's set, else the default, translated.
     *
     * @since 3.0.0
     *
     * @param  string|null  $value  The prop.
     * @param  string  $default  The English default.
     *
     * @return string The text.
     */
    public function text(?string $value, string $default): string
    {
        return null !== $value && '' !== $value ? $value : __($default);
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.spotlight');
    }
}
