<?php

/**
 * ValidationSummary Component
 *
 * The design-system ValidationSummary (#53): lists every error in a form
 * after a failed submit, each linked to its field. It replaces `errors`.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ViewErrorBag;

/**
 * ValidationSummary Component Class
 *
 * Renders `.apui-validation-summary`: the icon, the title and a list of
 * links, one per field, to `#field`. Without an `errors` prop it reads the
 * first message of every key in Laravel's `$errors` (`only` picks and orders
 * the keys). It renders nothing when there are no errors. An error summary
 * is an alert and takes focus when it appears; a warning is a status.
 *
 * @since 3.0.0
 */
class ValidationSummary extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  array<int|string, mixed>|null  $errors  `[{ field, message }]`, or a map of field to message(s). Defaults to `$errors`.
     * @param  list<string>  $only  The fields to list, in order. Defaults to every one.
     * @param  string  $color  error or warning.
     * @param  string|null  $title  The heading. A `title` slot overrides it; defaults to a translated count.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public ?array $errors = null,
        public array $only = [],
        public string $color = 'error',
        public ?string $title = null,
        public array $classes = [],
    ) {}

    /**
     * The entries to list: the `errors` prop, else the view's error bag.
     *
     * @since 3.0.0
     *
     * @param  mixed  $bag  An error bag. Defaults to the shared `$errors`, which the `errors` prop shadows in the view.
     *
     * @return list<array{field: string, message: string}> The entries.
     */
    public function entries(mixed $bag = null): array
    {
        $bag ??= app('view')->getShared()['errors'] ?? null;

        if (null !== $this->errors && [] !== $this->errors) {
            $entries = self::normalize($this->errors);
            if ([] === $this->only) {
                return $entries;
            }

            $picked = [];
            foreach ($this->only as $key) {
                foreach ($entries as $entry) {
                    if ($entry['field'] === $key) {
                        $picked[] = $entry;
                    }
                }
            }

            return $picked;
        }

        if ($bag instanceof ViewErrorBag) {
            $bag = $bag->getBag('default');
        }

        if (! $bag instanceof MessageBag) {
            return [];
        }

        $messages = $bag->getMessages();
        $keys     = [] === $this->only ? array_keys($messages) : array_values(array_filter($this->only, 'is_string'));
        $entries  = [];
        foreach ($keys as $key) {
            $message = $bag->first((string) $key);
            if (is_string($message) && '' !== $message) {
                $entries[] = ['field' => (string) $key, 'message' => $message];
            }
        }

        return $entries;
    }

    /**
     * Normalizes `[{ field, message }]` or `{ field: message | message[] }`
     * to a list, keeping the first message per field.
     *
     * @since 3.0.0
     *
     * @param  array<int|string, mixed>  $errors  The errors.
     *
     * @return list<array{field: string, message: string}> The entries.
     */
    public static function normalize(array $errors): array
    {
        $entries = [];
        foreach ($errors as $key => $item) {
            if (is_array($item) && isset($item['field'], $item['message'])) {
                $entries[] = ['field' => (string) $item['field'], 'message' => (string) $item['message']];

                continue;
            }

            $message = is_array($item) ? reset($item) : $item;
            if (is_string($key) && is_scalar($message) && '' !== (string) $message) {
                $entries[] = ['field' => $key, 'message' => (string) $message];
            }
        }

        return $entries;
    }

    /**
     * The default title for a count: how many fields to fix, or how many
     * recommendations for a warning.
     *
     * @since 3.0.0
     *
     * @param  int  $count  The number of entries.
     *
     * @return string The title.
     */
    public function defaultTitle(int $count): string
    {
        return 'warning' === $this->color
            ? trans_choice(':count recommendation|:count recommendations', $count, ['count' => $count])
            : trans_choice('Fix :count field|Fix :count fields', $count, ['count' => $count]);
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.validation-summary');
    }
}
