<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

test('the Boost guidelines describe 3.0, not DaisyUI or 2.x tokens', function (): void {
    $dir = dirname(__DIR__, 2).'/resources/boost/guidelines';

    expect(glob($dir.'/*.blade.php'))->toBe([$dir.'/core.blade.php']);

    $guide = Blade::render((string) file_get_contents($dir.'/core.blade.php'));

    expect($guide)->toContain('<x-artisanpack-dialog')
        ->toContain('@apuiThemeScript')
        ->toContain('<x-artisanpack-toaster />')
        ->toContain('artisanpack:generate-preset')
        ->toContain('`fas.user`')
        ->not->toContain('70+')
        ->not->toContain('daisyUI color')
        ->not->toContain('glass-tokens')
        ->not->toContain('artisanpack-artisanpack')
        ->not->toContain('gitlab.com')
        ->not->toMatch('/icon="fa-|leading-icon="o-/');
});
