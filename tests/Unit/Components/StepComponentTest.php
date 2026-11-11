<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Step (#56): one step of Steps. The Steps tests cover it inside a Steps;
 * these cover it on its own and its slot.
 */

test('renders a step part on its own with its own state', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-step title="Basics" state="current" description="Title and type" />'));
    $step = $dom->query('//li')->item(0);

    expect($step->getAttribute('data-scope'))->toBe('steps')
        ->and($step->getAttribute('data-part'))->toBe('step')
        ->and($step->getAttribute('data-state'))->toBe('current')
        ->and($step->getAttribute('aria-current'))->toBe('step')
        ->and(trim($dom->query('//*[@data-part="marker"]')->item(0)->textContent))->toBe('1');
});

test('an unknown state falls back to upcoming', function (): void {
    $step = designSystemDom(Blade::render('<x-artisanpack-step title="A" state="done" />'))->query('//li')->item(0);

    expect($step->getAttribute('data-state'))->toBe('upcoming')
        ->and($step->hasAttribute('aria-current'))->toBeFalse();
});

test('the default slot overrides the title', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-steps :value="1"><x-artisanpack-step title="Plain"><strong>Rich</strong> title</x-artisanpack-step></x-artisanpack-steps>'));

    expect($dom->query('//*[@data-part="title"]/strong'))->toHaveCount(1)
        ->and(trim($dom->query('//*[@data-part="title"]')->item(0)->textContent))->toBe('Rich title');
});

test('attributes land on the step', function (): void {
    $step = designSystemDom(Blade::render('<x-artisanpack-step title="A" data-testid="first" wire:key="s1" />'))->query('//li')->item(0);

    expect($step->getAttribute('data-testid'))->toBe('first')
        ->and($step->getAttribute('wire:key'))->toBe('s1');
});

test('an icon replaces the marker number', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-step title="Pay" icon="lock" />'));

    expect($dom->query('//*[@data-part="marker"]/svg'))->toHaveCount(1)
        ->and(trim($dom->query('//*[@data-part="marker"]')->item(0)->textContent))->toBe('');
});
