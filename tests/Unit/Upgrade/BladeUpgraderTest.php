<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Upgrade\BladeUpgrader;
use ArtisanPack\LivewireUiComponents\Upgrade\Finding;
use ArtisanPack\LivewireUiComponents\Upgrade\UpgradeResult;
use ArtisanPack\LivewireUiComponents\Upgrade\UpgradeRules;

/*
 * BladeUpgrader (design-system #67): each kind of rule, on small Blade
 * snippets. The ecosystem fixtures are in tests/Feature/UpgradeUiCommandTest.
 */

function upgradeBlade(string $source): UpgradeResult
{
    return (new BladeUpgrader(UpgradeRules::load()))->upgrade($source, false);
}

/**
 * The findings as `kind member` strings.
 *
 * @return list<string>
 */
function upgradeFindings(UpgradeResult $result): array
{
    return array_map(fn (Finding $finding): string => trim($finding->kind.' '.$finding->member), $result->findings);
}

test('renames a component, its closing tag and its attributes', function (): void {
    $result = upgradeBlade('<x-artisanpack-modal wire:model="open" subtitle="Sure?">Body</x-artisanpack-modal>');

    expect($result->code)->toBe('<x-artisanpack-dialog wire:model="open" description="Sure?">Body</x-artisanpack-dialog>')
        ->and($result->changed())->toBeTrue()
        ->and(upgradeFindings($result))->toBe(['rewrote', 'rewrote subtitle']);
});

test('renames bound, camelCase and short attributes', function (string $source, string $expected): void {
    expect(upgradeBlade($source)->code)->toBe($expected);
})->with([
    'bound'      => ['<x-artisanpack-stat :title="$label" />', '<x-artisanpack-stat :label="$label" />'],
    'kebab-case' => ['<x-artisanpack-button icon-right="o-x" />', '<x-artisanpack-button trailing-icon="o-x" />'],
    'camelCase'  => ['<x-artisanpack-button iconRight="o-x" />', '<x-artisanpack-button trailing-icon="o-x" />'],
    'short'      => ['<x-artisanpack-button :$link />', '<x-artisanpack-button :href="$link" />'],
    'multi-line' => ["<x-artisanpack-stat\n    title=\"Sales\"\n    value=\"5\"\n/>", "<x-artisanpack-stat\n    label=\"Sales\"\n    value=\"5\"\n/>"],
]);

test('reads the value of Button variant', function (): void {
    expect(upgradeBlade('<x-artisanpack-button variant="primary" />')->code)->toBe('<x-artisanpack-button color="primary" />')
        ->and(upgradeBlade('<x-artisanpack-button :variant="\'error\'" />')->code)->toBe('<x-artisanpack-button :color="\'error\'" />')
        ->and(upgradeBlade('<x-artisanpack-button variant="ghost" />')->changed())->toBeFalse();

    $dynamic = upgradeBlade('<x-artisanpack-button :variant="$variant" />');

    expect($dynamic->changed())->toBeFalse()
        ->and($dynamic->findings(Finding::MANUAL))->toHaveCount(1)
        ->and($dynamic->findings[0]->message)->toContain('`color`')
        ->and([$dynamic->findings[0]->line, $dynamic->findings[0]->column])->toBe([1, 23]);
});

test('turns a flag into a value', function (): void {
    expect(upgradeBlade('<x-artisanpack-drawer wire:model="open" right />')->code)->toBe('<x-artisanpack-drawer wire:model="open" side="end" />')
        ->and(upgradeBlade('<x-artisanpack-drawer side="start" :right="false" />')->code)->toBe('<x-artisanpack-drawer side="start" />');

    $dynamic = upgradeBlade('<x-artisanpack-drawer side="start" :right="$rtl" />');

    expect($dynamic->changed())->toBeFalse()
        ->and($dynamic->findings(Finding::MANUAL)[0]->message)->toContain('side="end"');
});

test('inverts a negative flag', function (string $source, string $expected): void {
    expect(upgradeBlade($source)->code)->toBe($expected);
})->with([
    'flag'    => ['<x-artisanpack-breadcrumbs :items="$items" no-wire-navigate />', '<x-artisanpack-breadcrumbs :items="$items" :navigate="false" />'],
    'false'   => ['<x-artisanpack-breadcrumbs :no-wire-navigate="false" />', '<x-artisanpack-breadcrumbs navigate />'],
    'dynamic' => ['<x-artisanpack-breadcrumbs :no-wire-navigate="$spa" />', '<x-artisanpack-breadcrumbs :navigate="! ($spa)" />'],
]);

test('removes flags that are gone and says so', function (): void {
    $result = upgradeBlade("<x-artisanpack-header title=\"Orders\"\n    separator\n/>");

    expect($result->code)->toBe("<x-artisanpack-page-header title=\"Orders\"\n/>")
        ->and($result->findings(Finding::REMOVED))->toHaveCount(1)
        ->and($result->findings(Finding::REMOVED)[0]->line)->toBe(2);
});

test('merges per-part classes into classes', function (): void {
    expect(upgradeBlade('<x-artisanpack-modal box-class="w-96" />')->code)
        ->toBe('<x-artisanpack-dialog :classes="[\'box\' => \'w-96\']" />');

    $existing = upgradeBlade('<x-artisanpack-modal box-class="w-96" :classes="$classes" />');

    expect($existing->code)->toBe('<x-artisanpack-dialog box-class="w-96" :classes="$classes" />')
        ->and($existing->findings(Finding::MANUAL))->toHaveCount(1);
});

test('moves a label into the default slot of a self-closing tag', function (): void {
    expect(upgradeBlade('<x-artisanpack-button label="Save" wire:click="save" />')->code)
        ->toBe('<x-artisanpack-button wire:click="save">Save</x-artisanpack-button>')
        ->and(upgradeBlade('<x-artisanpack-button :label="__( \'Save\' )" />')->code)
        ->toBe('<x-artisanpack-button>{{ __( \'Save\' ) }}</x-artisanpack-button>');

    $paired = upgradeBlade('<x-artisanpack-button label="Save">Icon</x-artisanpack-button>');

    expect($paired->changed())->toBeFalse()
        ->and($paired->findings(Finding::MANUAL))->toHaveCount(1);
});

test('does not turn escaped text into raw HTML', function (string $source): void {
    $result = upgradeBlade($source);

    expect($result->changed())->toBeFalse()
        ->and($result->findings(Finding::MANUAL))->toHaveCount(1);
})->with([
    'markup'       => ['<x-artisanpack-button label="<b>Save</b>" />'],
    'an entity'    => ['<x-artisanpack-button label="Save &amp; close" />'],
    'a raw echo'   => ['<x-artisanpack-button label="{!! $html !!}" />'],
]);

test('keeps an escaped echo escaped in the slot', function (): void {
    expect(upgradeBlade('<x-artisanpack-button label="{{ $user->name }}" />')->code)
        ->toBe('<x-artisanpack-button>{{ $user->name }}</x-artisanpack-button>');
});

test('does not write an attribute twice or negate text', function (): void {
    $taken = upgradeBlade('<x-artisanpack-drawer side="start" right />');
    $text  = upgradeBlade('<x-artisanpack-breadcrumbs no-wire-navigate="{{ $spa }}" />');

    expect($taken->changed())->toBeFalse()
        ->and($taken->findings(Finding::MANUAL)[0]->message)->toContain('already set')
        ->and($text->changed())->toBeFalse()
        ->and($text->findings(Finding::MANUAL)[0]->message)->toContain('opposite meaning');
});

test('wraps an element in a Tooltip', function (): void {
    expect(upgradeBlade('<x-artisanpack-button tooltip="Save" icon="o-check" />')->code)
        ->toBe('<x-artisanpack-tooltip content="Save"><x-artisanpack-button leading-icon="o-check" /></x-artisanpack-tooltip>')
        ->and(upgradeBlade('<x-artisanpack-button :tooltip-left="__( \'Save\' )">Go</x-artisanpack-button>')->code)
        ->toBe('<x-artisanpack-tooltip :content="__( \'Save\' )" placement="start"><x-artisanpack-button>Go</x-artisanpack-button></x-artisanpack-tooltip>')
        ->and(upgradeBlade('<x-artisanpack-button tooltip="Save" tooltip-position="left" />')->code)
        ->toBe('<x-artisanpack-tooltip content="Save" placement="start"><x-artisanpack-button /></x-artisanpack-tooltip>');
});

test('renames slots, inline and named, with their closing tags', function (): void {
    $source = <<<'BLADE'
    <x-artisanpack-modal wire:model="open">
        <x-slot:actions><x-artisanpack-button>OK</x-artisanpack-button></x-slot:actions>
    </x-artisanpack-modal>
    <x-artisanpack-modal wire:model="other">
        <x-slot name="actions">OK</x-slot>
    </x-artisanpack-modal>
    <x-card><x-slot:actions>Not ours</x-slot:actions></x-card>
    BLADE;

    expect(upgradeBlade($source)->code)->toBe(<<<'BLADE'
    <x-artisanpack-dialog wire:model="open">
        <x-slot:footer><x-artisanpack-button>OK</x-artisanpack-button></x-slot:footer>
    </x-artisanpack-dialog>
    <x-artisanpack-dialog wire:model="other">
        <x-slot name="footer">OK</x-slot>
    </x-artisanpack-dialog>
    <x-card><x-slot:actions>Not ours</x-slot:actions></x-card>
    BLADE);
});

test('adds what a consolidated component needs', function (): void {
    expect(upgradeBlade('<x-artisanpack-choices-offline :options="$tags" wire:model="tags" />')->code)
        ->toBe('<x-artisanpack-combobox multiple :options="$tags" wire:model="tags" />')
        ->and(upgradeBlade('<x-artisanpack-choices-offline :options="$tags" single />')->code)
        ->toBe('<x-artisanpack-combobox :options="$tags" :multiple="false" />')
        ->and(upgradeBlade('<x-artisanpack-choices :options="$tags" :single="$one" searchable />')->code)
        ->toBe('<x-artisanpack-combobox :options="$tags" :multiple="! ($one)" remote />')
        ->and(upgradeBlade('<x-artisanpack-tags wire:model="tags" />')->code)
        ->toBe('<x-artisanpack-combobox multiple allow-create wire:model="tags" />')
        ->and(upgradeBlade('<x-artisanpack-subheading>Lead</x-artisanpack-subheading>')->code)
        ->toBe('<x-artisanpack-text size="lead">Lead</x-artisanpack-text>');
});

test('leaves package-owned tags, other components and non-props alone', function (string $source): void {
    $result = upgradeBlade($source);

    expect($result->changed())->toBeFalse()
        ->and($result->findings)->toBe([]);
})->with([
    'package-owned tag'  => ['<x-artisanpack-ec-price :amount="$total" variant="primary" title="Total" />'],
    'another library'    => ['<x-modal subtitle="x" separator />'],
    'a Blade comment'    => ['{{-- <x-artisanpack-modal subtitle="x" /> --}}'],
    'verbatim'           => ['@verbatim <x-artisanpack-modal subtitle="x" /> @endverbatim'],
    'wire and Alpine'    => ['<x-artisanpack-input wire:model.live="title" x-on:link="go" @separator="x" ::title="js" />'],
    'a 3.0 tag'          => ['<x-artisanpack-dialog wire:model="open" description="Sure?" />'],
    'an unchanged value' => ['<x-artisanpack-button variant="outline" color="error">Delete</x-artisanpack-button>'],
]);

test('reports what it cannot decide, with the line and column', function (): void {
    $result = upgradeBlade("<div>\n    <x-artisanpack-select :options=\"\$users\" option-label=\"email\" />\n</div>");

    expect($result->changed())->toBeFalse()
        ->and($result->findings)->toHaveCount(1)
        ->and($result->findings[0]->kind)->toBe(Finding::MANUAL)
        ->and($result->findings[0]->component)->toBe('select')
        ->and($result->findings[0]->member)->toBe('option-label')
        ->and([$result->findings[0]->line, $result->findings[0]->column])->toBe([2, 45]);
});

test('flags a tag whose attributes Blade builds', function (): void {
    $result = upgradeBlade('<x-artisanpack-button {{ $attributes->merge([\'icon\' => \'o-x\']) }} @class([\'a\' => $b]) icon-right="o-x">Go</x-artisanpack-button>');

    expect($result->code)->toContain('trailing-icon="o-x"')
        ->and($result->findings(Finding::MANUAL)[0]->message)->toContain('builds its attributes');
});

test('does not rename onto an attribute that is already set', function (): void {
    $result = upgradeBlade('<x-artisanpack-stat title="Old" label="New" />');

    expect($result->changed())->toBeFalse()
        ->and($result->findings(Finding::MANUAL)[0]->message)->toContain('already set');
});

test('is stable when run twice', function (): void {
    $source = '<x-artisanpack-modal wire:model="open" subtitle="Sure?" separator><x-artisanpack-button label="OK" tooltip="Fine" variant="primary" /><x-artisanpack-drawer /></x-artisanpack-modal>';
    $once   = upgradeBlade($source)->code;

    expect(upgradeBlade($once)->code)->toBe($once);
});

test('survives malformed markup', function (string $source): void {
    expect(upgradeBlade($source)->code)->toBeString();
})->with([
    'an unclosed tag'    => ['<x-artisanpack-modal subtitle="x'],
    'an unclosed quote'  => ['<x-artisanpack-button label="Save />'],
    'a stray close'      => ['</x-artisanpack-modal><x-artisanpack-modal subtitle="x">'],
    'an unclosed echo'   => ['<x-artisanpack-button {{ $attributes label="x" />'],
    'an empty file'      => [''],
]);
