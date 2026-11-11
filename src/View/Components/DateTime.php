<?php

/**
 * DateTime Component
 *
 * Deprecated in 3.0: `<x-artisanpack-datetime>` renders the design-system
 * DatePicker in its `time` mode (#55). Use `<x-artisanpack-date-picker time>`.
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

/**
 * DateTime Component Class
 *
 * The 2.x `datetime` (a native input, `type="date"` by default) on the
 * design-system DatePicker. `type` decides the mode: `date` (the default,
 * as in 2.x) is a date; `datetime-local` and `time` are DatePicker's time
 * mode, valued `2026-11-12T11:00` (a time needs a date: for a time on its
 * own, use `<x-artisanpack-input type="time">`). It takes every DatePicker
 * prop, and `time` turns time mode on whatever `type` says.
 *
 * @since 1.0.0
 * @deprecated 3.0.0 Use `<x-artisanpack-date-picker>` (with `time` for date and time). Removed in a later major release.
 */
class DateTime extends DatePicker
{
    /**
     * Whether the time list shows: with `time`, or `type="datetime-local"`
     * or `type="time"`; never in range mode.
     *
     * @since 3.0.0
     */
    public function hasTime(): bool
    {
        if ($this->isRange()) {
            return false;
        }

        return $this->time || in_array($this->attributes?->get('type'), ['datetime-local', 'time'], true);
    }
}
