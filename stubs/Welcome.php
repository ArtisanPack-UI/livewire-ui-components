<?php

declare(strict_types=1);

namespace App\Livewire;

use ArtisanPack\LivewireUiComponents\Traits\Toast;
use Illuminate\Support\Collection;
use Livewire\Component;

class Welcome extends Component
{
    use Toast;

    public string $search = '';

    public bool $drawer = false;

    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    // Clear filters
    public function clear(): void
    {
        $this->reset();
        $this->success(__('Filters cleared.'));
    }

    // Delete action
    public function delete($id): void
    {
        $this->warning(__('Will delete #:id', ['id' => $id]), __('It is fake.'));
    }

    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'name', 'label' => 'Name', 'class' => 'w-64'],
            ['key' => 'age', 'label' => 'Age', 'class' => 'w-20'],
            ['key' => 'email', 'label' => 'E-mail', 'sortable' => false],
        ];
    }

    /**
     * For demo purpose, this is a static collection.
     *
     * On real projects you do it with Eloquent collections.
     * Please, refer to maryUI docs to see the eloquent examples.
     */
    public function users(): Collection
    {
        return collect([
            ['id' => 1, 'name' => 'Mary', 'email' => 'mary@mary-ui.com', 'age' => 23],
            ['id' => 2, 'name' => 'Giovanna', 'email' => 'giovanna@mary-ui.com', 'age' => 7],
            ['id' => 3, 'name' => 'Marina', 'email' => 'marina@mary-ui.com', 'age' => 5],
        ])
            ->sortBy([[...array_values($this->sortBy)]])
            ->when($this->search, function (Collection $collection) {
                return $collection->filter(fn (array $item) => str($item['name'])->contains($this->search, true));
            });
    }

    public function render()
    {
        return view('livewire.welcome', [
            'users'   => $this->users(),
            'headers' => $this->headers(),
        ]);
    }
}
