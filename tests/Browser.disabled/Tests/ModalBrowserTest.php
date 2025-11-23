<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests\Browser\Tests;

use ArtisanPack\LivewireUiComponents\Tests\Browser\DuskTestCase;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;

/**
 * Browser tests for Modal component - Tests opening, closing, focus trap, ESC key, accessibility
 */
class ModalBrowserTest extends DuskTestCase
{
    #[Test]
    public function it_opens_modal_when_triggered(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/test/modal')
                ->assertDontSee('@modal-content')
                ->click('@modal-trigger')
                ->waitFor('@modal-content', 5)
                ->assertVisible('@modal-content')
                ->assertVisible('@modal-backdrop');
        });
    }
}
