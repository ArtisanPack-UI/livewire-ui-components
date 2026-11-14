<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Timeline (#61): the design-system markup contract. Composition is the
 * base and items a convenience (D-023). The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

function timelineDom(string $template, array $data = []): DOMXPath
{
    return designSystemDom(Blade::render($template, $data));
}

test('composes an ordered list from timeline-item children', function (): void {
    $dom = timelineDom('<x-artisanpack-timeline label="Activity">
        <x-artisanpack-timeline-item title="Offer accepted" time="2h ago" datetime="2026-11-12T08:00" icon="circle-check" color="success" description="Phoenix Baker accepted $248,000." />
        <x-artisanpack-timeline-item title="Listing published" time="Dec 13" />
    </x-artisanpack-timeline>');
    $root  = $dom->query('//ol')->item(0);
    $items = $dom->query('//ol/li[@data-scope="timeline"][@data-part="item"]');

    expect($root->getAttribute('class'))->toBe('apui-timeline')
        ->and($root->getAttribute('data-variant'))->toBe('icon')
        ->and($root->getAttribute('aria-label'))->toBe('Activity')
        ->and($items)->toHaveCount(2);

    $first = $items->item(0);
    $parts = [];
    foreach ($first->childNodes as $child) {
        if ($child instanceof DOMElement) {
            $parts[] = $child->getAttribute('data-part');
        }
    }

    expect($parts)->toBe(['marker', 'head', 'body'])
        ->and($dom->query('.//*[@data-part="marker"]', $first)->item(0)->getAttribute('data-color'))->toBe('success')
        ->and($dom->query('.//*[@data-part="marker"]', $first)->item(0)->getAttribute('aria-hidden'))->toBe('true')
        ->and($dom->query('.//*[@data-part="marker"]/svg', $first))->toHaveCount(1)
        ->and($dom->query('.//*[@data-part="head"]/time[@data-part="time"]', $first)->item(0)->getAttribute('datetime'))->toBe('2026-11-12T08:00')
        ->and(trim($dom->query('.//*[@data-part="head"]', $first)->item(0)->textContent))->toBe('Offer accepted2h ago')
        ->and($dom->query('.//*[@data-part="marker"]', $items->item(1))->item(0)->hasAttribute('data-color'))->toBeFalse()
        ->and($dom->query('.//*[@data-part="marker"]', $items->item(1))->item(0)->childNodes->length)->toBe(0);
});

test('items render the same children, before composed ones', function (): void {
    $dom = timelineDom('<x-artisanpack-timeline variant="dot" :items="$items"><x-artisanpack-timeline-item title="Shipped" /></x-artisanpack-timeline>', [
        'items' => [
            ['title' => 'Delivered', 'time' => 'Nov 7, 14:02', 'color' => 'success'],
            ['title' => 'Olivia commented', 'card' => 'Thursday at 11?', 'icon' => 'message-square'],
        ],
    ]);
    $heads = [];
    foreach ($dom->query('//*[@data-part="head"]') as $head) {
        $heads[] = trim($head->firstChild->textContent);
    }

    expect($dom->query('//ol')->item(0)->getAttribute('data-variant'))->toBe('dot')
        ->and($heads)->toBe(['Delivered', 'Olivia commented', 'Shipped'])
        ->and(trim($dom->query('//*[@data-part="card"]')->item(0)->textContent))->toBe('Thursday at 11?')
        ->and($dom->query('//time')->item(0)->hasAttribute('datetime'))->toBeFalse();
});

test('the default slot is the head and the card slot holds rich content', function (): void {
    $dom = timelineDom('<x-artisanpack-timeline><x-artisanpack-timeline-item time="5h ago"><strong>Olivia Rhye</strong> commented<x-slot:card><p>Move it to <em>Thursday</em>?</p></x-slot:card></x-artisanpack-timeline-item></x-artisanpack-timeline>');

    expect($dom->query('//*[@data-part="head"]/strong')->item(0)->textContent)->toBe('Olivia Rhye')
        ->and($dom->query('//*[@data-part="card"]/p/em')->item(0)->textContent)->toBe('Thursday');
});

test('classes reach the parts of every item', function (): void {
    $dom = timelineDom('<x-artisanpack-timeline :classes="[\'root\' => \'r\', \'item\' => \'i\', \'time\' => \'t\']" :items="[[\'title\' => \'A\', \'time\' => \'now\']]"><x-artisanpack-timeline-item title="B" time="then" /></x-artisanpack-timeline>');

    expect($dom->query('//ol')->item(0)->getAttribute('class'))->toBe('apui-timeline r')
        ->and($dom->query('//li[@class="i"]'))->toHaveCount(2)
        ->and($dom->query('//time[@class="t"]'))->toHaveCount(2);
});

test('an unknown variant falls back to icon', function (): void {
    expect(timelineDom('<x-artisanpack-timeline variant="compact" />')->query('//ol')->item(0)->getAttribute('data-variant'))->toBe('icon');
});
