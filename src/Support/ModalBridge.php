<?php

declare(strict_types=1);
/**
 * ModalBridge
 *
 * Server-side bridge for firing modal lifecycle hooks. Modals in this
 * package open and close through Alpine on the client, so there is no
 * automatic server-side seam — apps that want to react to modal open
 * or close (analytics, permission gates, prefetch, etc.) should call
 * these methods when they invoke the modal.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      2.1.0
 */

namespace ArtisanPack\LivewireUiComponents\Support;

/**
 * ModalBridge Class
 *
 * @since 2.1.0
 */
class ModalBridge
{
    /**
     * Fire the `ap.livewireUiComponents.modalWillOpen` action.
     *
     * Call this from a Livewire action or controller immediately before
     * the modal is opened on the client.
     *
     * @since 2.1.0
     *
     * @param  string  $modalId  The modal's id (as passed to the `x-artisanpack-modal` `id` prop).
     */
    public static function willOpen(string $modalId): void
    {
        doAction('ap.livewireUiComponents.modalWillOpen', $modalId);
    }

    /**
     * Fire the `ap.livewireUiComponents.modalWillClose` action.
     *
     * Call this from a Livewire action or controller immediately before
     * the modal is closed on the client.
     *
     * @since 2.1.0
     *
     * @param  string  $modalId  The modal's id (as passed to the `x-artisanpack-modal` `id` prop).
     */
    public static function willClose(string $modalId): void
    {
        doAction('ap.livewireUiComponents.modalWillClose', $modalId);
    }
}
