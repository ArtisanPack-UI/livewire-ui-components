<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Exceptions\ToastException;
use ArtisanPack\LivewireUiComponents\Support\Toasts;
use ArtisanPack\LivewireUiComponents\Traits\Toast;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\Livewire;

/*
 * The Toast trait and ToastException (#51): toasts from a Livewire
 * component, at once (the `toast` event) or across a redirect (flashed).
 */

class ToastTraitTestComponent extends Component
{
    use Toast;

    public function save(): void
    {
        $this->toast(__('Listing saved'), __('Cedarwood Estates is live now.'), color: 'success');
    }

    public function saveAndLeave(): mixed
    {
        return $this->toast(__('Listing saved'), color: 'success', redirectTo: '/listings');
    }

    public function archive(): void
    {
        $this->info(__('Listing archived'), duration: 0);
    }

    public function flashAndRedirect(): void
    {
        $this->flashToast(__('See you'), variant: 'inverse');
        $this->redirect('/goodbye');
    }

    public function render(): string
    {
        return '<div>{{ __( \'Listings\' ) }}</div>';
    }
}

test('toast dispatches the toast event with the options', function (): void {
    Livewire::test(ToastTraitTestComponent::class)
        ->call('save')
        ->assertDispatched('toast', title: 'Listing saved', description: 'Cedarwood Estates is live now.', color: 'success', variant: 'default', surface: 'overlay');
});

test('the color helpers set the color', function (): void {
    Livewire::test(ToastTraitTestComponent::class)
        ->call('archive')
        ->assertDispatched('toast', title: 'Listing archived', color: 'info', duration: 0);
});

test('redirectTo flashes the toast and redirects instead of dispatching', function (): void {
    Livewire::test(ToastTraitTestComponent::class)
        ->call('saveAndLeave')
        ->assertNotDispatched('toast')
        ->assertRedirect('/listings');

    expect(Toasts::pull())->toBe([['title' => 'Listing saved', 'color' => 'success', 'variant' => 'default', 'surface' => 'overlay']]);
});

test('flashToast flashes for a redirect of your own', function (): void {
    Livewire::test(ToastTraitTestComponent::class)
        ->call('flashAndRedirect')
        ->assertNotDispatched('toast')
        ->assertRedirect('/goodbye');

    expect(Toasts::pull())->toBe([['title' => 'See you', 'color' => 'neutral', 'variant' => 'inverse', 'surface' => 'overlay']]);
});

test('toast messages are translatable', function (): void {
    app('translator')->addLines(['*.Listing saved' => 'Annonce enregistrée'], 'fr');
    app()->setLocale('fr');

    Livewire::test(ToastTraitTestComponent::class)
        ->call('save')
        ->assertDispatched('toast', title: 'Annonce enregistrée');

    app()->setLocale('en');
});

test('ToastException answers a Livewire request with the toast', function (): void {
    $request  = Request::create('/livewire/update', 'POST', server: ['HTTP_X_LIVEWIRE' => 'true']);
    $response = ToastException::error(__('Upload failed'), __('The file is larger than 10 MB.'))->render($request);

    expect($response->getStatusCode())->toBe(500)
        ->and($response->getData(true))->toBe([
            'toast'           => ['title' => 'Upload failed', 'description' => 'The file is larger than 10 MB.', 'color' => 'error'],
            'prevent_default' => true,
        ])
        ->and(ToastException::info('A')->permitDefault()->render($request)->getData(true)['prevent_default'])->toBeFalse()
        ->and(ToastException::success('A')->render(Request::create('/')))->toBeFalse();
});

/**
 * Runs a callback and returns the E_USER_DEPRECATED messages it raised.
 *
 * @return list<string>
 */
function toastDeprecations(Closure $callback): array
{
    $messages = [];
    set_error_handler(static function (int $level, string $message) use (&$messages): bool {
        $messages[] = $message;

        return true;
    }, E_USER_DEPRECATED);

    try {
        $callback();
    } finally {
        restore_error_handler();
    }

    return $messages;
}

test('a 2.x positional call raises a deprecation and keeps the new meaning', function (): void {
    $harness = new class {
        use Toast;

        public array $dispatched = [];

        public function dispatch(string $event, mixed ...$params): void
        {
            $this->dispatched[] = $params;
        }

        public function redirect(string $url, bool $navigate = false): string
        {
            return $url;
        }
    };

    $legacy = toastDeprecations(static fn () => $harness->toast('success', 'Saved'));
    $modern = toastDeprecations(static fn () => $harness->toast('Saved', 'It worked'));
    $single = toastDeprecations(static fn () => $harness->toast('success'));

    expect($legacy)->toHaveCount(1)
        ->and($legacy[0])->toContain('2.x toast( $type, $title )')
        ->and($harness->dispatched[0])->toMatchArray(['title' => 'success', 'description' => 'Saved'])
        ->and($modern)->toBe([])
        ->and($single)->toBe([]);
});

test('ToastException falls back to no icon instead of a 500', function (): void {
    // A 2.x call passed the position third, where the icon is now.
    expect(ToastException::info(__('Saved'), null, 'toast-top toast-end')->toast())->toBe(['title' => 'Saved', 'color' => 'info'])
        ->and(ToastException::typedMessage('nope', __('Saved'))->toast())->toBe(['title' => 'Saved']);
});

test('a ToastException built with only a message shows the message', function (): void {
    $request = Request::create('/livewire/update', 'POST', server: ['HTTP_X_LIVEWIRE' => 'true']);
    $plain   = (new ToastException('Quota reached'))->render($request);

    expect($plain->getStatusCode())->toBe(500)
        ->and($plain->getData(true)['toast'])->toBe(['title' => 'Quota reached', 'color' => 'error'])
        ->and((new ToastException)->toast())->toBe(['title' => 'Something went wrong.', 'color' => 'error']);
});
