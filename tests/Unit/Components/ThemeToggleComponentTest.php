<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\ThemeScript;
use ArtisanPack\LivewireUiComponents\View\Components\ThemeToggle;
use Illuminate\Support\Facades\Blade;

/*
 * ThemeToggle (#65): segmented radios, the icon button and the menu, the
 * blocking head snippet, and parity with the manifest. The shared parity
 * list is in tests/Feature/DesignSystemParityTest.php.
 */

function themeToggleDom(string $attributes = ''): DOMXPath
{
    return designSystemDom(Blade::render('<x-artisanpack-theme-toggle '.$attributes.' />'));
}

test('segmented is a radio group on the tabs track with system checked by default', function (): void {
    $dom    = themeToggleDom();
    $root   = $dom->query('//div[contains(@class, "apui-theme-toggle")]')->item(0);
    $list   = $dom->query('.//*[@data-scope="tabs"][@data-part="list"]', $root)->item(0);
    $radios = $dom->query('.//*[@role="radio"]', $list);

    expect($root->getAttribute('class'))->toContain('apui-tabs')
        ->and($root->getAttribute('data-variant'))->toBe('segmented')
        ->and($root->getAttribute('data-size'))->toBe('md')
        ->and($root->getAttribute('x-data'))->toBe("apuiThemeToggle({ value: null, choices: JSON.parse('[\\u0022light\\u0022,\\u0022dark\\u0022,\\u0022high-contrast\\u0022,\\u0022system\\u0022]'), persist: true, storageKey: 'apui-theme' })")
        ->and($list->getAttribute('role'))->toBe('radiogroup')
        ->and($list->getAttribute('aria-label'))->toBe('Theme')
        ->and($radios)->toHaveCount(4)
        ->and(array_map(static fn (DOMElement $radio): string => trim($radio->textContent), iterator_to_array($radios)))->toBe(['Light', 'Dark', 'High contrast', 'System'])
        ->and($dom->query('.//*[@role="radio"][@aria-checked="true"]', $list)->item(0)->getAttribute('data-value'))->toBe('system')
        ->and($dom->query('.//*[@role="radio"][@tabindex="0"]', $list))->toHaveCount(1)
        ->and($dom->query('.//*[@role="radio"]/*[@data-part="icon"][@aria-hidden="true"]/svg', $list))->toHaveCount(4);
});

test('value checks a choice and choices narrows them in the canonical order', function (): void {
    $dom = themeToggleDom('value="dark" :choices="[ \'system\', \'dark\', \'light\' ]"');

    expect(array_map(static fn (DOMElement $radio): string => $radio->getAttribute('data-value'), iterator_to_array($dom->query('//*[@role="radio"]'))))->toBe(['light', 'dark', 'system'])
        ->and($dom->query('//*[@role="radio"][@aria-checked="true"]')->item(0)->getAttribute('data-value'))->toBe('dark')
        ->and($dom->query('//*[@role="radio"][@tabindex="0"]')->item(0)->getAttribute('data-value'))->toBe('dark');
});

test('an unknown value falls back to system and an unoffered one keeps the first as the tab stop', function (): void {
    expect(themeToggleDom('value="sepia"')->query('//*[@aria-checked="true"]')->item(0)->getAttribute('data-value'))->toBe('system');

    $dom = themeToggleDom('value="high-contrast" :choices="[ \'light\', \'dark\' ]"');
    expect($dom->query('//*[@aria-checked="true"]'))->toHaveCount(0)
        ->and($dom->query('//*[@tabindex="0"]')->item(0)->getAttribute('data-value'))->toBe('light');
});

test('icon-only keeps each name visually hidden', function (): void {
    $dom = themeToggleDom('icon-only size="sm"');

    expect($dom->query('//div[@data-icon-only][@data-size="sm"]'))->toHaveCount(1)
        ->and($dom->query('//*[@role="radio"]/span[@class="apui-sr-only"]'))->toHaveCount(4)
        ->and(trim($dom->query('//*[@role="radio"][@data-value="high-contrast"]')->item(0)->textContent))->toBe('High contrast');
});

test('the radios bind their state and keys to the toggle', function (): void {
    $radio = themeToggleDom()->query('//*[@role="radio"][@data-value="light"]')->item(0);

    expect($radio->getAttribute('x-bind:aria-checked'))->toBe("checked( 'light' )")
        ->and($radio->getAttribute('x-bind:tabindex'))->toBe("tabStop( 'light' )")
        ->and($radio->getAttribute('x-on:click'))->toBe("choose( 'light' )")
        ->and(themeToggleDom()->query('//*[@role="radiogroup"]')->item(0)->getAttribute('x-on:keydown'))->toBe('keydown( $event )');
});

test('the icon variant is a ghost icon button named for what it does', function (): void {
    $dom    = themeToggleDom('variant="icon"');
    $button = $dom->query('//button[contains(@class, "apui-button")]')->item(0);

    expect($button->getAttribute('data-variant'))->toBe('ghost')
        ->and($button->getAttribute('data-color'))->toBe('neutral')
        ->and($button->hasAttribute('data-icon-only'))->toBeTrue()
        ->and($button->getAttribute('aria-label'))->toBe('Switch to dark theme')
        ->and($button->getAttribute('x-on:click'))->toBe('toggle()')
        ->and($dom->query('.//svg', $button))->toHaveCount(2)
        ->and($dom->query('.//svg[not(@style)]', $button)->item(0)->getAttribute('x-show'))->toBe("'dark' === nextTheme()");

    $dark = themeToggleDom('variant="icon" value="dark"')->query('//button')->item(0);
    expect($dark->getAttribute('aria-label'))->toBe('Switch to light theme');
});

test('the menu variant is a dropdown menu of radio items with system last', function (): void {
    $dom     = themeToggleDom('variant="menu" value="dark" size="sm"');
    $trigger = $dom->query('//button[@data-scope="dropdown-menu"][@data-part="trigger"]')->item(0);
    $items   = $dom->query('//*[@role="menuitemradio"]');

    expect($trigger->getAttribute('aria-haspopup'))->toBe('menu')
        ->and($trigger->getAttribute('data-variant'))->toBe('outline')
        ->and($trigger->getAttribute('data-size'))->toBe('sm')
        ->and(trim($trigger->textContent))->toBe('Theme')
        ->and($dom->query('.//*[@data-part="icon"]/svg[not(@style)]', $trigger)->item(0)->getAttribute('x-show'))->toBe("'dark' === current()")
        ->and($dom->query('//div[contains(@class, "apui-dropdown-menu")][@role="menu"][@hidden]'))->toHaveCount(1)
        ->and($items)->toHaveCount(4)
        ->and(trim($items->item(3)->textContent))->toBe('Match system')
        ->and($dom->query('//*[@role="menuitemradio"][@aria-checked="true"]')->item(0)->getAttribute('data-value'))->toBe('dark')
        ->and($dom->query('//hr[@data-part="separator"]/following-sibling::*[1]')->item(0)->getAttribute('data-value'))->toBe('system')
        ->and($items->item(0)->getAttribute('x-on:checked-change'))->toBe("choose( 'light' )")
        ->and($items->item(0)->getAttribute('id'))->toEndWith('-menu/light');
});

test('labels translate, and the 2.x light and dark labels still work', function (): void {
    $dom = themeToggleDom('label="Tema" light="Día" dark="Noche" system-label="Auto" light-theme="corporate" dark-class="dark"');

    expect($dom->query('//*[@role="radiogroup"]')->item(0)->getAttribute('aria-label'))->toBe('Tema')
        ->and(trim($dom->query('//*[@role="radio"][@data-value="light"]')->item(0)->textContent))->toBe('Día')
        ->and(trim($dom->query('//*[@role="radio"][@data-value="dark"]')->item(0)->textContent))->toBe('Noche')
        ->and(trim($dom->query('//*[@role="radio"][@data-value="system"]')->item(0)->textContent))->toBe('Auto')
        ->and($dom->query('//*[@light-theme or @dark-class or @light]'))->toHaveCount(0);
});

test('persist and the storage key reach the toggle', function (): void {
    $root = themeToggleDom(':persist="false" storage-key="site-theme"')->query('//div[@data-part="root"]')->item(0);

    expect($root->getAttribute('x-data'))->toContain('persist: false')
        ->and($root->getAttribute('x-data'))->toContain("storageKey: 'site-theme'");
});

test('only wire:model marks the value as bound', function (): void {
    $bound = themeToggleDom('wire:model="theme"')->query('//div[@data-part="root"]')->item(0)->getAttribute('x-data');
    $plain = themeToggleDom('value="dark"')->query('//div[@data-part="root"]')->item(0)->getAttribute('x-data');

    expect($bound)->toContain("\$wire.entangle('theme')")
        ->and($bound)->toContain(', bound: true })')
        ->and($plain)->not->toContain('bound');
});

test('it matches its manifest', function (): void {
    expect('ThemeToggle')->toMatchManifest([
        'ignoreEffects'  => ['matchSystemLabel', 'switchToLightLabel', 'switchToDarkLabel'],
        'ignoreDeclared' => ['open-change', 'select', 'checked-change'],
    ]);
});

test('the head snippet is the one behavior ships', function (): void {
    expect(ThemeScript::source())->toBe(
        '(function(){try{var c=null;try{c=localStorage.getItem("apui-theme")}catch(e){}'
        .'if(["light","dark","high-contrast","system"].indexOf(c)<0)c="system";'
        .'if(c==="system")c=window.matchMedia&&matchMedia("(prefers-color-scheme: dark)").matches?"dark":"light";'
        .'document.documentElement.setAttribute("data-theme",c)}catch(e){}})();',
    )
        ->and(ThemeScript::source('</script>', 'dark'))->toContain('getItem("\\u003c/script>")')
        ->and(ThemeScript::source('k', 'dark'))->toContain('c="dark";')
        ->and(ThemeScript::source('k', 'sepia'))->toContain('c="system";');
});

test('@apuiThemeScript prints the snippet in a script tag', function (): void {
    expect(Blade::render('@apuiThemeScript'))->toBe('<script>'.ThemeScript::source().'</script>')
        ->and(Blade::render("@apuiThemeScript([ 'storageKey' => 'site-theme', 'default' => 'dark' ])"))->toBe('<script>'.ThemeScript::source('site-theme', 'dark').'</script>');
});

test('the theme colors filter renames the choices', function (): void {
    addFilter('ap.livewireUiComponents.themeColors', function (array $themes): array {
        $themes['light']['label'] = 'Day';

        return $themes;
    });

    $toggle = (new ThemeToggle)->withAttributes([]);

    expect($toggle->labels()['light'])->toBe('Day')
        ->and($toggle->labels()['dark'])->toBe('Dark');

    removeAllFilters('ap.livewireUiComponents.themeColors');
});
