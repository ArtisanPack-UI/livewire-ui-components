<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Upgrade\UpgradeRules;
use Illuminate\Support\Facades\Blade;

/*
 * The upgrade rules (design-system #67) against the components this package
 * registers: a rule may only write a tag that exists and an attribute its
 * class takes, so the upgrade can't produce markup 3.0 doesn't understand.
 */

/**
 * The class registered for a 3.0 tag.
 */
function upgradeTargetClass(string $tag): ?string
{
    return Blade::getClassComponentAliases()['artisanpack-'.$tag] ?? null;
}

/**
 * The constructor arguments of a component class.
 *
 * @return list<string>
 */
function upgradeConstructorArguments(string $class): array
{
    $constructor = (new ReflectionClass($class))->getConstructor();

    return array_map(fn (ReflectionParameter $parameter): string => $parameter->getName(), $constructor?->getParameters() ?? []);
}

test('loads the shipped rules', function (): void {
    $rules = UpgradeRules::load();

    expect($rules->component('modal')['to'])->toBe('dialog')
        ->and($rules->component('ec-price'))->toBeNull()
        ->and($rules->versions())->toBe(['from' => '2.x', 'to' => '3.0'])
        ->and($rules->classes()['component'])->toContain('btn');
});

test('rejects a missing or malformed rules file', function (): void {
    expect(fn () => UpgradeRules::load('/nope/rules.php'))->toThrow(RuntimeException::class, 'missing');

    $file = tempnam(sys_get_temp_dir(), 'rules');
    file_put_contents($file, '<?php return [];');

    expect(fn () => UpgradeRules::load($file))->toThrow(RuntimeException::class, 'Not an upgrade rules file');
    unlink($file);
});

test('converts between kebab-case and camelCase', function (): void {
    expect(UpgradeRules::kebab('noWireNavigate'))->toBe('no-wire-navigate')
        ->and(UpgradeRules::camel('no-wire-navigate'))->toBe('noWireNavigate');
});

test('every rule writes a registered tag', function (): void {
    $missing = [];

    foreach (UpgradeRules::load()->components() as $name => $rule) {
        // `event-modal-content` is the Calendar's internal Livewire child, not a Blade tag.
        if (null === upgradeTargetClass($rule['to']) && 'event-modal-content' !== $name) {
            $missing[] = "{$name} → {$rule['to']}";
        }

        foreach ($rule['props'] ?? [] as $member) {
            if ('wrap' === $member['kind'] && null === upgradeTargetClass($member['tag'])) {
                $missing[] = "{$name} wraps in {$member['tag']}";
            }
        }
    }

    expect($missing)->toBe([]);
});

test('every rule writes an attribute the 3.0 class takes', function (): void {
    $unknown = [];

    foreach (UpgradeRules::load()->components() as $name => $rule) {
        $class = upgradeTargetClass($rule['to']);

        // Only the Calendar's internal Livewire child has no Blade class.
        if (null === $class) {
            expect($name)->toBe('event-modal-content');

            continue;
        }

        $arguments = upgradeConstructorArguments($class);
        $check     = function (string $from, string $to) use (&$unknown, $arguments, $name, $rule): void {
            if (! in_array($to, $arguments, true)) {
                $unknown[] = "{$name}.{$from} → {$rule['to']}.{$to}";
            }
        };

        foreach ($rule['props'] ?? [] as $from => $member) {
            match ($member['kind']) {
                'rename', 'value', 'invert', 'part-class' => $check($from, $member['to']),
                'split' => array_map(fn (array $case) => $check($from, $case['to']), $member['cases']),
                default => null,
            };
        }

        foreach ($rule['add'] ?? [] as $added) {
            $check('add', $added['name']);
        }
    }

    expect($unknown)->toBe([]);
});
