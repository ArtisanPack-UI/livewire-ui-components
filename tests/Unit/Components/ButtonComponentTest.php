<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Unit\Components;

use ArtisanPack\LivewireUiComponents\Tests\Support\ComponentTestCase;
use ArtisanPack\LivewireUiComponents\Tests\Support\ComponentDataFactory;
use ArtisanPack\LivewireUiComponents\Tests\Support\TestHelpers;
use ArtisanPack\LivewireUiComponents\View\Components\Button;

/**
 * Comprehensive unit tests for the Button component.
 * 
 * This test class extends ComponentTestCase to inherit common testing patterns
 * and implements comprehensive testing for all Button component functionality.
 */
class ButtonComponentTest extends ComponentTestCase
{
    protected string $componentClass = Button::class;

    protected array $defaultProperties = [
        'variant' => 'primary',
        'resolvedColor' => 'primary',
        'responsive' => false,
        'external' => false,
        'noWireNavigate' => false,
    ];

    protected array $requiredProperties = [];

    /**
     * Test Button component specific properties and methods.
     */
    public function test_button_has_correct_variant_handling(): void
    {
        // Test default variant
        $button = $this->createComponent();
        $this->assertEquals('primary', $button->variant);
        $this->assertEquals('primary', $button->resolvedColor);
        
        // Test valid variants
        $validVariants = ['primary', 'secondary', 'accent', 'success', 'warning', 'error', 'ghost', 'outline'];
        foreach ($validVariants as $variant) {
            $button = $this->createComponent(['variant' => $variant]);
            $this->assertEquals($variant, $button->variant);
            $this->assertEquals($variant, $button->resolvedColor);
        }
        
        // Test invalid variant fallback
        $button = $this->createComponent(['variant' => 'invalid']);
        $this->assertEquals('primary', $button->variant);
        $this->assertEquals('primary', $button->resolvedColor);
    }

    public function test_button_color_system_functionality(): void
    {
        // Test color override functionality
        $button = $this->createComponent(['variant' => 'success', 'color' => 'red-500']);
        $this->assertEquals('success', $button->variant);
        $this->assertEquals('red-500', $button->resolvedColor);
        
        // Test color classes generation
        if (method_exists($button, 'getColorClasses')) {
            $colorClasses = $button->getColorClasses();
            $this->assertIsArray($colorClasses);
            $this->assertNotEmpty($colorClasses);
        }
    }

    public function test_button_variant_classes(): void
    {
        $button = $this->createComponent(['variant' => 'primary']);
        
        if (method_exists($button, 'getVariantClasses')) {
            $classes = $button->getVariantClasses();
            $this->assertIsString($classes);
            $this->assertStringContainsString('btn-primary', $classes);
        }
    }

    public function test_button_with_icons(): void
    {
        $testData = ComponentDataFactory::forComponent('Button');
        
        foreach ($testData['icons'] as $icon) {
            $button = $this->createComponent(['icon' => $icon]);
            $this->assertEquals($icon, $button->icon);
            
            // Test icon right
            $button = $this->createComponent(['iconRight' => $icon]);
            $this->assertEquals($icon, $button->iconRight);
        }
    }

    public function test_button_with_links(): void
    {
        $testUrls = ComponentDataFactory::urlValues();
        
        foreach ($testUrls as $url) {
            $button = $this->createComponent(['link' => $url]);
            $this->assertEquals($url, $button->link);
        }
    }

    public function test_button_with_external_links(): void
    {
        $button = $this->createComponent(['link' => 'https://example.com', 'external' => true]);
        $this->assertTrue($button->external);
        
        $button = $this->createComponent(['external' => false]);
        $this->assertFalse($button->external);
    }

    public function test_button_with_tooltips(): void
    {
        $testTexts = ComponentDataFactory::sampleTexts();
        
        foreach (['tooltip', 'tooltipLeft', 'tooltipRight', 'tooltipBottom'] as $tooltipProperty) {
            foreach ($testTexts as $text) {
                if (empty($text)) continue; // Skip empty tooltips
                
                $button = $this->createComponent([$tooltipProperty => $text]);
                $this->assertEquals($text, $button->$tooltipProperty);
            }
        }
    }

    public function test_button_with_badges(): void
    {
        $testTexts = array_filter(ComponentDataFactory::sampleTexts(), fn($text) => !empty(trim($text)));
        
        foreach ($testTexts as $badge) {
            $button = $this->createComponent(['badge' => $badge]);
            $this->assertEquals($badge, $button->badge);
        }
    }

    public function test_button_with_spinners(): void
    {
        $spinnerTypes = ['default', 'dots', 'ring', 'ball', 'bars'];
        
        foreach ($spinnerTypes as $spinner) {
            $button = $this->createComponent(['spinner' => $spinner]);
            $this->assertEquals($spinner, $button->spinner);
        }
    }

    public function test_button_spinner_target(): void
    {
        $button = $this->createComponent();
        
        if (method_exists($button, 'spinnerTarget')) {
            $target = $button->spinnerTarget();
            // Should return null or a string
            $this->assertTrue(is_null($target) || is_string($target));
        }
    }

    public function test_button_responsive_behavior(): void
    {
        $button = $this->createComponent(['responsive' => true]);
        $this->assertTrue($button->responsive);
        
        $button = $this->createComponent(['responsive' => false]);
        $this->assertFalse($button->responsive);
    }

    public function test_button_wire_navigate_settings(): void
    {
        $button = $this->createComponent(['noWireNavigate' => true]);
        $this->assertTrue($button->noWireNavigate);
        
        $button = $this->createComponent(['noWireNavigate' => false]);
        $this->assertFalse($button->noWireNavigate);
    }

    public function test_button_with_custom_classes(): void
    {
        $testClasses = ComponentDataFactory::cssClasses();
        
        foreach ($testClasses as $customClass) {
            $button = $this->createComponent(['badgeClasses' => $customClass]);
            $this->assertEquals($customClass, $button->badgeClasses);
        }
    }

    public function test_button_color_adjustments(): void
    {
        $adjustments = ['lighter', 'darker', 'transparent', 'subtle'];
        
        foreach ($adjustments as $adjustment) {
            $button = $this->createComponent(['colorAdjustment' => $adjustment]);
            $this->assertEquals($adjustment, $button->colorAdjustment);
        }
    }

    public function test_button_renders_with_all_properties(): void
    {
        $button = $this->createComponent([
            'id' => 'test-button',
            'label' => 'Test Button',
            'icon' => 'star',
            'iconRight' => 'arrow-right',
            'variant' => 'success',
            'color' => 'blue-500',
            'badge' => '5',
            'tooltip' => 'Test tooltip',
            'responsive' => true,
            'external' => false
        ]);

        $view = $button->render();
        $html = $view->render();

        // Verify component renders successfully
        $this->assertNotEmpty($html);
        $this->assertTrue(TestHelpers::assertValidHtml($html));
        
        // Check for expected content
        $this->assertStringContainsString('Test Button', $html);
    }

    public function test_button_accessibility_features(): void
    {
        // Test with aria-label
        $button = $this->createComponent(['label' => 'Accessible Button']);
        $view = $button->render();
        $html = $view->render();
        
        $accessibility = TestHelpers::hasAccessibilityAttributes($html);
        
        // Button should be keyboard accessible
        $this->assertStringContainsString('button', strtolower($html));
    }

    public function test_button_security_against_xss(): void
    {
        $xssPayloads = TestHelpers::securityTestPayloads();
        
        foreach ($xssPayloads as $payload) {
            $button = $this->createComponent(['label' => $payload]);
            $view = $button->render();
            $html = $view->render();
            
            // Should not contain unescaped script tags
            $this->assertStringNotContainsString('<script', $html);
            $this->assertStringNotContainsString('javascript:', $html);
        }
    }

    public function test_button_performance_with_large_datasets(): void
    {
        $performanceData = ComponentDataFactory::performanceTestData();
        
        foreach ($performanceData as $datasetName => $dataset) {
            if ($datasetName === 'extra_large_dataset') {
                continue; // Skip extremely large datasets for unit tests
            }
            
            $label = implode(' ', array_slice($dataset, 0, 10));
            $button = $this->createComponent(['label' => $label]);
            
            $performance = TestHelpers::measureRenderingPerformance($button, 10);
            
            // Performance should be reasonable (less than 100ms average)
            $this->assertLessThan(100, $performance['average_time']);
        }
    }

    public function test_button_with_edge_cases(): void
    {
        $edgeCases = ComponentDataFactory::edgeCases();
        
        // Test empty values
        foreach ($edgeCases['empty_values'] as $property => $value) {
            if (in_array($property, ['text', 'array'])) {
                $button = $this->createComponent(['label' => $value]);
                $this->assertInstanceOf(Button::class, $button);
            }
        }
        
        // Test special characters
        foreach ($edgeCases['special_characters'] as $property => $value) {
            $button = $this->createComponent(['label' => $value]);
            $view = $button->render();
            $html = $view->render();
            
            // Should handle special characters safely
            $this->assertNotEmpty($html);
        }
    }

    public function test_button_internationalization(): void
    {
        $i18nData = TestHelpers::i18nTestData();
        
        foreach ($i18nData as $locale => $data) {
            $button = $this->createComponent(['label' => $data['text']]);
            $view = $button->render();
            $html = $view->render();
            
            // Should render international text correctly
            $this->assertStringContainsString($data['text'], $html);
        }
    }

    public function test_button_memory_usage(): void
    {
        $memoryUsage = TestHelpers::measureMemoryUsage(
            fn() => $this->createComponent(['label' => 'Memory Test']),
            50
        );
        
        // Memory per component should be reasonable (less than 10KB)
        $this->assertLessThan(10240, $memoryUsage['memory_per_component']);
    }

    public function test_button_color_contrast_accessibility(): void
    {
        // Test common color combinations
        $colorCombinations = [
            ['#000000', '#FFFFFF'], // Black on white
            ['#FFFFFF', '#000000'], // White on black
            ['#007BFF', '#FFFFFF'], // Blue on white
            ['#28A745', '#FFFFFF'], // Green on white
        ];
        
        foreach ($colorCombinations as [$fg, $bg]) {
            $contrast = TestHelpers::validateColorContrast($fg, $bg);
            
            // Should meet WCAG AA standards for normal text
            $this->assertTrue(
                $contrast['wcag_aa_normal'], 
                "Color combination {$fg} on {$bg} should meet WCAG AA standards"
            );
        }
    }

    public function test_button_html_structure_validation(): void
    {
        $button = $this->createComponent(['label' => 'Validate Structure']);
        $view = $button->render();
        $html = $view->render();
        
        $validation = TestHelpers::validateHtmlStructure($html);
        
        // Should have valid HTML structure
        $this->assertTrue($validation['is_valid'], 
            'Button HTML structure should be valid. Issues: ' . implode(', ', $validation['issues'])
        );
    }
}