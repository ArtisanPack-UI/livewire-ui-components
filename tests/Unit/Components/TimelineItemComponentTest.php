<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * TimelineItem (#61): one event of a Timeline, and its 2.x props. The list
 * itself is covered by TimelineComponentTest.
 */

test('2.x subtitle still works as the time, and old props stay off the item', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-timeline><x-artisanpack-timeline-item title="Order placed" subtitle="Nov 2" first pending /></x-artisanpack-timeline>'));
    $item = $dom->query('//li')->item(0);

    expect($dom->query('//time[@data-part="time"]')->item(0)->textContent)->toBe('Nov 2')
        ->and($item->hasAttribute('subtitle'))->toBeFalse()
        ->and($item->hasAttribute('first'))->toBeFalse()
        ->and($item->hasAttribute('pending'))->toBeFalse();
});

test('an unknown color leaves the marker neutral', function (): void {
    $marker = designSystemDom(Blade::render('<x-artisanpack-timeline-item title="A" color="bg-primary" />'))->query('//*[@data-part="marker"]')->item(0);

    expect($marker->hasAttribute('data-color'))->toBeFalse();
});

test('attributes land on the item', function (): void {
    $item = designSystemDom(Blade::render('<x-artisanpack-timeline-item title="A" wire:key="event-1" id="e1" />'))->query('//li')->item(0);

    expect($item->getAttribute('id'))->toBe('e1')
        ->and($item->getAttribute('data-part'))->toBe('item');
});
