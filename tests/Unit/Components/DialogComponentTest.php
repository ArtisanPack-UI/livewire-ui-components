<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Dialog;
use Illuminate\Support\Facades\Blade;

/*
 * Dialog (#49): the design-system markup contract (D-019). The manifest
 * parity check is in tests/Feature/DesignSystemParityTest.php.
 */

function dialogPanel(DOMXPath $dom): DOMElement
{
    return $dom->query('//div[contains(@class, "apui-dialog")]')->item(0);
}

test('the fixture renders every part in order with stable ids', function (): void {
    $dom = designSystemDom(Blade::render(
        '<x-artisanpack-dialog id="invite" trigger="Invite" title="Invite member" description="Members can edit every listing." icon="user" footer="Send invite" :portal="false">They\'ll get an email.</x-artisanpack-dialog>',
    ));

    $parts = [];
    foreach ($dom->query('//*[@data-scope="dialog"]') as $element) {
        $parts[$element->getAttribute('data-part')] = $element->getAttribute('id');
    }

    expect($parts)->toBe([
        'trigger'       => 'invite-trigger',
        'backdrop'      => 'invite-backdrop',
        'positioner'    => 'invite-positioner',
        'content'       => 'invite-content',
        'header'        => 'invite-header',
        'icon'          => 'invite-icon',
        'title'         => 'invite-title',
        'description'   => 'invite-description',
        'close-trigger' => 'invite-close-trigger',
        'body'          => 'invite-body',
        'footer'        => 'invite-footer',
    ]);

    $panel = dialogPanel($dom);
    expect($panel->getAttribute('role'))->toBe('dialog')
        ->and($panel->getAttribute('aria-modal'))->toBe('true')
        ->and($panel->getAttribute('aria-labelledby'))->toBe('invite-title')
        ->and($panel->getAttribute('aria-describedby'))->toBe('invite-description')
        ->and($panel->getAttribute('tabindex'))->toBe('-1')
        ->and($panel->getAttribute('data-size'))->toBe('md')
        ->and($panel->getAttribute('data-surface'))->toBe('overlay')
        ->and($panel->hasAttribute('data-color'))->toBeFalse()
        ->and($panel->hasAttribute('x-apui:content'))->toBeTrue()
        ->and($dom->query('//h2[@data-part="title"]')->item(0)->textContent)->toBe('Invite member')
        ->and($dom->query('//p[@data-part="description"]')->item(0)->textContent)->toBe('Members can edit every listing.')
        ->and($dom->query('//*[@data-part="body"]')->item(0)->textContent)->toBe('They\'ll get an email.')
        ->and($dom->query('//*[@data-part="footer"]')->item(0)->textContent)->toBe('Send invite');
});

test('a closed dialog still renders its panel, hidden', function (): void {
    $dom   = designSystemDom(Blade::render('<x-artisanpack-dialog title="Edit" :portal="false" />'));
    $panel = dialogPanel($dom);

    expect($panel)->not->toBeNull()
        ->and($panel->getAttribute('data-state'))->toBe('closed')
        ->and($panel->hasAttribute('hidden'))->toBeTrue()
        ->and($dom->query('//*[@data-part="backdrop"]')->item(0)->hasAttribute('hidden'))->toBeTrue();
});

test('an open dialog renders open on the server', function (): void {
    $dom   = designSystemDom(Blade::render('<x-artisanpack-dialog title="Edit" trigger="Edit" open :portal="false" />'));
    $panel = dialogPanel($dom);

    expect($panel->getAttribute('data-state'))->toBe('open')
        ->and($panel->hasAttribute('hidden'))->toBeFalse()
        ->and($dom->query('//*[@data-part="trigger"]')->item(0)->getAttribute('aria-expanded'))->toBe('true')
        ->and($dom->query('//*[@data-part="backdrop"]')->item(0)->hasAttribute('hidden'))->toBeFalse();
});

test('the trigger is an outline neutral Button that controls the panel', function (): void {
    $dom     = designSystemDom(Blade::render('<x-artisanpack-dialog id="d" trigger="Invite" title="Invite" :portal="false" />'));
    $trigger = $dom->query('//button[@data-part="trigger"]')->item(0);

    expect($trigger->getAttribute('class'))->toContain('apui-button')
        ->and($trigger->getAttribute('data-variant'))->toBe('outline')
        ->and($trigger->getAttribute('data-color'))->toBe('neutral')
        ->and($trigger->getAttribute('type'))->toBe('button')
        ->and($trigger->getAttribute('aria-haspopup'))->toBe('dialog')
        ->and($trigger->getAttribute('aria-controls'))->toBe('d-content')
        ->and($trigger->getAttribute('x-apui:trigger'))->toBe('')
        ->and($trigger->textContent)->toContain('Invite');
});

test('without trigger there is no trigger part', function (): void {
    expect(designSystemDom(Blade::render('<x-artisanpack-dialog title="Edit" />'))->query('//*[@data-part="trigger"]'))->toHaveCount(0);
});

test('the close button is a ghost, neutral, small icon-only Button with a translated name', function (): void {
    app('translator')->addLines(['*.Close' => 'Fermer'], 'fr');
    app()->setLocale('fr');

    $close = designSystemDom(Blade::render('<x-artisanpack-dialog title="Edit" :portal="false" />'))->query('//button[@data-part="close-trigger"]')->item(0);

    expect($close->getAttribute('aria-label'))->toBe('Fermer')
        ->and($close->getAttribute('data-variant'))->toBe('ghost')
        ->and($close->getAttribute('data-color'))->toBe('neutral')
        ->and($close->getAttribute('data-size'))->toBe('sm')
        ->and($close->hasAttribute('data-icon-only'))->toBeTrue()
        ->and($close->getElementsByTagName('svg'))->toHaveCount(1);

    app()->setLocale('en');
});

test('closeLabel names the close button', function (): void {
    $close = designSystemDom(Blade::render('<x-artisanpack-dialog title="Edit" close-label="Cancel" />'))->query('//button[@data-part="close-trigger"]')->item(0);

    expect($close->getAttribute('aria-label'))->toBe('Cancel');
});

test('hideClose drops the close button', function (): void {
    expect(designSystemDom(Blade::render('<x-artisanpack-dialog title="Edit" hide-close />'))->query('//*[@data-part="close-trigger"]'))->toHaveCount(0);
});

test('size, surface, color and role render on the panel', function (): void {
    $panel = dialogPanel(designSystemDom(Blade::render('<x-artisanpack-dialog title="Delete?" size="sm" surface="glass" color="error" role="alertdialog" />')));

    expect($panel->getAttribute('data-size'))->toBe('sm')
        ->and($panel->getAttribute('data-surface'))->toBe('glass')
        ->and($panel->getAttribute('data-color'))->toBe('error')
        ->and($panel->getAttribute('role'))->toBe('alertdialog');
});

test('a non-modal dialog drops aria-modal and tells the machine', function (): void {
    $html = Blade::render('<x-artisanpack-dialog id="d" title="Edit" :modal="false" />');

    expect(dialogPanel(designSystemDom($html))->hasAttribute('aria-modal'))->toBeFalse()
        ->and($html)->toContain('modal: false');
});

test('portal teleports the overlay to the body; off renders it in place', function (): void {
    $portal = Blade::render('<x-artisanpack-dialog title="Edit" />');
    $inline = Blade::render('<x-artisanpack-dialog title="Edit" :portal="false" />');

    expect($portal)->toContain('<template x-teleport="body">')
        ->and($inline)->not->toContain('x-teleport');
});

test('wire:model entangles the open state and leaves the host attributes alone', function (): void {
    $html = Blade::render('<x-artisanpack-dialog wire:model="showInvite" title="Invite" x-on:open-change="log($event.detail)" />');
    $host = designSystemDom($html)->query('//div[@x-data]')->item(0);

    expect($host->getAttribute('x-data'))->toContain("open: \$wire.entangle('showInvite')")
        ->and($host->getAttribute('x-data'))->not->toContain('.live')
        ->and($host->getAttribute('x-on:open-change'))->toBe('log($event.detail)')
        ->and($host->hasAttribute('wire:model'))->toBeFalse()
        ->and($host->getAttribute('id'))->toBe('apui-dialog-showinvite-'.substr(md5("\0showInvite"), 0, 8));
});

test('wire:model.live entangles live', function (): void {
    expect(Blade::render('<x-artisanpack-dialog wire:model.live="showInvite" title="Invite" />'))
        ->toContain('$wire.entangle(&#039;showInvite&#039;).live');
});

test('without wire:model, open is plain Alpine state', function (): void {
    expect(Blade::render('<x-artisanpack-dialog title="Edit" open />'))->toContain('open: true');
});

test('the id ignores open state and text, so a re-render keeps it', function (): void {
    $render = static function (string $blade): string {
        app()->forgetScopedInstances();

        return designSystemDom(Blade::render($blade))->query('//div[@x-data]')->item(0)->getAttribute('id');
    };

    $closed  = $render('<x-artisanpack-dialog title="Edit" />');
    $opened  = $render('<x-artisanpack-dialog title="Edit" open />');
    $renamed = $render('<x-artisanpack-dialog title="Rename" description="Changed" footer="Save" trigger="Open" />');
    $sized   = $render('<x-artisanpack-dialog title="Edit" size="lg" />');

    expect($closed)->toMatch('/^apui-dialog-[0-9a-f]{12}$/')
        ->and($opened)->toBe($closed)
        ->and($renamed)->toBe($closed)
        ->and($sized)->not->toBe($closed);
});

test('dialogs repeated in one render get numbered ids', function (): void {
    $html = Blade::render('@foreach ( [ 1, 2, 3 ] as $row )<x-artisanpack-dialog title="Edit" />@endforeach');
    $ids  = [];
    foreach (designSystemDom($html)->query('//div[@x-data]') as $host) {
        $ids[] = $host->getAttribute('id');
    }

    expect($ids)->toHaveCount(3)
        ->and($ids[1])->toBe($ids[0].'-2')
        ->and($ids[2])->toBe($ids[0].'-3')
        ->and($html)->toContain('id="'.$ids[2].'-content"');
});

test('class lands on the panel after classes.root, and part classes on their parts', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-dialog title="Edit" class="w-full" :classes="[\'root\' => \'shadow-none\', \'body\' => \'gap-2\', \'closeTrigger\' => \'ms-2\']">Body</x-artisanpack-dialog>'));

    expect(dialogPanel($dom)->getAttribute('class'))->toBe('apui-dialog shadow-none w-full')
        ->and($dom->query('//*[@data-part="body"]')->item(0)->getAttribute('class'))->toBe('gap-2')
        ->and($dom->query('//*[@data-part="close-trigger"]')->item(0)->getAttribute('class'))->toContain('ms-2');
});

test('named slots override the props', function (): void {
    $html = Blade::render(<<<'BLADE'
        <x-artisanpack-dialog :portal="false">
            <x-slot:trigger><strong>Open</strong></x-slot:trigger>
            <x-slot:icon><svg data-test="own"></svg></x-slot:icon>
            <x-slot:title>Rich <em>title</em></x-slot:title>
            <x-slot:description>Rich description</x-slot:description>
            <x-slot:footer><button>Save</button></x-slot:footer>
            Body
        </x-artisanpack-dialog>
        BLADE);
    $dom = designSystemDom($html);

    expect($dom->query('//*[@data-part="trigger"]//strong')->item(0)->textContent)->toBe('Open')
        ->and($dom->query('//*[@data-part="icon"]/svg[@data-test="own"]'))->toHaveCount(1)
        ->and($dom->query('//h2[@data-part="title"]/em')->item(0)->textContent)->toBe('title')
        ->and($dom->query('//*[@data-part="footer"]/button')->item(0)->textContent)->toBe('Save');
});

test('the title is escaped', function (): void {
    expect(Blade::render('<x-artisanpack-dialog :title="$title" />', ['title' => '<script>alert(1)</script>']))
        ->not->toContain('<script>alert')
        ->toContain('&lt;script&gt;');
});

test('the constructor declares only the manifest props', function (): void {
    $parameters = array_map(
        static fn (ReflectionParameter $parameter): string => $parameter->getName(),
        (new ReflectionClass(Dialog::class))->getConstructor()->getParameters(),
    );

    expect($parameters)->toBe(['open', 'size', 'role', 'surface', 'color', 'icon', 'title', 'description', 'footer', 'trigger', 'modal', 'portal', 'hideClose', 'closeLabel', 'classes']);
});
