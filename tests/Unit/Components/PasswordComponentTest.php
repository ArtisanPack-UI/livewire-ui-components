<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\IconRegistry;
use ArtisanPack\LivewireUiComponents\View\Components\Password;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

/*
 * Password (#53): the design-system markup contract, the reveal button, the
 * strength meter, requirements, ids and the error lookup. The manifest parity
 * check is in tests/Feature/DesignSystemParityTest.php.
 */

test('the fixture renders the contract markup in order', function (): void {
    $html = Blade::render(
        '<x-artisanpack-password id="pw" label="Password" leading-icon="lock" required strength-meter hint="Use a password you don\'t use elsewhere." error="Passwords don\'t match." :requirements="[ [ \'label\' => \'At least 12 characters\', \'pattern\' => \'.{12,}\' ] ]" />',
    );
    $dom = designSystemDom($html);

    $parts = [];
    foreach ($dom->query('//*[@data-scope="password"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    $control = $dom->query('//input')->item(0);

    expect($parts)->toBe(['label', 'required', 'box', 'icon', 'control', 'reveal', 'strength', 'segment', 'segment', 'segment', 'segment', 'strengthLabel', 'requirements', 'requirement', 'hint', 'error'])
        ->and($control->getAttribute('type'))->toBe('password')
        ->and($control->getAttribute('autocomplete'))->toBe('current-password')
        ->and($control->getAttribute('aria-describedby'))->toBe('pw-hint pw-requirements pw-error')
        ->and($html)->toContain('<p data-scope="password" data-part="error" id="pw-error">'.IconRegistry::svg('circle-alert').'Passwords don&#039;t match.</p>');
});

test('the reveal button keeps its name and toggles aria-pressed', function (): void {
    $dom    = designSystemDom(Blade::render('<x-artisanpack-password id="pw" label="Password" />'));
    $button = $dom->query('//button')->item(0);

    $icons = [];
    foreach ($dom->query('//button/svg') as $svg) {
        $icons[] = $svg->getAttribute('data-icon');
    }

    expect($button->getAttribute('type'))->toBe('button')
        ->and($button->getAttribute('aria-label'))->toBe('Show password')
        ->and($button->getAttribute('aria-pressed'))->toBe('false')
        ->and($button->getAttribute('aria-controls'))->toBe('pw')
        ->and($button->getAttribute('x-on:click'))->toBe('revealed = ! revealed')
        ->and($dom->query('//input')->item(0)->getAttribute('x-bind:type'))->toBe("revealed ? 'text' : 'password'")
        ->and($icons)->toBe(['eye', 'eye-off']);
});

test('revealLabel and labels override the built-in text', function (): void {
    $html = Blade::render('<x-artisanpack-password label="Clave" reveal-label="Mostrar" strength-meter :labels="[ \'strength\' => \'Seguridad\' ]" />');

    expect($html)->toContain('aria-label="Mostrar"')
        ->and($html)->toContain('<span>Seguridad</span>');
});

test('the strength level follows the shared algorithm', function (string $value, int $level): void {
    expect(Password::strengthLevel($value))->toBe($level);
})->with([
    'empty'          => ['', 0],
    'short'          => ['abc', 1],
    'eight + digit'  => ['password1', 1],
    'mixed case'     => ['Password1', 2],
    'symbol'         => ['Password1!', 3],
    'long, all four' => ['Password12345!', 4],
]);

test('the server renders the meter and requirements for the initial value', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-password label="P" value="Password1!" strength-meter :requirements="[ [ \'label\' => \'12+\', \'pattern\' => \'.{12,}\' ], [ \'label\' => \'Number\', \'pattern\' => \'\\d\' ] ]" />'));

    $items = $dom->query('//li');

    expect($dom->query('//*[@data-part="strength"]')->item(0)->getAttribute('data-level'))->toBe('3')
        ->and($dom->query('//*[@aria-live="polite"]')->item(0)->textContent)->toBe('Good')
        ->and($items->item(0)->hasAttribute('data-met'))->toBeFalse()
        ->and($items->item(0)->textContent)->toContain('(Not met)')
        ->and($items->item(1)->hasAttribute('data-met'))->toBeTrue()
        ->and($items->item(1)->textContent)->toContain('(Met)');
});

test('an invalid requirement pattern is never met', function (): void {
    expect(Password::meets('(', 'anything'))->toBeFalse()
        ->and(Password::meets('a~b', 'a~b'))->toBeTrue();
});

test('autocomplete, size and states render', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-password label="P" autocomplete="new-password" size="lg" disabled readonly />'));
    $root = $dom->query('//div')->item(0);

    expect($dom->query('//input')->item(0)->getAttribute('autocomplete'))->toBe('new-password')
        ->and($root->getAttribute('data-size'))->toBe('lg')
        ->and($root->hasAttribute('data-disabled'))->toBeTrue()
        ->and($root->hasAttribute('data-readonly'))->toBeTrue()
        ->and($dom->query('//button')->item(0)->hasAttribute('disabled'))->toBeTrue();
});

test('class lands on the root and every other attribute on the control', function (): void {
    $dom     = designSystemDom(Blade::render('<x-artisanpack-password class="mt-2" wire:model="password" name="password" label="Password" />'));
    $control = $dom->query('//input')->item(0);

    expect($dom->query('//div')->item(0)->getAttribute('class'))->toBe('apui-password mt-2')
        ->and($control->getAttribute('wire:model'))->toBe('password')
        ->and($control->getAttribute('name'))->toBe('password')
        ->and($control->getAttribute('id'))->toBe('apui-password-password-'.substr(md5('password'), 0, 8));
});

test('the error comes from $errors for the wire:model name', function (): void {
    view()->share('errors', (new ViewErrorBag)->put('default', new MessageBag(['password' => 'The password is too short.'])));

    $dom = designSystemDom(Blade::render('<x-artisanpack-password wire:model="password" label="Password" />'));

    expect($dom->query('//p[@data-part="error"]')->item(0)->textContent)->toBe('The password is too short.');
});

test('Alpine rereads the control after a form reset and unhooks on destroy', function (): void {
    $xData = designSystemDom(Blade::render('<x-artisanpack-password label="A" />'))->query('//div')->item(0)->getAttribute('x-data');

    expect($xData)->toContain("this.form = input.form; this.onReset = () => setTimeout(read); this.form?.addEventListener('reset', this.onReset);")
        ->and($xData)->toContain("this.form?.removeEventListener('reset', this.onReset)");
});
