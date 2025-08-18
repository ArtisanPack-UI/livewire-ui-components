<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Feature\Components;

use ArtisanPack\LivewireUiComponents\View\Components\Rating;
use ArtisanPack\LivewireUiComponents\Tests\TestCase;
use Illuminate\View\Component;

class RatingTest extends TestCase
{
    /** @test */
    public function it_renders_basic_rating_component()
    {
        $component = new Rating();
        $view = $component->render();

        $this->assertInstanceOf(\Illuminate\Contracts\View\View::class, $view);
        $this->assertEquals('livewire-ui-components::components.rating', $view->name());
    }

    /** @test */
    public function it_has_default_values()
    {
        $component = new Rating();

        $this->assertNull($component->id);
        $this->assertEquals(5, $component->total);
        $this->assertEquals('heroicon-s-star', $component->icon);
        $this->assertEquals('warning', $component->color);
        $this->assertEquals('gray-200', $component->emptyColor);
        $this->assertEquals('md', $component->size);
        $this->assertFalse($component->halfStars);
        $this->assertFalse($component->hoverEffect);
        $this->assertFalse($component->showValue);
        $this->assertEquals('{value}', $component->valueFormat);
        $this->assertFalse($component->clearable);
        $this->assertEquals('heroicon-o-x-circle', $component->clearIcon);
        $this->assertFalse($component->inlineLabel);
        $this->assertFalse($component->required);
        $this->assertFalse($component->disabled);
        $this->assertFalse($component->readonly);
        $this->assertEquals(0, $component->value);
    }

    /** @test */
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

    /** @test */
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

    /** @test */
    public function it_resolves_filled_color_with_priority()
    {
        // filledColor takes priority
        $component = new Rating(color: 'primary', filledColor: 'red-500');
        $this->assertEquals('red-500', $component->resolveFilledColor());

        // color is fallback
        $component = new Rating(color: 'primary');
        $this->assertEquals('primary', $component->resolveFilledColor());

        // default fallback
        $component = new Rating();
        $this->assertEquals('warning', $component->resolveFilledColor());
    }

    /** @test */
    public function it_resolves_empty_color_correctly()
    {
        $component = new Rating(emptyColor: 'gray-400');
        $this->assertEquals('gray-400', $component->resolveEmptyColor());

        $component = new Rating();
        $this->assertEquals('gray-200', $component->resolveEmptyColor());
    }

    /** @test */
    public function it_resolves_filled_icon_with_priority()
    {
        // filledIcon takes priority
        $component = new Rating(icon: 'heroicon-o-heart', filledIcon: 'heroicon-s-heart');
        $this->assertEquals('heroicon-s-heart', $component->resolveFilledIcon());

        // icon is fallback
        $component = new Rating(icon: 'heroicon-o-heart');
        $this->assertEquals('heroicon-o-heart', $component->resolveFilledIcon());

        // default fallback
        $component = new Rating();
        $this->assertEquals('heroicon-s-star', $component->resolveFilledIcon());
    }

    /** @test */
    public function it_resolves_empty_icon_with_priority()
    {
        // emptyIcon takes priority
        $component = new Rating(icon: 'heroicon-s-heart', emptyIcon: 'heroicon-o-heart');
        $this->assertEquals('heroicon-o-heart', $component->resolveEmptyIcon());

        // icon is fallback
        $component = new Rating(icon: 'heroicon-s-heart');
        $this->assertEquals('heroicon-s-heart', $component->resolveEmptyIcon());

        // default fallback
        $component = new Rating();
        $this->assertEquals('heroicon-o-star', $component->resolveEmptyIcon());
    }

    /** @test */
    public function it_generates_correct_color_classes()
    {
        $component = new Rating();

        // Semantic colors
        $this->assertEquals('text-primary', $component->getColorClass('primary'));
        $this->assertEquals('text-warning', $component->getColorClass('warning'));
        $this->assertEquals('text-error', $component->getColorClass('error'));

        // Tailwind colors
        $this->assertEquals('text-red-500', $component->getColorClass('red-500'));
        $this->assertEquals('text-blue-300', $component->getColorClass('blue-300'));

        // Hex colors return empty (handled by inline styles)
        $this->assertEquals('', $component->getColorClass('#ff0000'));
        $this->assertEquals('', $component->getColorClass('#00ff00'));
    }

    /** @test */
    public function it_generates_correct_color_styles()
    {
        $component = new Rating();

        // Hex colors generate inline styles
        $this->assertEquals('color: #ff0000;', $component->getColorStyle('#ff0000'));
        $this->assertEquals('color: #00ff00;', $component->getColorStyle('#00ff00'));

        // Non-hex colors return empty
        $this->assertEquals('', $component->getColorStyle('primary'));
        $this->assertEquals('', $component->getColorStyle('red-500'));
    }

    /** @test */
    public function it_generates_correct_size_classes()
    {
        $this->assertEquals('rating-sm', (new Rating(size: 'sm'))->getSizeClasses());
        $this->assertEquals('rating-md', (new Rating(size: 'md'))->getSizeClasses());
        $this->assertEquals('rating-lg', (new Rating(size: 'lg'))->getSizeClasses());
        $this->assertEquals('rating-xl', (new Rating(size: 'xl'))->getSizeClasses());
        $this->assertEquals('rating-md', (new Rating(size: 'invalid'))->getSizeClasses());
    }

    /** @test */
    public function it_formats_value_display_correctly()
    {
        // Default format
        $component = new Rating(showValue: true, value: 3);
        $this->assertEquals('3', $component->getFormattedValue());

        // Custom format with placeholders
        $component = new Rating(showValue: true, value: 3.5, total: 5, valueFormat: '{value}/{max}');
        $this->assertEquals('3.5/5', $component->getFormattedValue());

        // Returns empty when showValue is false
        $component = new Rating(showValue: false, value: 4);
        $this->assertEquals('', $component->getFormattedValue());
    }

    /** @test */
    public function it_determines_star_state_without_half_stars()
    {
        $component = new Rating(halfStars: false, value: 3);

        $this->assertEquals('filled', $component->getStarState(1));
        $this->assertEquals('filled', $component->getStarState(2));
        $this->assertEquals('filled', $component->getStarState(3));
        $this->assertEquals('empty', $component->getStarState(4));
        $this->assertEquals('empty', $component->getStarState(5));
    }

    /** @test */
    public function it_determines_star_state_with_half_stars()
    {
        $component = new Rating(halfStars: true, value: 3.5);

        $this->assertEquals('filled', $component->getStarState(1));
        $this->assertEquals('filled', $component->getStarState(2));
        $this->assertEquals('filled', $component->getStarState(3));
        $this->assertEquals('half', $component->getStarState(4));
        $this->assertEquals('empty', $component->getStarState(5));
    }

    /** @test */
    public function it_gets_correct_star_icon_based_on_state()
    {
        $component = new Rating(
            halfStars: true,
            value: 2.5,
            icon: 'heroicon-s-star',
            filledIcon: 'heroicon-s-star',
            emptyIcon: 'heroicon-o-star'
        );

        $this->assertEquals('heroicon-s-star', $component->getStarIcon(1)); // filled
        $this->assertEquals('heroicon-s-star', $component->getStarIcon(2)); // filled
        $this->assertEquals('heroicon-s-star', $component->getStarIcon(3)); // half (uses filled icon)
        $this->assertEquals('heroicon-o-star', $component->getStarIcon(4)); // empty
        $this->assertEquals('heroicon-o-star', $component->getStarIcon(5)); // empty
    }

    /** @test */
    public function it_gets_correct_star_color_class_based_on_state()
    {
        $component = new Rating(
            halfStars: true,
            value: 2.5,
            filledColor: 'warning',
            emptyColor: 'gray-200'
        );

        $this->assertEquals('text-warning', $component->getStarColorClass(1)); // filled
        $this->assertEquals('text-warning', $component->getStarColorClass(2)); // filled
        $this->assertEquals('text-warning', $component->getStarColorClass(3)); // half
        $this->assertEquals('text-gray-200', $component->getStarColorClass(4)); // empty
        $this->assertEquals('text-gray-200', $component->getStarColorClass(5)); // empty
    }

    /** @test */
    public function it_gets_correct_star_color_style_based_on_state()
    {
        $component = new Rating(
            halfStars: true,
            value: 2.5,
            filledColor: '#ff0000',
            emptyColor: '#cccccc'
        );

        $this->assertEquals('color: #ff0000;', $component->getStarColorStyle(1)); // filled
        $this->assertEquals('color: #ff0000;', $component->getStarColorStyle(2)); // filled
        $this->assertEquals('color: #ff0000;', $component->getStarColorStyle(3)); // half
        $this->assertEquals('color: #cccccc;', $component->getStarColorStyle(4)); // empty
        $this->assertEquals('color: #cccccc;', $component->getStarColorStyle(5)); // empty
    }

    /** @test */
    public function it_detects_half_values_correctly()
    {
        $this->assertTrue((new Rating(value: 3.5))->hasHalfValue());
        $this->assertTrue((new Rating(value: 2.7))->hasHalfValue());
        $this->assertFalse((new Rating(value: 3.0))->hasHalfValue());
        $this->assertFalse((new Rating(value: 4.3))->hasHalfValue());
        $this->assertFalse((new Rating(value: 0))->hasHalfValue());
    }

    /** @test */
    public function it_accepts_float_values()
    {
        $component = new Rating(value: 3.5);
        $this->assertEquals(3.5, $component->value);

        $component = new Rating(value: 4);
        $this->assertEquals(4, $component->value);
    }

    /** @test */
    public function it_generates_unique_uuid()
    {
        $component1 = new Rating();
        $component2 = new Rating();

        $this->assertNotEquals($component1->uuid, $component2->uuid);
        $this->assertStringStartsWith('artisanpack', $component1->uuid);
        $this->assertStringStartsWith('artisanpack', $component2->uuid);
    }

    /** @test */
    public function it_uses_custom_id_in_uuid()
    {
        $component = new Rating(id: 'custom-rating');
        $this->assertStringEndsWith('custom-rating', $component->uuid);
    }

    /** @test */
    public function it_maintains_backward_compatibility()
    {
        // Old usage should still work
        $component = new Rating(id: 'old-rating', total: 10);

        $this->assertEquals('old-rating', $component->id);
        $this->assertEquals(10, $component->total);

        // Default values should match old behavior
        $this->assertEquals('heroicon-s-star', $component->icon);
        $this->assertEquals('warning', $component->color);
    }
}
