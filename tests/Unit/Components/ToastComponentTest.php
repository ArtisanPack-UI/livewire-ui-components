<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\IconRegistry;
use ArtisanPack\LivewireUiComponents\View\Components\Toast;
use Illuminate\Support\Facades\Blade;

/*
 * Toast (#51), rendered on its own: a static toast with the attributes of
 * staticToastParts() in the behavior package. The manifest parity check is
 * in tests/Feature/DesignSystemParityTest.php.
 */

function toastRoot(DOMXPath $dom): DOMElement
{
    return $dom->query('//div[contains(@class, "apui-toast")]')->item(0);
}

test('the fixture renders the contract markup in order', function (): void {
    $html = Blade::render('<x-artisanpack-toast id="saved" title="Listing saved" description="Cedarwood Estates is live now." actions="Undo" />');
    $dom  = designSystemDom($html);
    $root = toastRoot($dom);

    $parts = [];
    foreach ($dom->query('//*[@data-scope="toast"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    expect($parts)->toBe(['root', 'icon', 'title', 'description', 'actions', 'close-trigger'])
        ->and($root->getAttribute('id'))->toBe('saved-root')
        ->and($root->getAttribute('data-state'))->toBe('open')
        ->and($root->getAttribute('data-type'))->toBe('info')
        ->and($root->getAttribute('role'))->toBe('status')
        ->and($root->getAttribute('aria-atomic'))->toBe('true')
        ->and($root->getAttribute('aria-labelledby'))->toBe('saved-title')
        ->and($root->getAttribute('aria-describedby'))->toBe('saved-description')
        ->and($root->getAttribute('data-color'))->toBe('neutral')
        ->and($root->getAttribute('data-variant'))->toBe('default')
        ->and($root->getAttribute('data-surface'))->toBe('overlay')
        ->and($html)->toContain('<p data-scope="toast" data-part="title" id="saved-title">Listing saved</p>')
        ->and($html)->toContain('<p data-scope="toast" data-part="description" id="saved-description">Cedarwood Estates is live now.</p>')
        ->and($html)->toContain('<div data-scope="toast" data-part="actions">Undo</div>')
        ->and($html)->toContain('<span data-scope="toast" data-part="icon" aria-hidden="true">'.IconRegistry::render('info').'</span>');
});

test('each color shows its status icon and type; error is an alert', function (string $color, string $icon, string $type, string $role): void {
    $html = Blade::render('<x-artisanpack-toast title="Done" :color="$color" />', ['color' => $color]);
    $root = toastRoot(designSystemDom($html));

    expect($root->getAttribute('data-color'))->toBe($color)
        ->and($root->getAttribute('data-type'))->toBe($type)
        ->and($root->getAttribute('role'))->toBe($role)
        ->and($html)->toContain(IconRegistry::render($icon));
})->with([
    'success' => ['success', 'circle-check', 'success', 'status'],
    'warning' => ['warning', 'triangle-alert', 'warning', 'status'],
    'error'   => ['error', 'circle-alert', 'error', 'alert'],
    'info'    => ['info', 'info', 'info', 'status'],
    'primary' => ['primary', 'info', 'info', 'status'],
]);

test('variant, surface and icon render as given', function (): void {
    $html = Blade::render('<x-artisanpack-toast title="Archived" variant="inverse" surface="glass" icon="trash" />');
    $root = toastRoot(designSystemDom($html));

    expect($root->getAttribute('data-variant'))->toBe('inverse')
        ->and($root->getAttribute('data-surface'))->toBe('glass')
        ->and($html)->toContain(IconRegistry::render('trash'));
});

test('without a title or description there are no labelling attributes', function (): void {
    $root = toastRoot(designSystemDom(Blade::render('<x-artisanpack-toast actions="Undo" />')));

    expect($root->hasAttribute('aria-labelledby'))->toBeFalse()
        ->and($root->hasAttribute('aria-describedby'))->toBeFalse();
});

test('the close button dispatches open-change and has a translated name', function (): void {
    app('translator')->addLines(['*.Dismiss notification' => 'Fermer la notification'], 'fr');
    app()->setLocale('fr');

    $close = designSystemDom(Blade::render('<x-artisanpack-toast title="Saved" />'))->query('//button[@data-part="close-trigger"]')->item(0);

    expect($close->getAttribute('aria-label'))->toBe('Fermer la notification')
        ->and($close->getAttribute('x-on:click'))->toBe("\$dispatch( 'open-change', { open: false } )")
        ->and($close->getAttribute('data-variant'))->toBe('ghost')
        ->and($close->getAttribute('data-size'))->toBe('sm')
        ->and($close->hasAttribute('data-icon-only'))->toBeTrue();

    app()->setLocale('en');
});

test('closeLabel names the close button', function (): void {
    $close = designSystemDom(Blade::render('<x-artisanpack-toast title="Saved" close-label="Hide" />'))->query('//button[@data-part="close-trigger"]')->item(0);

    expect($close->getAttribute('aria-label'))->toBe('Hide');
});

test('hideClose drops the close button', function (): void {
    expect(designSystemDom(Blade::render('<x-artisanpack-toast title="Saved" hide-close />'))->query('//*[@data-part="close-trigger"]'))->toHaveCount(0);
});

test('slots override the props, and class lands on the root', function (): void {
    $dom = designSystemDom(Blade::render(<<<'BLADE'
        <x-artisanpack-toast class="w-full" :classes="['title' => 'font-bold']">
            <x-slot:icon><svg data-test="own"></svg></x-slot:icon>
            <x-slot:title>Rich <em>title</em></x-slot:title>
            <x-slot:actions><button>Undo</button></x-slot:actions>
        </x-artisanpack-toast>
        BLADE));

    expect(toastRoot($dom)->getAttribute('class'))->toBe('apui-toast w-full')
        ->and($dom->query('//*[@data-part="icon"]/svg[@data-test="own"]'))->toHaveCount(1)
        ->and($dom->query('//p[@data-part="title"]/em')->item(0)->textContent)->toBe('title')
        ->and($dom->query('//p[@data-part="title"]')->item(0)->getAttribute('class'))->toBe('font-bold')
        ->and($dom->query('//*[@data-part="actions"]/button')->item(0)->textContent)->toBe('Undo');
});

test('the id ignores text, and repeats in one render are numbered', function (): void {
    $first = toastRoot(designSystemDom(Blade::render('<x-artisanpack-toast title="A" />')))->getAttribute('id');
    app()->forgetScopedInstances();
    $retitled = toastRoot(designSystemDom(Blade::render('<x-artisanpack-toast title="B" description="C" />')))->getAttribute('id');
    $repeated = toastRoot(designSystemDom(Blade::render('<x-artisanpack-toast title="A" />')))->getAttribute('id');

    expect($first)->toMatch('/^apui-toast-[0-9a-f]{12}-root$/')
        ->and($retitled)->toBe($first)
        ->and($repeated)->toBe(str_replace('-root', '-2-root', $first));
});

test('a bare toast, the 2.x toaster, renders nothing and raises a deprecation', function (): void {
    $deprecations = [];
    set_error_handler(static function (int $level, string $message) use (&$deprecations): bool {
        $deprecations[] = [$level, $message];

        return true;
    }, E_USER_DEPRECATED);

    try {
        $html = Blade::render('<x-artisanpack-toast />');
    } finally {
        restore_error_handler();
    }

    expect(trim($html))->toBe('')
        ->and($deprecations)->toHaveCount(1)
        ->and($deprecations[0][0])->toBe(E_USER_DEPRECATED)
        ->and($deprecations[0][1])->toContain('<x-artisanpack-toaster />');
});

test('the title is escaped', function (): void {
    expect(Blade::render('<x-artisanpack-toast :title="$title" />', ['title' => '<script>alert(1)</script>']))
        ->not->toContain('<script>alert')
        ->toContain('&lt;script&gt;');
});

test('the constructor declares only the manifest props', function (): void {
    $parameters = array_map(
        static fn (ReflectionParameter $parameter): string => $parameter->getName(),
        (new ReflectionClass(Toast::class))->getConstructor()->getParameters(),
    );

    expect($parameters)->toBe(['title', 'description', 'color', 'variant', 'surface', 'icon', 'actions', 'hideClose', 'closeLabel', 'classes']);
});
