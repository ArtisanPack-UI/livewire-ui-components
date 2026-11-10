<?php

/**
 * Shared field behavior for the design-system form components.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Concerns;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Support\Str;
use Illuminate\Support\ViewErrorBag;

/**
 * The field anatomy Input, Select and Checkbox share (D-018): a stable
 * control id, the `wire:model` name, and the error read from `$errors`.
 *
 * The using class declares `public ?string $error` and
 * `public ?string $errorField`, and sets `$fieldName` (`input`, `select`,
 * `checkbox`).
 *
 * @since 3.0.0
 */
trait InteractsWithFieldControl
{
    /**
     * The `wire:model` name, whatever its modifiers.
     *
     * @since 3.0.0
     *
     * @return string|null The model name.
     */
    public function modelName(): ?string
    {
        $model = $this->attributes->whereStartsWith('wire:model')->first();

        return is_string($model) && '' !== $model ? $model : null;
    }

    /**
     * The control's id, stable across Livewire re-renders (D-015): the `id`
     * attribute, else one derived from the `wire:model` name (a slug plus a
     * short hash of the raw name), else a hash of the props and attributes.
     *
     * @since 3.0.0
     *
     * @return string The id.
     */
    public function controlId(): string
    {
        $id = $this->attributes->get('id');
        if (is_string($id) && '' !== $id) {
            return $id;
        }

        $model = $this->modelName();
        if (null !== $model) {
            // The slug keeps it readable; the hash of the raw values keeps
            // `user.name` and `user_name` (or values `A+` and `A`) apart.
            $seed = [$model, ...$this->idSeed()];

            return 'apui-'.$this->fieldName.'-'.trim(Str::slug(str_replace('.', '-', implode(' ', $seed))).'-'.substr(md5(implode("\0", $seed)), 0, 8), '-');
        }

        return 'apui-'.$this->fieldName.'-'.md5((string) json_encode(
            [$this->extractPublicProperties(), $this->attributes->getAttributes()],
            JSON_PARTIAL_OUTPUT_ON_ERROR,
        ));
    }

    /**
     * The validation key the error is read from: `errorField`, else the
     * `wire:model` name.
     *
     * @since 3.0.0
     *
     * @return string|null The key.
     */
    public function errorKey(): ?string
    {
        return $this->errorField ?? $this->modelName();
    }

    /**
     * The error to show: the `error` prop, else the first message for
     * `errorKey()` in the view's `$errors`.
     *
     * @since 3.0.0
     *
     * @param  mixed  $errors  The view's `$errors`, when it has one.
     *
     * @return string|null The message, or null when there is none.
     */
    public function errorMessage(mixed $errors = null): ?string
    {
        if (null !== $this->error && '' !== $this->error) {
            return $this->error;
        }

        $key = $this->errorKey();
        if (null === $key || ! ($errors instanceof ViewErrorBag || $errors instanceof MessageBag)) {
            return null;
        }

        $message = $errors->first($key);

        return is_string($message) && '' !== $message ? $message : null;
    }

    /**
     * `aria-describedby` for the control: the given ids, then any the caller
     * passed.
     *
     * @since 3.0.0
     *
     * @param  list<string>  $ids  The rendered hint, description and error ids, in order.
     *
     * @return string|null The value, or null when there are none.
     */
    public function describedBy(array $ids): ?string
    {
        $own = $this->attributes->get('aria-describedby');
        if (is_string($own) && '' !== trim($own)) {
            $ids[] = trim($own);
        }

        return [] === $ids ? null : implode(' ', $ids);
    }

    /**
     * Extra raw values a `wire:model`-derived id is built from, after the
     * model name.
     *
     * @since 3.0.0
     *
     * @return list<string> The values.
     */
    protected function idSeed(): array
    {
        return [];
    }
}
