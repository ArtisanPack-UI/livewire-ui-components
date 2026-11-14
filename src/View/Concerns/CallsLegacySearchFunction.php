<?php

/**
 * The 2.x `search-function` path for the deprecated Combobox aliases.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Concerns;

use Illuminate\Support\Js;

/**
 * Keeps 2.x server-side search working on the `choices` and `tags` aliases:
 * when `searchable` is set and there's no `search-model`, typing calls the
 * 2.x `search-function` on the Livewire component with the typed text (plus
 * any arguments written as `method(5)`), once it has `min-chars` characters.
 * The using class extends Combobox.
 *
 * @since 3.0.0
 */
trait CallsLegacySearchFunction
{
    /**
     * The 2.x search method called with the typed text, when searchable.
     *
     * @since 3.0.0
     */
    protected ?string $legacySearchFunction = null;

    /**
     * The fewest characters that call the 2.x search method.
     *
     * @since 3.0.0
     */
    protected int $legacyMinChars = 0;

    /**
     * The `x-on:search-change` handler: Combobox's with search-model, else,
     * when searchable, a call to the 2.x search method.
     *
     * @since 3.0.0
     *
     * @return string|null The handler.
     */
    public function searchHandler(): ?string
    {
        $handler = parent::searchHandler();
        if (null !== $handler || null === $this->legacySearchFunction) {
            return $handler;
        }

        $method    = $this->legacySearchFunction;
        $arguments = '';
        if (1 === preg_match('/^([A-Za-z_][A-Za-z0-9_]*)\((.*)\)$/s', $method, $matches)) {
            $method    = $matches[1];
            $arguments = '' !== trim($matches[2]) ? ', '.trim($matches[2]) : '';
        }

        $call = sprintf(
            '(loading = true, Promise.resolve($wire.call(%s, $event.detail.search%s)).finally(() => loading = %s))',
            Js::from($method)->toHtml(),
            $arguments,
            $this->loading ? 'true' : 'false',
        );

        if (0 === $this->legacyMinChars) {
            return $call;
        }

        return sprintf('$event.detail.search.length < %d ? null : %s', $this->legacyMinChars, $call);
    }

    /**
     * Records the 2.x search method, when the component is searchable.
     *
     * @since 3.0.0
     *
     * @param  bool|null  $searchable  Whether the 2.x component searched on the server.
     * @param  string|null  $searchFunction  The Livewire method to call.
     * @param  int|null  $minChars  The fewest characters that call it.
     */
    protected function useLegacySearchFunction(?bool $searchable, ?string $searchFunction, ?int $minChars): void
    {
        if ($searchable && null !== $searchFunction && '' !== trim($searchFunction)) {
            $this->legacySearchFunction = trim($searchFunction);
            $this->legacyMinChars       = max(0, (int) $minChars);
        }
    }
}
