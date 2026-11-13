{{--
    Form field with a linked error and hint. See Field.

    The library control gets `omit-error` and its hint is printed here
    instead, so each has an id `aria-describedby` can point at.

    @package    ArtisanPack_UI
    @subpackage EcommerceStorefrontLivewire

    @since      1.0.0
--}}
@php
    $ecommerceFieldKey     = $errorKey();
    $ecommerceFieldError   = null === $ecommerceFieldKey || ! isset( $errors ) ? '' : (string) $errors->first( $ecommerceFieldKey );
    $ecommerceFieldInvalid = '' !== $ecommerceFieldError;
    $ecommerceFieldHint    = 'checkbox' === $control || null === $hint || '' === $hint ? null : $hint;
    $ecommerceFieldErrorId = $baseId() . '-error';
    $ecommerceFieldHintId  = $baseId() . '-hint';
    $ecommerceFieldAria    = array_filter( [
        // The library labels selects and textareas with a <legend>, which doesn't name the control (#117).
        'aria-label'       => in_array( $control, [ 'select', 'textarea' ], true ) && null !== $label && ! $attributes->has( 'aria-label' ) && ! $attributes->has( 'aria-labelledby' ) ? $label : null,
        'aria-invalid'     => $ecommerceFieldInvalid ? 'true' : null,
        'aria-describedby' => trim( implode( ' ', array_filter( [
            (string) $attributes->get( 'aria-describedby', '' ),
            $ecommerceFieldInvalid ? $ecommerceFieldErrorId : '',
            null !== $ecommerceFieldHint ? $ecommerceFieldHintId : '',
        ] ) ) ),
    ], static fn ( ?string $value ): bool => null !== $value && '' !== $value );
    $ecommerceFieldClasses = [
        '!input-error'    => $ecommerceFieldInvalid && in_array( $control, [ 'input', 'password' ], true ),
        '!select-error'   => $ecommerceFieldInvalid && 'select' === $control,
        '!textarea-error' => $ecommerceFieldInvalid && 'textarea' === $control,
        'checkbox-error'  => $ecommerceFieldInvalid && 'checkbox' === $control,
    ];
@endphp
<div class="w-full" data-ec-field="{{ $id }}" @if ( null !== $ecommerceFieldKey ) data-field-key="{{ $ecommerceFieldKey }}" @endif>
    @switch ( $control )
        @case( 'select' )
            <x-artisanpack-select
                :id="$id"
                :label="$label"
                :options="$options"
                :placeholder="$placeholder"
                :placeholder-value="$placeholderValue"
                :option-value="$optionValue"
                :option-label="$optionLabel"
                omit-error
                {{ $attributes->except( [ 'aria-describedby', 'aria-invalid' ] )->merge( $ecommerceFieldAria )->class( $ecommerceFieldClasses ) }}
            />
            @break

        @case( 'textarea' )
            <x-artisanpack-textarea :id="$id" :label="$label" omit-error {{ $attributes->except( [ 'aria-describedby', 'aria-invalid' ] )->merge( $ecommerceFieldAria )->class( $ecommerceFieldClasses ) }} />
            @break

        @case( 'password' )
            <x-artisanpack-password :id="$id" :label="$label" omit-error {{ $attributes->except( [ 'aria-describedby', 'aria-invalid' ] )->merge( $ecommerceFieldAria )->class( $ecommerceFieldClasses ) }} />
            @break

        @case( 'checkbox' )
            <x-artisanpack-checkbox :id="$id" :label="$label" :hint="$hint" omit-error {{ $attributes->except( [ 'aria-describedby', 'aria-invalid' ] )->merge( $ecommerceFieldAria )->class( $ecommerceFieldClasses ) }} />
            @break

        @default
            <x-artisanpack-input :id="$id" :label="$label" omit-error {{ $attributes->except( [ 'aria-describedby', 'aria-invalid' ] )->merge( $ecommerceFieldAria )->class( $ecommerceFieldClasses ) }} />
    @endswitch

    @if ( $ecommerceFieldInvalid )
        <p id="{{ $ecommerceFieldErrorId }}" class="mt-1 text-xs text-[color-mix(in_oklab,var(--color-error)_55%,var(--color-base-content))]" data-field-error>{{ $ecommerceFieldError }}</p>
    @endif

    @if ( null !== $ecommerceFieldHint )
        <p id="{{ $ecommerceFieldHintId }}" class="mt-1 text-xs text-base-content/70" data-field-hint>{{ $ecommerceFieldHint }}</p>
    @endif
</div>
