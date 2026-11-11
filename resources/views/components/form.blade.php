{{--
    Form (#53). Markup contract: design-system form-text wave, Form.
    A native form: attributes (wire:submit, action, method, aria-label) land
    on it. Fields stack with the field gap; the actions slot ends it.
--}}
<form
    {{ $attributes->class( $rootClasses( 'apui-form' ) ) }}
    @if ( $divided ) data-divided @endif
>
    {{ $slot }}
    @if ( $filled( $actions ) )
        <div data-scope="form" data-part="actions"{{ $partClass( 'actions' ) }}>{{ $content( $actions ) }}</div>
    @endif
</form>
