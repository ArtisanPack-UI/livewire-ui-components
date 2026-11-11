<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests\Feature\Components;

use ArtisanPack\LivewireUiComponents\Tests\TestCase;
use ArtisanPack\LivewireUiComponents\View\Components\Rating;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;
use PHPUnit\Framework\Attributes\Test;

/**
 * Rating (#53) in a feature context: the design-system component renders
 * its view and its defaults. The markup contract is covered in
 * tests/Unit/Components/RatingComponentTest.php; the old DaisyUI props
 * (`total`, `icon`, `color`, …) were removed in 3.0.
 */
class RatingTest extends TestCase
{
    #[Test]
    public function it_renders_its_view(): void
    {
        $view = (new Rating)->render();

        $this->assertInstanceOf(View::class, $view);
        $this->assertEquals('livewire-ui-components::components.rating', $view->name());
    }

    #[Test]
    public function it_has_default_values(): void
    {
        $component = new Rating;

        $this->assertNull($component->value);
        $this->assertSame(5, $component->max);
        $this->assertFalse($component->readonly);
        $this->assertSame('md', $component->size);
    }

    #[Test]
    public function it_renders_a_radiogroup_bound_with_wire_model(): void
    {
        $html = Blade::render('<x-artisanpack-rating wire:model="score" :max="3" />');

        $this->assertStringContainsString('role="radiogroup"', $html);
        $this->assertSame(3, substr_count($html, 'wire:model="score"'));
    }
}
