<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Feature\Components;

use ArtisanPack\LivewireUiComponents\View\Components\Rating;
use ArtisanPack\LivewireUiComponents\Tests\TestCase;
use Illuminate\View\Component;
use PHPUnit\Framework\Attributes\Test;

class RatingTest extends TestCase
{
    #[Test]
    public function it_renders_basic_rating_component()
    {
        $component = new Rating();
        $view = $component->render();

        $this->assertInstanceOf(\Illuminate\Contracts\View\View::class, $view);
        $this->assertEquals('livewire-ui-components::components.rating', $view->name());
    }

    #[Test]
    public function it_has_default_values()
    {
        $component = new Rating();

        $this->assertNull($component->id);
        $this->assertEquals(5, $component->total);
        $this->assertEquals('s-star', $component->icon);
        $this->assertEquals('warning', $component->color);
        $this->assertEquals('gray-200', $component->emptyColor);
        $this->assertEquals('md', $component->size);
        $this->assertFalse($component->halfStars);
        $this->assertFalse($component->hoverEffect);
        $this->assertFalse($component->showValue);
        $this->assertEquals('{value}', $component->valueFormat);
        $this->assertFalse($component->clearable);
        $this->assertEquals('o-x-circle', $component->clearIcon);
        $this->assertFalse($component->inlineLabel);
        $this->assertFalse($component->required);
        $this->assertFalse($component->disabled);
        $this->assertFalse($component->readonly);
        $this->assertEquals(0, $component->value);
    }

    #[Test]
    public function it_accepts_custom_icon_props()
    {
        $component = new Rating(
            icon: 'heroicon-o-heart',
            filledIcon: 'heroicon-s-heart',
            emptyIcon: 'heroicon-o-heart'
        );

        $this->assertEquals('heroicon-o-heart', $component->icon);
        $this->assertEquals('heroicon-s-heart', $component->filledIcon);
        $this->assertEquals('heroicon-o-heart', $component->emptyIcon);
    }

    #[Test]
    public function it_accepts_custom_color_props()
    {
        $component = new Rating(
            color: 'primary',
            filledColor: 'red-500',
            emptyColor: 'gray-300'
        );

        $this->assertEquals('primary', $component->color);
        $this->assertEquals('red-500', $component->filledColor);
        $this->assertEquals('gray-300', $component->emptyColor);
    }

    #[Test]
    public function it_resolves_filled_color_with_priority()
    {
        // filledColor takes priority
        $component = new Rating(color: 'primary', filledColor: 'red-500');
        $this->assertEquals('red-500', $component->filledColor);

        // color is fallback when filledColor is null
        $component = new Rating(color: 'primary');
        $this->assertEquals('primary', $component->color);
        $this->assertNull($component->filledColor);

        // default fallback
        $component = new Rating();
        $this->assertEquals('warning', $component->color);
    }

    #[Test]
    public function it_resolves_empty_color_correctly()
    {
        $component = new Rating(emptyColor: 'gray-400');
        $this->assertEquals('gray-400', $component->emptyColor);

        $component = new Rating();
        $this->assertEquals('gray-200', $component->emptyColor);
    }

    #[Test]
    public function it_resolves_filled_icon_with_priority()
    {
        // filledIcon takes priority
        $component = new Rating(icon: 'o-heart', filledIcon: 's-heart');
        $this->assertEquals('s-heart', $component->filledIcon);

        // icon is fallback when filledIcon is null
        $component = new Rating(icon: 'o-heart');
        $this->assertEquals('o-heart', $component->icon);
        $this->assertNull($component->filledIcon);

        // default fallback
        $component = new Rating();
        $this->assertEquals('s-star', $component->icon);
    }

    #[Test]
    public function it_resolves_empty_icon_with_priority()
    {
        // emptyIcon takes priority
        $component = new Rating(icon: 's-heart', emptyIcon: 'o-heart');
        $this->assertEquals('o-heart', $component->emptyIcon);

        // icon is fallback when emptyIcon is null
        $component = new Rating(icon: 's-heart');
        $this->assertEquals('s-heart', $component->icon);
        $this->assertNull($component->emptyIcon);

        // default fallback
        $component = new Rating();
        $this->assertEquals('s-star', $component->icon);
        $this->assertNull($component->emptyIcon);
    }






    #[Test]
    public function it_accepts_float_values()
    {
        $component = new Rating(value: 3.5);
        $this->assertEquals(3.5, $component->value);

        $component = new Rating(value: 4);
        $this->assertEquals(4, $component->value);
    }

    #[Test]
    public function it_generates_unique_uuid()
    {
        $component1 = new Rating();
        $component2 = new Rating();

        $this->assertNotEquals($component1->uuid, $component2->uuid);
        $this->assertStringStartsWith('artisanpack', $component1->uuid);
        $this->assertStringStartsWith('artisanpack', $component2->uuid);
    }

    #[Test]
    public function it_uses_custom_id_in_uuid()
    {
        $component = new Rating(id: 'custom-rating');
        $this->assertStringEndsWith('custom-rating', $component->uuid);
    }

    #[Test]
    public function it_maintains_backward_compatibility()
    {
        // Old usage should still work
        $component = new Rating(id: 'old-rating', total: 10);

        $this->assertEquals('old-rating', $component->id);
        $this->assertEquals(10, $component->total);

        // Default values should match old behavior
        $this->assertEquals('s-star', $component->icon);
        $this->assertEquals('warning', $component->color);
    }
}
