<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\RadioGroup;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

/*
 * RadioGroup (#53): the design-system markup contract, native radios that
 * share a name, ids, the error lookup and the variants. The manifest parity
 * check is in tests/Feature/DesignSystemParityTest.php.
 */

$plans = "[ [ 'value' => 'starter', 'label' => 'Starter', 'description' => 'Up to 5', 'meta' => '\$0' ], [ 'value' => 'growth', 'label' => 'Growth' ], [ 'value' => 'team', 'label' => 'Team', 'disabled' => true ] ]";

test('the fixture renders a fieldset with the contract parts in order', function () use ($plans): void {
    $html = Blade::render('<x-artisanpack-radio-group id="plan" label="Plan" hint="Change any time." error="Choose a plan." required :options="'.$plans.'" />');
    $dom  = designSystemDom($html);

    $root  = $dom->query('//fieldset')->item(0);
    $parts = [];
    foreach ($dom->query('//fieldset/*') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    expect($root->getAttribute('class'))->toBe('apui-radio-group')
        ->and($root->getAttribute('data-orientation'))->toBe('vertical')
        ->and($root->getAttribute('data-variant'))->toBe('default')
        ->and($root->hasAttribute('data-invalid'))->toBeTrue()
        ->and($root->getAttribute('aria-describedby'))->toBe('plan-hint plan-error')
        ->and($parts)->toBe(['label', 'items', 'hint', 'error'])
        ->and($dom->query('//legend/span[@data-part="required"]')->length)->toBe(1)
        ->and($dom->query('//label[@data-part="item"]')->length)->toBe(3);
});

test('every radio shares one name, so arrow keys move between them', function () use ($plans): void {
    $dom    = designSystemDom(Blade::render('<x-artisanpack-radio-group wire:model="plan" :options="'.$plans.'" />'));
    $names  = [];
    $values = [];
    foreach ($dom->query('//input') as $input) {
        $names[]  = $input->getAttribute('name');
        $values[] = $input->getAttribute('value');
        expect($input->getAttribute('type'))->toBe('radio')
            ->and($input->getAttribute('wire:model'))->toBe('plan');
    }

    expect(array_unique($names))->toHaveCount(1)
        ->and($names[0])->toBe('apui-radio-group-plan-'.substr(md5("\0plan"), 0, 8))
        ->and($values)->toBe(['starter', 'growth', 'team']);
});

test('identical groups in one render get their own names and ids', function () use ($plans): void {
    $dom   = designSystemDom(Blade::render('<x-artisanpack-radio-group value="growth" :options="'.$plans.'" /><x-artisanpack-radio-group value="growth" :options="'.$plans.'" />'));
    $names = [];
    foreach ($dom->query('//fieldset') as $fieldset) {
        $names[] = $dom->query('.//input', $fieldset)->item(0)->getAttribute('name');
    }

    expect($names)->toHaveCount(2)
        ->and($names[0])->not->toBe($names[1])
        ->and($dom->query('//input[@value="growth"][@checked]')->length)->toBe(2);
});

test('an explicit name wins', function () use ($plans): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-radio-group name="plan" :options="'.$plans.'" />'));

    expect($dom->query('//input')->item(0)->getAttribute('name'))->toBe('plan');
});

test('value checks the matching radio', function () use ($plans): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-radio-group value="growth" :options="'.$plans.'" />'));

    expect($dom->query('//input[@value="growth"]')->item(0)->hasAttribute('checked'))->toBeTrue()
        ->and($dom->query('//input[@value="starter"]')->item(0)->hasAttribute('checked'))->toBeFalse();
});

test('descriptions are linked and meta renders, an option can be disabled', function () use ($plans): void {
    $dom   = designSystemDom(Blade::render('<x-artisanpack-radio-group id="p" :options="'.$plans.'" />'));
    $first = $dom->query('//input')->item(0);

    expect($first->getAttribute('aria-describedby'))->toBe('p-0-description')
        ->and($dom->query('//*[@id="p-0-description"]')->item(0)->textContent)->toBe('Up to 5')
        ->and($dom->query('//*[@data-part="item-meta"]')->item(0)->textContent)->toBe('$0')
        ->and($dom->query('//input[@value="team"]')->item(0)->hasAttribute('disabled'))->toBeTrue()
        ->and($dom->query('//label[@data-part="item"][3]')->item(0)->hasAttribute('data-disabled'))->toBeTrue();
});

test('segments render no description or meta', function () use ($plans): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-radio-group variant="segmented" :options="'.$plans.'" />'));

    expect($dom->query('//*[@data-part="item-description"]')->length)->toBe(0)
        ->and($dom->query('//*[@data-part="item-meta"]')->length)->toBe(0)
        ->and($dom->query('//input')->item(0)->hasAttribute('aria-describedby'))->toBeFalse();
});

test('strings work as options', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-radio-group :options="[\'Monthly\', \'Yearly\']" />'));

    expect($dom->query('//input')->item(1)->getAttribute('value'))->toBe('Yearly')
        ->and($dom->query('//*[@data-part="item-text"]')->item(1)->textContent)->toBe('Yearly');
});

test('variant and orientation are data attributes', function (string $variant): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-radio-group variant="'.$variant.'" orientation="horizontal" :options="[\'A\']" />'));

    expect($dom->query('//fieldset')->item(0)->getAttribute('data-variant'))->toBe($variant)
        ->and($dom->query('//fieldset')->item(0)->getAttribute('data-orientation'))->toBe('horizontal');
})->with(['default', 'cards', 'segmented']);

test('the error comes from $errors and marks every radio invalid', function (): void {
    view()->share('errors', (new ViewErrorBag)->put('default', new MessageBag(['plan' => 'Pick one.'])));

    $dom = designSystemDom(Blade::render('<x-artisanpack-radio-group wire:model="plan" :options="[\'A\', \'B\']" />'));

    expect($dom->query('//p[@data-part="error"]')->item(0)->textContent)->toBe('Pick one.')
        ->and($dom->query('//input[@aria-invalid="true"]')->length)->toBe(2);
});

test('disabled disables the fieldset', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-radio-group disabled :options="[\'A\']" />'))->query('//fieldset')->item(0);

    expect($root->hasAttribute('disabled'))->toBeTrue()->and($root->hasAttribute('data-disabled'))->toBeTrue();
});

test('the constructor declares only the manifest props', function (): void {
    $parameters = array_map(
        static fn (ReflectionParameter $parameter): string => $parameter->getName(),
        (new ReflectionClass(RadioGroup::class))->getConstructor()->getParameters(),
    );

    expect($parameters)->toBe(['options', 'value', 'label', 'hint', 'error', 'invalid', 'disabled', 'required', 'orientation', 'variant', 'errorField', 'classes']);
});
