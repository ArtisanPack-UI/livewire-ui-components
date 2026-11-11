<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\DatePicker;
use Illuminate\Support\Facades\Blade;

/*
 * DatePicker (#55): the design-system markup contract, DateTime as its time
 * mode, and the deprecated datepicker alias. The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

function datePickerParts(DOMXPath $dom): array
{
    $parts = [];
    foreach ($dom->query('//*[@data-scope="date-picker"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    return array_values(array_unique($parts));
}

test('it renders the field and the popover with stable ids', function (): void {
    $dom = designSystemDom(Blade::render(
        '<x-artisanpack-date-picker id="move-in" label="Move-in date" hint="MM/DD/YYYY" required footer="Apply" open :portal="false" />',
    ));

    expect(datePickerParts($dom))->toBe([
        'root', 'label', 'required', 'box', 'trigger', 'control', 'hint', 'positioner', 'content', 'view',
        'view-control', 'prev-trigger', 'range-text', 'next-trigger', 'table', 'table-head', 'table-row',
        'table-header', 'table-body', 'table-cell', 'table-cell-trigger', 'footer',
    ]);

    $root = $dom->query('//*[@data-part="root"]')->item(0);
    expect($root->getAttribute('class'))->toContain('apui-date-picker')
        ->and($root->getAttribute('id'))->toBe('move-in-root')
        ->and($root->getAttribute('data-size'))->toBe('md')
        ->and($root->getAttribute('data-mode'))->toBe('single')
        ->and($root->getAttribute('data-state'))->toBe('open')
        ->and($root->getAttribute('x-data'))->toStartWith('apuiDatePicker(');

    $control = $dom->query('//input[@data-part="control"]')->item(0);
    expect($control->getAttribute('id'))->toBe('move-in-control')
        ->and($control->hasAttribute('required'))->toBeTrue()
        ->and($control->getAttribute('aria-describedby'))->toBe('move-in-control-hint')
        ->and($dom->query('//label[@data-part="label"]')->item(0)->getAttribute('for'))->toBe('move-in-control');

    $trigger = $dom->query('//*[@data-part="trigger"]')->item(0);
    expect($trigger->getAttribute('aria-label'))->toBe('Choose date')
        ->and($trigger->getAttribute('aria-controls'))->toBe('move-in-content')
        ->and($trigger->getAttribute('aria-expanded'))->toBe('true');

    expect($dom->query('//*[@data-part="table"]')->item(0)->getAttribute('role'))->toBe('grid')
        ->and($dom->query('//*[@data-part="positioner"]')->item(0)->hasAttribute('wire:ignore'))->toBeTrue()
        ->and($dom->query('//*[@data-part="prev-trigger"]')->item(0)->getAttribute('aria-label'))->toBe('Previous month')
        ->and($dom->query('//*[@data-part="next-trigger"]')->item(0)->getAttribute('aria-label'))->toBe('Next month');
});

test('a closed popover renders hidden and teleports by default', function (): void {
    $html = Blade::render('<x-artisanpack-date-picker label="Date" />');
    $dom  = designSystemDom($html);

    expect($html)->toContain('x-teleport="body"')
        ->and($dom->query('//*[@data-part="content"]')->item(0)->hasAttribute('hidden'))->toBeTrue()
        ->and($dom->query('//*[@data-part="root"]')->item(0)->getAttribute('data-state'))->toBe('closed');
});

test('wire:model entangles the value and other attributes reach the input', function (): void {
    $html = Blade::render('<x-artisanpack-date-picker wire:model.live="moveIn" label="Date" autocomplete="bday" x-on:value-change="changed = true" />');
    $dom  = designSystemDom($html);
    $root = $dom->query('//*[@data-part="root"]')->item(0);

    expect($root->getAttribute('x-data'))->toContain('value: $wire.entangle(\'moveIn\').live')
        ->and($root->getAttribute('x-on:value-change'))->toBe('changed = true')
        ->and($dom->query('//input[@data-part="control"]')->item(0)->getAttribute('autocomplete'))->toBe('bday')
        ->and($dom->query('//input[@data-part="control"]')->item(0)->hasAttribute('wire:model.live'))->toBeFalse();
});

test('range mode renders two inputs and submits two hidden values', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-date-picker id="stay" mode="range" name="stay" label="Stay" :value="[\'2026-11-12\', \'2026-11-18\']" />'));

    $inputs = $dom->query('//input[@data-part="control"]');
    expect($inputs->length)->toBe(2)
        ->and($inputs->item(1)->getAttribute('id'))->toBe('stay-control-1')
        ->and($inputs->item(1)->getAttribute('aria-label'))->toBe('End date')
        ->and($dom->query('//input[@type="hidden"][@name="stay[]"]')->length)->toBe(2)
        ->and($dom->query('//*[@data-part="root"]')->item(0)->getAttribute('data-mode'))->toBe('range')
        ->and($dom->query('//*[@data-part="root"]')->item(0)->getAttribute('x-data'))->toContain('2026-11-18');
});

test('time mode adds the time listbox and the time zone', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-date-picker id="viewing" time :step="60" time-zone="Europe/Madrid" label="Viewing" />'));

    $options = $dom->query('//*[@data-part="time-option"]');
    expect($dom->query('//*[@data-part="time-list"]')->item(0)->getAttribute('role'))->toBe('listbox')
        ->and($dom->query('//*[@data-part="time-list"]')->item(0)->getAttribute('aria-label'))->toBe('Time')
        ->and($options->length)->toBe(24)
        ->and($options->item(11)->getAttribute('data-value'))->toBe('11:00')
        ->and($options->item(11)->getAttribute('role'))->toBe('option')
        ->and($dom->query('//*[@data-part="time-zone"]')->item(0)->textContent)->toBe('Time zone: Europe/Madrid')
        ->and($dom->query('//*[@data-part="trigger"]')->item(0)->getAttribute('aria-label'))->toBe('Choose date and time')
        ->and($dom->query('//*[@data-part="content"]')->item(0)->hasAttribute('data-time'))->toBeTrue()
        ->and($dom->query('//input[@data-part="control"]')->item(0)->getAttribute('aria-describedby'))->toBe('viewing-control-time-zone');
});

test('labels are translatable props', function (): void {
    app('translator')->addLines(['*.Previous month' => 'Mois précédent', '*.Choose date' => 'Choisir une date'], 'fr');
    app()->setLocale('fr');

    $dom = designSystemDom(Blade::render('<x-artisanpack-date-picker label="Date" next-label="Suivant" />'));

    expect($dom->query('//*[@data-part="prev-trigger"]')->item(0)->getAttribute('aria-label'))->toBe('Mois précédent')
        ->and($dom->query('//*[@data-part="next-trigger"]')->item(0)->getAttribute('aria-label'))->toBe('Suivant')
        ->and($dom->query('//*[@data-part="trigger"]')->item(0)->getAttribute('aria-label'))->toBe('Choisir une date')
        ->and($dom->query('//*[@data-part="root"]')->item(0)->getAttribute('x-data'))->toContain('\u0022locale\u0022:\u0022fr\u0022');
});

test('errors mark the field invalid', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-date-picker id="closing" label="Closing date" error="That date doesn\'t exist." />'));

    expect($dom->query('//*[@data-part="root"]')->item(0)->hasAttribute('data-invalid'))->toBeTrue()
        ->and($dom->query('//input[@data-part="control"]')->item(0)->getAttribute('aria-invalid'))->toBe('true')
        ->and($dom->query('//*[@data-part="error"]')->item(0)->textContent)->toContain('That date doesn');
});

test('the datepicker alias renders the same component', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-datepicker id="old" label="Date" />'));

    expect($dom->query('//*[contains(@class, "apui-date-picker")]')->length)->toBe(1);
});

test('the time options follow the step', function (): void {
    $picker = new DatePicker(time: true, step: 90);

    expect($picker->timeOptions())->toHaveCount(16)
        ->and($picker->timeOptions()[1])->toBe('01:30')
        ->and((new DatePicker(step: 0))->timeOptions())->toHaveCount(48);
});

test('the 2.x flatpickr config maps onto the new props instead of crashing', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-datepicker id="stay" label="Stay" :config="[\'mode\' => \'range\', \'minDate\' => \'2026-11-01\', \'maxDate\' => \'2026-12-31\', \'dateFormat\' => \'d.m.Y\', \'altInput\' => true]" />'));
    $root = $dom->query('//*[@data-part="root"]')->item(0);
    $data = $root->getAttribute('x-data');

    expect($root->getAttribute('data-mode'))->toBe('range')
        ->and($dom->query('//input[@data-part="control"]')->length)->toBe(2)
        ->and($data)->toContain('\u0022min\u0022:\u00222026-11-01\u0022')
        ->and($data)->toContain('\u0022max\u0022:\u00222026-12-31\u0022')
        ->and($data)->toContain('\u0022format\u0022:\u0022dd.MM.yyyy\u0022')
        ->and($dom->query('//input[@data-part="control"]')->item(0)->getAttribute('placeholder'))->toBe('DD.MM.YYYY')
        ->and($dom->query('//input[@data-part="control"]')->item(0)->hasAttribute('config'))->toBeFalse();
});

test('flatpickr enableTime turns time mode on and today becomes a date', function (): void {
    $picker = new DatePicker;
    $picker->withAttributes(['config' => ['enableTime' => true, 'minDate' => 'today', 'dateFormat' => 'Y-m-d H:i']]);
    $picker->applyLegacyConfig();

    expect($picker->hasTime())->toBeTrue()
        ->and($picker->min)->toBe((new DateTimeImmutable('today'))->format('Y-m-d'))
        ->and($picker->format)->toBe('yyyy-MM-dd');
});

test('dropped 2.x props and non-scalar attributes never reach the input', function (): void {
    $html  = Blade::render('<x-artisanpack-datepicker label="Date" icon="o-calendar" icon-right="o-x" prefix="$" suffix="!" inline clearable hint-class="x" omit-error first-error-only :data-list="[1, 2]" autocomplete="bday" />');
    $input = designSystemDom($html)->query('//input[@data-part="control"]')->item(0);

    foreach (['icon', 'icon-right', 'prefix', 'suffix', 'inline', 'clearable', 'hint-class', 'omit-error', 'first-error-only', 'data-list'] as $attribute) {
        expect($input->hasAttribute($attribute))->toBeFalse($attribute);
    }
    expect($input->getAttribute('autocomplete'))->toBe('bday');
});
