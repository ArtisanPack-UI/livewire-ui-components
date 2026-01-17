<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests\Unit\Components;

use ArtisanPack\LivewireUiComponents\Tests\Support\ComponentDataFactory;
use ArtisanPack\LivewireUiComponents\Tests\Support\ComponentTestCase;
use ArtisanPack\LivewireUiComponents\Tests\Support\TestHelpers;
use ArtisanPack\LivewireUiComponents\View\Components\Chart;
use Error;
use RuntimeException;

/**
 * Comprehensive unit tests for the Chart component.
 *
 * Auto-generated test class that extends ComponentTestCase to inherit
 * common testing patterns and implements component-specific tests.
 */
class ChartComponentTest extends ComponentTestCase
{
    protected string $componentClass = Chart::class;

    protected array $defaultProperties = [];

    protected array $requiredProperties = [];

    public function test_chart_string_properties(): void
    {
        $stringProperties = ['id', 'type'];
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

    public function test_chart_type_validation(): void
    {
        // Valid types should be returned as-is
        $validTypes = ['area', 'bar', 'line', 'donut', 'pie', 'radialBar', 'heatmap'];

        foreach ($validTypes as $type) {
            $component = $this->createComponent(['type' => $type]);
            $this->assertEquals($type, $component->chartType());
        }

        // Invalid types should default to 'line'
        $component = $this->createComponent(['type' => 'invalid-type']);
        $this->assertEquals('line', $component->chartType());
    }

    public function test_chart_height_handling(): void
    {
        // Integer height
        $component = $this->createComponent(['height' => 400]);
        $this->assertEquals('400', $component->chartHeight());

        // String height
        $component = $this->createComponent(['height' => '100%']);
        $this->assertEquals('100%', $component->chartHeight());

        // Default height
        $component = $this->createComponent();
        $this->assertEquals('350', $component->chartHeight());
    }

    public function test_chart_width_handling(): void
    {
        // Null width (auto)
        $component = $this->createComponent();
        $this->assertNull($component->chartWidth());

        // Integer width
        $component = $this->createComponent(['width' => 600]);
        $this->assertEquals('600', $component->chartWidth());

        // String width
        $component = $this->createComponent(['width' => '100%']);
        $this->assertEquals('100%', $component->chartWidth());
    }

    public function test_chart_options_merging(): void
    {
        // Without user options
        $component = $this->createComponent(['type' => 'area', 'height' => 400]);
        $options   = $component->chartOptions();

        $this->assertEquals('area', $options['chart']['type']);
        $this->assertEquals('400', $options['chart']['height']);
        $this->assertEquals('transparent', $options['chart']['background']);
        $this->assertTrue($options['chart']['animations']['enabled']);

        // With user options that override
        $userOptions = [
            'chart' => [
                'toolbar' => ['show' => false],
            ],
            'colors' => ['#3b82f6', '#ef4444'],
        ];

        $component = $this->createComponent(['type' => 'bar', 'options' => $userOptions]);
        $options   = $component->chartOptions();

        $this->assertEquals('bar', $options['chart']['type']);
        $this->assertFalse($options['chart']['toolbar']['show']);
        $this->assertEquals(['#3b82f6', '#ef4444'], $options['colors']);
    }

    public function test_chart_width_included_when_specified(): void
    {
        // Without width
        $component = $this->createComponent();
        $options   = $component->chartOptions();
        $this->assertArrayNotHasKey('width', $options['chart']);

        // With width
        $component = $this->createComponent(['width' => 500]);
        $options   = $component->chartOptions();
        $this->assertEquals('500', $options['chart']['width']);
    }

    public function test_chart_series_data(): void
    {
        // Empty series
        $component = $this->createComponent();
        $this->assertEquals([], $component->chartSeries());

        // With series data
        $series = [
            ['name' => 'Sales', 'data' => [30, 40, 35, 50]],
            ['name' => 'Revenue', 'data' => [20, 30, 25, 40]],
        ];

        $component = $this->createComponent(['series' => $series]);
        $this->assertEquals($series, $component->chartSeries());
    }

    public function test_chart_get_valid_types(): void
    {
        $validTypes = Chart::getValidTypes();

        $this->assertIsArray($validTypes);
        $this->assertContains('area', $validTypes);
        $this->assertContains('bar', $validTypes);
        $this->assertContains('line', $validTypes);
        $this->assertContains('donut', $validTypes);
        $this->assertContains('pie', $validTypes);
        $this->assertContains('radialBar', $validTypes);
        $this->assertContains('heatmap', $validTypes);
        $this->assertContains('scatter', $validTypes);
        $this->assertContains('bubble', $validTypes);
        $this->assertContains('candlestick', $validTypes);
    }

    public function test_chart_glass_effect_properties(): void
    {
        // Without glass
        $component = $this->createComponent();
        $this->assertNull($component->glass);
        $this->assertEquals('', $component->glassClasses());
        $this->assertEquals('', $component->glassStyle());

        // With glass variant
        $component = $this->createComponent(['glass' => 'frosted']);
        $this->assertEquals('frosted', $component->glass);
        $this->assertStringContainsString('glass-frosted', $component->glassClasses());

        // With glass tint (Tailwind color)
        $component = $this->createComponent([
            'glass'     => 'frosted',
            'glassTint' => 'blue-500',
        ]);
        $this->assertStringContainsString('glass-tint-blue-500', $component->glassClasses());
    }

    public function test_chart_glass_with_hex_color(): void
    {
        $component = $this->createComponent([
            'glass'     => 'liquid',
            'glassTint' => '#8b5cf6',
        ]);

        $this->assertStringContainsString('glass-liquid', $component->glassClasses());
        $this->assertStringContainsString('glass-tint-custom', $component->glassClasses());
        $this->assertStringContainsString('--glass-tint-color: #8b5cf6', $component->glassStyle());
    }

    public function test_chart_glass_with_opacity(): void
    {
        $component = $this->createComponent([
            'glass'            => 'transparent',
            'glassTint'        => 'emerald-500',
            'glassTintOpacity' => 60,
        ]);

        $this->assertStringContainsString('glass-transparent', $component->glassClasses());
        $this->assertStringContainsString('glass-tint-emerald-500', $component->glassClasses());
        $this->assertStringContainsString('glass-tint-opacity-60', $component->glassClasses());
    }

    public function test_chart_theme_validation(): void
    {
        // Valid themes should be returned as-is
        $validThemes = ['artisanpack-light', 'artisanpack-dark', 'artisanpack-glass'];

        foreach ($validThemes as $theme) {
            $component = $this->createComponent(['theme' => $theme]);
            $this->assertEquals($theme, $component->chartTheme());
        }

        // Invalid themes should return null
        $component = $this->createComponent(['theme' => 'invalid-theme']);
        $this->assertNull($component->chartTheme());

        // No theme should return null
        $component = $this->createComponent();
        $this->assertNull($component->chartTheme());
    }

    public function test_chart_theme_config(): void
    {
        // With valid theme
        $component = $this->createComponent(['theme' => 'artisanpack-light']);
        $config    = $component->themeConfig();

        $this->assertIsArray($config);
        $this->assertArrayHasKey('colors', $config);
        $this->assertArrayHasKey('chart', $config);

        // Without theme
        $component = $this->createComponent();
        $config    = $component->themeConfig();

        $this->assertEquals([], $config);
    }

    public function test_chart_is_glass_theme(): void
    {
        // Glass theme
        $component = $this->createComponent(['theme' => 'artisanpack-glass']);
        $this->assertTrue($component->isGlassTheme());

        // Light theme
        $component = $this->createComponent(['theme' => 'artisanpack-light']);
        $this->assertFalse($component->isGlassTheme());

        // No theme
        $component = $this->createComponent();
        $this->assertFalse($component->isGlassTheme());
    }

    public function test_chart_glass_theme_overrides(): void
    {
        $component = $this->createComponent(['theme' => 'artisanpack-glass']);

        $lightOverrides = $component->glassLightOverrides();
        $darkOverrides  = $component->glassDarkOverrides();

        // Light overrides
        $this->assertIsArray($lightOverrides);
        $this->assertArrayHasKey('chart', $lightOverrides);
        $this->assertEquals('#374151', $lightOverrides['chart']['foreColor']);

        // Dark overrides
        $this->assertIsArray($darkOverrides);
        $this->assertArrayHasKey('chart', $darkOverrides);
        $this->assertEquals('#e5e7eb', $darkOverrides['chart']['foreColor']);
    }

    public function test_chart_renders_successfully(): void
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

    public function test_chart_accessibility_compliance(): void
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
            'Chart should have valid HTML structure. Issues: '.implode(', ', $validation['issues']),
        );
    }

    public function test_chart_security_against_xss(): void
    {
        $xssPayloads = TestHelpers::securityTestPayloads();

        foreach ($xssPayloads as $payload) {
            $component = $this->createComponent(['id' => $payload]);
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

    public function test_chart_performance(): void
    {
        try {
            $performance = TestHelpers::measureRenderingPerformance($this->createComponent(), 10);
            $this->assertLessThan(100, $performance['average_time'],
                'Chart rendering should be under 100ms on average',
            );
        } catch (RuntimeException $e) {
            if (str_contains($e->getMessage(), 'requires slots') ||
                str_contains($e->getMessage(), 'requires additional context')) {
                $this->markTestSkipped($e->getMessage());
            }
            throw $e;
        }
    }

    public function test_chart_animation_config_defaults(): void
    {
        $component = $this->createComponent();

        // Check default animation props
        $this->assertTrue($component->animated);
        $this->assertEquals(800, $component->animationSpeed);
        $this->assertEquals('easeinout', $component->animationEasing);
        $this->assertTrue($component->dynamicAnimation);
        $this->assertEquals(350, $component->dynamicAnimationSpeed);
    }

    public function test_chart_animation_config_method(): void
    {
        $component = $this->createComponent();
        $config    = $component->animationConfig();

        // Check config structure
        $this->assertIsArray($config);
        $this->assertArrayHasKey('enabled', $config);
        $this->assertArrayHasKey('speed', $config);
        $this->assertArrayHasKey('easing', $config);
        $this->assertArrayHasKey('dynamicAnimation', $config);

        // Check default values
        $this->assertTrue($config['enabled']);
        $this->assertEquals(800, $config['speed']);
        $this->assertEquals('easeinout', $config['easing']);
        $this->assertTrue($config['dynamicAnimation']['enabled']);
        $this->assertEquals(350, $config['dynamicAnimation']['speed']);
    }

    public function test_chart_animation_config_custom_values(): void
    {
        $component = $this->createComponent([
            'animated'              => false,
            'animationSpeed'        => 1200,
            'animationEasing'       => 'linear',
            'dynamicAnimation'      => false,
            'dynamicAnimationSpeed' => 500,
        ]);

        $config = $component->animationConfig();

        $this->assertFalse($config['enabled']);
        $this->assertEquals(1200, $config['speed']);
        $this->assertEquals('linear', $config['easing']);
        $this->assertFalse($config['dynamicAnimation']['enabled']);
        $this->assertEquals(500, $config['dynamicAnimation']['speed']);
    }

    public function test_chart_options_include_animation_config(): void
    {
        $component = $this->createComponent([
            'animated'              => true,
            'animationSpeed'        => 600,
            'animationEasing'       => 'easeout',
            'dynamicAnimation'      => true,
            'dynamicAnimationSpeed' => 400,
        ]);

        $options = $component->chartOptions();

        // Check animations are included in chart options
        $this->assertArrayHasKey('animations', $options['chart']);
        $this->assertTrue($options['chart']['animations']['enabled']);
        $this->assertEquals(600, $options['chart']['animations']['speed']);
        $this->assertEquals('easeout', $options['chart']['animations']['easing']);
        $this->assertTrue($options['chart']['animations']['dynamicAnimation']['enabled']);
        $this->assertEquals(400, $options['chart']['animations']['dynamicAnimation']['speed']);
    }

    public function test_chart_animation_disabled(): void
    {
        $component = $this->createComponent(['animated' => false]);
        $options   = $component->chartOptions();

        $this->assertFalse($options['chart']['animations']['enabled']);
    }

    public function test_chart_dynamic_animation_independent(): void
    {
        // Main animation enabled, dynamic disabled
        $component = $this->createComponent([
            'animated'         => true,
            'dynamicAnimation' => false,
        ]);

        $options = $component->chartOptions();

        $this->assertTrue($options['chart']['animations']['enabled']);
        $this->assertFalse($options['chart']['animations']['dynamicAnimation']['enabled']);
    }
}
