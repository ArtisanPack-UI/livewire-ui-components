<?php

declare(strict_types=1);

use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\Livewire;
use Livewire\WithPagination;

/*
 * The 2.x Table with pagination (#56 follow-up): Pagination keeps to its
 * spec, and the Table renders the "Rows per page" Select beside it until the
 * Data Display wave rebuilds Table.
 */

class TablePaginationTestComponent extends Component
{
    use WithPagination;

    public int $perPage = 20;

    public bool $withPerPage = true;

    public function render(): string
    {
        return <<<'BLADE'
            <div>
                @if ( $withPerPage )
                    <x-artisanpack-table :headers="$this->headers()" :rows="$this->rows()" with-pagination per-page="perPage" />
                @else
                    <x-artisanpack-table :headers="$this->headers()" :rows="$this->rows()" with-pagination />
                @endif
            </div>
            BLADE;
    }

    /** @return list<array{key: string, label: string}> */
    public function headers(): array
    {
        return [['key' => 'id', 'label' => 'ID'], ['key' => 'name', 'label' => 'Name']];
    }

    public function rows(): LengthAwarePaginator
    {
        $items = collect(range(1, 10))->map(static fn (int $id): array => ['id' => $id, 'name' => 'Row '.$id]);

        return new LengthAwarePaginator($items, 318, $this->perPage, 3, ['path' => '/rows']);
    }
}

function tablePaginationHtml(bool $withPerPage): string
{
    return Livewire::test(TablePaginationTestComponent::class, ['withPerPage' => $withPerPage])->html();
}

test('renders the rows per page select beside the pagination', function (): void {
    $dom    = designSystemDom(tablePaginationHtml(true));
    $select = $dom->query('//*[@data-table-pagination]//select')->item(0);

    expect($select)->not->toBeNull()
        ->and($select->getAttribute('wire:model.live'))->toBe('perPage')
        ->and($dom->query('//label[@data-part="label"]')->item(0)->textContent)->toBe('Rows per page')
        ->and($dom->query('//select/option[@selected]')->item(0)->getAttribute('value'))->toBe('20')
        ->and($dom->query('//select/option'))->toHaveCount(4)
        ->and($dom->query('//*[@data-table-pagination]//nav[contains(@class, "apui-pagination")]'))->toHaveCount(1)
        ->and(trim($dom->query('//*[@data-part="summary"]')->item(0)->textContent))->toBe('Showing 41–60 of 318');
});

test('omits the select without a per-page property, and keeps Pagination to its spec', function (): void {
    $html = tablePaginationHtml(false);
    $dom  = designSystemDom($html);

    expect($dom->query('//*[@data-table-pagination]//select'))->toHaveCount(0)
        ->and($dom->query('//nav[contains(@class, "apui-pagination")]'))->toHaveCount(1)
        ->and($html)->not->toContain('per-page-values');
});
