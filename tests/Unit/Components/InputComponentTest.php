<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\IconRegistry;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

/*
 * Input (#45): the design-system markup contract, ids, attribute routing and
 * the error lookup. The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

/**
 * Shares a `$errors` bag with the views.
 *
 * @param  array<string, string>  $messages  Key => message.
 */
function shareInputErrors(array $messages): void
{
    view()->share('errors', (new ViewErrorBag)->put('default', new MessageBag($messages)));
}

test('the fixture renders the contract markup in order', function (): void {
    $html = Blade::render(
        '<x-artisanpack-input id="email" label="Email" placeholder="you@example.com" required hint="We never share it." error="Enter a valid email address." prefix="to:" suffix="@work" leading-icon="mail" trailing-icon="search" />',
    );
    $dom = designSystemDom($html);

    $parts = [];
    foreach ($dom->query('//*[@data-scope="input"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    expect($parts)->toBe(['label', 'required', 'box', 'icon', 'prefix', 'control', 'suffix', 'iconEnd', 'hint', 'error'])
        ->and($html)->toContain('<label data-scope="input" data-part="label" for="email">Email<span data-scope="input" data-part="required" aria-hidden="true">*</span></label>')
        ->and($html)->toContain('<p data-scope="input" data-part="error" id="email-error">'.IconRegistry::svg('circle-alert').'Enter a valid email address.</p>')
        ->and($html)->toContain('<p data-scope="input" data-part="hint" id="email-hint">We never share it.</p>');

    $root    = $dom->query('//div[contains(@class, "apui-input")]')->item(0);
    $control = $dom->query('//input')->item(0);

    expect($root->getAttribute('data-size'))->toBe('md')
        ->and($root->hasAttribute('data-invalid'))->toBeTrue()
        ->and($control->getAttribute('id'))->toBe('email')
        ->and($control->getAttribute('type'))->toBe('text')
        ->and($control->getAttribute('placeholder'))->toBe('you@example.com')
        ->and($control->hasAttribute('required'))->toBeTrue()
        ->and($control->getAttribute('aria-invalid'))->toBe('true')
        ->and($control->getAttribute('aria-describedby'))->toBe('email-hint email-error');
});

test('a bare input renders only the root, box and control', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-input aria-label="Search" />'));

    $parts = [];
    foreach ($dom->query('//*[@data-scope="input"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    $control = $dom->query('//input')->item(0);

    expect($parts)->toBe(['box', 'control'])
        ->and($control->hasAttribute('aria-describedby'))->toBeFalse()
        ->and($control->hasAttribute('aria-invalid'))->toBeFalse()
        ->and($control->getAttribute('aria-label'))->toBe('Search');
});

test('the required mark needs a label', function (): void {
    expect(Blade::render('<x-artisanpack-input required aria-label="Name" />'))->not->toContain('data-part="required"');
});

test('aria-describedby lists only the rendered hint and error', function (): void {
    $hint  = designSystemDom(Blade::render('<x-artisanpack-input id="a" label="A" hint="Help" />'))->query('//input')->item(0);
    $error = designSystemDom(Blade::render('<x-artisanpack-input id="b" label="B" error="Wrong" />'))->query('//input')->item(0);

    expect($hint->getAttribute('aria-describedby'))->toBe('a-hint')
        ->and($error->getAttribute('aria-describedby'))->toBe('b-error');
});

test('a caller aria-describedby is kept after the field ids', function (): void {
    $control = designSystemDom(Blade::render('<x-artisanpack-input id="a" label="A" hint="Help" aria-describedby="extra" />'))->query('//input')->item(0);

    expect($control->getAttribute('aria-describedby'))->toBe('a-hint extra');
});

test('the id derives from wire:model when none is given', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-input wire:model.live="user.email" label="Email" hint="Help" />'));
    $id  = 'apui-input-user-email-'.substr(md5('user.email'), 0, 8);

    expect($dom->query('//input')->item(0)->getAttribute('id'))->toBe($id)
        ->and($dom->query('//label')->item(0)->getAttribute('for'))->toBe($id)
        ->and($dom->query('//p[@data-part="hint"]')->item(0)->getAttribute('id'))->toBe($id.'-hint');
});

test('models that slug alike still get different ids', function (): void {
    $dotted     = designSystemDom(Blade::render('<x-artisanpack-input wire:model="user.name" label="A" />'))->query('//input')->item(0)->getAttribute('id');
    $underscore = designSystemDom(Blade::render('<x-artisanpack-input wire:model="user_name" label="B" />'))->query('//input')->item(0)->getAttribute('id');

    expect($dotted)->not->toBe($underscore)
        ->and($dotted)->toStartWith('apui-input-user-name-')
        ->and($underscore)->toStartWith('apui-input-user-name-');
});

test('without id or wire:model the id is a stable hash of the props', function (): void {
    $first  = designSystemDom(Blade::render('<x-artisanpack-input label="Name" />'))->query('//input')->item(0)->getAttribute('id');
    $second = designSystemDom(Blade::render('<x-artisanpack-input label="Name" />'))->query('//input')->item(0)->getAttribute('id');
    $other  = designSystemDom(Blade::render('<x-artisanpack-input label="Other" />'))->query('//input')->item(0)->getAttribute('id');

    expect($first)->toMatch('/^apui-input-[0-9a-f]{32}$/')
        ->and($second)->toBe($first)
        ->and($other)->not->toBe($first);
});

test('class lands on the root and every other attribute on the control', function (): void {
    $dom = designSystemDom(Blade::render(
        '<x-artisanpack-input class="w-64" wire:model="email" name="email" autocomplete="email" data-test="x" label="Email" :classes="[\'root\' => \'extra\', \'control\' => \'mono\']" />',
    ));
    $root    = $dom->query('//div[contains(@class, "apui-input")]')->item(0);
    $control = $dom->query('//input')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-input extra w-64')
        ->and($root->hasAttribute('wire:model'))->toBeFalse()
        ->and($root->hasAttribute('name'))->toBeFalse()
        ->and($control->getAttribute('wire:model'))->toBe('email')
        ->and($control->getAttribute('name'))->toBe('email')
        ->and($control->getAttribute('autocomplete'))->toBe('email')
        ->and($control->getAttribute('data-test'))->toBe('x')
        ->and($control->getAttribute('class'))->toBe('mono');
});

test('the error comes from $errors for the wire:model name', function (): void {
    shareInputErrors(['email' => 'The email is taken.']);

    $html = Blade::render('<x-artisanpack-input wire:model="email" label="Email" />');
    $dom  = designSystemDom($html);

    expect($dom->query('//p[@data-part="error"]')->item(0)->textContent)->toBe('The email is taken.')
        ->and($dom->query('//div[contains(@class, "apui-input")]')->item(0)->hasAttribute('data-invalid'))->toBeTrue()
        ->and($dom->query('//input')->item(0)->getAttribute('aria-describedby'))->toBe('apui-input-email-'.substr(md5('email'), 0, 8).'-error');
});

test('errorField picks the $errors key', function (): void {
    shareInputErrors(['profile.email' => 'Profile email is invalid.', 'email' => 'Wrong key.']);

    expect(Blade::render('<x-artisanpack-input wire:model="email" error-field="profile.email" label="Email" />'))
        ->toContain('Profile email is invalid.')
        ->not->toContain('Wrong key.');
});

test('an error prop wins over $errors', function (): void {
    shareInputErrors(['email' => 'From the bag.']);

    expect(Blade::render('<x-artisanpack-input wire:model="email" error="From the prop." label="Email" />'))
        ->toContain('From the prop.')
        ->not->toContain('From the bag.');
});

test('no error part without an error', function (): void {
    shareInputErrors(['other' => 'Not this field.']);

    expect(Blade::render('<x-artisanpack-input wire:model="email" label="Email" />'))->not->toContain('data-part="error"');
});

test('invalid alone marks the field without an error part', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-input invalid label="Email" />'));

    expect($dom->query('//div[contains(@class, "apui-input")]')->item(0)->hasAttribute('data-invalid'))->toBeTrue()
        ->and($dom->query('//input')->item(0)->getAttribute('aria-invalid'))->toBe('true')
        ->and($dom->query('//*[@data-part="error"]'))->toHaveCount(0);
});

test('disabled and readonly set the control and the root', function (): void {
    $disabled = designSystemDom(Blade::render('<x-artisanpack-input disabled label="Team" value="Design" />'));
    $readonly = designSystemDom(Blade::render('<x-artisanpack-input readonly label="ID" value="AP-1" />'));

    expect($disabled->query('//input')->item(0)->hasAttribute('disabled'))->toBeTrue()
        ->and($disabled->query('//div[contains(@class, "apui-input")]')->item(0)->hasAttribute('data-disabled'))->toBeTrue()
        ->and($disabled->query('//input')->item(0)->getAttribute('value'))->toBe('Design')
        ->and($readonly->query('//input')->item(0)->hasAttribute('readonly'))->toBeTrue()
        ->and($readonly->query('//div[contains(@class, "apui-input")]')->item(0)->hasAttribute('data-readonly'))->toBeTrue();
});

test('type and size render as given', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-input type="email" size="lg" label="Email" />'));

    expect($dom->query('//input')->item(0)->getAttribute('type'))->toBe('email')
        ->and($dom->query('//div[contains(@class, "apui-input")]')->item(0)->getAttribute('data-size'))->toBe('lg');
});

test('slots override the prefix, suffix and icons', function (): void {
    $html = Blade::render(<<<'BLADE'
        <x-artisanpack-input label="Price" prefix="USD" leading-icon="mail">
            <x-slot:prefix><strong>€</strong></x-slot:prefix>
            <x-slot:suffix><em>/mo</em></x-slot:suffix>
            <x-slot:icon><svg data-custom="start"></svg></x-slot:icon>
            <x-slot:iconEnd><svg data-custom="end"></svg></x-slot:iconEnd>
        </x-artisanpack-input>
        BLADE);

    expect($html)->toContain('<span data-scope="input" data-part="prefix"><strong>€</strong></span>')
        ->toContain('<span data-scope="input" data-part="suffix"><em>/mo</em></span>')
        ->toContain('<span data-scope="input" data-part="icon" aria-hidden="true"><svg data-custom="start"></svg></span>')
        ->toContain('<span data-scope="input" data-part="iconEnd" aria-hidden="true"><svg data-custom="end"></svg></span>')
        ->not->toContain('USD');
});

test('the label and the error are escaped', function (): void {
    $html = Blade::render('<x-artisanpack-input :label="$label" :error="$label" />', ['label' => '<img src=x onerror=alert(1)>']);

    expect($html)->not->toContain('<img src=x')->toContain('&lt;img src=x');
});
