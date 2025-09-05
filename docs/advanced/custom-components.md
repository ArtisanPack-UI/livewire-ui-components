---
title: Custom Components
---

# Custom Components

This guide explains how to create custom components that integrate with ArtisanPack UI Livewire Components. You'll learn how to extend existing components, create wrapper components, and build entirely new components that follow the same design patterns.

## Approaches to Custom Components

There are several approaches to creating custom components that work with ArtisanPack UI:

1. **Extending existing components**: Inherit from an ArtisanPack UI component to add or modify functionality
2. **Creating wrapper components**: Create a new component that uses ArtisanPack UI components internally
3. **Building new components**: Create entirely new components that follow the same design patterns

## Extending Existing Components

Extending an existing component allows you to inherit all of its functionality while adding or modifying behavior.

### Basic Component Extension

Here's an example of extending the Button component:

```php
<?php

namespace App\View\Components;

use ArtisanPack\LivewireUiComponents\View\Components\Button as BaseButton;

class CustomButton extends BaseButton
{
    /**
     * Add a new property to the component
     */
    public $icon;
    
    /**
     * Create a new component instance.
     *
     * @param  string|null  $icon
     * @return void
     */
    public function __construct($icon = null, $type = 'button', $color = 'primary', $size = null)
    {
        parent::__construct($type, $color, $size);
        
        $this->icon = $icon;
    }
    
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.custom-button');
    }
}
```

Create a view for your custom component:

```php
<!-- resources/views/components/custom-button.blade.php -->
<button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }}>
    @if($icon)
        <x-artisanpack-icon :name="$icon" class="w-5 h-5 mr-2" />
    @endif
    {{ $slot }}
</button>
```

Register your component in your `AppServiceProvider`:

```php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\View\Components\CustomButton;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::component('custom-button', CustomButton::class);
    }
}
```

Now you can use your custom component:

```php
<x-custom-button icon="heroicon-o-plus">
    Add Item
</x-custom-button>
```

### Overriding Methods

You can override methods from the base component to modify behavior:

```php
<?php

namespace App\View\Components;

use ArtisanPack\LivewireUiComponents\View\Components\Input as BaseInput;

class CustomInput extends BaseInput
{
    /**
     * Get the input classes.
     *
     * @return string
     */
    protected function getInputClasses()
    {
        // Get the original classes
        $classes = parent::getInputClasses();
        
        // Add your custom classes
        $classes .= ' border-2 focus:ring-4';
        
        return $classes;
    }
    
    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        // You can either use the parent's view
        return parent::render();
        
        // Or use your own view
        // return view('components.custom-input');
    }
}
```

## Creating Wrapper Components

Wrapper components use ArtisanPack UI components internally but provide a simplified or specialized interface.

### Basic Wrapper Component

Here's an example of a wrapper component for a search input:

```php
<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SearchInput extends Component
{
    /**
     * The placeholder text.
     *
     * @var string
     */
    public $placeholder;
    
    /**
     * Create a new component instance.
     *
     * @param  string  $placeholder
     * @return void
     */
    public function __construct($placeholder = 'Search...')
    {
        $this->placeholder = $placeholder;
    }
    
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.search-input');
    }
}
```

Create a view for your wrapper component:

```php
<!-- resources/views/components/search-input.blade.php -->
<div>
    <x-artisanpack-input 
        type="search" 
        :placeholder="$placeholder" 
        {{ $attributes }}
    >
        <x-slot:prefix>
            <x-artisanpack-icon name="heroicon-o-magnifying-glass" class="w-5 h-5" />
        </x-slot:prefix>
    </x-artisanpack-input>
</div>
```

Register your component in your `AppServiceProvider`:

```php
Blade::component('search-input', SearchInput::class);
```

Now you can use your wrapper component:

```php
<x-search-input wire:model="search" placeholder="Search users..." />
```

### Complex Wrapper Component

Here's an example of a more complex wrapper component that combines multiple ArtisanPack UI components:

```php
<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FilterableTable extends Component
{
    /**
     * The table headers.
     *
     * @var array
     */
    public $headers;
    
    /**
     * Whether to show the search input.
     *
     * @var bool
     */
    public $searchable;
    
    /**
     * Create a new component instance.
     *
     * @param  array  $headers
     * @param  bool  $searchable
     * @return void
     */
    public function __construct($headers = [], $searchable = true)
    {
        $this->headers = $headers;
        $this->searchable = $searchable;
    }
    
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.filterable-table');
    }
}
```

Create a view for your complex wrapper component:

```php
<!-- resources/views/components/filterable-table.blade.php -->
<div>
    @if($searchable)
        <div class="mb-4">
            <x-artisanpack-input 
                type="search" 
                placeholder="Search..." 
                wire:model.debounce.300ms="search"
            >
                <x-slot:prefix>
                    <x-artisanpack-icon name="heroicon-o-magnifying-glass" class="w-5 h-5" />
                </x-slot:prefix>
            </x-artisanpack-input>
        </div>
    @endif
    
    <x-artisanpack-card>
        <x-artisanpack-table :headers="$headers" {{ $attributes }}>
            {{ $slot }}
            
            @if(isset($pagination))
                <x-slot:pagination>
                    {{ $pagination }}
                </x-slot:pagination>
            @endif
        </x-artisanpack-table>
    </x-artisanpack-card>
</div>
```

Register your component in your `AppServiceProvider`:

```php
Blade::component('filterable-table', FilterableTable::class);
```

Now you can use your complex wrapper component:

```php
<x-filterable-table :headers="['Name', 'Email', 'Role']" searchable>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
        </tr>
    @endforeach
    
    <x-slot:pagination>
        {{ $users->links() }}
    </x-slot:pagination>
</x-filterable-table>
```

## Building New Components

When building entirely new components, you can follow the same design patterns used by ArtisanPack UI components.

### Basic New Component

Here's an example of a new Rating component:

```php
<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Rating extends Component
{
    /**
     * The current rating value.
     *
     * @var int
     */
    public $value;
    
    /**
     * The maximum rating value.
     *
     * @var int
     */
    public $max;
    
    /**
     * Whether the rating is readonly.
     *
     * @var bool
     */
    public $readonly;
    
    /**
     * Create a new component instance.
     *
     * @param  int  $value
     * @param  int  $max
     * @param  bool  $readonly
     * @return void
     */
    public function __construct($value = 0, $max = 5, $readonly = false)
    {
        $this->value = $value;
        $this->max = $max;
        $this->readonly = $readonly;
    }
    
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.rating');
    }
}
```

Create a view for your new component:

```php
<!-- resources/views/components/rating.blade.php -->
<div {{ $attributes->class(['rating']) }}>
    @for($i = 1; $i <= $max; $i++)
        <input 
            type="radio" 
            name="{{ $attributes->get('name', 'rating') }}" 
            value="{{ $i }}" 
            class="mask mask-star-2 bg-orange-400" 
            @if($i == $value) checked @endif
            @if($readonly) disabled @endif
            {{ $attributes->whereStartsWith('wire:') }}
        />
    @endfor
</div>
```

Register your component in your `AppServiceProvider`:

```php
Blade::component('rating', Rating::class);
```

Now you can use your new component:

```php
<x-rating :value="3" :max="5" wire:model="productRating" />
```

### Component with JavaScript

For components that require JavaScript, you can use Alpine.js or Livewire:

```php
<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ColorPicker extends Component
{
    /**
     * The current color value.
     *
     * @var string
     */
    public $value;
    
    /**
     * Create a new component instance.
     *
     * @param  string  $value
     * @return void
     */
    public function __construct($value = '#000000')
    {
        $this->value = $value;
    }
    
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.color-picker');
    }
}
```

Create a view for your component with Alpine.js:

```php
<!-- resources/views/components/color-picker.blade.php -->
<div x-data="{ color: '{{ $value }}' }" {{ $attributes->class(['relative']) }}>
    <x-artisanpack-input 
        type="text" 
        x-model="color"
        {{ $attributes->whereStartsWith('wire:') }}
    >
        <x-slot:suffix>
            <div 
                class="w-6 h-6 rounded border cursor-pointer" 
                :style="{ backgroundColor: color }"
                @click="$refs.colorInput.click()"
            ></div>
            <input 
                type="color" 
                x-model="color" 
                x-ref="colorInput"
                class="sr-only"
                {{ $attributes->whereStartsWith('wire:') }}
            />
        </x-slot:suffix>
    </x-artisanpack-input>
</div>
```

Register your component in your `AppServiceProvider`:

```php
Blade::component('color-picker', ColorPicker::class);
```

Now you can use your component:

```php
<x-color-picker value="#ff5500" wire:model="brandColor" />
```

## Best Practices

### 1. Follow the Same Design Patterns

Follow the same design patterns used by ArtisanPack UI components:
- Use props for configuration
- Use slots for content
- Support attribute merging
- Support Livewire binding

### 2. Make Components Reusable

Design your components to be reusable across different parts of your application:
- Use props for customization
- Don't hardcode values
- Support common attributes and events

### 3. Document Your Components

Document your components to make them easier to use:
- Add PHPDoc comments to your component class
- Document props, slots, and events
- Provide usage examples

### 4. Test Your Components

Test your components to ensure they work as expected:
- Write unit tests for component logic
- Write browser tests for component rendering and interaction

### 5. Use Consistent Naming

Use consistent naming for your components:
- Follow the same naming conventions as ArtisanPack UI
- Use descriptive names that indicate the component's purpose

## Advanced Techniques

### Component Composition

You can compose complex components by combining multiple components:

```php
<!-- resources/views/components/user-card.blade.php -->
<x-artisanpack-card>
    <div class="flex items-center">
        <x-artisanpack-avatar :src="$user->profile_photo_url" :alt="$user->name" class="mr-4" />
        <div>
            <h3 class="text-lg font-bold">{{ $user->name }}</h3>
            <p class="text-gray-500">{{ $user->email }}</p>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $slot }}
    </div>
    
    @if(isset($footer))
        <x-slot:footer>
            {{ $footer }}
        </x-slot:footer>
    @endif
</x-artisanpack-card>
```

### Dynamic Components

You can create components that render different ArtisanPack UI components based on props:

```php
<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormField extends Component
{
    /**
     * The field type.
     *
     * @var string
     */
    public $type;
    
    /**
     * The field label.
     *
     * @var string
     */
    public $label;
    
    /**
     * Create a new component instance.
     *
     * @param  string  $type
     * @param  string  $label
     * @return void
     */
    public function __construct($type = 'text', $label = null)
    {
        $this->type = $type;
        $this->label = $label;
    }
    
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form-field');
    }
}
```

```php
<!-- resources/views/components/form-field.blade.php -->
<div>
    @switch($type)
        @case('text')
        @case('email')
        @case('password')
        @case('number')
        @case('date')
            <x-artisanpack-input :type="$type" :label="$label" {{ $attributes }} />
            @break
            
        @case('textarea')
            <x-artisanpack-textarea :label="$label" {{ $attributes }} />
            @break
            
        @case('select')
            <x-artisanpack-select :label="$label" {{ $attributes }}>
                {{ $slot }}
            </x-artisanpack-select>
            @break
            
        @case('checkbox')
            <x-artisanpack-checkbox :label="$label" {{ $attributes }} />
            @break
            
        @case('radio')
            <x-artisanpack-radio :label="$label" {{ $attributes }} />
            @break
            
        @default
            <x-artisanpack-input :type="$type" :label="$label" {{ $attributes }} />
    @endswitch
</div>
```

## Related Topics

- [Component Prefixing](component-prefixing) - Learn how to use component prefixing to avoid naming collisions
- [Customization](../customization) - General customization options for ArtisanPack UI components
