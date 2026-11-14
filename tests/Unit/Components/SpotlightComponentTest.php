<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Spotlight (#57): the design-system markup contract (D-019), ids, the
 * groups, the shortcut, the translated strings and the url endpoint. The
 * manifest parity check is in tests/Feature/DesignSystemParityTest.php.
 */

function spotlightGroups(): array
{
    return [
        [
            'id'    => 'pages',
            'label' => 'Pages',
            'items' => [
                ['label' => 'Reports', 'href' => '/reports', 'icon' => 'file-text', 'description' => 'Monthly', 'keywords' => ['analytics']],
                ['value' => 'settings', 'label' => 'Settings', 'icon' => 'settings', 'color' => 'primary'],
            ],
        ],
        [
            'id'    => 'actions',
            'label' => 'Actions',
            'items' => [
                ['value' => 'add', 'label' => 'Add property', 'shortcut' => '⌘ N'],
                ['value'       => 'archive', 'label' => 'Archive', 'disabled' => true],
                ['description' => 'No label, dropped'],
            ],
        ],
    ];
}

function spotlightRender(string $attributes = '', array $groups = []): DOMXPath
{
    return designSystemDom(Blade::render(
        '<x-artisanpack-spotlight id="s" :groups="$groups" :portal="false" '.$attributes.' />',
        ['groups' => [] === $groups ? spotlightGroups() : $groups],
    ));
}

function spotlightPanel(DOMXPath $dom): DOMElement
{
    return $dom->query('//div[contains(@class, "apui-spotlight")]')->item(0);
}

test('the fixture renders every part in order with stable ids', function (): void {
    $dom = spotlightRender('trigger="Search" open');

    $parts = [];
    foreach ($dom->query('//*[@data-scope="spotlight"][@id][not(@data-part="item")]') as $element) {
        $parts[$element->getAttribute('id')] = $element->getAttribute('data-part');
    }

    expect($parts)->toBe([
        's-trigger'              => 'trigger',
        's-backdrop'             => 'backdrop',
        's-positioner'           => 'positioner',
        's-content'              => 'content',
        's-search'               => 'search',
        's-icon'                 => 'icon',
        's-query'                => 'query',
        's-close-trigger'        => 'close-trigger',
        's-results'              => 'results',
        's-group-pages'          => 'item-group',
        's-group-pages-label'    => 'item-group-label',
        's-group-actions'        => 'item-group',
        's-group-actions-label'  => 'item-group-label',
        's-empty'                => 'empty',
        's-loading'              => 'loading',
        's-footer'               => 'footer',
    ]);

    $panel = spotlightPanel($dom);
    expect($panel->getAttribute('data-part'))->toBe('content')
        ->and($panel->getAttribute('role'))->toBe('dialog')
        ->and($panel->getAttribute('aria-label'))->toBe('Search')
        ->and($panel->getAttribute('aria-modal'))->toBe('true')
        ->and($panel->getAttribute('data-state'))->toBe('open')
        ->and($panel->hasAttribute('hidden'))->toBeFalse()
        ->and($panel->hasAttribute('x-apui:content'))->toBeTrue();
});

test('the query is a combobox controlling the results listbox', function (): void {
    $dom     = spotlightRender();
    $query   = $dom->query('//input[@data-part="query"]')->item(0);
    $results = $dom->query('//*[@data-part="results"]')->item(0);

    expect($query->getAttribute('role'))->toBe('combobox')
        ->and($query->getAttribute('aria-autocomplete'))->toBe('list')
        ->and($query->getAttribute('aria-controls'))->toBe('s-results')
        ->and($query->getAttribute('aria-label'))->toBe('Search')
        ->and($query->getAttribute('placeholder'))->toBe('Search…')
        ->and($query->getAttribute('aria-keyshortcuts'))->toBe('Meta+K Control+K')
        ->and($results->getAttribute('role'))->toBe('listbox')
        ->and($results->hasAttribute('aria-busy'))->toBeFalse();
});

test('groups render labelled groups of options; items without a label are dropped', function (): void {
    $dom = spotlightRender();

    $group = $dom->query('//*[@id="s-group-pages"]')->item(0);
    expect($group->getAttribute('role'))->toBe('group')
        ->and($group->getAttribute('aria-labelledby'))->toBe('s-group-pages-label')
        ->and($dom->query('//*[@id="s-group-pages-label"]')->item(0)->textContent)->toBe('Pages');

    $values = [];
    foreach ($dom->query('//*[@data-part="item"][not(ancestor::template)]') as $item) {
        $values[] = $item->getAttribute('data-value');
        expect($item->getAttribute('role'))->toBe('option')
            ->and($item->getAttribute('aria-selected'))->toBe('false')
            ->and($item->getAttribute('wire:key'))->toBe('s-item-'.$item->getAttribute('data-value'));
    }
    expect($values)->toBe(['/reports', 'settings', 'add', 'archive']);
});

test('items render their link, tile, description, color, shortcut and disabled state', function (): void {
    $dom = spotlightRender('navigate');

    $link = $dom->query('//a[@data-part="item"][not(ancestor::template)]')->item(0);
    expect($link->getAttribute('href'))->toBe('/reports')
        ->and($link->hasAttribute('wire:navigate'))->toBeTrue()
        ->and($link->getAttribute('x-apui:item'))->toContain('analytics')
        ->and($dom->query('.//*[@data-part="tile"]/*[local-name()="svg"]', $link))->toHaveCount(1)
        ->and($dom->query('.//*[@data-part="item-text"]', $link)->item(0)->textContent)->toBe('ReportsMonthly')
        ->and($dom->query('.//*[@data-part="item-description"]', $link)->item(0)->textContent)->toBe('Monthly');

    expect($dom->query('//*[@data-value="settings"]')->item(0)->getAttribute('data-color'))->toBe('primary');

    $keys = [];
    foreach ($dom->query('//*[@data-value="add"]//*[@data-part="shortcut"]/kbd') as $kbd) {
        $keys[] = $kbd->textContent;
    }
    expect($keys)->toBe(['⌘', 'N']);

    $archive = $dom->query('//*[@data-value="archive"]')->item(0);
    expect($archive->getAttribute('aria-disabled'))->toBe('true')
        ->and($archive->hasAttribute('data-disabled'))->toBeTrue();
});

test('internal link items use wire:navigate by default, and navigate off turns it off', function (): void {
    expect(Blade::render('<x-artisanpack-spotlight id="s" :groups="$groups" :portal="false" />', ['groups' => spotlightGroups()]))->toContain('wire:navigate')
        ->and(Blade::render('<x-artisanpack-spotlight id="s" :groups="$groups" :portal="false" :navigate="false" />', ['groups' => spotlightGroups()]))->not->toContain('wire:navigate');
});

test('an open palette starts on its first enabled result, as the machine does', function (): void {
    $dom    = spotlightRender('open');
    $active = $dom->query('//*[@data-part="item"][@data-highlighted][not(ancestor::template)]');

    expect($active)->toHaveCount(1)
        ->and($active->item(0)->getAttribute('data-value'))->toBe('/reports')
        ->and($active->item(0)->getAttribute('aria-selected'))->toBe('true')
        ->and($active->item(0)->getAttribute('id'))->toBe('combobox:s:option:/reports')
        ->and($dom->query('//input[@data-part="query"]')->item(0)->getAttribute('aria-activedescendant'))->toBe('combobox:s:option:/reports');

    $closed = spotlightRender();
    expect($closed->query('//*[@data-highlighted]'))->toHaveCount(0)
        ->and($closed->query('//input[@data-part="query"]')->item(0)->hasAttribute('aria-activedescendant'))->toBeFalse();
});

test('a closed palette still renders its panel, hidden', function (): void {
    $dom = spotlightRender();

    expect(spotlightPanel($dom)->getAttribute('data-state'))->toBe('closed')
        ->and(spotlightPanel($dom)->hasAttribute('hidden'))->toBeTrue()
        ->and($dom->query('//*[@data-part="backdrop"]')->item(0)->hasAttribute('hidden'))->toBeTrue();
});

test('the trigger is an outline neutral Button showing the shortcut keys', function (): void {
    $trigger = spotlightRender('trigger="Search"')->query('//button[@data-part="trigger"]')->item(0);

    expect($trigger->getAttribute('class'))->toContain('apui-button')
        ->and($trigger->getAttribute('data-variant'))->toBe('outline')
        ->and($trigger->getAttribute('data-color'))->toBe('neutral')
        ->and($trigger->getAttribute('aria-haspopup'))->toBe('dialog')
        ->and($trigger->getAttribute('aria-controls'))->toBe('s-content')
        ->and($trigger->getAttribute('aria-keyshortcuts'))->toBe('Meta+K Control+K')
        ->and($trigger->textContent)->toContain('Search')
        ->and($trigger->textContent)->toContain('CtrlK');
});

test('the shortcut can change or be turned off', function (): void {
    $custom = spotlightRender('shortcut="ctrl+shift+p"')->query('//input[@data-part="query"]')->item(0);
    $off    = designSystemDom(Blade::render('<x-artisanpack-spotlight id="s" shortcut="" trigger="Go" />'));

    expect($custom->getAttribute('aria-keyshortcuts'))->toBe('Control+Shift+P')
        ->and($off->query('//input[@data-part="query"]')->item(0)->hasAttribute('aria-keyshortcuts'))->toBeFalse()
        ->and($off->query('//button[@data-part="trigger"]')->item(0)->hasAttribute('aria-keyshortcuts'))->toBeFalse()
        // The Esc button keeps its own key.
        ->and($off->query('//button[@data-part="close-trigger"]')->item(0)->getAttribute('aria-keyshortcuts'))->toBe('Escape');
});

test('the empty state shows only when nothing renders, and loading marks the results busy', function (): void {
    $withItems = spotlightRender();
    $none      = designSystemDom(Blade::render('<x-artisanpack-spotlight id="s" :groups="[[ \'items\' => [] ]]" :portal="false" />'));
    $loading   = spotlightRender('loading');

    expect($withItems->query('//*[@data-part="empty"]')->item(0)->hasAttribute('hidden'))->toBeTrue()
        ->and($none->query('//*[@data-part="empty"]')->item(0)->hasAttribute('hidden'))->toBeFalse()
        ->and($none->query('//*[@data-part="empty"]')->item(0)->textContent)->toBe('No results.')
        ->and($loading->query('//*[@data-part="results"]')->item(0)->getAttribute('aria-busy'))->toBe('true')
        ->and($loading->query('//*[@data-part="loading"]')->item(0)->hasAttribute('hidden'))->toBeFalse()
        ->and($loading->query('//*[@data-part="empty"]')->item(0)->hasAttribute('hidden'))->toBeTrue();
});

test('the empty slot replaces the empty text', function (): void {
    $html = Blade::render('<x-artisanpack-spotlight id="s" :portal="false"><x-slot:empty>Try another word</x-slot:empty></x-artisanpack-spotlight>');

    expect(designSystemDom($html)->query('//*[@data-part="empty"]')->item(0)->textContent)->toBe('Try another word');
});

test('built-in strings are translated, and props override them', function (): void {
    app('translator')->addLines([
        '*.Search'   => 'Rechercher',
        '*.Close'    => 'Fermer',
        '*.Navigate' => 'Naviguer',
        '*.Open'     => 'Ouvrir',
    ], 'fr');
    app()->setLocale('fr');

    $dom = spotlightRender();
    expect(spotlightPanel($dom)->getAttribute('aria-label'))->toBe('Rechercher')
        ->and($dom->query('//*[@data-part="close-trigger"]')->item(0)->getAttribute('aria-label'))->toBe('Esc, Fermer')
        ->and($dom->query('//*[@data-part="footer"]')->item(0)->textContent)->toContain('Naviguer')
        ->and($dom->query('//*[@data-part="footer"]')->item(0)->textContent)->toContain('Ouvrir');

    app()->setLocale('en');

    $custom = spotlightRender('label="Find" placeholder="Find anything" close-label="Dismiss" empty-label="Nothing" loading-label="Wait" navigate-label="Move" select-label="Go"');
    expect(spotlightPanel($custom)->getAttribute('aria-label'))->toBe('Find')
        ->and($custom->query('//input[@data-part="query"]')->item(0)->getAttribute('placeholder'))->toBe('Find anything')
        ->and($custom->query('//*[@data-part="close-trigger"]')->item(0)->getAttribute('aria-label'))->toBe('Esc, Dismiss')
        ->and($custom->query('//*[@data-part="footer"]')->item(0)->textContent)->toContain('Move')
        ->and($custom->query('//*[@data-part="empty"]')->item(0)->textContent)->toBe('Nothing');
});

test('the footer prop replaces the key hints', function (): void {
    $footer = spotlightRender('footer="Tip: type a page name"')->query('//*[@data-part="footer"]')->item(0);

    expect(trim($footer->textContent))->toBe('Tip: type a page name');
});

test('the x-data carries the options and entangles wire:model', function (): void {
    $html = Blade::render('<x-artisanpack-spotlight wire:model="showSpotlight" shortcut="ctrl+k" recent remote x-on:select="go($event.detail)" />');
    $host = designSystemDom($html)->query('//div[@x-data]')->item(0);
    $data = html_entity_decode($host->getAttribute('x-data'));

    expect($data)->toStartWith('apuiSpotlight(')
        ->and($data)->toContain("open: \$wire.entangle('showSpotlight')")
        ->and($data)->toContain('ctrl+k')
        ->and($data)->toContain(':count result|:count results')
        ->and($host->getAttribute('x-on:select'))->toBe('go($event.detail)')
        ->and($host->hasAttribute('wire:model'))->toBeFalse();
});

test('url defaults to the package route without groups, and stays off with groups', function (): void {
    $options = static function (string $html): array {
        preg_match("/JSON\\.parse\\('(.*?)'\\)/", html_entity_decode($html), $match);

        return json_decode(json_decode('"'.($match[1] ?? '').'"'), true) ?? [];
    };

    expect($options(Blade::render('<x-artisanpack-spotlight id="s" />'))['url'] ?? null)->toBe('/artisanpack/spotlight')
        ->and($options(Blade::render('<x-artisanpack-spotlight id="s" url="/search" />'))['url'] ?? null)->toBe('/search')
        ->and($options(Blade::render('<x-artisanpack-spotlight id="s" :groups="$groups" />', ['groups' => spotlightGroups()])))->not->toHaveKey('url')
        ->and($options(Blade::render('<x-artisanpack-spotlight id="s" remote />')))->not->toHaveKey('url');
});

test('portal teleports the palette to the body; off renders it in place', function (): void {
    expect(Blade::render('<x-artisanpack-spotlight :groups="[]" />'))->toContain('<template x-teleport="body">')
        ->and(Blade::render('<x-artisanpack-spotlight :groups="[]" :portal="false" />'))->not->toContain('x-teleport');
});

test('recent and url results render on the client, outside the morph', function (): void {
    $html = Blade::render('<x-artisanpack-spotlight id="s" :portal="false" recent />');

    expect($html)->toContain("clientGroups( 'recent' )")
        ->and($html)->toContain("clientGroups( 'remote' )")
        ->and(substr_count($html, 'wire:ignore'))->toBe(2);

    // Recent choices live in localStorage, so they're opt-in.
    expect(Blade::render('<x-artisanpack-spotlight id="s" />'))->not->toContain("clientGroups( 'recent' )");
});

test('classes land on their parts and class on the panel', function (): void {
    $dom = spotlightRender('class="extra" :classes="[ \'results\' => \'r-x\', \'item\' => \'i-x\' ]"');

    expect(spotlightPanel($dom)->getAttribute('class'))->toContain('extra')
        ->and($dom->query('//*[@data-part="results"]')->item(0)->getAttribute('class'))->toBe('r-x')
        ->and($dom->query('//*[@data-part="item"][not(ancestor::template)]')->item(0)->getAttribute('class'))->toBe('i-x');
});

test('ids derive from the props, not the text, and repeats are numbered', function (): void {
    $id = static function (string $html): string {
        preg_match('/id="(apui-spotlight-[a-f0-9]{12}(?:-\\d+)?)"/', $html, $match);

        return $match[1] ?? '';
    };

    $first  = $id(Blade::render('<x-artisanpack-spotlight trigger="Go" />'));
    $second = $id(Blade::render('<x-artisanpack-spotlight trigger="Open" />'));

    expect($first)->toMatch('/^apui-spotlight-[a-f0-9]{12}$/')
        ->and($second)->toBe($first.'-2');
});

test('each label sits in a span the client marks matches in, and each item ends with the Enter glyph', function (): void {
    $dom  = spotlightRender();
    $item = $dom->query('//*[@data-value="settings"][not(ancestor::template)]')->item(0);

    expect($dom->query('.//*[@data-apui-label]', $item)->item(0)->textContent)->toBe('Settings');

    $indicator = $dom->query('.//*[@data-part="item-indicator"]', $item)->item(0);
    expect($indicator->getAttribute('aria-hidden'))->toBe('true')
        ->and($dom->query('./*[local-name()="svg"]', $indicator))->toHaveCount(1)
        ->and(XML_ELEMENT_NODE === $item->lastChild->nodeType ? $item->lastChild : $indicator)->toBe($indicator);
});

test('the 2.x prop names still work as deprecated aliases', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-spotlight id="s" :groups="[]" :portal="false" search-text="Find anything" no-results-text="Nothing here" />'));

    expect($dom->query('//input[@data-part="query"]')->item(0)->getAttribute('placeholder'))->toBe('Find anything')
        ->and($dom->query('//*[@data-part="empty"]')->item(0)->textContent)->toBe('Nothing here');

    $both = designSystemDom(Blade::render('<x-artisanpack-spotlight id="s" :portal="false" placeholder="New" search-text="Old" empty-text="New empty" no-results-text="Old empty" />'));
    expect($both->query('//input[@data-part="query"]')->item(0)->getAttribute('placeholder'))->toBe('New')
        ->and($both->query('//*[@data-part="empty"]')->item(0)->textContent)->toBe('New empty');
});

test('the 2.x append slot fills the footer, and the footer slot wins', function (): void {
    $append = designSystemDom(Blade::render('<x-artisanpack-spotlight id="s" :portal="false"><x-slot:append>Press Esc to close</x-slot:append></x-artisanpack-spotlight>'));
    $both   = designSystemDom(Blade::render('<x-artisanpack-spotlight id="s" :portal="false"><x-slot:append>Old</x-slot:append><x-slot:footer>New</x-slot:footer></x-artisanpack-spotlight>'));

    expect(trim($append->query('//*[@data-part="footer"]')->item(0)->textContent))->toBe('Press Esc to close')
        ->and(trim($both->query('//*[@data-part="footer"]')->item(0)->textContent))->toBe('New');
});

test('the 2.x shortcut spelling still reads, following the platform', function (string $legacy, string $current, string $aria): void {
    $component = new ArtisanPack\LivewireUiComponents\View\Components\Spotlight(shortcut: $legacy);

    expect($component->normalizedShortcut())->toBe($current)
        ->and($component->ariaKeyShortcuts())->toBe($aria);
})->with([
    'meta.g'       => ['meta.g', 'mod+g', 'Meta+G Control+G'],
    'cmd.k'        => ['cmd.k', 'mod+k', 'Meta+K Control+K'],
    'ctrl.shift.p' => ['ctrl.shift.p', 'ctrl+shift+p', 'Control+Shift+P'],
    'mod+k'        => ['mod+k', 'mod+k', 'Meta+K Control+K'],
    'slash'        => ['/', '/', '/'],
]);

test('the 2.x and pre-release label names still work as deprecated aliases of emptyLabel and loadingLabel', function (): void {
    $render = static fn (string $attributes): DOMXPath => designSystemDom(Blade::render('<x-artisanpack-spotlight id="s" :groups="[]" :portal="false" loading '.$attributes.' />'));
    $empty  = static fn (DOMXPath $dom): string => $dom->query('//*[@data-part="empty"]')->item(0)->textContent;
    $wait   = static fn (DOMXPath $dom): string => $dom->query('//*[@data-part="loading"]')->item(0)->textContent;

    expect($empty($render('empty-text="A"')))->toBe('A')
        ->and($empty($render('no-results-text="B"')))->toBe('B')
        ->and($empty($render('empty-label="C" empty-text="A" no-results-text="B"')))->toBe('C')
        ->and($wait($render('loading-text="D"')))->toBe('D')
        ->and($wait($render('loading-label="E" loading-text="D"')))->toBe('E');
});

test('the Esc button name is one translatable string', function (): void {
    app('translator')->addLines(['*.Esc, :label' => ':label (Échap)'], 'fr');
    app()->setLocale('fr');

    $name = spotlightRender('close-label="Fermer"')->query('//*[@data-part="close-trigger"]')->item(0)->getAttribute('aria-label');

    app()->setLocale('en');

    expect($name)->toBe('Fermer (Échap)');
});

test('unsafe links are dropped: the item renders as a plain option', function (): void {
    $dom = spotlightRender('', [[
        'label' => 'Links',
        'items' => [
            ['value' => 'evil', 'label' => 'Evil', 'href' => 'javascript:alert(1)'],
            ['value' => 'spaced', 'label' => 'Spaced', 'link' => ' JavaScript:alert(1)'],
            ['value' => 'ok', 'label' => 'OK', 'href' => '/reports'],
        ],
    ]]);

    $evil = $dom->query('//*[@data-value="evil"][not(ancestor::template)]')->item(0);
    expect($evil->tagName)->toBe('div')
        ->and($evil->hasAttribute('href'))->toBeFalse()
        ->and($evil->getAttribute('x-apui:item'))->not->toContain('javascript')
        ->and($dom->query('//*[@data-value="spaced"][not(ancestor::template)]')->item(0)->tagName)->toBe('div')
        ->and($dom->query('//a[@data-value="ok"]')->item(0)->getAttribute('href'))->toBe('/reports')
        ->and(Blade::render('<x-artisanpack-spotlight id="s" :groups="$groups" />', ['groups' => [['items' => [['label' => 'X', 'href' => 'javascript:alert(1)']]]]]))->not->toContain('javascript:');
});
