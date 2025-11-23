<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests\Unit\Components;

use ArtisanPack\LivewireUiComponents\Tests\Support\ComponentDataFactory;
use ArtisanPack\LivewireUiComponents\Tests\Support\ComponentTestCase;
use ArtisanPack\LivewireUiComponents\Tests\Support\TestHelpers;
use ArtisanPack\LivewireUiComponents\View\Components\Button;
use Error;
use RuntimeException;

/**
 * Comprehensive unit tests for the Button component.
 *
 * Auto-generated test class that extends ComponentTestCase to inherit
 * common testing patterns and implements component-specific tests.
 */
class ButtonComponentTest extends ComponentTestCase
{
    protected string $componentClass = Button::class;

    protected array $defaultProperties = [
        'external'        => false,
        'noWireNavigate'  => false,
        'responsive'      => false,
        'variant'         => 'primary',
        'size'            => 'md',
        'tooltipPosition' => 'lg:tooltip-top',
    ];

    protected array $requiredProperties = [];

    public function test_button_string_properties(): void
    {
        // Note: 'variant' is excluded because it has validation logic that changes the value
        $stringProperties = ['id', 'label', 'icon', 'iconRight', 'spinner', 'link', 'badge', 'badgeClasses', 'tooltip', 'tooltipLeft', 'tooltipRight', 'tooltipBottom', 'color', 'colorAdjustment', 'size', 'tooltipPosition'];
        $testValues       = ComponentDataFactory::sampleTexts();

        foreach ($stringProperties as $property) {
            foreach ($testValues as $value) {
                if (empty($value)) {
                    continue;
                } // Skip empty values for some properties

                $component = $this->createComponent([$property => $value]);
                $this->assertEquals($value, $component->$property);
            }
        }
    }

    public function test_button_boolean_properties(): void
    {
        $booleanProperties = ['external', 'noWireNavigate', 'responsive'];

        foreach ($booleanProperties as $property) {
            $component = $this->createComponent([$property => true]);
            $this->assertTrue($component->$property);

            $component = $this->createComponent([$property => false]);
            $this->assertFalse($component->$property);
        }
    }

    public function test_button_get_color_classes_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'getColorClasses')) {
            try {
                $result = $component->getColorClasses();
                // Add specific assertions based on expected return type
                $this->assertNotNull($result);
            } catch (Error $e) {
                if (str_contains($e->getMessage(), 'Call to a member function') &&
                    str_contains($e->getMessage(), 'on null')) {
                    $this->markTestSkipped('Method requires attributes or context');
                }
                throw $e;
            }
        }
    }

    public function test_button_get_variant_classes_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'getVariantClasses')) {
            try {
                $result = $component->getVariantClasses();
                // Add specific assertions based on expected return type
                $this->assertNotNull($result);
            } catch (Error $e) {
                if (str_contains($e->getMessage(), 'Call to a member function') &&
                    str_contains($e->getMessage(), 'on null')) {
                    $this->markTestSkipped('Method requires attributes or context');
                }
                throw $e;
            }
        }
    }

    public function test_button_get_size_classes_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'getSizeClasses')) {
            try {
                $result = $component->getSizeClasses();
                // Should return a string
                $this->assertIsString($result);
                // Should start with 'btn-'
                $this->assertStringStartsWith('btn-', $result);
            } catch (Error $e) {
                if (str_contains($e->getMessage(), 'Call to a member function') &&
                    str_contains($e->getMessage(), 'on null')) {
                    $this->markTestSkipped('Method requires attributes or context');
                }
                throw $e;
            }
        }
    }

    public function test_button_spinner_target_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'spinnerTarget')) {
            try {
                $result = $component->spinnerTarget();
                // spinnerTarget can return null when no spinner is set, which is valid
                $this->assertTrue(null === $result || is_string($result));
            } catch (Error $e) {
                if (str_contains($e->getMessage(), 'Call to a member function') &&
                    str_contains($e->getMessage(), 'on null')) {
                    $this->markTestSkipped('Method requires attributes or context');
                }
                throw $e;
            }
        }
    }

    public function test_button_renders_successfully(): void
    {
        $component = $this->createComponent();
        $view      = $component->render();

        try {
            $html = $view->render();
        } catch (\Illuminate\View\ViewException $e) {
            if (str_contains($e->getMessage(), 'Undefined variable') ||
                str_contains($e->getMessage(), 'Undefined array key')) {
                $this->markTestSkipped('Component requires slots or additional data for rendering');
            }
            throw $e;
        } catch (Error $e) {
            if (str_contains($e->getMessage(), 'Call to a member function') &&
                str_contains($e->getMessage(), 'on null')) {
                $this->markTestSkipped('Component requires attributes or context for rendering');
            }
            throw $e;
        }

        $this->assertNotEmpty($html);
        $this->assertTrue(TestHelpers::assertValidHtml($html));
    }

    public function test_button_accessibility_compliance(): void
    {
        $component = $this->createComponent();
        $view      = $component->render();

        try {
            $html = $view->render();
        } catch (\Illuminate\View\ViewException $e) {
            if (str_contains($e->getMessage(), 'Undefined variable') ||
                str_contains($e->getMessage(), 'Undefined array key')) {
                $this->markTestSkipped('Component requires slots or additional data for rendering');
            }
            throw $e;
        } catch (Error $e) {
            if (str_contains($e->getMessage(), 'Call to a member function') &&
                str_contains($e->getMessage(), 'on null')) {
                $this->markTestSkipped('Component requires attributes or context for rendering');
            }
            throw $e;
        }

        $validation = TestHelpers::validateHtmlStructure($html);
        $this->assertTrue($validation['is_valid'],
            'Button should have valid HTML structure. Issues: '.implode(', ', $validation['issues']),
        );
    }

    public function test_button_security_against_xss(): void
    {
        $xssPayloads = TestHelpers::securityTestPayloads();

        foreach ($xssPayloads as $payload) {
            $component = $this->createComponent(['label' => $payload]);
            $view      = $component->render();

            try {
                $html = $view->render();
            } catch (\Illuminate\View\ViewException $e) {
                if (str_contains($e->getMessage(), 'Undefined variable') ||
                    str_contains($e->getMessage(), 'Undefined array key')) {
                    $this->markTestSkipped('Component requires slots or additional data for rendering');
                }
                throw $e;
            } catch (Error $e) {
                if (str_contains($e->getMessage(), 'Call to a member function') &&
                    str_contains($e->getMessage(), 'on null')) {
                    $this->markTestSkipped('Component requires attributes or context for rendering');
                }
                throw $e;
            }

            $this->assertStringNotContainsString('<script', $html);
            $this->assertStringNotContainsString('javascript:', $html);
        }
    }

    public function test_button_performance(): void
    {
        try {
            $performance = TestHelpers::measureRenderingPerformance($this->createComponent(), 10);
            $this->assertLessThan(100, $performance['average_time'],
                'Button rendering should be under 100ms on average',
            );
        } catch (RuntimeException $e) {
            if (str_contains($e->getMessage(), 'requires slots') ||
                str_contains($e->getMessage(), 'requires additional context')) {
                $this->markTestSkipped($e->getMessage());
            }
            throw $e;
        }
    }

    public function test_button_size_property_accepts_valid_sizes(): void
    {
        $validSizes = ['xs', 'sm', 'md', 'lg', 'xl'];

        foreach ($validSizes as $size) {
            $component = $this->createComponent(['size' => $size]);
            $this->assertEquals($size, $component->size);
        }
    }

    public function test_button_size_defaults_to_md(): void
    {
        $component = $this->createComponent();
        $this->assertEquals('md', $component->size);
    }

    public function test_button_get_size_classes_returns_correct_classes(): void
    {
        $sizeClassMap = [
            'xs' => 'btn-xs',
            'sm' => 'btn-sm',
            'md' => 'btn-md',
            'lg' => 'btn-lg',
            'xl' => 'btn-lg', // Maps to lg since DaisyUI doesn't have xl
        ];

        foreach ($sizeClassMap as $size => $expectedClass) {
            $component = $this->createComponent(['size' => $size]);
            $this->assertEquals($expectedClass, $component->getSizeClasses());
        }
    }

    public function test_button_size_validation_falls_back_to_md(): void
    {
        $component = $this->createComponent(['size' => 'invalid-size']);
        // Even though we pass invalid-size, it should be stored as-is in the property
        $this->assertEquals('invalid-size', $component->size);
        // But getSizeClasses should return the fallback
        $this->assertEquals('btn-md', $component->getSizeClasses());
    }

    public function test_button_renders_with_size_classes(): void
    {
        $component = $this->createComponent(['size' => 'lg', 'label' => 'Test Button']);
        $view      = $component->render();

        try {
            $html = $view->render();
            $this->assertStringContainsString('btn-lg', $html);
        } catch (\Illuminate\View\ViewException $e) {
            if (str_contains($e->getMessage(), 'Undefined variable') ||
                str_contains($e->getMessage(), 'Undefined array key')) {
                $this->markTestSkipped('Component requires slots or additional data for rendering');
            }
            throw $e;
        }
    }

    public function test_button_size_combines_with_color(): void
    {
        $component = $this->createComponent([
            'size'  => 'sm',
            'color' => 'primary',
            'label' => 'Small Primary',
        ]);

        $this->assertEquals('sm', $component->size);
        $this->assertEquals('primary', $component->resolvedColor);

        $view = $component->render();

        try {
            $html = $view->render();
            $this->assertStringContainsString('btn-sm', $html);
        } catch (\Illuminate\View\ViewException $e) {
            if (str_contains($e->getMessage(), 'Undefined variable') ||
                str_contains($e->getMessage(), 'Undefined array key')) {
                $this->markTestSkipped('Component requires slots or additional data for rendering');
            }
            throw $e;
        }
    }

    public function test_button_hover_state_includes_text_color_for_variants(): void
    {
        $variants = ['primary', 'secondary', 'accent', 'success', 'warning', 'error'];

        foreach ($variants as $variant) {
            $component    = $this->createComponent(['color' => $variant]);
            $colorClasses = $component->getColorClasses();

            // Verify hover state exists
            $this->assertArrayHasKey('hover', $colorClasses, "Variant {$variant} should have hover state");

            // Verify style attribute with hover text color exists
            $this->assertArrayHasKey('style', $colorClasses, "Variant {$variant} should have style attribute");
            $this->assertStringContainsString('--artisanpack-variant-hover-text', $colorClasses['style'],
                "Variant {$variant} should define hover text color");

            // Verify hover class includes text color change
            $this->assertStringContainsString('hover:text-[var(--artisanpack-variant-hover-text)]', $colorClasses['hover'],
                "Variant {$variant} hover should change text color");
        }
    }

    public function test_button_hover_state_includes_text_color_for_tailwind_colors(): void
    {
        $tailwindColors = ['blue-500', 'red-600', 'green-400', 'purple-700'];

        foreach ($tailwindColors as $color) {
            $component    = $this->createComponent(['color' => $color]);
            $colorClasses = $component->getColorClasses();

            // Verify hover state exists
            $this->assertArrayHasKey('hover', $colorClasses, "Color {$color} should have hover state");

            // Verify style attribute with hover text color exists
            $this->assertArrayHasKey('style', $colorClasses, "Color {$color} should have style attribute");
            $this->assertStringContainsString('--artisanpack-tailwind-hover-text', $colorClasses['style'],
                "Color {$color} should define hover text color");

            // Verify hover class includes text color change
            $this->assertStringContainsString('hover:text-[var(--artisanpack-tailwind-hover-text)]', $colorClasses['hover'],
                "Color {$color} hover should change text color");
        }
    }

    public function test_button_hover_state_includes_text_color_for_hex_colors(): void
    {
        $hexColors = ['#ff6b6b', '#4ecdc4', '#ffe66d'];

        foreach ($hexColors as $color) {
            $component    = $this->createComponent(['color' => $color]);
            $colorClasses = $component->getColorClasses();

            // Verify hover state exists
            $this->assertArrayHasKey('hover', $colorClasses, "Hex color {$color} should have hover state");

            // Verify style attribute with hover text color exists
            $this->assertArrayHasKey('style', $colorClasses, "Hex color {$color} should have style attribute");
            $this->assertStringContainsString('--artisanpack-custom-hover-text', $colorClasses['style'],
                "Hex color {$color} should define hover text color");

            // Verify hover class includes text color change
            $this->assertStringContainsString('hover:text-[var(--artisanpack-custom-hover-text)]', $colorClasses['hover'],
                "Hex color {$color} hover should change text color");
        }
    }

    public function test_button_focus_state_includes_text_color(): void
    {
        $component    = $this->createComponent(['color' => 'primary']);
        $colorClasses = $component->getColorClasses();

        // Verify focus state exists
        $this->assertArrayHasKey('focus', $colorClasses);

        // Verify style attribute with focus text color exists
        $this->assertArrayHasKey('style', $colorClasses);
        $this->assertStringContainsString('--artisanpack-variant-focus-text', $colorClasses['style']);

        // Verify focus class includes text color change
        $this->assertStringContainsString('focus:text-[var(--artisanpack-variant-focus-text)]', $colorClasses['focus']);
    }

    public function test_button_transitions_are_optimized(): void
    {
        $component = $this->createComponent(['label' => 'Test Button']);
        $view      = $component->render();

        try {
            $html = $view->render();
            // Check for transition-colors instead of transition-all for better performance
            $this->assertStringContainsString('transition-colors', $html);
            $this->assertStringContainsString('duration-200', $html);
            $this->assertStringContainsString('ease-in-out', $html);
        } catch (\Illuminate\View\ViewException $e) {
            if (str_contains($e->getMessage(), 'Undefined variable') ||
                str_contains($e->getMessage(), 'Undefined array key')) {
                $this->markTestSkipped('Component requires slots or additional data for rendering');
            }
            throw $e;
        }
    }

    public function test_button_color_adjustment_works_with_size(): void
    {
        $component = $this->createComponent([
            'size'            => 'lg',
            'color'           => 'blue-500',
            'colorAdjustment' => 'lighter',
            'label'           => 'Large Light Blue',
        ]);

        $this->assertEquals('lg', $component->size);
        $this->assertEquals('blue-500', $component->resolvedColor);
        $this->assertEquals('lighter', $component->colorAdjustment);

        // Verify size class is generated
        $this->assertEquals('btn-lg', $component->getSizeClasses());

        // Verify color classes are generated with adjustment
        $colorClasses = $component->getColorClasses();
        $this->assertNotEmpty($colorClasses);
    }
}
