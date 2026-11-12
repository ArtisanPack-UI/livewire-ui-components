<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Tooltip (#59): the design-system markup contract (D-019) on the tooltip
 * machine. The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

function tooltipLabel(DOMXPath $dom): DOMElement
{
    return $dom->query('//div[contains(@class, "apui-tooltip")]')->item(0);
}

test('the fixture renders every part in order with stable ids', function (): void {
    $dom = designSystemDom(Blade::render(
        '<x-artisanpack-tooltip id="tip" content="Archive this listing" shortcut="⌘+E" placement="bottom" :open="true" :portal="false">Archive</x-artisanpack-tooltip>',
    ));

    $parts = [];
    foreach ($dom->query('//*[@data-scope="tooltip"]') as $element) {
        $parts[] = [$element->getAttribute('data-part'), $element->getAttribute('id')];
    }

    expect($parts)->toBe([
        ['trigger', 'tip-trigger'],
        ['positioner', 'tip-positioner'],
        ['content', 'tip-content'],
        ['arrow', 'tip-arrow'],
        ['kbd', 'tip-kbd-1'],
        ['kbd', 'tip-kbd-2'],
    ]);

    $label = tooltipLabel($dom);
    expect($label->getAttribute('role'))->toBe('tooltip')
        ->and($label->getAttribute('data-placement'))->toBe('bottom')
        ->and($label->getAttribute('data-size'))->toBe('md')
        ->and($label->getAttribute('data-state'))->toBe('open')
        ->and($label->hasAttribute('hidden'))->toBeFalse()
        ->and(trim($label->textContent))->toBe('Archive this listing⌘E')
        ->and($dom->query('//*[@data-part="trigger"]')->item(0)->getAttribute('aria-describedby'))->toBe('tip-content');
});

test('the first element of the slot is the trigger and keeps its id and attributes', function (): void {
    $dom = designSystemDom(Blade::render(
        '<x-artisanpack-tooltip id="t" content="Archive"><x-artisanpack-button id="archive" icon-only leading-icon="pencil" aria-label="Archive" aria-describedby="hint" /></x-artisanpack-tooltip>',
    ));
    $trigger = $dom->query('//*[@data-part="trigger"]')->item(0);

    expect($trigger->nodeName)->toBe('button')
        ->and($trigger->getAttribute('id'))->toBe('archive')
        ->and($trigger->getAttribute('aria-label'))->toBe('Archive')
        ->and($trigger->getAttribute('aria-describedby'))->toBe('hint')
        ->and($trigger->getAttribute('data-scope'))->toBe('tooltip')
        ->and($trigger->getAttribute('data-state'))->toBe('closed')
        ->and($trigger->hasAttribute('x-apui:trigger'))->toBeTrue()
        ->and($dom->query('//*[@data-scope="tooltip"][@data-part="trigger"]'))->toHaveCount(1);
});

test('text in the slot is wrapped in a focusable span', function (): void {
    $trigger = designSystemDom(Blade::render('<x-artisanpack-tooltip id="t" content="More">Help</x-artisanpack-tooltip>'))->query('//*[@data-part="trigger"]')->item(0);

    expect($trigger->nodeName)->toBe('span')
        ->and($trigger->getAttribute('tabindex'))->toBe('0')
        ->and($trigger->getAttribute('id'))->toBe('t-trigger')
        ->and($trigger->textContent)->toBe('Help');
});

test('without open the tooltip keeps its own state and renders hidden', function (): void {
    $html  = Blade::render('<x-artisanpack-tooltip content="Archive"><button>A</button></x-artisanpack-tooltip>');
    $label = tooltipLabel(designSystemDom($html));

    expect($label->hasAttribute('hidden'))->toBeTrue()
        ->and($label->getAttribute('data-state'))->toBe('closed')
        ->and($html)->toContain('apuiTooltip(')
        ->and($html)->not->toContain('open:')
        ->and($html)->toContain('delay: 400');
});

test('open forces it; wire:model binds it', function (): void {
    expect(Blade::render('<x-artisanpack-tooltip content="A" :open="false"><button>A</button></x-artisanpack-tooltip>'))->toContain('open: false')
        ->and(Blade::render('<x-artisanpack-tooltip content="A" wire:model="hint"><button>A</button></x-artisanpack-tooltip>'))
        ->toContain('open: $wire.entangle(&#039;hint&#039;), bound: true');
});

test('placement, delay, size and disabled reach the markup and the machine', function (): void {
    $dom   = designSystemDom(Blade::render('<x-artisanpack-tooltip content="A" placement="end" :delay="100" size="lg" disabled><button>A</button></x-artisanpack-tooltip>'));
    $label = tooltipLabel($dom);
    $html  = $dom->query('//span[@x-data]')->item(0)->getAttribute('x-data');

    expect($label->getAttribute('data-placement'))->toBe('end')
        ->and($label->getAttribute('data-size'))->toBe('lg')
        ->and($html)->toContain("placement: 'end'")
        ->and($html)->toContain('delay: 100')
        ->and($html)->toContain('disabled: true')
        ->and($label->hasAttribute('data-disabled'))->toBeTrue();
});

test('the shortcut splits into kbd parts, keeping a + key', function (string $shortcut, array $keys): void {
    $dom    = designSystemDom(Blade::render('<x-artisanpack-tooltip content="Zoom" shortcut="'.$shortcut.'"><button>Z</button></x-artisanpack-tooltip>'));
    $found  = [];
    foreach ($dom->query('//kbd[@data-part="kbd"]') as $kbd) {
        $found[] = $kbd->textContent;
    }

    expect($found)->toBe($keys);
})->with([
    'one key'  => ['E', ['E']],
    'combo'    => ['⌘+K', ['⌘', 'K']],
    'plus key' => ['Ctrl++', ['Ctrl', '+']],
    'only +'   => ['+', ['+']],
]);

test('a content slot overrides the prop and the label escapes text', function (): void {
    $slotted = tooltipLabel(designSystemDom(Blade::render('<x-artisanpack-tooltip content="Plain"><x-slot:content><strong>Bold</strong></x-slot:content><button>B</button></x-artisanpack-tooltip>')));
    $escaped = Blade::render('<x-artisanpack-tooltip content="<b>x</b>"><button>B</button></x-artisanpack-tooltip>');

    expect($slotted->getElementsByTagName('strong')->item(0)->textContent)->toBe('Bold')
        ->and($escaped)->toContain('&lt;b&gt;x&lt;/b&gt;');
});

test('class lands on the label and classes on the parts', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-tooltip content="A" shortcut="E" class="mt-1" :classes="[\'root\' => \'x\', \'trigger\' => \'t\', \'kbd\' => \'k\']"><button class="b">A</button></x-artisanpack-tooltip>'));

    expect(tooltipLabel($dom)->getAttribute('class'))->toBe('apui-tooltip x mt-1')
        ->and($dom->query('//*[@data-part="trigger"]')->item(0)->getAttribute('class'))->toBe('b t')
        ->and($dom->query('//*[@data-part="kbd"]')->item(0)->getAttribute('class'))->toBe('k');
});

test('ids are stable through text changes and unique when repeated', function (): void {
    $render = static function (string $blade): string {
        app()->forgetScopedInstances();

        return designSystemDom(Blade::render($blade))->query('//span[@x-data]')->item(0)->getAttribute('id');
    };

    expect($render('<x-artisanpack-tooltip content="A"><button>A</button></x-artisanpack-tooltip>'))
        ->toBe($render('<x-artisanpack-tooltip content="B" shortcut="E"><button>A</button></x-artisanpack-tooltip>'));

    app()->forgetScopedInstances();
    $html = Blade::render('@foreach ( [ 1, 2 ] as $row )<x-artisanpack-tooltip content="A"><button>A</button></x-artisanpack-tooltip>@endforeach');
    $ids  = [];
    foreach (designSystemDom($html)->query('//span[@x-data]') as $host) {
        $ids[] = $host->getAttribute('id');
    }

    expect($ids[1])->toBe($ids[0].'-2');
});

test('portal teleports the label; off renders it in place', function (): void {
    expect(Blade::render('<x-artisanpack-tooltip content="A"><button>A</button></x-artisanpack-tooltip>'))->toContain('<template x-teleport="body">')
        ->and(Blade::render('<x-artisanpack-tooltip content="A" :portal="false"><button>A</button></x-artisanpack-tooltip>'))->not->toContain('x-teleport');
});
