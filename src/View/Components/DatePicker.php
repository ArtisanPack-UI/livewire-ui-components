<?php

/**
 * DatePicker Component
 *
 * The design-system DatePicker (#55): a text field with a calendar popover
 * for a single date or a range, and, with `time`, DateTime (date and time in
 * one field). Replaces the flatpickr `datepicker` and the native `datetime`.
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

use ArtisanPack\LivewireUiComponents\View\Concerns\HasStablePartIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\InteractsWithFieldControl;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Js;
use Illuminate\View\ComponentAttributeBag;
use IntlCalendar;
use IntlDateFormatter;
use Stringable;

/**
 * DatePicker Component Class
 *
 * Renders `apuiDatePicker` on the behavior engine's date-picker machine:
 * the `.apui-date-picker` field (label, box with the calendar trigger and
 * the input, hint, time zone and error) and the popover (month header, the
 * `role=grid` month and, in time mode, the time listbox). `wire:model` binds
 * the ISO value (an array of two in range mode) through `$wire.entangle()`.
 * `class` lands on the root, event listeners (`x-on:*`, `wire:*`) on the
 * root where the widget dispatches, and every other attribute on the input.
 *
 * Registered as `date-picker`, plus the deprecated `datepicker` alias.
 *
 * @since 1.0.0
 */
class DatePicker extends BaseComponent
{
    use HasStablePartIds, InteractsWithFieldControl {
        HasStablePartIds::modelName insteadof InteractsWithFieldControl;
    }
    use RendersDesignSystemParts;

    /**
     * 2.x attributes the 3.0 component no longer uses (flatpickr's `config`,
     * the DaisyUI field props, `datetime`'s native `type`). They're accepted
     * and dropped, so 2.x markup renders instead of leaking them onto the
     * input. `config` is read first (`applyLegacyConfig()`).
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected const LEGACY_ATTRIBUTES = [
        'config', 'type', 'icon', 'icon-right', 'iconRight', 'prefix', 'suffix', 'inline', 'clearable',
        'hint-class', 'hintClass', 'omit-error', 'omitError', 'first-error-only', 'firstErrorOnly',
        'error-class', 'errorClass', 'color', 'color-adjustment', 'colorAdjustment', 'theme',
        'font-config', 'fontConfig', 'dark-mode-support', 'darkModeSupport', 'prepend', 'append',
    ];

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'date-picker';

    /**
     * The component name used in derived ids.
     *
     * @since 3.0.0
     */
    protected string $fieldName = 'date-picker';

    /**
     * Props that change between renders without changing the id: state and text.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['value', 'open', 'label', 'hint', 'error', 'invalid', 'footer', 'placeholder'];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  array<int, string>|string|null  $value  The ISO value (2026-11-12, 2026-11-12T11:00 in time mode), or two in range mode. `wire:model` binds it instead.
     * @param  string  $mode  single or range.
     * @param  bool  $time  Date and time: adds the time list.
     * @param  int  $step  Minutes between times in the time list.
     * @param  string|null  $timeZone  The IANA time zone, shown under the field in time mode.
     * @param  string|null  $locale  BCP 47 locale for month and weekday names. Defaults to the app locale.
     * @param  string|null  $min  The earliest date, ISO.
     * @param  string|null  $max  The latest date, ISO.
     * @param  array<int, string>  $disabledDates  Dates that can't be chosen, ISO.
     * @param  int|null  $weekStartsOn  First day of the week, 0 (Sunday) to 6. Follows the locale unless set.
     * @param  string|null  $format  The text pattern (yyyy, MM, dd). Follows the locale unless set.
     * @param  string  $size  Control height: sm, md or lg.
     * @param  string|null  $label  The visible label. A `label` slot overrides it.
     * @param  string|null  $hint  Help text. A `hint` slot overrides it.
     * @param  string|null  $error  Validation message. Defaults to the first `$errors` message for errorField.
     * @param  bool  $invalid  Marks the field invalid without a message.
     * @param  bool  $disabled  Disables the field and the calendar.
     * @param  bool  $readonly  Makes the field read-only.
     * @param  bool  $required  Marks the field required.
     * @param  string|null  $placeholder  Placeholder text. Defaults to the pattern.
     * @param  string|null  $name  Submits the ISO value under this name (name[] in range mode).
     * @param  bool  $open  Whether the calendar is open.
     * @param  bool  $portal  Renders the popover at the end of the body (x-teleport).
     * @param  string|null  $footer  A row under the calendar. A `footer` slot overrides it.
     * @param  string|null  $triggerLabel  The calendar button's name. Defaults to "Choose date", translated.
     * @param  string|null  $previousLabel  The previous-month button's name. Defaults to "Previous month", translated.
     * @param  string|null  $nextLabel  The next-month button's name. Defaults to "Next month", translated.
     * @param  string|null  $calendarLabel  The popover's name. Defaults to "Calendar", translated.
     * @param  string|null  $timeLabel  The time list's name and heading. Defaults to "Time", translated.
     * @param  string|null  $endLabel  The end input's name in range mode. Defaults to "End date", translated.
     * @param  string|null  $timeZoneLabel  The time-zone line, with {zone}. Defaults to "Time zone: :zone", translated.
     * @param  string|null  $errorField  The `$errors` key. Defaults to the wire:model name.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public string|array|null $value = null,
        public string $mode = 'single',
        public bool $time = false,
        public int $step = 30,
        public ?string $timeZone = null,
        public ?string $locale = null,
        public ?string $min = null,
        public ?string $max = null,
        public array $disabledDates = [],
        public ?int $weekStartsOn = null,
        public ?string $format = null,
        public string $size = 'md',
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $error = null,
        public bool $invalid = false,
        public bool $disabled = false,
        public bool $readonly = false,
        public bool $required = false,
        public ?string $placeholder = null,
        public ?string $name = null,
        public bool $open = false,
        public bool $portal = true,
        public ?string $footer = null,
        public ?string $triggerLabel = null,
        public ?string $previousLabel = null,
        public ?string $nextLabel = null,
        public ?string $calendarLabel = null,
        public ?string $timeLabel = null,
        public ?string $endLabel = null,
        public ?string $timeZoneLabel = null,
        public ?string $errorField = null,
        public array $classes = [],
    ) {}

    /**
     * Whether it picks a range of two dates.
     *
     * @since 3.0.0
     */
    public function isRange(): bool
    {
        return 'range' === $this->mode;
    }

    /**
     * Whether the time list shows: time mode, single dates only.
     *
     * @since 3.0.0
     */
    public function hasTime(): bool
    {
        return $this->time && ! $this->isRange();
    }

    /**
     * The id of the (first) text input: the `id` attribute, else the base id.
     *
     * @since 3.0.0
     *
     * @return string The id.
     */
    public function controlId(): string
    {
        return $this->partId('control');
    }

    /**
     * The locale as BCP 47: the `locale` prop, else the app locale (`en_US` → `en-US`).
     *
     * @since 3.0.0
     *
     * @return string The locale.
     */
    public function resolvedLocale(): string
    {
        $locale = null !== $this->locale && '' !== $this->locale ? $this->locale : (string) app()->getLocale();

        return str_replace('_', '-', '' !== $locale ? $locale : 'en');
    }

    /**
     * The value as an Alpine expression: `$wire.entangle()` of the
     * `wire:model` property (`.live` with `wire:model.live`), else the prop.
     *
     * @since 3.0.0
     *
     * @return string The expression.
     */
    public function valueExpression(): string
    {
        $model = $this->modelName();
        if (null === $model) {
            $value = $this->value ?? ($this->isRange() ? [] : null);

            return Js::from($value)->toHtml();
        }

        $live = [] !== array_filter(
            array_keys($this->attributes->whereStartsWith('wire:model')->getAttributes()),
            static fn (string $key): bool => str_contains($key, '.live'),
        );

        return '$wire.entangle('.Js::from($model)->toHtml().')'.($live ? '.live' : '');
    }

    /**
     * The `x-data` expression: `apuiDatePicker()` with the machine id, the
     * bound value and the options, labels translated.
     *
     * @since 3.0.0
     *
     * @param  bool  $isInvalid  Whether the field renders invalid.
     *
     * @return string The expression.
     */
    public function alpineData(bool $isInvalid = false): string
    {
        $options = array_filter([
            'id'            => $this->baseId(),
            'open'          => $this->open,
            'mode'          => $this->isRange() ? 'range' : 'single',
            'time'          => $this->hasTime(),
            'step'          => $this->step,
            'locale'        => $this->resolvedLocale(),
            'timeZone'      => $this->timeZone,
            'min'           => $this->min,
            'max'           => $this->max,
            'disabledDates' => [] !== $this->disabledDates ? array_values($this->disabledDates) : null,
            'weekStartsOn'  => $this->weekStartsOn,
            'format'        => $this->format,
            'disabled'      => $this->disabled,
            'readOnly'      => $this->readonly,
            'required'      => $this->required,
            'invalid'       => $isInvalid,
            'placeholder'   => $this->placeholder,
            'timeLabel'     => $this->timeListLabel(),
            'labels'        => [
                'trigger'  => $this->triggerName(),
                'previous' => $this->builtInName('previousLabel', 'Previous month'),
                'next'     => $this->builtInName('nextLabel', 'Next month'),
                'calendar' => $this->builtInName('calendarLabel', 'Calendar'),
            ],
        ], static fn (mixed $value): bool => null !== $value);

        return sprintf(
            'apuiDatePicker({ ...%s, value: %s })',
            Js::from($options)->toHtml(),
            $this->valueExpression(),
        );
    }

    /**
     * The calendar button's accessible name: `triggerLabel`, else "Choose
     * date" ("Choose date and time" in time mode), translated.
     *
     * @since 3.0.0
     *
     * @return string The name.
     */
    public function triggerName(): string
    {
        return $this->builtInName('triggerLabel', $this->hasTime() ? 'Choose date and time' : 'Choose date');
    }

    /**
     * The time list's name and heading: `timeLabel`, else "Time", translated.
     *
     * @since 3.0.0
     *
     * @return string The name.
     */
    public function timeListLabel(): string
    {
        return $this->builtInName('timeLabel', 'Time');
    }

    /**
     * The end input's accessible name in range mode.
     *
     * @since 3.0.0
     *
     * @return string The name.
     */
    public function endInputLabel(): string
    {
        return $this->builtInName('endLabel', 'End date');
    }

    /**
     * The time-zone line: `timeZoneLabel` with `{zone}` replaced, else "Time
     * zone: :zone", translated. Null when there's no zone or no time.
     *
     * @since 3.0.0
     *
     * @return string|null The text.
     */
    public function timeZoneText(): ?string
    {
        if (! $this->hasTime() || null === $this->timeZone || '' === $this->timeZone) {
            return null;
        }

        if (null !== $this->timeZoneLabel && '' !== $this->timeZoneLabel) {
            return str_replace('{zone}', $this->timeZone, $this->timeZoneLabel);
        }

        return __('Time zone: :zone', ['zone' => $this->timeZone]);
    }

    /**
     * The times in the time list, `HH:mm`, every `step` minutes from 00:00.
     *
     * @since 3.0.0
     *
     * @return list<string> The times.
     */
    public function timeOptions(): array
    {
        $step    = $this->step >= 1 && $this->step <= 720 ? $this->step : 30;
        $options = [];
        for ($minutes = 0; $minutes < 24 * 60; $minutes += $step) {
            $options[] = sprintf('%02d:%02d', intdiv($minutes, 60), $minutes % 60);
        }

        return $options;
    }

    /**
     * The attributes for the root: event listeners (`x-on:*`, `@*`,
     * `wire:*` other than `wire:model`), where the widget dispatches.
     *
     * @since 3.0.0
     *
     * @return ComponentAttributeBag The attributes.
     */
    public function listenerAttributes(): ComponentAttributeBag
    {
        return $this->attributes->filter(static fn (mixed $value, string $key): bool => self::isListener($key));
    }

    /**
     * The attributes for the (first) input: everything except `class`, `id`,
     * `wire:model`, `aria-describedby` and the listeners.
     *
     * @since 3.0.0
     *
     * @return ComponentAttributeBag The attributes.
     */
    public function controlAttributes(): ComponentAttributeBag
    {
        return $this->attributes
            ->except(['class', 'id', 'aria-describedby', 'name', ...self::LEGACY_ATTRIBUTES])
            ->filter(static fn (mixed $value, string $key): bool => ! self::isListener($key)
                && ! str_starts_with($key, 'wire:model')
                && (null === $value || is_scalar($value) || $value instanceof Stringable));
    }

    /**
     * The hidden inputs' name: `name`, else a `name` attribute.
     *
     * @since 3.0.0
     *
     * @return string|null The name.
     */
    public function submitName(): ?string
    {
        $name = $this->name ?? $this->attributes->get('name');

        return is_string($name) && '' !== $name ? $name : null;
    }

    /**
     * The value the server renders: the bound Livewire property with
     * `wire:model`, else the `value` prop, as a list of ISO strings.
     *
     * @since 3.0.0
     *
     * @return list<string> The ISO values.
     */
    public function isoValues(): array
    {
        $model = $this->modelName();
        $value = $this->value;
        if (null !== $model) {
            $component = $this->livewireComponent();
            $value     = null !== $component ? data_get($component, $model) : $value;
        }

        $list = is_array($value) ? $value : (null === $value || '' === $value ? [] : [$value]);

        return array_values(array_filter(
            array_map(static fn (mixed $item): string => is_string($item) ? $item : '', $list),
            fn (string $item): bool => null !== $this->parseIso($item),
        ));
    }

    /**
     * The numeric pattern the text uses, with `yyyy`, `MM` and `dd`: the
     * `format` prop, else the locale's short date with a four-digit year
     * (`MM/dd/yyyy` for en-US), as the behavior engine derives it from Intl.
     *
     * @since 3.0.0
     *
     * @return string The pattern.
     */
    public function pattern(): string
    {
        if (null !== $this->format && str_contains($this->format, 'yyyy') && str_contains($this->format, 'MM') && str_contains($this->format, 'dd')) {
            return $this->format;
        }

        if (! class_exists(IntlDateFormatter::class)) {
            return 'MM/dd/yyyy';
        }

        $short   = (new IntlDateFormatter(str_replace('-', '_', $this->resolvedLocale()), IntlDateFormatter::SHORT, IntlDateFormatter::NONE))->getPattern();
        $short   = preg_replace('/[\x{200E}\x{200F}\x{061C}]/u', '', (string) $short) ?? '';
        $pattern = preg_replace(['/y+/', '/M+/', '/d+/'], ['yyyy', 'MM', 'dd'], $short) ?? '';

        return str_contains($pattern, 'yyyy') && str_contains($pattern, 'MM') && str_contains($pattern, 'dd') ? $pattern : 'yyyy-MM-dd';
    }

    /**
     * The placeholder: `placeholder`, else the pattern in capitals (plus
     * ` HH:MM` in time mode), as the engine writes it.
     *
     * @since 3.0.0
     *
     * @return string The placeholder.
     */
    public function placeholderText(): string
    {
        if (null !== $this->placeholder && '' !== $this->placeholder) {
            return $this->placeholder;
        }

        return strtoupper($this->pattern()).($this->hasTime() ? ' HH:MM' : '');
    }

    /**
     * An input's text for the server render, in the pattern.
     *
     * @since 3.0.0
     *
     * @param  int  $index  0, or 1 for a range's end.
     *
     * @return string The text.
     */
    public function inputText(int $index = 0): string
    {
        $date = $this->parseIso($this->isoValues()[$index] ?? '');
        if (null === $date) {
            return '';
        }

        $text = str_replace(['yyyy', 'MM', 'dd'], [$date->format('Y'), $date->format('m'), $date->format('d')], $this->pattern());

        return $this->hasTime() ? $text.' '.$date->format('H:i') : $text;
    }

    /**
     * The time selected in the time list, `HH:mm`, when the value has one.
     *
     * @since 3.0.0
     *
     * @return string|null The time.
     */
    public function selectedTime(): ?string
    {
        $first = $this->isoValues()[0] ?? '';

        return 1 === preg_match('/T(\d{2}:\d{2})/', $first, $match) ? $match[1] : null;
    }

    /**
     * The month the server renders, before Alpine takes over: its name,
     * the weekday headings and the weeks of day cells with the states the
     * machine writes (outside the month, selected, in range, unavailable,
     * today). Names come from Intl in the locale, as in the browser.
     *
     * @since 3.0.0
     *
     * @return array{label: string, weekDays: list<array{short: string, long: string}>, weeks: list<list<array{iso: string, day: int, label: string, outside: bool, selected: bool, inRange: bool, disabled: bool, unavailable: bool, today: bool, focused: bool}>>} The month.
     */
    public function calendar(): array
    {
        $values    = array_map(fn (string $iso): DateTimeImmutable => $this->parseIso($iso), $this->isoValues());
        $today     = new DateTimeImmutable('today', new DateTimeZone($this->safeTimeZone()));
        $focus     = $values[0] ?? $today;
        $first     = $focus->setDate((int) $focus->format('Y'), (int) $focus->format('n'), 1)->setTime(0, 0);
        $last      = $first->modify('last day of this month');
        $weekStart = $this->weekStart();
        $start     = $first->modify('-'.(((int) $first->format('w') - $weekStart + 7) % 7).' days');
        $end       = $last->modify('+'.((($weekStart + 6) - (int) $last->format('w') + 7) % 7).' days');
        $min       = $this->parseIso((string) $this->min);
        $max       = $this->parseIso((string) $this->max);
        $days      = array_map(static fn (DateTimeImmutable $date): string => $date->format('Y-m-d'), $values);
        $range     = $this->isRange() && 2 === count($days) ? [min($days), max($days)] : null;
        $focused   = $focus->format('Y-m-d');

        $weeks = [];
        $index = 0;
        for ($date = $start; $date <= $end; $date = $date->modify('+1 day')) {
            $iso                          = $date->format('Y-m-d');
            $outside                      = $date->format('n') !== $first->format('n');
            $outOfBounds                  = (null !== $min && $iso < $min->format('Y-m-d')) || (null !== $max && $iso > $max->format('Y-m-d'));
            $unavailable                  = in_array($iso, $this->disabledDates, true) || $outOfBounds;
            $weeks[intdiv($index++, 7)][] = [
                'iso'         => $iso,
                'day'         => (int) $date->format('j'),
                'label'       => $this->formatIntl($date, 'EEEE, MMMM d, y', 'l, F j, Y'),
                'outside'     => $outside,
                'selected'    => in_array($iso, $days, true),
                'inRange'     => null !== $range && $iso >= $range[0] && $iso <= $range[1],
                'disabled'    => $outside || $outOfBounds,
                'unavailable' => $unavailable,
                'today'       => $iso === $today->format('Y-m-d'),
                'focused'     => $iso === $focused,
            ];
        }

        $weekDays = [];
        foreach (array_slice($weeks[0] ?? [], 0, 7) as $cell) {
            $date       = new DateTimeImmutable($cell['iso']);
            $weekDays[] = [
                'short' => $this->formatIntl($date, 'EEE', 'D'),
                'long'  => $this->formatIntl($date, 'EEEE', 'l'),
            ];
        }

        return [
            'label'    => $this->formatIntl($first, 'MMMM y', 'F Y'),
            'weekDays' => $weekDays,
            'weeks'    => $weeks,
        ];
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.date-picker');
    }

    /**
     * Sets the attributes, then maps a 2.x `config` among them onto the props.
     *
     * @since 3.0.0
     *
     * @param  array  $attributes  The attributes to merge into the bag.
     *
     * @return $this
     */
    public function withAttributes(array $attributes): static
    {
        parent::withAttributes($attributes);
        $this->applyLegacyConfig();

        return $this;
    }

    /**
     * Maps a 2.x flatpickr `config` attribute (`<x-artisanpack-datepicker
     * :config="[…]">`) onto the 3.0 props where one exists: `mode`
     * (`range`), `minDate` and `maxDate` (an ISO date or `today`),
     * `dateFormat` (its `Y`, `m` and `d` tokens become the pattern) and
     * `enableTime`. Everything else is dropped.
     *
     * @since 3.0.0
     */
    public function applyLegacyConfig(): void
    {
        $config = $this->attributes?->get('config');
        if (! is_array($config)) {
            return;
        }

        if ('range' === ($config['mode'] ?? null)) {
            $this->mode = 'range';
        }

        foreach (['minDate' => 'min', 'maxDate' => 'max'] as $key => $prop) {
            $date = $config[$key] ?? null;
            if ('today' === $date) {
                $this->{$prop} = (new DateTimeImmutable('today'))->format('Y-m-d');
            } elseif (is_string($date) && null !== $this->parseIso($date)) {
                $this->{$prop} = substr($date, 0, 10);
            }
        }

        $format = $config['dateFormat'] ?? null;
        if (is_string($format)) {
            $date    = trim((string) preg_replace('/[\sT]*H.*$/', '', $format));
            $pattern = strtr($date, ['Y' => 'yyyy', 'm' => 'MM', 'd' => 'dd']);
            if (1 === preg_match('/^(?=.*yyyy)(?=.*MM)(?=.*dd)[yMd\/.\- ]+$/', $pattern)) {
                $this->format = $pattern;
            }
        }

        if (true === ($config['enableTime'] ?? null) && ! $this->isRange()) {
            $this->time = true;
        }
    }

    /**
     * A built-in label: the prop when set, else the English default, translated.
     *
     * @since 3.0.0
     *
     * @param  string  $prop  The prop name.
     * @param  string  $default  The English default.
     *
     * @return string The label.
     */
    public function builtInName(string $prop, string $default): string
    {
        $value = $this->{$prop};

        return is_string($value) && '' !== $value ? $value : __($default);
    }

    /**
     * Whether an attribute is an event listener that belongs on the root.
     *
     * @since 3.0.0
     *
     * @param  string  $key  The attribute name.
     */
    private static function isListener(string $key): bool
    {
        return str_starts_with($key, 'x-on:') || str_starts_with($key, '@')
            || (str_starts_with($key, 'wire:') && ! str_starts_with($key, 'wire:model'));
    }

    /**
     * An ISO date or date-time as a date, or null when it isn't a real one.
     *
     * @since 3.0.0
     *
     * @param  string  $value  The ISO value.
     */
    private function parseIso(string $value): ?DateTimeImmutable
    {
        if (1 !== preg_match('/^(\d{4})-(\d{2})-(\d{2})(?:[T ](\d{2}):(\d{2}))?/', trim($value), $m) || ! checkdate((int) $m[2], (int) $m[3], (int) $m[1])) {
            return null;
        }

        return new DateTimeImmutable(sprintf('%s-%s-%s %s:%s:00', $m[1], $m[2], $m[3], $m[4] ?? '00', $m[5] ?? '00'));
    }

    /**
     * The first day of the week, 0 (Sunday) to 6: `weekStartsOn`, else the locale's.
     *
     * @since 3.0.0
     */
    private function weekStart(): int
    {
        if (null !== $this->weekStartsOn && $this->weekStartsOn >= 0 && $this->weekStartsOn <= 6) {
            return $this->weekStartsOn;
        }

        if (! class_exists(IntlCalendar::class)) {
            return 0;
        }

        $calendar = IntlCalendar::createInstance(null, str_replace('-', '_', $this->resolvedLocale()));

        return $calendar instanceof IntlCalendar ? ($calendar->getFirstDayOfWeek() + 6) % 7 : 0;
    }

    /**
     * The time zone for today's date: `timeZone` when valid, else UTC, as the machine.
     *
     * @since 3.0.0
     */
    private function safeTimeZone(): string
    {
        return null !== $this->timeZone && in_array($this->timeZone, DateTimeZone::listIdentifiers(), true) ? $this->timeZone : 'UTC';
    }

    /**
     * A date formatted in the locale through Intl, else in English with `date()`.
     *
     * @since 3.0.0
     *
     * @param  DateTimeImmutable  $date  The date.
     * @param  string  $pattern  The ICU pattern.
     * @param  string  $fallback  The `date()` format without Intl.
     */
    private function formatIntl(DateTimeImmutable $date, string $pattern, string $fallback): string
    {
        if (! class_exists(IntlDateFormatter::class)) {
            return $date->format($fallback);
        }

        $formatter = new IntlDateFormatter(str_replace('-', '_', $this->resolvedLocale()), IntlDateFormatter::NONE, IntlDateFormatter::NONE, 'UTC', null, $pattern);
        $text      = $formatter->format(new DateTimeImmutable($date->format('Y-m-d').' 12:00:00', new DateTimeZone('UTC')));

        return is_string($text) ? $text : $date->format($fallback);
    }
}
