<?php

declare(strict_types=1);

use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\Livewire;
use Livewire\WithPagination;

/*
 * Pagination (#56) inside a Livewire component that uses WithPagination:
 * the paginator's links call gotoPage() instead of reloading, and keep their
 * hrefs for new tabs.
 */

class PaginationLivewireTestComponent extends Component
{
    use WithPagination;

    public function render(): string
    {
        return <<<'BLADE'
            <div>
                <x-artisanpack-pagination :rows="$this->listings()" />
            </div>
            BLADE;
    }

    public function listings(): LengthAwarePaginator
    {
        $page = $this->getPage();

        return new LengthAwarePaginator(range(1, 10), 100, 10, $page, ['path' => '/listings']);
    }
}

it('calls gotoPage from the paginator links inside Livewire', function (): void {
    $html = Livewire::test(PaginationLivewireTestComponent::class)->html();

    expect($html)->toContain('wire:click.prevent="gotoPage(2, &quot;page&quot;)"')
        ->and($html)->toContain('href="/listings?page=2"');
});

it('moves to the chosen page', function (): void {
    Livewire::test(PaginationLivewireTestComponent::class)
        ->call('gotoPage', 4, 'page')
        ->assertSeeHtml('aria-current="page"')
        ->assertSeeHtml('aria-label="Page 4"')
        ->assertSeeHtml('wire:click.prevent="gotoPage(5, &quot;page&quot;)"');
});
