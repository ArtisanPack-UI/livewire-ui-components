<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

/*
 * errors (#145) is a deprecated alias of validation-summary.
 */

beforeEach(function (): void {
    view()->share('errors', (new ViewErrorBag)->put('default', new MessageBag([
        'email'    => ['Enter an email.', 'Second message.'],
        'password' => 'Enter a password.',
    ])));
});

test('renders the validation summary markup from $errors', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-errors id="summary" title="Fix these" />'));
    $root = $dom->query('//div')->item(0);

    $links = [];
    foreach ($dom->query('//a[@data-part="link"]') as $link) {
        $links[$link->getAttribute('href')] = trim($link->textContent);
    }

    expect($root->getAttribute('class'))->toBe('apui-validation-summary')
        ->and($root->getAttribute('id'))->toBe('summary')
        ->and($root->getAttribute('role'))->toBe('alert')
        ->and($root->getAttribute('data-color'))->toBe('error')
        ->and(trim($dom->query('//*[@data-part="title"]')->item(0)->textContent))->toBe('Fix these')
        ->and($links)->toBe(['#email' => 'Enter an email.', '#password' => 'Enter a password.']);
});

test('only limits the listed fields', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-errors :only="[ \'password\' ]" />'));

    expect($dom->query('//a[@data-part="link"]'))->toHaveCount(1)
        ->and($dom->query('//a[@data-part="link"]')->item(0)->getAttribute('href'))->toBe('#password')
        ->and(trim($dom->query('//*[@data-part="title"]')->item(0)->textContent))->toBe('Fix 1 field');
});

test('renders nothing without errors', function (): void {
    view()->share('errors', new ViewErrorBag);

    expect(trim(Blade::render('<x-artisanpack-errors />')))->toBe('');
});

test('removed 2.x props do not leak as HTML attributes', function (): void {
    $html = Blade::render('<x-artisanpack-errors description="Please fix the following." icon="o-bolt" />');

    expect($html)->not->toContain('description')
        ->and($html)->not->toContain('Please fix the following.')
        ->and($html)->not->toContain('o-bolt');
});
