<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * KpiCard (#61): the design-system markup contract, built from Stat with a
 * Sparkline and a Progress. The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

function kpiCardDom(string $template, array $data = []): DOMXPath
{
    return designSystemDom(Blade::render($template, $data));
}

const KPI_STAT = ['label' => 'Profit', 'value' => '$15,708.98', 'icon' => 'trending-up', 'color' => 'success', 'change' => 8.1, 'description' => 'vs last month'];

test('renders a card with the stat parts in the kpi-card scope', function (): void {
    $dom  = kpiCardDom('<x-artisanpack-kpi-card :stat="$stat" />', ['stat' => KPI_STAT]);
    $root = $dom->query('//div')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-card apui-kpi-card')
        ->and($root->getAttribute('data-color'))->toBe('success')
        ->and(trim($dom->query('//*[@data-scope="kpi-card"][@data-part="top"]/*[@data-part="label"]')->item(0)->textContent))->toBe('Profit')
        ->and($dom->query('//*[@data-part="top"]/*[@data-part="icon"][@aria-hidden="true"]/svg'))->toHaveCount(1)
        ->and(trim($dom->query('//*[@data-part="row"]/*[@data-part="summary"]/*[@data-part="value"]')->item(0)->textContent))->toBe('$15,708.98')
        ->and($dom->query('//*[@data-part="delta"]')->item(0)->getAttribute('data-trend'))->toBe('up')
        ->and(trim($dom->query('//*[@data-part="delta"]')->item(0)->textContent))->toBe('Increase 8.1%')
        ->and($dom->query('//svg[@data-part="sparkline"]'))->toHaveCount(0)
        ->and($dom->query('//*[@data-part="target"]'))->toHaveCount(0);
});

test('draws the trend as an area sparkline beside the value', function (): void {
    $svg = kpiCardDom('<x-artisanpack-kpi-card :stat="$stat" :sparkline="[2, 4, 8]" />', ['stat' => KPI_STAT])
        ->query('//*[@data-part="row"]/svg')->item(0);

    expect($svg->getAttribute('class'))->toBe('apui-sparkline')
        ->and($svg->getAttribute('data-scope'))->toBe('kpi-card')
        ->and($svg->getAttribute('data-part'))->toBe('sparkline')
        ->and($svg->getAttribute('data-variant'))->toBe('area')
        ->and($svg->getAttribute('viewbox'))->toBe('0 0 96 36')
        ->and($svg->getAttribute('aria-label'))->toBe('Trend from 2 to 8');
});

test('shows progress toward a target with its text', function (): void {
    $dom    = kpiCardDom('<x-artisanpack-kpi-card :stat="$stat" :target="[\'value\' => 15708.98, \'max\' => 25000, \'label\' => \'Goal $25,000\']" />', ['stat' => KPI_STAT]);
    $target = $dom->query('//*[@data-part="target"]')->item(0);
    $spans  = $dom->query('//*[@data-part="targetText"]/span');

    expect($target)->not->toBeNull()
        ->and($spans)->toHaveCount(2)
        ->and($spans->item(0)->textContent)->toBe('Goal $25,000')
        ->and($spans->item(1)->textContent)->toBe('63% of goal');
});

test('a translated target label and sparkline label', function (): void {
    $dom = kpiCardDom('<x-artisanpack-kpi-card :stat="$stat" :sparkline="[1, 3]" sparkline-label="De {first} a {latest}" :target="[\'value\' => 40]" target-label="{percent} % del objetivo" />', ['stat' => KPI_STAT]);

    expect($dom->query('//svg[@role="img"]')->item(0)->getAttribute('aria-label'))->toBe('De 1 a 3')
        ->and(trim($dom->query('//*[@data-part="targetText"]')->item(0)->textContent))->toBe('40 % del objetivo');
});

test('the period slot sits in the label row', function (): void {
    $dom = kpiCardDom('<x-artisanpack-kpi-card :stat="$stat"><x-slot:period><select aria-label="Period"><option>This month</option></select></x-slot:period></x-artisanpack-kpi-card>', ['stat' => KPI_STAT]);

    expect($dom->query('//*[@data-part="top"]/*[@data-part="period"]/select'))->toHaveCount(1);
});

test('2.x props still fill stat and sparkline, and stay off the root', function (): void {
    $dom  = kpiCardDom('<x-artisanpack-kpi-card title="Revenue" value="$1,200" icon="wallet" :change="-3.25" change-label="vs May" :sparkline-data="[1, 2, 3]" />');
    $root = $dom->query('//div')->item(0);

    expect(trim($dom->query('//*[@data-part="label"]')->item(0)->textContent))->toBe('Revenue')
        ->and(trim($dom->query('//*[@data-part="value"]')->item(0)->textContent))->toBe('$1,200')
        ->and($dom->query('//*[@data-part="delta"]')->item(0)->getAttribute('data-trend'))->toBe('down')
        ->and(trim($dom->query('//*[@data-part="description"]')->item(0)->textContent))->toBe('vs May')
        ->and($dom->query('//svg[@data-part="sparkline"]'))->toHaveCount(1)
        ->and($root->hasAttribute('title'))->toBeFalse()
        ->and($root->hasAttribute('value'))->toBeFalse();
});

test('classes reach their parts', function (): void {
    $dom = kpiCardDom('<x-artisanpack-kpi-card :stat="$stat" :sparkline="[1, 2]" :classes="[\'root\' => \'r\', \'value\' => \'v\', \'sparkline\' => \'s\']" />', ['stat' => KPI_STAT]);

    expect($dom->query('//div')->item(0)->getAttribute('class'))->toBe('apui-card apui-kpi-card r')
        ->and($dom->query('//*[@data-part="value"]')->item(0)->getAttribute('class'))->toBe('v')
        ->and($dom->query('//svg[@data-part="sparkline"]')->item(0)->getAttribute('class'))->toBe('apui-sparkline s');
});

test('draws a sparkline from numeric strings, such as database decimals', function (): void {
    $svg = kpiCardDom('<x-artisanpack-kpi-card :stat="$stat" :sparkline="$points" />', ['stat' => KPI_STAT, 'points' => ['2.00', '4.50', '8.25']])
        ->query('//*[@data-part="row"]/svg')->item(0);

    expect($svg)->not->toBeNull()
        ->and($svg->getAttribute('aria-label'))->toBe('Trend from 2 to 8.25');
});
