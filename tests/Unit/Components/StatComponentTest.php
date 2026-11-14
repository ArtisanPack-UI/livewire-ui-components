<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Stat (#61): the design-system markup contract. The manifest parity check
 * is in tests/Feature/DesignSystemParityTest.php.
 */

function statDom(string $template, array $data = []): DOMXPath
{
    return designSystemDom(Blade::render($template, $data));
}

test('renders the root with its size and color defaults', function (): void {
    $root = statDom('<x-artisanpack-stat label="Profit" value="$15,708.98" />')->query('//div')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-stat')
        ->and($root->getAttribute('data-size'))->toBe('md')
        ->and($root->getAttribute('data-color'))->toBe('neutral')
        ->and($root->hasAttribute('data-emphasis'))->toBeFalse()
        ->and($root->hasAttribute('data-bare'))->toBeFalse();
});

test('renders the label row, value and unit', function (): void {
    $dom = statDom('<x-artisanpack-stat label="Latency" value="182" unit="ms" icon="wallet" color="info" />');

    expect(trim($dom->query('//*[@data-part="top"]/*[@data-part="label"]')->item(0)->textContent))->toBe('Latency')
        ->and($dom->query('//*[@data-part="top"]/*[@data-part="icon"][@aria-hidden="true"]/svg'))->toHaveCount(1)
        ->and($dom->query('//*[@data-part="value"]')->item(0)->textContent)->toBe('182ms')
        ->and($dom->query('//*[@data-part="value"]/*[@data-part="unit"]')->item(0)->textContent)->toBe('ms')
        ->and($dom->query('//div[@data-color="info"]'))->toHaveCount(1);
});

test('formats change as the delta, with hidden direction text and an arrow', function (float $change, string $trend, string $text, string $label): void {
    $dom   = statDom('<x-artisanpack-stat value="1" :change="$change" description="vs last month" />', ['change' => $change]);
    $delta = $dom->query('//*[@data-part="foot"]/*[@data-part="delta"]')->item(0);

    expect($delta->getAttribute('data-trend'))->toBe($trend)
        ->and($delta->getElementsByTagName('svg'))->toHaveCount(1)
        ->and(trim($dom->query('.//*[@class="apui-sr-only"]', $delta)->item(0)->textContent))->toBe($label)
        ->and(trim($delta->textContent))->toEndWith($text)
        ->and(trim($dom->query('//*[@data-part="description"]')->item(0)->textContent))->toBe('vs last month');
})->with([
    'up'   => [10.94, 'up', '10.9%', 'Increase'],
    'down' => [-5.4, 'down', '5.4%', 'Decrease'],
    'flat' => [0.0, 'flat', '0.0%', 'No change'],
]);

test('delta text and trend win over change', function (): void {
    $delta = statDom('<x-artisanpack-stat value="34.6" delta="6.2 hrs" trend="up" :change="-1" down-label="Bajada" />')
        ->query('//*[@data-part="delta"]')->item(0);

    expect($delta->getAttribute('data-trend'))->toBe('up')
        ->and(trim($delta->textContent))->toBe('Increase 6.2 hrs');
});

test('translatable direction labels replace the defaults', function (): void {
    $delta = statDom('<x-artisanpack-stat value="1" :change="-2" down-label="Baja" />')->query('//*[@data-part="delta"]')->item(0);

    expect(trim($delta->textContent))->toBe('Baja 2.0%');
});

test('a delta without a trend has no arrow or hidden text', function (): void {
    $delta = statDom('<x-artisanpack-stat value="7" delta="days in a row" />')->query('//*[@data-part="delta"]')->item(0);

    expect($delta->hasAttribute('data-trend'))->toBeFalse()
        ->and($delta->getElementsByTagName('svg'))->toHaveCount(0)
        ->and(trim($delta->textContent))->toBe('days in a row');
});

test('emphasis and bare are data attributes', function (): void {
    $root = statDom('<x-artisanpack-stat value="1" emphasis bare size="lg" color="primary" />')->query('//div')->item(0);

    expect($root->hasAttribute('data-emphasis'))->toBeTrue()
        ->and($root->hasAttribute('data-bare'))->toBeTrue()
        ->and($root->getAttribute('data-size'))->toBe('lg')
        ->and($root->getAttribute('data-color'))->toBe('primary');
});

test('unknown sizes and colors fall back to the defaults', function (): void {
    $root = statDom('<x-artisanpack-stat value="1" size="xl" color="text-red-500" />')->query('//div')->item(0);

    expect($root->getAttribute('data-size'))->toBe('md')
        ->and($root->getAttribute('data-color'))->toBe('neutral');
});

test('the default slot replaces value and the icon slot the named icon', function (): void {
    $dom = statDom('<x-artisanpack-stat label="Users" icon="wallet"><x-slot:icon><svg data-custom></svg></x-slot:icon><strong>4,200</strong></x-artisanpack-stat>');

    expect($dom->query('//*[@data-part="value"]/strong')->item(0)->textContent)->toBe('4,200')
        ->and($dom->query('//*[@data-part="icon"]/svg[@data-custom]'))->toHaveCount(1);
});

test('2.x title and change-label still work, and stay off the root', function (): void {
    $dom  = statDom('<x-artisanpack-stat title="Revenue" value="$1" change-label="since May" />');
    $root = $dom->query('//div')->item(0);

    expect(trim($dom->query('//*[@data-part="label"]')->item(0)->textContent))->toBe('Revenue')
        ->and(trim($dom->query('//*[@data-part="description"]')->item(0)->textContent))->toBe('since May')
        ->and($root->hasAttribute('title'))->toBeFalse()
        ->and($root->hasAttribute('change-label'))->toBeFalse();
});

test('classes reach their parts, and other attributes the root', function (): void {
    $dom = statDom('<x-artisanpack-stat id="rev" value="1" label="A" :classes="[\'root\' => \'r\', \'value\' => \'v\', \'label\' => \'l\']" />');

    expect($dom->query('//div[@id="rev"]')->item(0)->getAttribute('class'))->toBe('apui-stat r')
        ->and($dom->query('//*[@data-part="value"]')->item(0)->getAttribute('class'))->toBe('v')
        ->and($dom->query('//*[@data-part="label"]')->item(0)->getAttribute('class'))->toBe('l');
});

test('leaves no DaisyUI or Tailwind classes', function (): void {
    $html = Blade::render('<x-artisanpack-stat label="A" value="1" icon="wallet" :change="2" description="d" emphasis />');

    expect($html)->not->toContain('stat-')
        ->and($html)->not->toContain('text-success')
        ->and($html)->not->toContain('rounded');
});
