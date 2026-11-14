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
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => __('Name')],
            ['key' => 'age', 'label' => __('Age'), 'numeric' => true],
            ['key' => 'email', 'label' => __('Email'), 'sortable' => false],
        ];
    }

    /**
     * For demo purpose, this is a static collection.
     *
     * On real projects you query an Eloquent model instead.
     */
    public function users(): Collection
    {
        return collect([
            ['id' => 1, 'name' => 'Ada', 'email' => 'ada@example.com', 'age' => 36],
            ['id' => 2, 'name' => 'Grace', 'email' => 'grace@example.com', 'age' => 45],
            ['id' => 3, 'name' => 'Linus', 'email' => 'linus@example.com', 'age' => 28],
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
