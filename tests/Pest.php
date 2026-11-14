<?php

declare(strict_types=1);

use ArtisanPackUI\DesignSystem\Testing\Pest\Expectations;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(ArtisanPack\LivewireUiComponents\Tests\TestCase::class)->in('Feature', 'Unit', 'Integration', 'Accessibility', 'Performance');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

// toMatchManifest: Blade parity with the design-system manifests (D-016).
if (class_exists(Expectations::class)) {
    Expectations::register();
}

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something(): void
{
    // ..
}

/**
 * Parses rendered markup for XPath assertions on the design-system parts.
 *
 * @param  string  $html  The markup.
 *
 * @return DOMXPath The query object.
 */
function designSystemDom(string $html): DOMXPath
{
    $document = new DOMDocument;
    $previous = libxml_use_internal_errors(true);
    $document->loadHTML('<?xml encoding="utf-8"?><!doctype html><html><body>'.$html.'</body></html>');
    libxml_clear_errors();
    libxml_use_internal_errors($previous);

    return new DOMXPath($document);
}
