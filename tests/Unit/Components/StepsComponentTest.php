<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Livewire\Livewire;

/*
 * Steps (#56): the design-system markup contract. The manifest parity check
 * is in tests/Feature/DesignSystemParityTest.php.
 */

class StepsLivewireTestComponent extends Component
{
    public int $progress = 3;

    public function render(): string
    {
        return <<<'BLADE'
            <div>
                <x-artisanpack-steps wire:model="progress" clickable>
                    <x-artisanpack-step title="Cart" />
                    <x-artisanpack-step title="Shipping" />
                    <x-artisanpack-step title="Payment" />
                    <x-artisanpack-step title="Confirm" />
                </x-artisanpack-steps>
            </div>
            BLADE;
    }
}

/**
 * Each step's state, in order.
 *
 * @return list<string>
 */
function stepStates(DOMXPath $dom): array
{
    $states = [];
    foreach ($dom->query('//li[@data-part="step"]') as $step) {
        $states[] = $step->getAttribute('data-state');
    }

    return $states;
}

test('composes an ordered list from step children', function (): void {
    $dom = designSystemDom(Blade::render(<<<'BLADE'
        <x-artisanpack-steps :value="2" label="Listing progress">
            <x-artisanpack-step title="Basics" description="Title and type" />
            <x-artisanpack-step title="Location" />
            <x-artisanpack-step title="Pricing" />
        </x-artisanpack-steps>
        BLADE));
    $root = $dom->query('//ol')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-steps')
        ->and($root->getAttribute('data-orientation'))->toBe('horizontal')
        ->and($root->getAttribute('aria-label'))->toBe('Listing progress')
        ->and(stepStates($dom))->toBe(['complete', 'current', 'upcoming'])
        ->and($dom->query('//li[@aria-current="step"]'))->toHaveCount(1)
        ->and(trim($dom->query('//li[@aria-current="step"]/*[@data-part="title"]')->item(0)->textContent))->toBe('Location');

    $parts = [];
    foreach ($dom->query('//li[1]/*') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }
    expect($parts)->toBe(['marker', 'title', 'connector', 'description']);
});

test('markers show numbers, a check or an x, with hidden state text', function (): void {
    $dom = designSystemDom(Blade::render(<<<'BLADE'
        <x-artisanpack-steps :value="3">
            <x-artisanpack-step title="A" />
            <x-artisanpack-step title="B" state="error" />
            <x-artisanpack-step title="C" />
            <x-artisanpack-step title="D" />
        </x-artisanpack-steps>
        BLADE));

    $markers = $dom->query('//*[@data-part="marker"]');

    expect(stepStates($dom))->toBe(['complete', 'error', 'current', 'upcoming'])
        ->and($markers->item(0)->getElementsByTagName('svg'))->toHaveCount(1)
        ->and(trim($markers->item(0)->textContent))->toBe('Completed')
        ->and(trim($markers->item(1)->textContent))->toBe('Error')
        ->and(trim($markers->item(2)->textContent))->toBe('3')
        ->and($dom->query('//*[@data-part="marker"]/span[@aria-hidden="true"]'))->toHaveCount(2)
        ->and($dom->query('//*[@data-part="connector"][@aria-hidden="true"]'))->toHaveCount(4);
});

test('steps data renders the same children', function (): void {
    $data = designSystemDom(Blade::render('<x-artisanpack-steps :value="2" orientation="vertical" :steps="$steps" />', [
        'steps' => [
            ['title' => 'Cart', 'description' => 'Two items'],
            ['title' => 'Shipping'],
            ['title' => 'Payment', 'icon' => 'lock'],
        ],
    ]));

    expect($data->query('//ol')->item(0)->getAttribute('data-orientation'))->toBe('vertical')
        ->and(stepStates($data))->toBe(['complete', 'current', 'upcoming'])
        ->and($data->query('//li[3]/*[@data-part="marker"]/svg'))->toHaveCount(1)
        ->and(trim($data->query('//li[1]/*[@data-part="description"]')->item(0)->textContent))->toBe('Two items');
});

test('the 2.x step and text props still work', function (): void {
    $dom = designSystemDom(Blade::render(<<<'BLADE'
        <x-artisanpack-steps :value="2">
            <x-artisanpack-step :step="1" text="Cart" />
            <x-artisanpack-step :step="2" text="Shipping" />
        </x-artisanpack-steps>
        BLADE));

    expect(stepStates($dom))->toBe(['complete', 'current'])
        ->and(trim($dom->query('//li[2]/*[@data-part="title"]')->item(0)->textContent))->toBe('Shipping');
});

test('without value every step is upcoming, and labels are translatable', function (): void {
    $plain = designSystemDom(Blade::render('<x-artisanpack-steps :steps="[[\'title\' => \'A\'], [\'title\' => \'B\']]" />'));
    $de    = designSystemDom(Blade::render('<x-artisanpack-steps :value="2" complete-label="Erledigt" :steps="[[\'title\' => \'A\'], [\'title\' => \'B\']]" />'));

    expect(stepStates($plain))->toBe(['upcoming', 'upcoming'])
        ->and(trim($de->query('//*[@data-part="marker"]')->item(0)->textContent))->toBe('Erledigt');
});

test('classes reach the root and the children', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-steps :classes="[\'root\' => \'r\', \'title\' => \'t\', \'step\' => \'s\']" :steps="[[\'title\' => \'A\']]" />'));

    expect($dom->query('//ol')->item(0)->getAttribute('class'))->toBe('apui-steps r')
        ->and($dom->query('//li')->item(0)->getAttribute('class'))->toBe('s')
        ->and($dom->query('//*[@data-part="title"]')->item(0)->getAttribute('class'))->toBe('t');
});

test('clickable without wire:model keeps plain titles', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-steps :value="2" clickable :steps="[[\'title\' => \'A\'], [\'title\' => \'B\']]" />'));

    expect($dom->query('//button'))->toHaveCount(0)
        ->and($dom->query('//ol')->item(0)->hasAttribute('x-data'))->toBeFalse();
});

test('wire:model reads the property, and clickable turns completed steps into buttons', function (): void {
    $html = Livewire::test(StepsLivewireTestComponent::class)->html();
    $dom  = designSystemDom($html);

    expect(stepStates($dom))->toBe(['complete', 'complete', 'current', 'upcoming'])
        ->and($dom->query('//button[@data-part="title"]'))->toHaveCount(2)
        ->and($dom->query('//button[@data-part="title"]')->item(1)->getAttribute('x-on:click'))->toBe('value = 2')
        ->and($dom->query('//ol')->item(0)->getAttribute('x-data'))->toBe("{ value: \$wire.entangle('progress').live }")
        ->and($html)->not->toContain('wire:model');
});

test('setting the property moves the current step', function (): void {
    $html = Livewire::test(StepsLivewireTestComponent::class)->set('progress', 1)->html();

    expect(stepStates(designSystemDom($html)))->toBe(['current', 'upcoming', 'upcoming', 'upcoming']);
});
