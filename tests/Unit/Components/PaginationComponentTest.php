<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Pagination;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;

/*
 * Pagination (#56): the design-system markup contract. The manifest parity
 * check is in tests/Feature/DesignSystemParityTest.php; inside Livewire, in
 * tests/Feature/PaginationVariantsTest.php.
 */

/**
 * The part names and labels in render order.
 *
 * @return list<string>
 */
function paginationSequence(DOMXPath $dom): array
{
    $sequence = [];
    foreach ($dom->query('//nav/*') as $element) {
        $sequence[] = $element->getAttribute('data-part').':'.trim($element->textContent);
    }

    return $sequence;
}

test('renders a named nav with previous, the pages and next', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-pagination :page="3" :total="318" :per-page="10" />'));
    $root = $dom->query('//nav')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-pagination')
        ->and($root->getAttribute('aria-label'))->toBe('Pagination')
        ->and(paginationSequence($dom))->toBe([
            'previous:', 'page:1', 'page:2', 'page:3', 'page:4', 'page:5', 'ellipsis:…', 'page:32', 'next:',
        ]);
});

test('marks the current page and names every control', function (): void {
    $dom     = designSystemDom(Blade::render('<x-artisanpack-pagination :page="3" :total="318" />'));
    $current = $dom->query('//*[@aria-current="page"]');

    expect($current)->toHaveCount(1)
        ->and($current->item(0)->textContent)->toBe('3')
        ->and($current->item(0)->hasAttribute('x-on:click'))->toBeFalse()
        ->and($dom->query('//*[@data-part="page"]')->item(0)->getAttribute('aria-label'))->toBe('Page 1')
        ->and($dom->query('//*[@data-part="previous"]')->item(0)->getAttribute('aria-label'))->toBe('Previous page')
        ->and($dom->query('//*[@data-part="next"]')->item(0)->getAttribute('aria-label'))->toBe('Next page')
        ->and($dom->query('//*[@data-part="ellipsis"]')->item(0)->getAttribute('aria-hidden'))->toBe('true');
});

test('dispatches page-change from every enabled control', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-pagination :page="3" :page-count="10" />'));

    expect($dom->query('//nav')->item(0)->hasAttribute('x-data'))->toBeTrue()
        ->and($dom->query('//*[@data-part="next"]')->item(0)->getAttribute('x-on:click'))->toBe("\$dispatch( 'page-change', { page: 4 } )")
        ->and($dom->query('//*[@data-part="previous"]')->item(0)->getAttribute('x-on:click'))->toBe("\$dispatch( 'page-change', { page: 2 } )")
        ->and($dom->query('//button[@data-part="page"][@*[name()="x-on:click"]]'))->toHaveCount(5);
});

test('previous and next are aria-disabled at the ends, and mirror', function (): void {
    $first = designSystemDom(Blade::render('<x-artisanpack-pagination :page="1" :page-count="5" />'));
    $last  = designSystemDom(Blade::render('<x-artisanpack-pagination :page="9" :page-count="5" />'));

    $previous = $first->query('//*[@data-part="previous"]')->item(0);
    expect($previous->getAttribute('aria-disabled'))->toBe('true')
        ->and($previous->hasAttribute('x-on:click'))->toBeFalse()
        ->and($previous->hasAttribute('data-mirror'))->toBeTrue()
        ->and($first->query('//*[@data-part="next"]')->item(0)->hasAttribute('aria-disabled'))->toBeFalse()
        ->and($last->query('//*[@data-part="next"]')->item(0)->getAttribute('aria-disabled'))->toBe('true')
        ->and($last->query('//*[@aria-current="page"]')->item(0)->textContent)->toBe('5');
});

test('pageCount wins over total and perPage', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-pagination :page="1" :page-count="3" :total="1000" />'));

    expect($dom->query('//*[@data-part="page"]'))->toHaveCount(3);
});

test('the page list keeps one width as the page moves', function (int $page, array $expected): void {
    expect(Pagination::range($page, 20, 1))->toBe($expected);
})->with([
    'start'  => [1, [1, 2, 3, 4, 5, null, 20]],
    'middle' => [10, [1, null, 9, 10, 11, null, 20]],
    'end'    => [20, [1, null, 16, 17, 18, 19, 20]],
    'short'  => [2, [1, 2, 3, 4, 5, null, 20]],
]);

test('few pages show every page', function (): void {
    expect(Pagination::range(2, 6, 1))->toBe([1, 2, 3, 4, 5, 6])
        ->and(Pagination::range(1, 1, 1))->toBe([1]);
});

test('the summary shows the range and is translatable', function (): void {
    $dom    = designSystemDom(Blade::render('<x-artisanpack-pagination :page="32" :total="318" summary />'));
    $custom = designSystemDom(Blade::render('<x-artisanpack-pagination :page="2" :page-count="32" summary summary-label="Seite {page} von {pageCount}" />'));
    $pages  = designSystemDom(Blade::render('<x-artisanpack-pagination :page="2" :page-count="32" summary />'));

    expect($dom->query('//nav/*')->item(0)->getAttribute('data-part'))->toBe('summary')
        ->and($dom->query('//*[@data-part="summary"]')->item(0)->textContent)->toBe('Showing 311–318 of 318')
        ->and($custom->query('//*[@data-part="summary"]')->item(0)->textContent)->toBe('Seite 2 von 32')
        ->and($pages->query('//*[@data-part="summary"]')->item(0)->textContent)->toBe('Page 2 of 32');
});

test('the labels are props', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-pagination :page="1" :page-count="2" label="Seiten" previous-label="Zurück" next-label="Weiter" page-label="Seite {page}" />'));

    expect($dom->query('//nav')->item(0)->getAttribute('aria-label'))->toBe('Seiten')
        ->and($dom->query('//*[@data-part="previous"]')->item(0)->getAttribute('aria-label'))->toBe('Zurück')
        ->and($dom->query('//*[@data-part="next"]')->item(0)->getAttribute('aria-label'))->toBe('Weiter')
        ->and($dom->query('//*[@data-part="page"]')->item(1)->getAttribute('aria-label'))->toBe('Seite 2');
});

test('a length-aware paginator renders links to its pages', function (): void {
    $rows = new LengthAwarePaginator(range(1, 10), 318, 10, 3, ['path' => '/listings']);
    $dom  = designSystemDom(Blade::render('<x-artisanpack-pagination :rows="$rows" summary />', ['rows' => $rows]));

    expect($dom->query('//a[@data-part="page"]'))->toHaveCount(6)
        ->and($dom->query('//a[@data-part="page"]')->item(0)->getAttribute('href'))->toBe('/listings?page=1')
        ->and($dom->query('//a[@data-part="next"]')->item(0)->getAttribute('href'))->toBe('/listings?page=4')
        ->and($dom->query('//a[@data-part="next"]')->item(0)->getAttribute('rel'))->toBe('next')
        ->and($dom->query('//*[@*[name()="wire:click.prevent"]]'))->toHaveCount(0)
        ->and($dom->query('//*[@data-part="summary"]')->item(0)->textContent)->toBe('Showing 21–30 of 318');
});

test('a simple paginator renders previous and next only', function (): void {
    $rows = new Paginator(range(1, 11), 10, 2, ['path' => '/feed']);
    $dom  = designSystemDom(Blade::render('<x-artisanpack-pagination :rows="$rows" summary />', ['rows' => $rows]));

    expect(paginationSequence($dom))->toBe(['previous:', 'next:'])
        ->and($dom->query('//a[@data-part="previous"]')->item(0)->getAttribute('href'))->toBe('/feed?page=1')
        ->and($dom->query('//a[@data-part="next"]')->item(0)->getAttribute('href'))->toBe('/feed?page=3');
});

test('a cursor paginator links its cursors and disables the start', function (): void {
    $rows = new CursorPaginator(array_map(static fn (int $id): array => ['id' => $id], range(1, 11)), 10, null, ['path' => '/feed', 'parameters' => ['id']]);
    $dom  = designSystemDom(Blade::render('<x-artisanpack-pagination :rows="$rows" />', ['rows' => $rows]));

    expect($dom->query('//button[@data-part="previous"]')->item(0)->getAttribute('aria-disabled'))->toBe('true')
        ->and($dom->query('//a[@data-part="next"]')->item(0)->getAttribute('href'))->toStartWith('/feed?cursor=')
        ->and($dom->query('//a[@data-part="next"]')->item(0)->hasAttribute('x-on:click'))->toBeFalse();
});

test('2.x attributes from the table are dropped instead of breaking', function (): void {
    $html = Blade::render('<x-artisanpack-pagination :page="1" :page-count="2" :per-page-values="[10, 20]" wire:model.live="perPage" />');

    expect($html)->not->toContain('per-page-values')
        ->and($html)->not->toContain('wire:model');
});

test('an unsafe paginator URL renders the button form, not an empty link', function (): void {
    $rows = new LengthAwarePaginator(range(1, 10), 50, 10, 2, ['path' => 'javascript:alert(1)']);
    $html = Blade::render('<x-artisanpack-pagination :rows="$rows" />', ['rows' => $rows]);
    $dom  = designSystemDom($html);

    expect($html)->not->toContain('javascript:')
        ->and($dom->query('//a'))->toHaveCount(0)
        ->and($dom->query('//button[@data-part="page"]'))->toHaveCount(5)
        ->and($dom->query('//button[@data-part="next"]')->item(0)->getAttribute('x-on:click'))->toBe("\$dispatch( 'page-change', { page: 3 } )");
});
