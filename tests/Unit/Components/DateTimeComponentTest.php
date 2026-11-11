<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\DatePicker;
use ArtisanPack\LivewireUiComponents\View\Components\DateTime;
use Illuminate\Support\Facades\Blade;

/*
 * DateTime is deprecated in 3.0: <x-artisanpack-datetime> renders the
 * design-system DatePicker in its time mode (#55).
 */

test('type="datetime-local" is the date picker with the time list on', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-datetime id="appointment" type="datetime-local" label="Appointment" wire:model="appointmentAt" />'));

    expect($dom->query('//*[contains(@class, "apui-date-picker")]')->length)->toBe(1)
        ->and($dom->query('//*[@data-part="time-list"]')->length)->toBe(1)
        ->and($dom->query('//*[@data-part="root"]')->item(0)->getAttribute('x-data'))->toContain('\u0022time\u0022:true');
});

test('range mode has no time list', function (): void {
    expect((new DateTime(mode: 'range'))->hasTime())->toBeFalse()
        ->and(new DateTime)->toBeInstanceOf(DatePicker::class);
});

test('it is date only by default, as 2.x type="date" was', function (string $markup): void {
    $dom   = designSystemDom(Blade::render($markup));
    $input = $dom->query('//input[@data-part="control"]')->item(0);

    expect($dom->query('//*[@data-part="time-list"]')->length)->toBe(0)
        ->and($input->getAttribute('type'))->toBe('text')
        ->and(substr_count(Blade::render($markup), 'type="date'))->toBe(0);
})->with([
    'no type' => '<x-artisanpack-datetime label="Day" />',
    'date'    => '<x-artisanpack-datetime label="Day" type="date" />',
]);

test('type="time" renders time mode with a single type attribute', function (): void {
    $html = Blade::render('<x-artisanpack-datetime label="Start" type="time" />');
    $dom  = designSystemDom($html);

    expect($dom->query('//*[@data-part="time-list"]')->length)->toBe(1)
        ->and(preg_match_all('/<input[^>]*data-part="control"[^>]*>/', $html, $inputs))->toBe(1)
        ->and(substr_count($inputs[0][0], 'type='))->toBe(1);
});
