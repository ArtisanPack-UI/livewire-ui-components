<?php

/**
 * Combobox Component
 *
 * The design-system Combobox (#54): a text input that filters a listbox, on
 * the behavior engine's combobox machine. Replaces Choices, ChoicesOffline
 * and Tags (multiple + allowCreate).
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

use ArtisanPack\LivewireUiComponents\View\Concerns\HasStablePartIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\InteractsWithFieldControl;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Js;
use Illuminate\Support\Str;

/**
 * Combobox Component Class
 *
 * Renders `apuiCombobox` on the `.apui-combobox` field root: the label, the
 * box (icon, chips, the text input, spinner, clear and chevron buttons), the
 * hint, the error, then the positioner holding the listbox. Zag's control
 * and input parts are renamed box and control (D-018). `wire:model` binds
 * the value through `$wire.entangle()`; `search-model` receives the search
 * text after `debounce`. `class` lands on the root, and so does every other
 * attribute, since `value-change`, `search-change`, `open-change` and
 * `create` are dispatched there.
 *
 * @since 3.0.0
 */
class Combobox extends BaseComponent
{
    use HasStablePartIds, InteractsWithFieldControl {
        HasStablePartIds::modelName insteadof InteractsWithFieldControl;
    }
    use RendersDesignSystemParts;

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'combobox';

    /**
     * The component name used by the field concern.
     *
     * @since 3.0.0
     */
    protected string $fieldName = 'combobox';

    /**
     * Props that change between renders without changing the id: state, data and text.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['options', 'value', 'search', 'loading', 'open', 'error', 'invalid', 'hint', 'label', 'placeholder'];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string  $size  Control height: sm, md or lg.
     * @param  iterable<mixed>  $options  Strings, or [ 'value', 'label', 'description', 'icon', 'color', 'disabled' ] entries.
     * @param  string|null  $label  The visible label. A `label` slot overrides it.
     * @param  string|null  $hint  Help text under the control. A `hint` slot overrides it.
     * @param  string|null  $error  Validation message. Defaults to the first `$errors` message for errorField.
     * @param  bool  $invalid  Marks the field invalid without a message.
     * @param  bool  $disabled  Disables the input, buttons and chips.
     * @param  bool  $required  Marks the field required.
     * @param  string|null  $placeholder  Shown in the empty input. Null uses the translated "Search…".
     * @param  string|null  $name  Submits the value with a native form through hidden inputs, one per value.
     * @param  array<int, mixed>|string|null  $value  The value: a string, or an array with multiple. `wire:model` binds it instead.
     * @param  bool  $multiple  Selects several values, shown as chips.
     * @param  int|null  $max  With multiple, the most values that can be chosen.
     * @param  bool  $allowCreate  Offers "Create …" for typed text that matches no option.
     * @param  bool  $remote  The server filters the options; nothing is filtered here.
     * @param  string|null  $search  The text in the input when it renders. Defaults to the search-model property.
     * @param  bool  $loading  Shows the spinner and the loading text.
     * @param  bool  $clearable  Adds a button that clears the value.
     * @param  bool  $open  Whether the listbox is open when it renders.
     * @param  bool  $portal  Renders the listbox at the end of the body (x-teleport). Off renders it in place.
     * @param  string|null  $leadingIcon  Icon name before the input. The `icon` slot overrides it.
     * @param  string|null  $emptyLabel  The empty text. `:search` is the typed text. Null uses the translated default.
     * @param  string|null  $loadingLabel  The loading text. Null uses the translated "Loading…".
     * @param  string|null  $createLabel  The create option's text. `{value}` is the typed text.
     * @param  string|null  $removeLabel  A chip remove button's name. `:label` is the chip's label.
     * @param  string|null  $removedLabel  Announced when a chip is removed. `:label` is the chip's label.
     * @param  string|null  $clearLabel  The clear button's name.
     * @param  string|null  $triggerLabel  The chevron button's name.
     * @param  string|null  $searchModel  The Livewire property the search text is set on, after debounce.
     * @param  int  $debounce  Milliseconds after the last keystroke before setting search-model.
     * @param  string|null  $errorField  The `$errors` key. Defaults to the wire:model name.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public string $size = 'md',
        public iterable $options = [],
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $error = null,
        public bool $invalid = false,
        public bool $disabled = false,
        public bool $required = false,
        public ?string $placeholder = null,
        public ?string $name = null,
        public string|array|null $value = null,
        public bool $multiple = false,
        public ?int $max = null,
        public bool $allowCreate = false,
        public bool $remote = false,
        public ?string $search = null,
        public bool $loading = false,
        public bool $clearable = false,
        public bool $open = false,
        public bool $portal = true,
        public ?string $leadingIcon = null,
        public ?string $emptyLabel = null,
        public ?string $loadingLabel = null,
        public ?string $createLabel = null,
        public ?string $removeLabel = null,
        public ?string $removedLabel = null,
        public ?string $clearLabel = null,
        public ?string $triggerLabel = null,
        public ?string $searchModel = null,
        public int $debounce = 250,
        public ?string $errorField = null,
        public array $classes = [],
    ) {}

    /**
     * The chosen values as strings: the bound Livewire property with
     * `wire:model`, else the `value` prop.
     *
     * @since 3.0.0
     *
     * @return list<string> The values, at most one without multiple.
     */
    public function values(): array
    {
        $value     = $this->value;
        $model     = $this->modelName();
        $component = null !== $model ? $this->livewireComponent() : null;
        if (null !== $component) {
            $value = data_get($component, $model);
        }

        if ($value instanceof Arrayable) {
            $value = $value->toArray();
        }

        $values = is_array($value) ? $value : (null === $value || '' === $value ? [] : [$value]);
        $values = array_values(array_map(static fn (mixed $entry): string => (string) $entry, array_filter(
            $values,
            static fn (mixed $entry): bool => is_scalar($entry) && '' !== (string) $entry,
        )));

        return $this->multiple ? $values : array_slice($values, 0, 1);
    }

    /**
     * The options as `value`, `label`, `description`, `icon`, `color` and
     * `disabled`, with string values. Chosen values that aren't options (a
     * created tag, say) are added, labelled by their value. `raw` is the
     * value as given when it's an integer (an Eloquent id), so the client
     * binds it back with its type.
     *
     * @since 3.0.0
     *
     * @return list<array{value: string, raw: int|string, label: string, description: string|null, icon: string|null, color: string|null, disabled: bool}> The options.
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

            if (! is_array($option)) {
                $option = ['value' => $option];
            }

            $value     = (string) ($option['value'] ?? $option['label'] ?? '');
            $options[] = [
                'value'       => $value,
                'raw'         => is_int($option['value'] ?? null) ? $option['value'] : $value,
                'label'       => (string) ($option['label'] ?? $value),
                'description' => isset($option['description']) ? (string) $option['description'] : null,
                'icon'        => isset($option['icon']) ? (string) $option['icon'] : null,
                'color'       => isset($option['color']) ? (string) $option['color'] : null,
                'disabled'    => (bool) ($option['disabled'] ?? false),
            ];
        }

        $listed = array_column($options, 'value');
        foreach ($this->values() as $value) {
            if (! in_array($value, $listed, true)) {
                $options[] = ['value' => $value, 'raw' => $value, 'label' => $value, 'description' => null, 'icon' => null, 'color' => null, 'disabled' => false];
            }
        }

        return $options;
    }

    /**
     * The text in the input when it renders: `search`, else the search-model
     * property's value.
     *
     * @since 3.0.0
     *
     * @return string The text.
     */
    public function searchText(): string
    {
        if (null !== $this->search) {
            return $this->search;
        }

        $component = null !== $this->searchModel ? $this->livewireComponent() : null;
        $search    = null !== $component ? data_get($component, $this->searchModel) : null;

        return is_scalar($search) ? (string) $search : '';
    }

    /**
     * Whether an option shows for the search text: always when remote, else
     * when its label contains the text, ignoring case and accents.
     *
     * @since 3.0.0
     *
     * @param  string  $label  The option's label.
     *
     * @return bool True when it shows.
     */
    public function matches(string $label): bool
    {
        $needle = static::fold(trim($this->searchText()));

        return $this->remote || '' === $needle || str_contains(static::fold($label), $needle);
    }

    /**
     * An option's label as HTML: the first match of the search text marked,
     * ignoring case and accents, the same way the behavior engine's
     * `highlight()` does on the client.
     *
     * @since 3.0.0
     *
     * @param  string  $label  The label.
     *
     * @return string Escaped HTML.
     */
    public function markedLabel(string $label): string
    {
        $needle = static::fold(trim($this->searchText()));
        if ('' === $needle) {
            return e($label);
        }

        $chars  = mb_str_split($label);
        $folded = '';
        $origin = [];
        foreach ($chars as $index => $char) {
            foreach (mb_str_split(static::fold($char)) as $piece) {
                $folded .= $piece;
                $origin[] = $index;
            }
        }

        $at = mb_strpos($folded, $needle);
        if (false === $at) {
            return e($label);
        }

        $start = $origin[$at];
        $end   = $origin[$at + mb_strlen($needle) - 1] + 1;

        return e(implode('', array_slice($chars, 0, $start)))
            .'<mark data-scope="combobox" data-part="mark">'.e(implode('', array_slice($chars, $start, $end - $start))).'</mark>'
            .e(implode('', array_slice($chars, $end)));
    }

    /**
     * The text the create option shows for the search text, or null when
     * there's nothing to create: no text, or it names an option or a chosen
     * value already.
     *
     * @since 3.0.0
     *
     * @return string|null The create option's value.
     */
    public function createValue(): ?string
    {
        $text = trim($this->searchText());
        if (! $this->allowCreate || '' === $text) {
            return null;
        }

        $needle = static::fold($text);
        foreach ($this->normalizedOptions() as $option) {
            if ($needle === static::fold($option['label']) || $needle === static::fold($option['value'])) {
                return null;
            }
        }

        return $text;
    }

    /**
     * The built-in labels, translated, with `{name}` placeholders for the
     * behavior engine to fill on the client.
     *
     * @since 3.0.0
     *
     * @return array{placeholder: string, empty: string, loading: string, create: string, remove: string, removed: string, clear: string, trigger: string} The labels.
     */
    public function labels(): array
    {
        return [
            'placeholder' => $this->placeholder ?? __('Search…'),
            'empty'       => $this->emptyLabel ?? __('No matches for “:search”.', ['search' => '{search}']),
            'loading'     => $this->loadingLabel ?? __('Loading…'),
            'create'      => $this->createLabel ?? __('Create “:value”', ['value' => '{value}']),
            'remove'      => $this->removeLabel ?? __('Remove :label', ['label' => '{label}']),
            'removed'     => $this->removedLabel ?? __('Removed :label.', ['label' => '{label}']),
            'clear'       => $this->clearLabel ?? __('Clear'),
            'trigger'     => $this->triggerLabel ?? __('Show options'),
        ];
    }

    /**
     * A label with its `{name}` placeholders filled, for the server render.
     *
     * @since 3.0.0
     *
     * @param  string  $key  The label, such as `remove`.
     * @param  array<string, string>  $values  The placeholder values.
     *
     * @return string The text.
     */
    public function labelText(string $key, array $values = []): string
    {
        $text = $this->labels()[$key] ?? '';
        foreach ($values as $name => $value) {
            $text = str_replace('{'.$name.'}', $value, $text);
        }

        return $text;
    }

    /**
     * The value as an Alpine expression: `$wire.entangle()` of the
     * `wire:model` property (`.live` with `wire:model.live`), else the value
     * as a literal.
     *
     * @since 3.0.0
     *
     * @return string The expression.
     */
    public function valueExpression(): string
    {
        $model = $this->modelName();
        if (null === $model) {
            $values = $this->values();

            return Js::from($this->multiple ? $values : ($values[0] ?? null))->toHtml();
        }

        $live = [] !== array_filter(
            array_keys($this->attributes->whereStartsWith('wire:model')->getAttributes()),
            static fn (string $key): bool => str_contains($key, '.live'),
        );

        return '$wire.entangle('.Js::from($model)->toHtml().')'.($live ? '.live' : '');
    }

    /**
     * The `x-data` expression: `apuiCombobox()` with the machine id, the
     * value, the modes, the state and the translated labels.
     *
     * @since 3.0.0
     *
     * @param  bool  $invalid  Whether the field renders invalid: the prop or an error.
     *
     * @return string The expression.
     */
    public function alpineData(bool $invalid = false): string
    {
        $labels = $this->labels();
        $config = [
            'id'          => $this->baseId(),
            'multiple'    => $this->multiple,
            'remote'      => $this->remote,
            'allowCreate' => $this->allowCreate,
            'max'         => $this->max,
            'search'      => $this->searchText(),
            'loading'     => $this->loading,
            'open'        => $this->open,
            'disabled'    => $this->disabled,
            'required'    => $this->required,
            'invalid'     => $invalid || $this->invalid,
            'placeholder' => $labels['placeholder'],
            'labels'      => $labels,
        ];
        $config = array_filter($config, static fn (mixed $entry): bool => null !== $entry);

        $resultsCount = sprintf(
            '(count) => (count === 1 ? %s : %s).replace(%s, count)',
            Js::from(__('1 result'))->toHtml(),
            Js::from(__(':count results', ['count' => '{count}']))->toHtml(),
            Js::from('{count}')->toHtml(),
        );

        $translations = sprintf(
            '{ triggerLabel: %s, clearTriggerLabel: %s, noResults: %s, loading: %s, resultsCount: %s }',
            Js::from($labels['trigger'])->toHtml(),
            Js::from($labels['clear'])->toHtml(),
            Js::from(__('No results'))->toHtml(),
            Js::from(__('Loading results'))->toHtml(),
            $resultsCount,
        );

        return sprintf(
            'apuiCombobox(Object.assign(%s, { value: %s, translations: %s }))',
            Js::from($config)->toHtml(),
            $this->valueExpression(),
            $translations,
        );
    }

    /**
     * The `x-on:search-change` handler that sets search-model after the
     * debounce, with the spinner on until the round trip ends, or null
     * without search-model.
     *
     * @since 3.0.0
     *
     * @return string|null The handler.
     */
    public function searchHandler(): ?string
    {
        if (null === $this->searchModel || '' === $this->searchModel) {
            return null;
        }

        return sprintf(
            'loading = true; Promise.resolve($wire.$set(%s, $event.detail.search)).finally(() => loading = %s)',
            Js::from($this->searchModel)->toHtml(),
            $this->loading ? 'true' : 'false',
        );
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.combobox');
    }

    /**
     * Lowercases text and strips accents, for matching.
     *
     * @since 3.0.0
     *
     * @param  string  $text  The text.
     *
     * @return string The folded text.
     */
    protected static function fold(string $text): string
    {
        $folded = '';
        foreach (mb_str_split($text) as $char) {
            $folded .= mb_strtolower(1 === strlen($char) ? $char : Str::ascii($char));
        }

        return $folded;
    }
}
