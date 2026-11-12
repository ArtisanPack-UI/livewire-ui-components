<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\SparklineGeometry;
use Illuminate\Support\Facades\Blade;

/*
 * Sparkline (#61): the design-system markup contract, and SparklineGeometry,
 * the PHP port of the behavior package's sparklineGeometry. Its expectations
 * match test/data-helpers.test.ts in the design system, so Blade draws what
 * React and Vue draw. The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

function sparklineDom(string $props, array $data = []): DOMXPath
{
    return designSystemDom(Blade::render('<x-artisanpack-sparkline '.$props.' />', $data));
}

test('the geometry matches the spec preview and the TypeScript helper', function (): void {
    $line = SparklineGeometry::compute([2, 4, 3, 7, 6, 9, 12, 11, 14], 'line', 96, 28);

    expect($line['line'])->toBe('M2.0,25.0 L13.5,21.3 L25.0,23.2 L36.5,15.8 L48.0,17.7 L59.5,12.2 L71.0,6.7 L82.5,8.5 L94.0,3.0')
        ->and($line['dot'])->toBe(['cx' => '94.0', 'cy' => '3.0'])
        ->and($line['baseline'])->toBeNull();

    $area = SparklineGeometry::compute([8, 12, 10, 16, 15, 19, 23, 22, 26], 'area', 96, 36);
    expect($area['area'])->toEndWith('L94.0,36 L2.0,36 Z');

    $bars = SparklineGeometry::compute([2, 6, 4, 7, 5, 8, 10, 7, 12], 'bar', 96, 28)['bars'];
    expect($bars)->toHaveCount(9)
        ->and($bars[0])->toBe(['x' => '4.0', 'y' => '25.0', 'width' => '6.1', 'height' => '3.0'])
        ->and($bars[8])->toBe(['x' => '85.8', 'y' => '3.0', 'width' => '6.1', 'height' => '25.0']);

    $crossing = SparklineGeometry::compute([-4, 4], 'bar', 96, 28);
    expect($crossing['baseline'])->toBe('14.0')
        ->and($crossing['bars'][0])->toBe(['x' => '11.2', 'y' => '14.0', 'width' => '27.6', 'height' => '11.0']);

    expect(SparklineGeometry::compute([5, 5], 'line', 96, 28)['line'])->toBe('M2.0,14.0 L94.0,14.0')
        ->and(SparklineGeometry::compute([5], 'line', 96, 28)['dot'])->toBe(['cx' => '48.0', 'cy' => '14.0'])
        ->and(SparklineGeometry::compute([], 'area')['line'])->toBe('')
        ->and(SparklineGeometry::label('Trend from {first} to {latest}', [8, 'x', 26.5]))->toBe('Trend from 8 to 26.5');
});

test('renders an image with a label stating the first and latest values', function (): void {
    $svg = sparklineDom(':data="[3, 5, 9]"')->query('//svg')->item(0);

    expect($svg->getAttribute('class'))->toBe('apui-sparkline')
        ->and($svg->getAttribute('role'))->toBe('img')
        ->and($svg->getAttribute('aria-label'))->toBe('Trend from 3 to 9')
        ->and($svg->getAttribute('data-variant'))->toBe('line')
        ->and($svg->getAttribute('width'))->toBe('96')
        ->and($svg->getAttribute('height'))->toBe('28')
        ->and($svg->getAttribute('viewbox'))->toBe('0 0 96 28');
});

test('draws the parts for each variant', function (string $variant, array $parts): void {
    $dom   = sparklineDom(':data="[-2, 4, 3, 9]" variant="'.$variant.'"');
    $drawn = [];
    foreach ($dom->query('//*[@data-scope="sparkline"]') as $node) {
        $drawn[] = $node->getAttribute('data-part');
    }

    expect(array_values(array_unique($drawn)))->toBe($parts);
})->with([
    'line' => ['line', ['baseline', 'line', 'dot']],
    'area' => ['area', ['baseline', 'area', 'line', 'dot']],
    'bar'  => ['bar', ['baseline', 'bar']],
]);

test('2.x type still works, as variant, and old props stay off the root', function (): void {
    $svg = sparklineDom(':data="[1, 2]" type="bar" color="primary" :stroke-width="3"')->query('//svg')->item(0);

    expect($svg->getAttribute('data-variant'))->toBe('bar')
        ->and($svg->hasAttribute('type'))->toBeFalse()
        ->and($svg->hasAttribute('color'))->toBeFalse()
        ->and($svg->hasAttribute('stroke-width'))->toBeFalse();
});

test('a translated label, size and part classes', function (): void {
    $dom = sparklineDom(':data="[1, 2]" :width="140" :height="48" label="Tendencia: {latest}" :classes="[\'line\' => \'l\']"');
    $svg = $dom->query('//svg')->item(0);

    expect($svg->getAttribute('aria-label'))->toBe('Tendencia: 2')
        ->and($svg->getAttribute('viewbox'))->toBe('0 0 140 48')
        ->and($dom->query('//*[@data-part="line"]')->item(0)->getAttribute('class'))->toBe('l');
});

test('needs no script or chart library', function (): void {
    $html = Blade::render('<x-artisanpack-sparkline :data="[1, 2, 3]" />');

    expect($html)->not->toContain('x-data')
        ->and($html)->not->toContain('ApexCharts')
        ->and($html)->not->toContain('<script');
});

test('numeric strings, such as database decimals, count as values', function (): void {
    expect(SparklineGeometry::values(['12.50', 3, '7', 'n/a', '', null, INF]))->toBe([12.5, 3, 7.0])
        ->and(SparklineGeometry::compute(['2', '4', '3', '7', '6', '9', '12', '11', '14'], 'line', 96, 28)['line'])
        ->toBe(SparklineGeometry::compute([2, 4, 3, 7, 6, 9, 12, 11, 14], 'line', 96, 28)['line']);

    $svg = sparklineDom('label="From {first} to {latest}" :data="$data"', ['data' => ['12.50', '18.75']])->query('//svg')->item(0);

    expect($svg->getAttribute('aria-label'))->toBe('From 12.5 to 18.75')
        ->and(sparklineDom(':data="$data"', ['data' => ['12.50', '18.75']])->query('//*[@data-part="line"]'))->toHaveCount(1);
});
