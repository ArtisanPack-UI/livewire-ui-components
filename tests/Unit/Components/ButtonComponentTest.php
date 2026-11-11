<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\IconRegistry;
use ArtisanPack\LivewireUiComponents\View\Components\Button;
use Illuminate\Support\Facades\Blade;

/*
 * Button (#44): the design-system markup contract. The API surface (data
 * attributes, state evidence, parts, constructor) is also checked against the
 * manifest in tests/Feature/DesignSystemParityTest.php.
 */

afterEach(function (): void {
    IconRegistry::reset();
});

test('the default button renders the contract markup', function (): void {
    $html = Blade::render('<x-artisanpack-button>Save</x-artisanpack-button>');
    $dom  = designSystemDom($html);
    $root = $dom->query('//button')->item(0);

    expect($root->getAttribute('type'))->toBe('button')
        ->and($root->getAttribute('class'))->toBe('apui-button')
        ->and($root->getAttribute('data-variant'))->toBe('solid')
        ->and($root->getAttribute('data-color'))->toBe('primary')
        ->and($root->getAttribute('data-size'))->toBe('md')
        ->and($root->hasAttribute('disabled'))->toBeFalse()
        ->and($root->hasAttribute('data-disabled'))->toBeFalse()
        ->and($root->hasAttribute('aria-busy'))->toBeFalse()
        ->and($root->hasAttribute('data-icon-only'))->toBeFalse();

    $parts = $dom->query('//*[@data-scope="button"]');
    expect($parts)->toHaveCount(1)
        ->and($parts->item(0)->getAttribute('data-part'))->toBe('label')
        ->and($parts->item(0)->textContent)->toBe('Save');
});

test('the fixture parts render in order: icon, label, iconEnd, spinner', function (): void {
    $html = Blade::render('<x-artisanpack-button loading leading-icon="plus" trailing-icon="arrow-right">Button</x-artisanpack-button>');
    $dom  = designSystemDom($html);

    $parts = [];
    foreach ($dom->query('//button/*[@data-scope="button"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    expect($parts)->toBe(['icon', 'label', 'iconEnd', 'spinner'])
        ->and($dom->query('//button')->item(0)->getAttribute('aria-busy'))->toBe('true')
        ->and($html)->toContain('<span data-scope="button" data-part="icon" aria-hidden="true">'.IconRegistry::svg('plus').'</span>')
        ->and($html)->toContain('<span data-scope="button" data-part="label">Button</span>')
        ->and($html)->toContain('<span data-scope="button" data-part="spinner" aria-hidden="true"></span>');
});

test('directional icons get data-mirror on their part', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-button leading-icon="plus" trailing-icon="arrow-right">Next</x-artisanpack-button>'));

    expect($dom->query('//*[@data-part="iconEnd"]')->item(0)->hasAttribute('data-mirror'))->toBeTrue()
        ->and($dom->query('//*[@data-part="icon"]')->item(0)->hasAttribute('data-mirror'))->toBeFalse();
});

test('variant, color, size and type render as given', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-button variant="outline" color="error" size="xs" type="submit">Delete</x-artisanpack-button>'))
        ->query('//button')->item(0);

    expect($root->getAttribute('data-variant'))->toBe('outline')
        ->and($root->getAttribute('data-color'))->toBe('error')
        ->and($root->getAttribute('data-size'))->toBe('xs')
        ->and($root->getAttribute('type'))->toBe('submit');
});

test('a disabled button is natively disabled and marked', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-button disabled>Save</x-artisanpack-button>'))->query('//button')->item(0);

    expect($root->hasAttribute('disabled'))->toBeTrue()
        ->and($root->hasAttribute('data-disabled'))->toBeTrue()
        ->and($root->getAttribute('data-disabled'))->toBe('');
});

test('href renders an anchor without a type', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-button href="/reports" type="submit">Reports</x-artisanpack-button>'));
    $a   = $dom->query('//a')->item(0);

    expect($dom->query('//button'))->toHaveCount(0)
        ->and($a->getAttribute('class'))->toBe('apui-button')
        ->and($a->getAttribute('href'))->toBe('/reports')
        ->and($a->hasAttribute('type'))->toBeFalse()
        ->and($a->getAttribute('data-variant'))->toBe('solid');
});

test('a disabled link drops its href and is not focusable', function (): void {
    $a = designSystemDom(Blade::render('<x-artisanpack-button href="/reports" disabled>Reports</x-artisanpack-button>'))->query('//a')->item(0);

    expect($a->hasAttribute('href'))->toBeFalse()
        ->and($a->getAttribute('aria-disabled'))->toBe('true')
        ->and($a->hasAttribute('data-disabled'))->toBeTrue()
        ->and($a->hasAttribute('tabindex'))->toBeFalse()
        ->and($a->hasAttribute('disabled'))->toBeFalse();
});

test('a loading link drops its href, is busy and is not marked disabled', function (): void {
    $a = designSystemDom(Blade::render('<x-artisanpack-button href="/reports" loading>Reports</x-artisanpack-button>'))->query('//a')->item(0);

    expect($a->hasAttribute('href'))->toBeFalse()
        ->and($a->getAttribute('aria-disabled'))->toBe('true')
        ->and($a->getAttribute('aria-busy'))->toBe('true')
        ->and($a->hasAttribute('data-disabled'))->toBeFalse();
});

test('a disabled or loading link drops its click handlers', function (string $state): void {
    $a = designSystemDom(Blade::render(
        '<x-artisanpack-button href="/r" '.$state.' wire:click="go" wire:click.prevent="go" @click="open = true" x-on:click.stop="x()" onclick="go()" onMouseOver="x()" wire:key="k" data-online="yes">Go</x-artisanpack-button>',
    ))->query('//a')->item(0);

    $names = [];
    foreach ($a->attributes as $attribute) {
        $names[] = $attribute->name;
    }

    expect(array_filter($names, static fn (string $name): bool => 1 === preg_match('/^(wire:click|@click|x-on:click|on[a-z]+$)/i', $name)))->toBe([])
        ->and($a->getAttribute('wire:key'))->toBe('k')
        ->and($a->getAttribute('data-online'))->toBe('yes');
})->with(['disabled', 'loading']);

test('an active link keeps its click handlers', function (): void {
    $a = designSystemDom(Blade::render('<x-artisanpack-button href="/r" wire:click="go">Go</x-artisanpack-button>'))->query('//a')->item(0);

    expect($a->getAttribute('wire:click'))->toBe('go')
        ->and($a->getAttribute('href'))->toBe('/r');
});

test('unsafe href schemes are ignored and render a button', function (string $href): void {
    $html = Blade::render('<x-artisanpack-button :href="$href">Go</x-artisanpack-button>', ['href' => $href]);
    $dom  = designSystemDom($html);

    expect($dom->query('//a'))->toHaveCount(0)
        ->and($dom->query('//button'))->toHaveCount(1)
        ->and($html)->not->toContain('alert(1)');
})->with([
    'javascript'        => 'javascript:alert(1)',
    'mixed case'        => 'JaVaScRiPt:alert(1)',
    'leading space'     => '  javascript:alert(1)',
    'control character' => "java\tscript:alert(1)",
    'vbscript'          => 'vbscript:alert(1)',
    'data'              => 'data:text/html,<script>alert(1)</script>',
    'other scheme'      => 'ftp://example.com/alert(1)',
]);

test('allowed href forms render a link', function (string $href): void {
    expect(designSystemDom(Blade::render('<x-artisanpack-button :href="$href">Go</x-artisanpack-button>', ['href' => $href]))
        ->query('//a')->item(0)->getAttribute('href'))->toBe($href);
})->with(['/r', './r', '?p=2', '#top', '//example.com', 'https://example.com', 'mailto:a@example.com', 'tel:+15555550100', 'sms:+15555550100']);

test('icon-only renders no label part and passes the aria-label through', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-button icon-only leading-icon="plus" aria-label="Add" />'));
    $root = $dom->query('//button')->item(0);

    expect($root->hasAttribute('data-icon-only'))->toBeTrue()
        ->and($root->getAttribute('aria-label'))->toBe('Add')
        ->and($dom->query('//*[@data-part="label"]'))->toHaveCount(0)
        ->and($dom->query('//*[@data-part="icon"]'))->toHaveCount(1);
});

test('the label part is omitted without content', function (): void {
    expect(Blade::render('<x-artisanpack-button leading-icon="plus" aria-label="Add" />'))->not->toContain('data-part="label"');
});

test('attributes land on the root and class merges after classes.root', function (): void {
    $root = designSystemDom(Blade::render(
        '<x-artisanpack-button wire:click="save" data-test="x" class="w-full" :classes="[\'root\' => \'extra\', \'label\' => \'truncate\']">Save</x-artisanpack-button>',
    ))->query('//button')->item(0);

    expect($root->getAttribute('wire:click'))->toBe('save')
        ->and($root->getAttribute('data-test'))->toBe('x')
        ->and($root->getAttribute('class'))->toBe('apui-button extra w-full')
        ->and($root->getElementsByTagName('span')->item(0)->getAttribute('class'))->toBe('truncate');
});

test('icon slots override the icon names', function (): void {
    $html = Blade::render(<<<'BLADE'
        <x-artisanpack-button leading-icon="plus" trailing-icon="arrow-right">
            <x-slot:icon><svg data-custom="start"></svg></x-slot:icon>
            Save
            <x-slot:iconEnd><svg data-custom="end"></svg></x-slot:iconEnd>
        </x-artisanpack-button>
        BLADE);
    $dom = designSystemDom($html);

    expect($dom->query('//*[@data-part="icon"]/svg')->item(0)->getAttribute('data-custom'))->toBe('start')
        ->and($dom->query('//*[@data-part="iconEnd"]/svg')->item(0)->getAttribute('data-custom'))->toBe('end')
        ->and($dom->query('//*[@data-part="iconEnd"]')->item(0)->hasAttribute('data-mirror'))->toBeFalse()
        ->and($dom->query('//*[@data-part="label"]')->item(0)->textContent)->toBe('Save');
});

test('names outside the registry fall back to blade-icons', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-button leading-icon="o-trash">Delete</x-artisanpack-button>'));
    $svg = $dom->query('//*[@data-part="icon"]/svg')->item(0);

    expect($svg)->not->toBeNull()
        ->and($svg->getAttribute('aria-hidden'))->toBe('true');
});

test('unknown icon names keep the part but render no svg', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-button leading-icon="no-such-icon">Go</x-artisanpack-button>'));

    expect($dom->query('//*[@data-part="icon"]'))->toHaveCount(1)
        ->and($dom->query('//*[@data-part="icon"]/*'))->toHaveCount(0);
});

test('registered icons render through the registry', function (): void {
    IconRegistry::register(['rocket' => '<path d="M1 1" />']);

    expect(Blade::render('<x-artisanpack-button leading-icon="rocket">Launch</x-artisanpack-button>'))->toContain(IconRegistry::svg('rocket'));
});

test('the label is escaped', function (): void {
    expect(Blade::render('<x-artisanpack-button>{{ $label }}</x-artisanpack-button>', ['label' => '<script>alert(1)</script>']))
        ->not->toContain('<script>')
        ->toContain('&lt;script&gt;');
});

test('the constructor declares only the manifest props', function (): void {
    $parameters = array_map(
        static fn (ReflectionParameter $parameter): string => $parameter->getName(),
        (new ReflectionClass(Button::class))->getConstructor()->getParameters(),
    );

    expect($parameters)->toBe(['variant', 'color', 'size', 'iconOnly', 'loading', 'disabled', 'href', 'leadingIcon', 'trailingIcon', 'type', 'classes']);
});
