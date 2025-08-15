<?php

use ArtisanPack\LivewireUiComponents\View\Components\Button;
use PHPUnit\Framework\TestCase;

class ButtonTest extends TestCase
{
    public function test_button_uses_primary_variant_by_default()
    {
        $button = new Button();

        $this->assertEquals('primary', $button->variant);
        $this->assertEquals('primary', $button->resolvedColor);
        $this->assertEquals('btn-primary', $button->getVariantClasses());
    }

    public function test_button_accepts_valid_variants()
    {
        $validVariants = [
            'primary' => 'btn-primary',
            'secondary' => 'btn-secondary',
            'accent' => 'btn-accent',
            'success' => 'btn-success',
            'warning' => 'btn-warning',
            'error' => 'btn-error',
            'ghost' => 'btn-ghost',
            'outline' => 'btn-outline'
        ];

        foreach ($validVariants as $variant => $expectedClass) {
            $button = new Button(variant: $variant);

            $this->assertEquals($variant, $button->variant);
            $this->assertEquals($variant, $button->resolvedColor);
            $this->assertEquals($expectedClass, $button->getVariantClasses());
        }
    }

    public function test_button_falls_back_to_primary_for_invalid_variants()
    {
        $invalidVariants = ['invalid', 'nonexistent', '', null];

        foreach ($invalidVariants as $variant) {
            $button = new Button(variant: $variant);

            $this->assertEquals('primary', $button->variant);
            $this->assertEquals('primary', $button->resolvedColor);
            $this->assertEquals('btn-primary', $button->getVariantClasses());
        }
    }

    public function test_button_maintains_existing_functionality()
    {
        $button = new Button(
            id: 'test-btn',
            label: 'Test Button',
            icon: 'save',
            variant: 'success'
        );

        $this->assertEquals('test-btn', $button->id);
        $this->assertEquals('Test Button', $button->label);
        $this->assertEquals('save', $button->icon);
        $this->assertEquals('success', $button->variant);
        $this->assertEquals('success', $button->resolvedColor);
        $this->assertEquals('btn-success', $button->getVariantClasses());
    }

    public function test_button_generates_uuid()
    {
        $button = new Button();

        $this->assertNotEmpty($button->uuid);
        $this->assertStringStartsWith('artisanpack', $button->uuid);
    }

    // New Color System Tests

    public function test_button_color_prop_overrides_variant()
    {
        $button = new Button(variant: 'success', color: 'red-500');

        $this->assertEquals('success', $button->variant);
        $this->assertEquals('red-500', $button->resolvedColor);
    }

    public function test_button_resolves_predefined_color_variants()
    {
        $colorVariants = ['primary', 'secondary', 'accent', 'success', 'warning', 'error', 'info', 'neutral'];

        foreach ($colorVariants as $color) {
            $button = new Button(color: $color);
            $colorClasses = $button->getColorClasses();

            $this->assertNotEmpty($colorClasses);
            $this->assertArrayHasKey('bg', $colorClasses);
            $this->assertArrayHasKey('border', $colorClasses);
            $this->assertArrayHasKey('text', $colorClasses);
        }
    }

    public function test_button_resolves_tailwind_colors()
    {
        $button = new Button(color: 'blue-500');
        $colorClasses = $button->getColorClasses();

        $expected = [
            'bg' => 'bg-blue-500',
            'border' => 'border-blue-500',
            'text' => 'text-white'
        ];

        $this->assertEquals($expected, $colorClasses);
    }

    public function test_button_resolves_tailwind_colors_without_intensity()
    {
        $button = new Button(color: 'red');
        $colorClasses = $button->getColorClasses();

        $expected = [
            'bg' => 'bg-red-500',
            'border' => 'border-red-500',
            'text' => 'text-white'
        ];

        $this->assertEquals($expected, $colorClasses);
    }

    public function test_button_resolves_hex_colors()
    {
        $button = new Button(color: '#FF5733');
        $colorClasses = $button->getColorClasses();

        $this->assertArrayHasKey('style', $colorClasses);
        $this->assertArrayHasKey('bg', $colorClasses);
        $this->assertArrayHasKey('border', $colorClasses);
        $this->assertArrayHasKey('text', $colorClasses);
        
        $this->assertEquals('--artisanpack-custom-color: #FF5733;', $colorClasses['style']);
        $this->assertEquals('[background-color:var(--artisanpack-custom-color)]', $colorClasses['bg']);
        $this->assertEquals('[border-color:var(--artisanpack-custom-color)]', $colorClasses['border']);
        $this->assertContains($colorClasses['text'], ['text-black', 'text-white']);
    }

    public function test_button_applies_color_adjustments()
    {
        // Test lighter adjustment
        $button = new Button(color: 'blue-500', colorAdjustment: 'lighter');
        $colorClasses = $button->getColorClasses();

        $this->assertEquals('bg-blue-100', $colorClasses['bg']);
        $this->assertEquals('border-blue-500', $colorClasses['border']);

        // Test darker adjustment
        $button = new Button(color: 'red-500', colorAdjustment: 'darker');
        $colorClasses = $button->getColorClasses();

        $this->assertEquals('bg-red-700', $colorClasses['bg']);
        $this->assertEquals('border-red-500', $colorClasses['border']);

        // Test transparent adjustment
        $button = new Button(color: 'green-500', colorAdjustment: 'transparent');
        $colorClasses = $button->getColorClasses();

        $this->assertEquals('bg-transparent', $colorClasses['bg']);
        $this->assertEquals('border-green-500', $colorClasses['border']);

        // Test subtle adjustment
        $button = new Button(color: 'purple-500', colorAdjustment: 'subtle');
        $colorClasses = $button->getColorClasses();

        $this->assertEquals('bg-purple-50', $colorClasses['bg']);
        $this->assertEquals('border-purple-500', $colorClasses['border']);
    }

    public function test_button_handles_invalid_colors()
    {
        $button = new Button(color: 'invalid-color');
        $colorClasses = $button->getColorClasses();

        // Should fall back to DaisyUI variant classes
        $this->assertNotEmpty($colorClasses);
        $this->assertArrayHasKey('btn', $colorClasses);
    }

    public function test_button_maintains_backward_compatibility_when_no_color_specified()
    {
        $button = new Button(variant: 'success');
        
        // When no color is specified, should use variant for color resolution
        $this->assertEquals('success', $button->resolvedColor);
        
        $colorClasses = $button->getColorClasses();
        $this->assertNotEmpty($colorClasses);
    }

    public function test_button_color_adjustment_with_variants()
    {
        $button = new Button(color: 'primary', colorAdjustment: 'lighter');
        $colorClasses = $button->getColorClasses();

        $this->assertNotEmpty($colorClasses);
        $this->assertArrayHasKey('bg', $colorClasses);
        $this->assertArrayHasKey('border', $colorClasses);
        $this->assertArrayHasKey('text', $colorClasses);
    }
}
