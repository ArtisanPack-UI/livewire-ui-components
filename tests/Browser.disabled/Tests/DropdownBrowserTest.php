<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests\Browser\Tests;

use ArtisanPack\LivewireUiComponents\Tests\Browser\DuskTestCase;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;

/**
 * Browser tests for Dropdown component - Tests opening, closing, keyboard navigation
 */
class DropdownBrowserTest extends DuskTestCase
{
    #[Test]
    public function it_opens_dropdown_on_click(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/test/dropdown')
                ->assertMissing('@dropdown-menu')
                ->click('@dropdown-trigger')
                ->waitFor('@dropdown-menu', 2)
                ->assertVisible('@dropdown-menu');
        });
    }
}
