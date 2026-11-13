@if ( ! $available() )
    <x-artisanpack-alert
        color="info"
        icon="o-information-circle"
        :title="$label"
        :description="$unavailableMessage()"
        role="status"
    />
@elseif ( $live )
    <x-artisanpack-choices
        :id="$id ?? $model"
        :label="$label"
        :hint="$hint"
        :options="$options"
        :single="$single"
        searchable
        :search-function="$searchFunction()"
        option-value="id"
        option-label="name"
        option-sub-label="description"
        debounce="300ms"
        :no-result-text="__( 'No results found.' )"
        wire:model.live="{{ $model }}"
        {{ $attributes->whereDoesntStartWith( 'wire:model' ) }}
    />
@else
    <x-artisanpack-choices
        :id="$id ?? $model"
        :label="$label"
        :hint="$hint"
        :options="$options"
        :single="$single"
        searchable
        :search-function="$searchFunction()"
        option-value="id"
        option-label="name"
        option-sub-label="description"
        debounce="300ms"
        :no-result-text="__( 'No results found.' )"
        wire:model="{{ $model }}"
        {{ $attributes->whereDoesntStartWith( 'wire:model' ) }}
    />
@endif
