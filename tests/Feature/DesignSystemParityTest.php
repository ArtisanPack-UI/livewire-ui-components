<?php

declare(strict_types=1);

use ArtisanPackUI\DesignSystem\Testing\ManifestParity;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

/*
 * API parity with the design-system manifests (#41, D-016): every component
 * that ships in Livewire and is past `planned` renders its root, data
 * attributes, state evidence and parts, and its constructor declares exactly
 * the manifest's props.
 */

beforeEach(function (): void {
    // errorField reads $errors, so give the parity sample key a message.
    view()->share('errors', (new ViewErrorBag)->put('default', new MessageBag([
        'apui-parity-sample' => 'The sample field is invalid.',
    ])));
});

it('matches its manifest', function (string $component): void {
    expect($component)->toMatchManifest();
})->with(ManifestParity::targets());

it('checks the five pilot components', function (): void {
    expect(array_keys(ManifestParity::targets()))->toContain('Button', 'Input', 'Select', 'Checkbox', 'Card');
});
