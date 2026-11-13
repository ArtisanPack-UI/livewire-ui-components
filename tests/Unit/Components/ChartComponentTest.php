<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\ChartGeometry;
use Illuminate\Support\Facades\Blade;

/*
 * Chart (#63): the design-system markup contract, and ChartGeometry, the PHP
 * port of the behavior package's chartGeometry. The vectors in
 * tests/Fixtures/design-system/chart-vectors.json are a copy of the behavior
 * package's test/fixtures/chart-vectors.json, which its own tests check the
 * TypeScript against, so Blade draws exactly what React and Vue draw. The
 * manifest parity check is in tests/Feature/DesignSystemParityTest.php.
 */

function chartVectors(): array
{
    return json_decode((string) file_get_contents(dirname(__DIR__, 2).'/Fixtures/design-system/chart-vectors.json'), true);
}

function chartDom(string $props, array $data = [], string $slot = ''): DOMXPath
{
    $tag = '' === $slot
        ? '<x-artisanpack-chart '.$props.' />'
        : '<x-artisanpack-chart '.$props.'>'.$slot.'</x-artisanpack-chart>';

    return designSystemDom(Blade::render($tag, $data));
}

const CHART_WEEKS = ['W1', 'W2', 'W3', 'W4'];

test('the geometry matches the shared vectors byte for byte', function (): void {
    $vectors = chartVectors();

    foreach ($vectors['geometry'] as $vector) {
        expect(json_encode(ChartGeometry::compute($vector['series'], $vector['options'])))
            ->toBe(json_encode($vector['expected']), $vector['name']);
    }

    foreach ($vectors['ticks'] as $vector) {
        expect(json_encode(ChartGeometry::ticks($vector['min'], $vector['max'])))->toBe(json_encode($vector['expected']));
    }

    foreach ($vectors['values'] as $vector) {
        expect(ChartGeometry::formatValue($vector['value'], $vector['options']))->toBe($vector['expected'], (string) $vector['value']);
    }

    foreach ($vectors['tickLabels'] as $vector) {
        expect(ChartGeometry::formatTicks($vector['ticks'], $vector['options']))->toBe($vector['expected']);
    }
});

test('toFixed rounds the exact double as JavaScript does, not as number_format does', function (): void {
    expect(ChartGeometry::toFixed(1.005, 2))->toBe('1.00')
        ->and(ChartGeometry::toFixed(1.15, 1))->toBe('1.1')
        ->and(ChartGeometry::toFixed(1.45, 1))->toBe('1.4')
        ->and(ChartGeometry::toFixed(8.25, 1))->toBe('8.3')
        ->and(ChartGeometry::toFixed(0.125, 2))->toBe('0.13')
        ->and(ChartGeometry::toFixed(9.995, 2))->toBe('9.99')
        ->and(ChartGeometry::toFixed(99.95, 1))->toBe('100.0')
        ->and(ChartGeometry::toFixed(-2.5, 0))->toBe('-3')
        ->and(ChartGeometry::toFixed(-0.001, 2))->toBe('-0.00')
        ->and(ChartGeometry::toFixed(-0.0, 1))->toBe('0.0')
        ->and(ChartGeometry::toFixed(1234567.891, 2))->toBe('1234567.89')
        ->and(ChartGeometry::toFixed(3, 0))->toBe('3');
});

test('small values keep their digits and the compact unit steps up after rounding', function (): void {
    expect(ChartGeometry::formatValue(0.0012))->toBe('0.0012')
        ->and(ChartGeometry::formatValue(999950, ['notation' => 'compact']))->toBe('1.0M')
        ->and(ChartGeometry::formatValue(999.996, ['notation' => 'compact']))->toBe('1.0k')
        ->and(ChartGeometry::formatValue(1150, ['notation' => 'compact', 'prefix' => '$']))->toBe('$1.1k')
        ->and(ChartGeometry::formatTicks([0, 0.001, 0.002]))->toBe(['0', '0.001', '0.002']);
});

test('any range gets a finite axis with distinct ends, never hanging or dividing by zero', function (): void {
    $tiny = ChartGeometry::compute([['name' => 'A', 'data' => [1e-60, -1e-300]]], ['type' => 'area']);

    expect(ChartGeometry::ticks(0, PHP_FLOAT_MAX))->toBe([0, 2.0e299, 4.0e299, 6.0e299, 8.0e299, 1.0e300])
        ->and(ChartGeometry::ticks(-INF, INF))->toBe([-1.0e300, -5.0e299, 0, 5.0e299, 1.0e300])
        ->and(ChartGeometry::ticks(0, 1e-60))->toBe([0, 1.0e-10])
        ->and(ChartGeometry::ticks(NAN, NAN))->toBe([0, 0.2, 0.4, 0.6, 0.8, 1])
        ->and(array_column($tiny['ticks'], 'label'))->toBe(['-0.0000000001', '0', '0.0000000001'])
        ->and(json_encode($tiny))->not->toMatch('/NaN|Infinity/');
});

test('values from 1e21 up print in full, as the TypeScript does', function (): void {
    expect(ChartGeometry::formatValue(2.5e21))->toBe('2,500,000,000,000,000,000,000')
        ->and(ChartGeometry::formatValue(-1e21, ['notation' => 'compact']))->toBe('-1,000,000,000,000.0B');
});

test('a viewBox that leaves no room inside the padding falls back to the default', function (): void {
    $size = static function (mixed $width, mixed $height): array {
        $geometry = ChartGeometry::compute([1, 2], ['width' => $width, 'height' => $height]);

        return [$geometry['width'], $geometry['height']];
    };

    expect($size(48, 36))->toBe([520, 220])
        ->and($size(49, 37))->toBe([49, 37])
        ->and($size(INF, NAN))->toBe([520, 220]);
});

test('the geometry draws the spec preview grid and labels the best week', function (): void {
    $vector   = chartVectors()['geometry'][0];
    $geometry = ChartGeometry::compute($vector['series'], $vector['options']);

    expect(array_column($geometry['ticks'], 'label'))->toBe(['$0k', '$2k', '$4k', '$6k', '$8k', '$10k'])
        ->and(array_column($geometry['ticks'], 'y'))->toBe(['196.0', '159.2', '122.4', '85.6', '48.8', '12.0'])
        ->and($geometry['value'])->toBe(['x' => '205.2', 'y' => '4.0', 'text' => '$10.0k'])
        ->and(ChartGeometry::series([1, 'x', '2.5'], 'Visits'))->toBe([['name' => 'Visits', 'data' => [1, null, 2.5]]])
        ->and(ChartGeometry::niceNumber(7.4, false))->toBe(10.0)
        ->and(ChartGeometry::formatTicks([0, 500, 1000, 1500], ['notation' => 'compact', 'prefix' => '$']))->toBe(['$0k', '$0.5k', '$1k', '$1.5k']);
});

test('renders a bar chart in a card with focusable, named marks', function (): void {
    $dom  = chartDom('type="bar" title="Weekly revenue" description="Best week labeled" :labels="$labels" :series="[ [ \'name\' => \'Revenue\', \'data\' => [ 4100, 6000, 8200, 10000 ] ] ]" prefix="$" notation="compact"', ['labels' => CHART_WEEKS]);
    $root = $dom->query('//div[contains(@class, "apui-chart")]')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-card apui-chart')
        ->and($root->getAttribute('data-type'))->toBe('bar')
        ->and($root->getAttribute('x-data'))->toBe('apuiChart')
        ->and($dom->query('//h3[@data-part="title"]')->item(0)->textContent)->toBe('Weekly revenue')
        ->and($dom->query('//p[@data-part="description"]')->item(0)->textContent)->toBe('Best week labeled');

    $plot = $dom->query('//svg[@data-part="plot"]')->item(0);
    expect($plot->getAttribute('role'))->toBe('group')
        ->and($plot->getAttribute('aria-label'))->toBe('Weekly revenue')
        ->and($plot->getAttribute('viewbox'))->toBe('0 0 520 220');

    $marks = $dom->query('//*[@data-part="mark"]');
    expect($marks)->toHaveCount(4);
    $first = $marks->item(0);
    expect($first->getAttribute('tabindex'))->toBe('0')
        ->and($first->getAttribute('role'))->toBe('img')
        ->and($first->getAttribute('aria-label'))->toBe('W1: $4.1k')
        ->and($first->getAttribute('data-tooltip-title'))->toBe('W1')
        ->and($first->getAttribute('data-tooltip-body'))->toBe('$4.1k')
        ->and($first->getAttribute('data-series'))->toBe('1')
        ->and($dom->query('//*[@data-part="value"]')->item(0)->textContent)->toBe('$10.0k')
        ->and($dom->query('//*[@data-part="legend"]'))->toHaveCount(0)
        ->and($dom->query('//*[@data-part="table"]'))->toHaveCount(0);

    $tooltip = $dom->query('//*[@data-part="tooltip"]')->item(0);
    expect($tooltip->hasAttribute('hidden'))->toBeTrue()
        ->and($tooltip->getAttribute('aria-hidden'))->toBe('true');
});

test('draws every part for an area chart with two series, actions and the table', function (): void {
    $dom = chartDom(
        'type="area" title="Analytic" :labels="[ \'Q1\', \'Q2\' ]" :series="[ [ \'name\' => \'Sales\', \'data\' => [ 1, 3 ] ], [ \'name\' => \'Target\', \'data\' => [ 2, 2 ] ] ]" tabular',
        [],
        '<x-slot:actions><button type="button">Export</button></x-slot:actions>',
    );
    $drawn = [];
    foreach ($dom->query('//*[@data-scope="chart"]') as $node) {
        $drawn[] = $node->getAttribute('data-part');
    }

    expect(array_values(array_unique($drawn)))->toEqualCanonicalizing([
        'header', 'title', 'legend', 'swatch', 'actions', 'body', 'plot', 'grid', 'axis', 'area', 'line',
        'point', 'crosshair', 'dot', 'value', 'mark', 'tooltip', 'table',
    ])
        ->and($dom->query('//*[@data-part="legend"]/li')->item(1)->textContent)->toBe('Target')
        ->and($dom->query('//*[@data-part="mark"]')->item(0)->getAttribute('aria-label'))->toBe('Q1: Sales 1, Target 2')
        ->and($dom->query('//*[@data-part="point"]')->item(0)->getAttribute('visibility'))->toBe('hidden')
        ->and($dom->query('//table/@aria-label')->item(0)->value)->toBe('Analytic')
        ->and($dom->query('//table/thead//th')->item(1)->textContent)->toBe('Target')
        ->and($dom->query('//table/tbody/tr[2]/th')->item(0)->textContent)->toBe('Q2')
        ->and($dom->query('//table/tbody/tr[2]/td')->item(0)->textContent)->toBe('3');
});

test('draws a donut with its shares in the legend beside it', function (): void {
    $dom = chartDom('type="donut" title="Active listings" :labels="[ \'United States\', \'Mexico\', \'Canada\', \'Other\' ]" :series="[ 1240, 990, 723, 260 ]"');

    expect($dom->query('//*[@data-part="mark"]'))->toHaveCount(4)
        ->and($dom->query('//*[@data-part="plot"]')->item(0)->getAttribute('viewbox'))->toBe('0 0 160 160')
        ->and($dom->query('//*[@data-part="body"]/*[@data-part="legend"]//*[@data-part="value"]')->item(0)->textContent)->toBe('38.6%')
        ->and($dom->query('//*[@data-part="mark"]')->item(1)->getAttribute('aria-label'))->toBe('Mexico: 990, 30.8%')
        ->and($dom->query('//*[@data-part="mark"]')->item(3)->getAttribute('data-series'))->toBe('4')
        ->and($dom->query('//*[@data-part="grid"]'))->toHaveCount(0)
        ->and(chartDom('type="pie" title="T" :series="[ 1, 2 ]"')->query('//div[@data-type="donut"]'))->toHaveCount(1);
});

test('2.x ApexCharts props are dropped, options fill labels and title, show-table still works', function (): void {
    $dom  = chartDom(
        'type="line" :series="[ [ \'name\' => \'Visits\', \'data\' => [ 3, 5 ] ] ]" :options="$options" theme="artisanpack-glass" glass="frosted" :animated="false" show-table',
        ['options' => ['xaxis' => ['categories' => ['Mon', 'Tue']], 'title' => ['text' => 'Visits']]],
    );
    $root = $dom->query('//div[contains(@class, "apui-chart")]')->item(0);

    expect($root->hasAttribute('options'))->toBeFalse()
        ->and($root->hasAttribute('theme'))->toBeFalse()
        ->and($root->hasAttribute('glass'))->toBeFalse()
        ->and($root->hasAttribute('show-table'))->toBeFalse()
        ->and($dom->query('//h3[@data-part="title"]')->item(0)->textContent)->toBe('Visits')
        ->and($dom->query('//*[@data-part="axis" and @text-anchor="middle"]')->item(1)->textContent)->toBe('Tue')
        ->and($dom->query('//table'))->toHaveCount(1)
        ->and(file_get_contents(dirname(__DIR__, 3).'/resources/views/components/chart.blade.php'))->not->toContain('ApexCharts');
});

test('empty options.xaxis.categories fall through to options.labels', function (): void {
    $dom = chartDom(
        'type="line" title="Visits" :series="[ [ \'name\' => \'Visits\', \'data\' => [ 3, 5 ] ] ]" :options="$options"',
        ['options' => ['xaxis' => ['categories' => []], 'labels' => ['Mon', 'Tue']]],
    );

    expect($dom->query('//*[@data-part="axis" and @text-anchor="middle"]')->item(1)->textContent)->toBe('Tue')
        ->and($dom->query('//*[@data-part="mark"]')->item(0)->getAttribute('aria-label'))->toBe('Mon: 3');
});

test('takes a viewBox size, part classes and falls back to a bar chart', function (): void {
    $dom = chartDom('type="radar" title="T" :series="[ 1, 2 ]" :width="300" :height="150" :classes="[ \'plot\' => \'p\', \'mark\' => \'m\' ]"');

    expect($dom->query('//div[contains(@class, "apui-chart")]')->item(0)->getAttribute('data-type'))->toBe('bar')
        ->and($dom->query('//*[@data-part="plot"]')->item(0)->getAttribute('viewbox'))->toBe('0 0 300 150')
        ->and($dom->query('//*[@data-part="plot"]')->item(0)->getAttribute('class'))->toBe('p')
        ->and($dom->query('//*[@data-part="mark"]')->item(0)->getAttribute('class'))->toBe('m');
});

test('reads ApexCharts {x, y} and [x, y] points, labeling the categories by x', function (): void {
    $dom = chartDom(
        'type="line" title="Visits" :series="$series"',
        ['series' => [
            ['name' => 'Visits', 'data' => [['x' => 'Jan', 'y' => 3], ['x' => 'Feb', 'y' => 5]]],
            ['name' => 'Signups', 'data' => [['Jan', 2], ['Feb', '4']]],
        ]],
    );

    expect($dom->query('//*[@data-part="axis" and @text-anchor="middle"]')->item(1)->textContent)->toBe('Feb')
        ->and($dom->query('//*[@data-part="mark"]')->item(1)->getAttribute('aria-label'))->toBe('Feb: Visits 5, Signups 4')
        ->and(ChartGeometry::pointLabels([['data' => [1, 2]]]))->toBe([])
        ->and(ChartGeometry::series([['name' => 'A', 'data' => [['x' => 'a'], [1, 2, 3], [null, 7]]]]))->toBe([['name' => 'A', 'data' => [null, null, 7]]]);
});

test('takes a null series as an empty chart', function (): void {
    $dom = chartDom('title="Empty" :series="null"');

    expect($dom->query('//div[contains(@class, "apui-chart")]')->item(0)->getAttribute('data-type'))->toBe('bar')
        ->and($dom->query('//*[@data-part="mark"]'))->toHaveCount(0);
});
