<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\Toasts;
use Illuminate\Support\Facades\Blade;

/*
 * Toaster (#51): the region that shows toasts from data (D-019).
 */

/**
 * The options the toaster passes to apuiToaster().
 *
 * @return array<string, mixed>
 */
function toasterOptions(string $html): array
{
    $xData = designSystemDom($html)->query('//div[@*[name()="wire:ignore"]]')->item(0)->getAttribute('x-data');
    preg_match("/^apuiToaster\\(JSON\\.parse\\('(.*)'\\)\\)$/s", $xData, $match);

    return json_decode(json_decode('"'.$match[1].'"'), true);
}

test('the region is wire:ignore with a stable id and the default options', function (): void {
    $html  = Blade::render('<x-artisanpack-toaster />');
    $dom   = designSystemDom($html);
    $host  = $dom->query('//div[@*[name()="wire:ignore"]]')->item(0);
    $group = $dom->query('//div[@*[name()="x-apui:group"]]')->item(0);

    expect($host->getAttribute('id'))->toBe('apui-toaster')
        ->and($group->getAttribute('id'))->toBe('apui-toaster-group')
        ->and(toasterOptions($html))->toBe([
            'id'         => 'apui-toaster',
            'placement'  => 'bottom-end',
            'max'        => 5,
            'gap'        => 8,
            'duration'   => 5000,
            'label'      => 'Notifications',
            'closeLabel' => 'Dismiss notification',
        ]);
});

test('the options render as given, with an unknown placement falling back', function (): void {
    expect(toasterOptions(Blade::render('<x-artisanpack-toaster id="notes" placement="top-start" :max="3" :gap="12" :duration="0" />')))
        ->toMatchArray(['id' => 'notes', 'placement' => 'top-start', 'max' => 3, 'gap' => 12, 'duration' => 0])
        ->and(toasterOptions(Blade::render('<x-artisanpack-toaster placement="middle" />'))['placement'])->toBe('bottom-end');
});

test('the labels are translated by default and can be overridden', function (): void {
    app('translator')->addLines(['*.Notifications' => 'Notifications FR', '*.Dismiss notification' => 'Fermer'], 'fr');
    app()->setLocale('fr');

    $html = Blade::render('<x-artisanpack-toaster />');

    expect(toasterOptions($html))->toMatchArray(['label' => 'Notifications FR', 'closeLabel' => 'Fermer'])
        ->and(designSystemDom($html)->query('//button[@*[name()="x-apui:close-trigger"]]')->item(0)->getAttribute('aria-label'))->toBe('Fermer')
        ->and(toasterOptions(Blade::render('<x-artisanpack-toaster label="Alerts" close-label="Hide" />')))->toMatchArray(['label' => 'Alerts', 'closeLabel' => 'Hide']);

    app()->setLocale('en');
});

test('the template renders the toast parts the machine binds', function (): void {
    $html = Blade::render('<x-artisanpack-toaster />');

    expect($html)->toContain('<template x-for="(entry, index) in toasts" :key="entry.id">')
        ->and($html)->toContain('<div x-data="apuiToast( entry, index )" x-apui:root class="apui-toast">')
        ->and($html)->toContain('<span x-apui:icon x-html="icon"></span>')
        ->and($html)->toContain('<p x-apui:title x-text="entry.title"></p>')
        ->and($html)->toContain('<p x-apui:description x-text="entry.description"></p>')
        ->and($html)->toContain('x-apui:actions')
        ->and($html)->toContain('x-apui:action-trigger')
        ->and($html)->toContain('x-apui:close-trigger=""');
});

/**
 * The flashed toasts the toaster renders for the client to read once.
 *
 * @return list<array<string, mixed>>|null
 */
function flashedToasts(string $html): ?array
{
    $script = designSystemDom($html)->query('//script[@data-apui-flashed-toasts]')->item(0);

    return null === $script ? null : json_decode($script->textContent, true);
}

test('flashed toasts render once, outside the persisted region, for the client to read and remove', function (): void {
    Toasts::flash(title: 'Saved </script><script>alert(1)</script>', color: 'success');

    $html = Blade::render('<x-artisanpack-toaster />');

    expect(flashedToasts($html))->toBe([['title' => 'Saved </script><script>alert(1)</script>', 'color' => 'success']])
        ->and($html)->not->toContain('</script><script>alert(1)')
        ->and(designSystemDom($html)->query('//*[@x-persist]//script[@data-apui-flashed-toasts]'))->toHaveCount(0)
        ->and(designSystemDom(Blade::render('<x-artisanpack-toaster />'))->query('//script[@data-apui-flashed-toasts]'))->toHaveCount(0);
});

test('the region is persisted across wire:navigate', function (): void {
    $dom     = designSystemDom(Blade::render('<x-artisanpack-toaster />'));
    $persist = $dom->query('//*[@x-persist]')->item(0);

    expect($persist->getAttribute('x-persist'))->toBe('apui-toaster-apui-toaster')
        ->and($dom->query('//*[@x-persist]/div[@id="apui-toaster"]'))->toHaveCount(1)
        ->and($dom->query('//div[@id="apui-toaster"]//script'))->toHaveCount(1);
});

test('the close button always renders; the machine hides it for hideClose toasts', function (): void {
    expect(Blade::render('<x-artisanpack-toaster />'))->not->toContain('entry.closable');
});

test('the ToastException handler script renders once per page', function (): void {
    $html = Blade::render('<x-artisanpack-toaster /><x-artisanpack-toaster id="second" />');

    expect(substr_count($html, 'window.apuiToastExceptionHook = true'))->toBe(1);
});
