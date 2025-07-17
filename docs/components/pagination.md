# Pagination Component

The Pagination component provides a user interface for navigating through paginated data, with support for per-page selection.

## Basic Usage

```blade
<x-artisanpack-pagination :rows="$users" wire:model.live="perPage" />
```

## With Custom Per-Page Values

```blade
<x-artisanpack-pagination 
    :rows="$users" 
    wire:model.live="perPage" 
    :per-page-values="[5, 10, 25, 50]" 
/>
```

## Usage in Livewire Component

```php
class UserList extends Component
{
    public $perPage = 10;
    
    public function render()
    {
        return view('livewire.user-list', [
            'users' => User::paginate($this->perPage)
        ]);
    }
}
```

```blade
<div>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <x-artisanpack-pagination :rows="$users" wire:model.live="perPage" />
</div>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | string | null | Optional identifier for the pagination component |
| rows | array\|ArrayAccess | | The paginated data to display |
| perPageValues | array | [10, 20, 50, 100] | Available options for the number of items per page |

## Notes

- The component requires a Livewire model binding (using `wire:model.live`) to track the selected per-page value.
- The component automatically displays the Laravel pagination links if the `$rows` parameter is a `LengthAwarePaginator` instance.
- The component will only show the per-page selector if there are items to display and a Livewire model is bound.
- The pagination links use `onEachSide(1)` to limit the number of page links displayed, and disable the automatic scroll-to-top behavior.
