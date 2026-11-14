<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Card;
use Illuminate\Support\Facades\Blade;

/*
 * Card (#48): the design-system markup contract. The manifest parity check is
 * in tests/Feature/DesignSystemParityTest.php.
 */

test('the fixture renders the contract markup in order', function (): void {
    $html = Blade::render(
        '<x-artisanpack-card title="Quarterly report" description="Updated 2 hours ago" interactive media="/q3.png" actions="Share" footer="3 comments">Revenue grew 12% this quarter.</x-artisanpack-card>',
    );
    $dom = designSystemDom($html);

    $parts = [];
    foreach ($dom->query('//*[@data-scope="card"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    $root = $dom->query('//div[contains(@class, "apui-card")]')->item(0);

    expect($parts)->toBe(['media', 'header', 'title', 'description', 'actions', 'body', 'footer'])
        ->and($root->getAttribute('data-surface'))->toBe('default')
        ->and($root->hasAttribute('data-interactive'))->toBeTrue()
        ->and($html)->toContain('<img data-scope="card" data-part="media" src="/q3.png" alt="" />')
        ->and($html)->toContain('<h3 data-scope="card" data-part="title">Quarterly report</h3>')
        ->and($html)->toContain('<p data-scope="card" data-part="description">Updated 2 hours ago</p>')
        ->and($html)->toContain('<div data-scope="card" data-part="actions">Share</div>')
        ->and($html)->toContain('<div data-scope="card" data-part="body">Revenue grew 12% this quarter.</div>')
        ->and($html)->toContain('<div data-scope="card" data-part="footer">3 comments</div>');

    $wrapper = $dom->query('//*[@data-part="header"]/div[not(@data-part)]')->item(0);
    expect($wrapper)->not->toBeNull()
        ->and($wrapper->getElementsByTagName('h3'))->toHaveCount(1);
});

test('an empty card renders only the root', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-card />'));
    $root = $dom->query('//div[contains(@class, "apui-card")]')->item(0);

    expect($dom->query('//*[@data-scope="card"]'))->toHaveCount(0)
        ->and($root->getAttribute('data-surface'))->toBe('default')
        ->and($root->hasAttribute('data-interactive'))->toBeFalse();
});

test('the header wrapper renders only with a title or description', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-card actions="Share">Body</x-artisanpack-card>'));

    expect($dom->query('//*[@data-part="header"]/*'))->toHaveCount(1)
        ->and($dom->query('//*[@data-part="header"]/*')->item(0)->getAttribute('data-part'))->toBe('actions');
});

test('href links the title and makes the card interactive', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-card href="/reports/q3" title="Q3 report" />'));
    $link = $dom->query('//h3[@data-part="title"]/a')->item(0);

    expect($link->getAttribute('href'))->toBe('/reports/q3')
        ->and($link->textContent)->toBe('Q3 report')
        ->and($dom->query('//a'))->toHaveCount(1)
        ->and($dom->query('//div[contains(@class, "apui-card")]')->item(0)->hasAttribute('data-interactive'))->toBeTrue();
});

test('href without a title is ignored', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-card href="/reports" description="No title">Body</x-artisanpack-card>'));
    $root = $dom->query('//div[contains(@class, "apui-card")]')->item(0);

    expect($dom->query('//a'))->toHaveCount(0)
        ->and($root->hasAttribute('data-interactive'))->toBeFalse();
});

test('interactive alone still adds the hover edge without a title', function (): void {
    expect(designSystemDom(Blade::render('<x-artisanpack-card href="/r" interactive>Body</x-artisanpack-card>'))
        ->query('//div[contains(@class, "apui-card")]')->item(0)->hasAttribute('data-interactive'))->toBeTrue();
});

test('a title slot is enough for href', function (): void {
    $dom = designSystemDom(Blade::render(<<<'BLADE'
        <x-artisanpack-card href="/reports">
            <x-slot:title>Slotted</x-slot:title>
        </x-artisanpack-card>
        BLADE));

    expect($dom->query('//h3[@data-part="title"]/a')->item(0)->getAttribute('href'))->toBe('/reports')
        ->and($dom->query('//div[contains(@class, "apui-card")]')->item(0)->hasAttribute('data-interactive'))->toBeTrue();
});

test('unsafe href schemes are ignored', function (string $href): void {
    $html = Blade::render('<x-artisanpack-card :href="$href" title="T" />', ['href' => $href]);
    $dom  = designSystemDom($html);

    expect($dom->query('//a'))->toHaveCount(0)
        ->and($dom->query('//div[contains(@class, "apui-card")]')->item(0)->hasAttribute('data-interactive'))->toBeFalse()
        ->and($html)->not->toContain('alert(1)');
})->with(['javascript:alert(1)', ' VBScript:alert(1)', 'data:text/html,alert(1)', 'ftp://example.com/alert(1)']);

test('media only allows relative, http(s), blob and data:image sources', function (): void {
    expect(Blade::render('<x-artisanpack-card media="data:text/html,x" title="T" />'))->not->toContain('data-part="media"')
        ->and(Blade::render('<x-artisanpack-card media="ftp://example.com/a.png" title="T" />'))->not->toContain('data-part="media"')
        ->and(Blade::render('<x-artisanpack-card media="blob:https://example.com/1" title="T" />'))->toContain('src="blob:https://example.com/1"');
});

test('media rejects javascript: and vbscript: but allows data: images', function (): void {
    expect(Blade::render('<x-artisanpack-card media="javascript:alert(1)" title="T" />'))->not->toContain('data-part="media"')
        ->and(Blade::render('<x-artisanpack-card media="vbscript:alert(1)" title="T" />'))->not->toContain('data-part="media"')
        ->and(Blade::render('<x-artisanpack-card media="data:image/svg+xml,%3Csvg%3E%3C/svg%3E" title="T" />'))
        ->toContain('<img data-scope="card" data-part="media" src="data:image/svg+xml,%3Csvg%3E%3C/svg%3E" alt="" />');
});

test('surfaces render as data-surface', function (string $surface): void {
    expect(designSystemDom(Blade::render('<x-artisanpack-card :surface="$surface" title="T" />', ['surface' => $surface]))
        ->query('//div[contains(@class, "apui-card")]')->item(0)->getAttribute('data-surface'))->toBe($surface);
})->with(['default', 'raised', 'sunken', 'glass']);

test('mediaAlt describes the media', function (): void {
    expect(Blade::render('<x-artisanpack-card media="/house.jpg" media-alt="A brick townhouse" />'))
        ->toContain('<img data-scope="card" data-part="media" src="/house.jpg" alt="A brick townhouse" />');
});

test('named slots override the props', function (): void {
    $html = Blade::render(<<<'BLADE'
        <x-artisanpack-card title="Plain" media="/plain.png" footer="Plain footer">
            <x-slot:media><video src="/clip.mp4"></video></x-slot:media>
            <x-slot:title><span>Rich</span> title</x-slot:title>
            <x-slot:description>Rich <em>description</em></x-slot:description>
            <x-slot:actions><x-artisanpack-button size="sm">Share</x-artisanpack-button></x-slot:actions>
            Body
            <x-slot:footer><strong>Rich footer</strong></x-slot:footer>
        </x-artisanpack-card>
        BLADE);
    $dom = designSystemDom($html);

    expect($html)->toContain('<div data-scope="card" data-part="media"><video src="/clip.mp4"></video></div>')
        ->toContain('<h3 data-scope="card" data-part="title"><span>Rich</span> title</h3>')
        ->toContain('<p data-scope="card" data-part="description">Rich <em>description</em></p>')
        ->toContain('<div data-scope="card" data-part="footer"><strong>Rich footer</strong></div>')
        ->not->toContain('/plain.png')
        ->not->toContain('Plain footer')
        ->and($dom->query('//*[@data-part="actions"]//button[contains(@class, "apui-button")]'))->toHaveCount(1)
        ->and($dom->query('//*[@data-part="body"]')->item(0)->textContent)->toBe('Body');
});

test('attributes land on the root and classes per part apply', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-card class="max-w-sm" wire:key="c1" title="T" :classes="[\'root\' => \'extra\', \'title\' => \'text-lg\']" />'));
    $root = $dom->query('//div[contains(@class, "apui-card")]')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-card extra max-w-sm')
        ->and($root->getAttribute('wire:key'))->toBe('c1')
        ->and($dom->query('//h3')->item(0)->getAttribute('class'))->toBe('text-lg');
});

test('the title is escaped', function (): void {
    expect(Blade::render('<x-artisanpack-card :title="$title" />', ['title' => '<script>x</script>']))->not->toContain('<script>');
});

test('the constructor declares only the manifest props', function (): void {
    $parameters = array_map(
        static fn (ReflectionParameter $parameter): string => $parameter->getName(),
        (new ReflectionClass(Card::class))->getConstructor()->getParameters(),
    );

    expect($parameters)->toBe(['surface', 'interactive', 'href', 'title', 'description', 'actions', 'footer', 'media', 'mediaAlt', 'classes']);
});
