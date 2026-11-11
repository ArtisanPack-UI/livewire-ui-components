<?php

/**
 * Option lists for the design-system choice groups.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Concerns;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Normalizes the `options` RadioGroup and CheckboxGroup take: strings, or
 * arrays and objects with `value`, `label`, `description`, `meta` and
 * `disabled`. The using class declares `public iterable $options`.
 *
 * @since 3.0.0
 */
trait NormalizesChoiceOptions
{
    /**
     * The options with string values and labels.
     *
     * @since 3.0.0
     *
     * @return list<array{value: string, label: string, description: string|null, meta: string|null, disabled: bool}> The options.
     */
    public function normalizedOptions(): array
    {
        $options = [];
        foreach ($this->options as $option) {
            if ($option instanceof Arrayable) {
                $option = $option->toArray();
            } elseif (is_object($option)) {
                $option = get_object_vars($option);
            }

            if (is_array($option)) {
                $value     = (string) ($option['value'] ?? '');
                $options[] = [
                    'value'       => $value,
                    'label'       => (string) ($option['label'] ?? $value),
                    'description' => isset($option['description']) && '' !== (string) $option['description'] ? (string) $option['description'] : null,
                    'meta'        => isset($option['meta']) && '' !== (string) $option['meta'] ? (string) $option['meta'] : null,
                    'disabled'    => (bool) ($option['disabled'] ?? false),
                ];

                continue;
            }

            $options[] = ['value' => (string) $option, 'label' => (string) $option, 'description' => null, 'meta' => null, 'disabled' => false];
        }

        return $options;
    }
}
