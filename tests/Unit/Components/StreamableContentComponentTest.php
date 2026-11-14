<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\IconRegistry;
use ArtisanPack\LivewireUiComponents\Support\LivewireHelper;
use Illuminate\Support\Facades\Blade;

/*
 * StreamableContent (#60, Livewire only per D-003). The manifest parity
 * check is in tests/Feature/DesignSystemParityTest.php.
 */

function streamableRoot(DOMXPath $dom): DOMElement
{
    return $dom->query('//div[contains(@class, "apui-streamable-content")]')->item(0);
}

test('streaming renders the status, Stop, the busy live region and the caret', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-streamable-content id="sum" target="summary" actions="Copy">Revenue grew</x-artisanpack-streamable-content>'));
    $root = streamableRoot($dom);

    $parts = [];
    foreach ($dom->query('//*[@data-scope="streamable-content"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    $content = $dom->query('//*[@data-part="content"]')->item(0);
    $stop    = $dom->query('//button[@data-part="stop-trigger"]')->item(0);

    expect($parts)->toBe(['root', 'status', 'stop-trigger', 'content', 'caret', 'actions'])
        ->and($root->getAttribute('data-state'))->toBe('streaming')
        ->and($root->getAttribute('id'))->toBe('sum')
        ->and($dom->query('//*[@data-part="status"]')->item(0)->getAttribute('role'))->toBe('status')
        ->and($dom->query('//*[@data-part="status"]//*[@data-variant="dots"][@aria-hidden="true"]'))->toHaveCount(1)
        ->and(trim($dom->query('//*[@data-part="status"]')->item(0)->textContent))->toStartWith('Writing…')
        ->and($stop->getAttribute('x-on:click'))->toBe("\$dispatch( 'stop' )")
        ->and(trim($stop->textContent))->toBe('Stop')
        ->and($content->getAttribute('aria-live'))->toBe('polite')
        ->and($content->getAttribute('aria-busy'))->toBe('true')
        ->and($dom->query('//*[@id="sum-stream"]')->item(0)->textContent)->toBe('Revenue grew')
        ->and($dom->query('//*[@data-part="caret"][@aria-hidden="true"]'))->toHaveCount(1);
});

test('wire:stream targets the stream on Livewire 3 and 4', function (): void {
    $stream = designSystemDom(Blade::render('<x-artisanpack-streamable-content id="sum" target="summary" />'))->query('//*[@id="sum-stream"]')->item(0);

    expect(LivewireHelper::supportsWireStream())->toBeTrue()
        ->and($stream->getAttribute('wire:stream'))->toBe('summary');
});

test('waiting shows the spinner and skeleton lines', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-streamable-content target="summary" state="waiting" />'));

    expect($dom->query('//*[@data-part="status"]//*[@data-variant="spinner"]'))->toHaveCount(1)
        ->and(trim($dom->query('//*[@data-part="status"]')->item(0)->textContent))->toBe('Waiting for a response…')
        ->and($dom->query('//*[@data-part="content"]/span[@data-shape="text"]'))->toHaveCount(3)
        ->and($dom->query('//*[@data-part="content"]')->item(0)->getAttribute('aria-busy'))->toBe('true')
        ->and($dom->query('//*[@data-part="stop-trigger"]'))->toHaveCount(0)
        ->and($dom->query('//*[@data-part="caret"]'))->toHaveCount(0);
});

test('finished states show their icon and text and are not busy', function (string $state, string $icon, string $text): void {
    $html = Blade::render('<x-artisanpack-streamable-content target="summary" :state="$state">Partial</x-artisanpack-streamable-content>', ['state' => $state]);
    $dom  = designSystemDom($html);

    expect(streamableRoot($dom)->getAttribute('data-state'))->toBe($state)
        ->and($html)->toContain(IconRegistry::render($icon))
        ->and(trim($dom->query('//*[@data-part="status"]')->item(0)->textContent))->toBe($text)
        ->and($dom->query('//*[@data-part="content"]')->item(0)->getAttribute('aria-busy'))->toBe('false')
        ->and($dom->query('//*[@data-part="stop-trigger"]'))->toHaveCount(0)
        ->and($dom->query('//*[@data-part="caret"]'))->toHaveCount(0);
})->with([
    'done'    => ['done', 'circle-check', 'Done'],
    'error'   => ['error', 'circle-alert', 'The response was interrupted.'],
    'stopped' => ['stopped', 'square', 'Stopped'],
]);

test('status and stopLabel override the defaults, which are translated', function (): void {
    app('translator')->addLines(['*.Stop' => 'Arrêter'], 'fr');
    app()->setLocale('fr');
    expect(trim(designSystemDom(Blade::render('<x-artisanpack-streamable-content target="s" />'))->query('//button[@data-part="stop-trigger"]')->item(0)->textContent))->toBe('Arrêter');
    app()->setLocale('en');

    $dom = designSystemDom(Blade::render('<x-artisanpack-streamable-content target="s" status="Writing summary…" stop-label="Cancel" />'));

    expect(trim($dom->query('//*[@data-part="status"]')->item(0)->textContent))->toStartWith('Writing summary…')
        ->and(trim($dom->query('//button[@data-part="stop-trigger"]')->item(0)->textContent))->toBe('Cancel');
});

test('slots override props, and class lands on the root', function (): void {
    $dom = designSystemDom(Blade::render(<<<'BLADE'
        <x-artisanpack-streamable-content target="s" state="done" class="mt-2" :classes="['content' => 'prose']">
            <x-slot:status>Summary ready · <strong>4</strong> sources</x-slot:status>
            <x-slot:actions><button>Copy</button></x-slot:actions>
            Text
        </x-artisanpack-streamable-content>
        BLADE));

    expect(streamableRoot($dom)->getAttribute('class'))->toBe('apui-streamable-content mt-2')
        ->and($dom->query('//*[@data-part="status"]/strong')->item(0)->textContent)->toBe('4')
        ->and($dom->query('//*[@data-part="content"]')->item(0)->getAttribute('class'))->toBe('prose')
        ->and($dom->query('//*[@data-part="actions"]/button')->item(0)->textContent)->toBe('Copy');
});

test('an author id lands on the root unchanged', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-streamable-content id="summary" target="summary" />'));

    expect($dom->query('//*[@data-part="root"][@id="summary"]'))->toHaveCount(1)
        ->and($dom->query('//*[@id="summary-root"]'))->toHaveCount(0);
});
