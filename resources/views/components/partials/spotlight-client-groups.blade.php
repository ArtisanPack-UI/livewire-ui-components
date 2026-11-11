{{--
    Spotlight's client-rendered groups (#57): recent choices (`recent`) and the
    url search's results (`remote`), from apuiSpotlight's clientGroups(). The
    markup matches the server-rendered groups in spotlight.blade.php. With
    navigate, wire:navigate reads the href when the link is used, so off-site,
    non-http and new-tab links still load normally.
--}}
<template x-for="group in clientGroups( @js( $source ) )" :key="group.id">
    <div data-scope="spotlight" data-part="item-group" role="group" x-apui:item-group="{ id: group.id }">
        <div data-scope="spotlight" data-part="item-group-label" role="presentation" x-show="group.label" x-text="group.label" x-apui:item-group-label="{ htmlFor: group.id }"></div>
        <template x-for="item in group.items" :key="item.value">
            <a data-scope="spotlight" data-part="item" role="option" tabindex="-1" :href="item.href || null" :data-color="item.color || null" @if ( $navigate ) wire:navigate @endif x-apui:item="item">
                <template x-if="item.icon">
                    <span data-scope="spotlight" data-part="tile" aria-hidden="true" x-html="icon( item.icon )"></span>
                </template>
                <span data-scope="spotlight" data-part="item-text" x-apui:item-text><span data-apui-label></span><template x-if="item.description"><span data-scope="spotlight" data-part="item-description" x-text="item.description"></span></template></span>
                <template x-if="shortcutKeys( item.shortcut ).length">
                    <span data-scope="spotlight" data-part="shortcut" aria-hidden="true"><template x-for="key in shortcutKeys( item.shortcut )"><kbd x-text="key"></kbd></template></span>
                </template>
                <span data-scope="spotlight" data-part="item-indicator" aria-hidden="true">{{ $iconSvg( 'corner-down-left' ) }}</span>
            </a>
        </template>
    </div>
</template>
