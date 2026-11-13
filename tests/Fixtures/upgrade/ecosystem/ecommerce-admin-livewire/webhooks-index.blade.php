{{--
    Webhook subscriptions. See ArtisanPackUI\EcommerceAdminLivewire\Livewire\Webhooks\Index.

    @package    ArtisanPack_UI
    @subpackage EcommerceAdminLivewire

    @since      1.0.0
--}}
<div>
    <x-artisanpack-header :title="__( 'Webhooks' )" :subtitle="__( 'Send store events to other systems as signed HTTPS requests.' )" :level="1" separator>
        @if ( $canCreate )
            <x-slot:actions>
                <x-artisanpack-button color="primary" icon="o-plus" wire:click="create" :label="__( 'New subscription' )" />
            </x-slot:actions>
        @endif
    </x-artisanpack-header>

    @include( 'ecommerce-admin::partials.resource-table', [
        'emptyIcon'        => 'o-bolt',
        'emptyTitle'       => __( 'No webhook subscriptions yet' ),
        'emptyDescription' => __( 'Subscribe a URL to store events, such as a fulfilment partner that needs every refunded order.' ),
        'rowLabel'         => static fn ( \ArtisanPackUI\Ecommerce\Models\WebhookSubscription $subscription ): string => (string) $subscription->name,
        'cellContext'      => [ 'canUpdate' => $canUpdate, 'canDelete' => $canDelete ],
    ] )

    <x-artisanpack-drawer
        wire:model="editing"
        :title="null === $subscriptionId ? __( 'New subscription' ) : __( 'Edit subscription' )"
        right
        separator
        with-close-button
        close-on-escape
        class="w-full max-w-lg"
    >
        <form wire:submit="save" class="flex flex-col gap-4" data-webhook-form>
            @include( 'ecommerce-admin::partials.error-summary' )
            <x-artisanpack-input id="webhook-name" :label="__( 'Name' )" wire:model="form.name" required />
            <x-artisanpack-input
                id="webhook-url"
                type="url"
                :label="__( 'URL' )"
                :hint="__( 'Must use HTTPS and point to a public address.' )"
                placeholder="https://"
                wire:model="form.url"
                required
            />
            <x-artisanpack-choices-offline
                id="webhook-events"
                :label="__( 'Events' )"
                :hint="__( 'Choose All events to receive every event, including ones added later.' )"
                :options="$eventOptions"
                values-as-string
                searchable
                :no-result-text="__( 'No results found.' )"
                wire:model="form.events"
            />
            <x-artisanpack-toggle id="webhook-active" :label="__( 'Active' )" :hint="__( 'Turning a subscription back on resets its failure count.' )" wire:model="form.is_active" />

            @if ( null === $subscriptionId )
                <p class="text-sm opacity-75">{{ __( 'A signing secret is generated when you save. It is shown once.' ) }}</p>
            @endif

            <div class="flex justify-end gap-2">
                <x-artisanpack-button variant="ghost" wire:click="$set( 'editing', false )" :label="__( 'Cancel' )" />
                <x-artisanpack-button type="submit" color="primary" wire:loading.attr="disabled" :label="__( 'Save subscription' )" />
            </div>
        </form>
    </x-artisanpack-drawer>

    @if ( null !== $deleting )
        @php( $deleteTitle = __( 'Delete ":name"?', [ 'name' => $deleting->name ] ) )
        <x-artisanpack-modal wire:model="confirmingDelete" :title="$deleteTitle" separator>
            <p data-delete-webhook>{{ __( 'The subscription and its deliveries log are deleted. Events stop being sent to this URL.' ) }}</p>

            <x-slot:actions>
                <x-artisanpack-button variant="ghost" wire:click="cancelDelete" :label="__( 'Keep subscription' )" />
                <x-artisanpack-button
                    color="error"
                    wire:click="delete( {{ \Illuminate\Support\Js::from( $deleteToken ) }} )"
                    wire:loading.attr="disabled"
                    :label="__( 'Delete subscription' )"
                />
            </x-slot:actions>
        </x-artisanpack-modal>
    @endif

    @include( 'ecommerce-admin::livewire.webhooks.secret-dialogs' )
</div>
