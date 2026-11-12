<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Divider (#58): the design-system rule. The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

test('renders a plain horizontal rule as an hr', function (): void {
    $hr = designSystemDom(Blade::render('<x-artisanpack-divider />'))->query('//hr')->item(0);

    expect($hr->getAttribute('class'))->toBe('apui-divider')
        ->and($hr->getAttribute('data-orientation'))->toBe('horizontal')
        ->and($hr->getAttribute('data-strength'))->toBe('subtle')
        ->and($hr->hasAttribute('role'))->toBeFalse();
});

test('renders a vertical rule as a separator div', function (): void {
    $div = designSystemDom(Blade::render('<x-artisanpack-divider orientation="vertical" strength="strong" />'))->query('//div[contains(@class, "apui-divider")]')->item(0);

    expect($div->getAttribute('role'))->toBe('separator')
        ->and($div->getAttribute('aria-orientation'))->toBe('vertical')
        ->and($div->getAttribute('data-strength'))->toBe('strong');
});

test('names a labelled divider by its label, from the prop or the slot', function (): void {
    foreach (['<x-artisanpack-divider label="or continue with" align="start" />', '<x-artisanpack-divider align="start">or continue with</x-artisanpack-divider>'] as $blade) {
        $dom   = designSystemDom(Blade::render($blade));
        $root  = $dom->query('//div[contains(@class, "apui-divider")]')->item(0);
        $label = $dom->query('//span[@data-part="label"]')->item(0);

        expect($root->getAttribute('role'))->toBe('separator')
            ->and($root->getAttribute('aria-labelledby'))->toBe($label->getAttribute('id'))
            ->and($root->getAttribute('data-align'))->toBe('start')
            ->and(trim($label->textContent))->toBe('or continue with');
    }
});

test('hides a decorative divider from assistive technology', function (): void {
    expect(designSystemDom(Blade::render('<x-artisanpack-divider decorative />'))->query('//hr')->item(0)->getAttribute('aria-hidden'))->toBe('true');

    $labelled = designSystemDom(Blade::render('<x-artisanpack-divider decorative label="or" />'))->query('//div[contains(@class, "apui-divider")]')->item(0);
    expect($labelled->getAttribute('role'))->toBe('none')
        ->and($labelled->hasAttribute('aria-labelledby'))->toBeFalse();
});

test('falls back for unknown values', function (): void {
    $hr = designSystemDom(Blade::render('<x-artisanpack-divider orientation="diagonal" strength="bold" align="middle" />'))->query('//hr')->item(0);

    expect($hr->getAttribute('data-strength'))->toBe('subtle')
        ->and($hr->getAttribute('data-align'))->toBe('center');
});
