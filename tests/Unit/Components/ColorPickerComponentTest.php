<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\ColorPicker;
use Illuminate\Support\Facades\Blade;

/*
 * ColorPicker (#53): the field and popover markup on the behavior engine's
 * color-picker machine (apuiColorPicker), stable ids, wire:model through
 * entangle, the format select, swatches and translated labels. The manifest
 * parity check is in tests/Feature/DesignSystemParityTest.php.
 */

test('the field renders the contract parts with stable ids', function (): void {
    $html = Blade::render('<x-artisanpack-color-picker id="c" label="Primary" hint="Brand" error="Bad color." required value="#1f74bf" :swatches="[\'#1f74bf\', \'#0b9488\']" />');
    $dom  = designSystemDom($html);

    $parts = [];
    foreach ($dom->query('//*[@data-scope="color-picker"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    expect(array_values(array_unique($parts)))->toBe([
        'root', 'label', 'required', 'box', 'trigger', 'swatch', 'control', 'hint', 'error',
        'positioner', 'content', 'area', 'area-background', 'area-thumb', 'channel-slider',
        'channel-slider-track', 'channel-slider-thumb', 'transparency-grid', 'row', 'format-select',
        'eye-dropper-trigger', 'swatch-group', 'swatch-trigger',
    ])
        ->and($dom->query('//*[@data-part="root"]')->item(0)->getAttribute('id'))->toBe('c-root')
        ->and($dom->query('//input[@data-part="control"]')->item(0)->getAttribute('id'))->toBe('c-control')
        ->and($dom->query('//input[@data-part="control"]')->item(0)->getAttribute('aria-describedby'))->toBe('c-control-hint c-control-error')
        ->and($dom->query('//input[@data-part="control"]')->item(0)->getAttribute('value'))->toBe('#1f74bf')
        ->and($dom->query('//*[@data-part="trigger"]')->item(0)->getAttribute('aria-label'))->toBe('Choose a color')
        ->and($dom->query('//*[@data-part="content"]')->item(0)->hasAttribute('hidden'))->toBeTrue()
        ->and($dom->query('//*[@data-part="positioner"]')->item(0)->hasAttribute('wire:ignore'))->toBeTrue();
});

test('wire:model binds through entangle; other attributes land on the text input', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-color-picker wire:model.live="brand" name="brand" class="mt-2" x-on:format-change="log()" />'));
    $root = $dom->query('//div[contains(@class, "apui-color-picker")]')->item(0);

    expect($root->getAttribute('x-data'))->toContain("value: \$wire.entangle('brand').live")
        ->and($root->getAttribute('class'))->toBe('apui-color-picker mt-2')
        ->and($root->getAttribute('x-on:format-change'))->toBe('log()')
        ->and($dom->query('//input[@data-part="control"]')->item(0)->getAttribute('name'))->toBe('brand')
        ->and($dom->query('//input[@data-part="control"]')->item(0)->hasAttribute('wire:model.live'))->toBeFalse();
});

test('the options carry the format, alpha and translated labels', function (): void {
    $data = designSystemDom(Blade::render('<x-artisanpack-color-picker format="oklch" :alpha="false" hue-label="Tono" />'))
        ->query('//div[contains(@class, "apui-color-picker")]')->item(0)->getAttribute('x-data');

    expect($data)->toStartWith('apuiColorPicker(')
        ->toContain('format\\u0022:\\u0022oklch')
        ->toContain('alpha\\u0022:false')
        ->toContain('hue\\u0022:\\u0022Tono')
        ->toContain('Pick a color from the screen');
});

test('the format select lists the four formats with the current one selected', function (): void {
    $dom     = designSystemDom(Blade::render('<x-artisanpack-color-picker format="hsl" />'));
    $options = [];
    foreach ($dom->query('//select[@data-part="format-select"]/option') as $option) {
        $options[$option->getAttribute('value')] = $option->hasAttribute('selected');
    }

    expect($options)->toBe(['hex' => false, 'rgb' => false, 'hsl' => true, 'oklch' => false])
        ->and($dom->query('//*[@data-part="root"]')->item(0)->getAttribute('data-format'))->toBe('hsl');
});

test('an unknown format falls back to hex', function (): void {
    expect((new ColorPicker(format: 'cmyk'))->resolvedFormat())->toBe('hex');
});

test('without alpha the opacity slider is left out', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-color-picker :alpha="false" />'));

    expect($dom->query('//*[@data-part="channel-slider"][@data-channel="alpha"]')->length)->toBe(0)
        ->and($dom->query('//*[@data-part="channel-slider"][@data-channel="hue"]')->length)->toBe(1);
});

test('swatches are named and the current one is pressed', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-color-picker value="#0B9488" :swatches="[\'#1f74bf\', \'#0b9488\']" />'));

    expect($dom->query('//button[@data-part="swatch-trigger"][@aria-pressed="true"]')->item(0)->getAttribute('aria-label'))->toBe('#0b9488')
        ->and($dom->query('//button[@data-part="swatch-trigger"]')->item(0)->getAttribute('x-apui:swatch-trigger'))->toBe("{ value: '#1f74bf' }");
});

test('unsafe swatch and value text is never written into a style', function (): void {
    $html = Blade::render('<x-artisanpack-color-picker value="red;}body{x:y" :swatches="[\'url(x)\', \'#000\']" />');

    $dom    = designSystemDom($html);
    $styles = [];
    foreach ($dom->query('//*[@style]') as $element) {
        $styles[] = $element->getAttribute('style');
    }

    expect(implode(';', $styles))->not->toContain('body')
        ->not->toContain('url(')
        ->toContain('background: #000')
        ->and($html)->not->toContain('url(x)')
        ->and($dom->query('//button[@data-part="swatch-trigger"]')->length)->toBe(1);
});

test('open and portal follow the props', function (): void {
    $open   = Blade::render('<x-artisanpack-color-picker open :portal="false" />');
    $portal = Blade::render('<x-artisanpack-color-picker />');

    expect(designSystemDom($open)->query('//*[@data-part="content"]')->item(0)->hasAttribute('hidden'))->toBeFalse()
        ->and($open)->not->toContain('x-teleport')
        ->and($portal)->toContain('x-teleport="body"');
});

test('the deprecated colorpicker tag renders the same component', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-colorpicker label="Old" />'));

    expect($dom->query('//div[contains(@class, "apui-color-picker")]')->length)->toBe(1);
});

test('the constructor declares only the manifest props', function (): void {
    $parameters = array_map(
        static fn (ReflectionParameter $parameter): string => $parameter->getName(),
        (new ReflectionClass(ColorPicker::class))->getConstructor()->getParameters(),
    );

    expect($parameters)->toBe([
        'value', 'format', 'alpha', 'swatches', 'size', 'label', 'hint', 'error', 'invalid', 'disabled',
        'required', 'open', 'portal', 'triggerLabel', 'areaLabel', 'hueLabel', 'alphaLabel', 'formatLabel',
        'eyeDropperLabel', 'swatchesLabel', 'errorField', 'classes',
    ]);
});

test('the server render draws the machine state: thumbs, gradients and slider values', function (): void {
    $dom   = designSystemDom(Blade::render('<x-artisanpack-color-picker value="#1f74bf80" open :portal="false" />'));
    $area  = $dom->query('//*[@data-part="area-thumb"]')->item(0);
    $hue   = $dom->query('//*[@data-part="channel-slider-thumb"][@data-channel="hue"]')->item(0);
    $alpha = $dom->query('//*[@data-part="channel-slider-thumb"][@data-channel="alpha"]')->item(0);

    expect($area->getAttribute('aria-valuenow'))->toBe('83.77')
        ->and($area->getAttribute('aria-valuetext'))->toBe('84%, 75%')
        ->and($area->getAttribute('style'))->toContain('--apui-_x: 83.77%')
        ->and($hue->getAttribute('aria-valuenow'))->toBe('208.13')
        ->and($hue->getAttribute('aria-valuetext'))->toBe('208°')
        ->and($alpha->getAttribute('aria-valuetext'))->toBe('50%')
        ->and($dom->query('//*[@data-part="area-background"]')->item(0)->getAttribute('style'))->toBe('--apui-_hue-color: hsl(208.13, 100%, 50%)')
        ->and($dom->query('//*[@data-part="eye-dropper-trigger"]')->item(0)->hasAttribute('hidden'))->toBeTrue();
});

test('color text in every format parses as the engine does', function (string $text, array $rgba): void {
    expect(ColorPicker::parseColor($text))->toBe($rgba);
})->with([
    ['#1f74bf', [31, 116, 191, 1.0]],
    ['#abc', [170, 187, 204, 1.0]],
    ['rgb(31 116 191 / 50%)', [31, 116, 191, 0.5]],
    ['hsl(0, 100%, 50%)', [255, 0, 0, 1.0]],
    ['oklch(0.549 0.14 250.2)', [31, 116, 191, 1.0]],
]);

test('text that is not a color has no channels', function (): void {
    expect(ColorPicker::parseColor('blue'))->toBeNull()
        ->and(ColorPicker::parseColor('rgb(1 2)'))->toBeNull();
});

test('hues come out right for primaries, secondaries and colors between', function (string $color, float $hue): void {
    expect((new ColorPicker(value: $color))->withAttributes([])->machineView()['hue'])->toBe($hue);
})->with([
    'red'     => ['#ff0000', 0.0],
    'orange'  => ['#ff8800', 32.0],
    'yellow'  => ['#ffff00', 60.0],
    'green'   => ['#00ff00', 120.0],
    'cyan'    => ['#00ffff', 180.0],
    'blue'    => ['#0000ff', 240.0],
    'magenta' => ['#ff00ff', 300.0],
    'brand'   => ['#1f74bf', 208.13],
    'white'   => ['#ffffff', 0.0],
]);

test('saturation and brightness of full and empty colors', function (): void {
    $red   = (new ColorPicker(value: '#ff0000'))->withAttributes([])->machineView();
    $black = (new ColorPicker(value: '#000000'))->withAttributes([])->machineView();

    expect([$red['saturation'], $red['brightness']])->toBe([100.0, 100.0])
        ->and($red['pure'])->toBe('hsl(0, 100%, 50%)')
        ->and([$black['saturation'], $black['brightness']])->toBe([0.0, 0.0]);
});

test('the deprecated colorpicker attributes do not leak onto the input', function (): void {
    $input = designSystemDom(Blade::render('<x-artisanpack-colorpicker icon="o-swatch" icon-right="x" prefix="a" suffix="b" inline clearable random hint-class="c" omit-error first-error-only name="brand" />'))
        ->query('//input[@data-part="control"]')->item(0);

    foreach (ColorPicker::LEGACY_ATTRIBUTES as $attribute) {
        expect($input->hasAttribute(strtolower($attribute)))->toBeFalse();
    }
    expect($input->getAttribute('name'))->toBe('brand');
});
