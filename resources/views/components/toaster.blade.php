{{--
    Toaster (#51). Markup contract: design-system pilot, Toast (D-019).
    The region (Zag's group part) renders the page's toasts from data, newest
    first, each with the same markup as <x-artisanpack-toast>. A `toast` browser
    event adds one: $this->dispatch( 'toast', title: … ) from a Livewire
    component. It's client-rendered, so it stays wire:ignore, and @persist keeps
    it (and its toasts) across wire:navigate. Flashed toasts (Toasts::flash())
    come in a JSON script outside the persisted part, which the toaster reads
    and removes, on load and after each navigation, so a page restored from
    history doesn't show them again. Put it once per page, in the layout.
--}}
@php
    $toasterId = $toasterId();
    $flashed   = $flashedToasts();
@endphp
<div {{ $attributes->except( 'id' ) }} style="display: contents">
    @persist( 'apui-toaster-' . $toasterId )
    <div
        id="{{ $toasterId }}"
        wire:ignore
        x-data="{{ $alpineData() }}"
        x-init="apuiTakeFlashedToasts( $data, $el ); document.addEventListener( 'livewire:navigated', () => apuiTakeFlashedToasts( $data, $el ) )"
    >
        <div id="{{ $toasterId }}-group" x-apui:group>
            <template x-for="(entry, index) in toasts" :key="entry.id">
                <div x-data="apuiToast( entry, index )" x-apui:root class="apui-toast">
                    <span x-apui:icon x-html="icon"></span>
                    <p x-apui:title x-text="entry.title"></p>
                    <template x-if="entry.description">
                        <p x-apui:description x-text="entry.description"></p>
                    </template>
                    <template x-if="entry.action">
                        <div x-apui:actions>
                            <button x-apui:action-trigger class="apui-button" data-variant="outline" data-color="neutral" data-size="sm"><span data-scope="button" data-part="label" x-text="entry.action.label"></span></button>
                        </div>
                    </template>
                    {{-- The machine hides the close button for hideClose toasts. --}}
                    <x-artisanpack-button
                        variant="ghost"
                        color="neutral"
                        size="sm"
                        icon-only
                        leading-icon="x"
                        :aria-label="$closeTriggerLabel()"
                        x-apui:close-trigger=""
                    />
                </div>
            </template>
        </div>
        @once
            <script>
                ( () => {
                    {{-- Shows the toasts a page flashed, once: the script is removed after reading. --}}
                    window.apuiTakeFlashedToasts = window.apuiTakeFlashedToasts || ( ( toaster, el ) => {
                        if ( ! el.isConnected ) {
                            return;
                        }
                        document.querySelectorAll( 'script[data-apui-flashed-toasts]' ).forEach( ( script ) => {
                            try {
                                JSON.parse( script.textContent || '[]' ).forEach( ( toast ) => toaster.create( toast ) );
                            } catch ( error ) {
                                // Not a list of toasts.
                            }
                            script.remove();
                        } );
                    } );

                    {{-- A ToastException thrown in a Livewire action answers with a toast: show it. Registered once, even when Livewire already started (a toaster that arrives through wire:navigate). --}}
                    if ( window.apuiToastExceptionHook ) {
                        return;
                    }
                    window.apuiToastExceptionHook = true;

                    const show = ( body, preventDefault ) => {
                        try {
                            const result = JSON.parse( body );
                            if ( result && result.toast ) {
                                window.dispatchEvent( new CustomEvent( 'toast', { detail: result.toast } ) );
                            }
                            if ( result && true === result.prevent_default && 'function' === typeof preventDefault ) {
                                preventDefault();
                            }
                        } catch ( error ) {
                            // Not a toast response.
                        }
                    };

                    const register = () => {
                        if ( 'function' === typeof Livewire.interceptRequest ) {
                            Livewire.interceptRequest( ( { onError } ) => {
                                onError( ( { response, preventDefault } ) => {
                                    response.clone().text().then( ( body ) => show( body, preventDefault ) ).catch( () => {} );
                                } );
                            } );
                        } else {
                            Livewire.hook( 'request', ( { fail } ) => {
                                fail( ( { content, preventDefault } ) => show( content, preventDefault ) );
                            } );
                        }
                    };

                    if ( window.Livewire && window.Livewire.hook ) {
                        register();
                    } else {
                        document.addEventListener( 'livewire:init', register, { once: true } );
                    }
                } )();
            </script>
        @endonce
    </div>
    @endpersist
    @if ( null !== $flashed )
        <script type="application/json" data-apui-flashed-toasts>{!! $flashed !!}</script>
    @endif
</div>
