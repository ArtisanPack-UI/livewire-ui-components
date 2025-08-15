<?php

use ArtisanPack\LivewireUiComponents\Styling\ColorGenerator;
use PHPUnit\Framework\TestCase;

class ColorGeneratorTest extends TestCase
{
    private ColorGenerator $colorGenerator;

    protected function setUp(): void
    {
        $this->colorGenerator = new ColorGenerator();
    }

    public function test_resolves_predefined_variants()
    {
        $variants = [
            'primary' => ['bg' => 'bg-primary', 'border' => 'border-primary', 'text' => 'text-primary-content'],
            'secondary' => ['bg' => 'bg-secondary', 'border' => 'border-secondary', 'text' => 'text-secondary-content'],
            'accent' => ['bg' => 'bg-accent', 'border' => 'border-accent', 'text' => 'text-accent-content'],
            'success' => ['bg' => 'bg-success', 'border' => 'border-success', 'text' => 'text-success-content'],
            'warning' => ['bg' => 'bg-warning', 'border' => 'border-warning', 'text' => 'text-warning-content'],
            'error' => ['bg' => 'bg-error', 'border' => 'border-error', 'text' => 'text-error-content'],
            'info' => ['bg' => 'bg-info', 'border' => 'border-info', 'text' => 'text-info-content'],
            'neutral' => ['bg' => 'bg-neutral', 'border' => 'border-neutral', 'text' => 'text-neutral-content'],
        ];

        foreach ($variants as $variant => $expected) {
            $result = $this->colorGenerator->resolveComponentColor($variant, null, 'button');
            $this->assertEquals($expected, $result, "Failed for variant: {$variant}");
        }
    }

    public function test_resolves_tailwind_colors_with_intensity()
    {
        $colors = [
            'red-500' => ['bg' => 'bg-red-500', 'border' => 'border-red-500', 'text' => 'text-white'],
            'blue-400' => ['bg' => 'bg-blue-400', 'border' => 'border-blue-400', 'text' => 'text-blue-900'],
            'green-600' => ['bg' => 'bg-green-600', 'border' => 'border-green-600', 'text' => 'text-white'],
            'purple-300' => ['bg' => 'bg-purple-300', 'border' => 'border-purple-300', 'text' => 'text-purple-900'],
        ];

        foreach ($colors as $color => $expected) {
            $result = $this->colorGenerator->resolveComponentColor($color, null, 'alert');
            $this->assertEquals($expected, $result, "Failed for color: {$color}");
        }
    }

    public function test_resolves_tailwind_colors_without_intensity()
    {
        $colors = [
            'red' => ['bg' => 'bg-red-500', 'border' => 'border-red-500', 'text' => 'text-white'],
            'blue' => ['bg' => 'bg-blue-500', 'border' => 'border-blue-500', 'text' => 'text-white'],
            'green' => ['bg' => 'bg-green-500', 'border' => 'border-green-500', 'text' => 'text-white'],
        ];

        foreach ($colors as $color => $expected) {
            $result = $this->colorGenerator->resolveComponentColor($color, null, 'badge');
            $this->assertEquals($expected, $result, "Failed for color: {$color}");
        }
    }

    public function test_resolves_hex_colors()
    {
        $hexColors = ['#FF5733', '#33FF57', '#3357FF'];

        foreach ($hexColors as $hex) {
            $result = $this->colorGenerator->resolveComponentColor($hex, null, 'avatar');
            
            $this->assertArrayHasKey('style', $result);
            $this->assertArrayHasKey('bg', $result);
            $this->assertArrayHasKey('border', $result);
            $this->assertArrayHasKey('text', $result);
            
            $this->assertEquals("--artisanpack-custom-color: {$hex};", $result['style']);
            $this->assertEquals('[background-color:var(--artisanpack-custom-color)]', $result['bg']);
            $this->assertEquals('[border-color:var(--artisanpack-custom-color)]', $result['border']);
            $this->assertContains($result['text'], ['text-black', 'text-white']);
        }
    }

    public function test_applies_color_adjustments()
    {
        // Test lighter adjustment
        $result = $this->colorGenerator->resolveComponentColor('red-500', 'lighter', 'button');
        $this->assertEquals('bg-red-100', $result['bg']);
        $this->assertEquals('border-red-500', $result['border']);
        $this->assertEquals('text-red-900', $result['text']);

        // Test darker adjustment
        $result = $this->colorGenerator->resolveComponentColor('blue-500', 'darker', 'button');
        $this->assertEquals('bg-blue-700', $result['bg']);
        $this->assertEquals('border-blue-500', $result['border']);
        $this->assertEquals('text-white', $result['text']);

        // Test transparent adjustment
        $result = $this->colorGenerator->resolveComponentColor('green-500', 'transparent', 'button');
        $this->assertEquals('bg-transparent', $result['bg']);
        $this->assertEquals('border-green-500', $result['border']);

        // Test subtle adjustment
        $result = $this->colorGenerator->resolveComponentColor('purple-500', 'subtle', 'button');
        $this->assertEquals('bg-purple-50', $result['bg']);
        $this->assertEquals('border-purple-500', $result['border']);
        $this->assertEquals('text-purple-900', $result['text']);
    }

    public function test_applies_hex_adjustments()
    {
        // Test lighter adjustment with hex
        $result = $this->colorGenerator->resolveComponentColor('#FF5733', 'lighter', 'button');
        $this->assertArrayHasKey('style', $result);
        $this->assertStringContains('--artisanpack-custom-color:', $result['style']);
        
        // Test transparent adjustment with hex
        $result = $this->colorGenerator->resolveComponentColor('#FF5733', 'transparent', 'button');
        $this->assertEquals('bg-transparent', $result['bg']);
        $this->assertEquals('[border-color:var(--artisanpack-custom-color)]', $result['border']);
    }

    public function test_handles_invalid_colors()
    {
        $invalidColors = [
            'invalid-color',
            'not-a-hex',
            'blue-1000',
            'red-',
            '#ZZZZZZ',
            '',
            'nonexistent'
        ];

        foreach ($invalidColors as $invalid) {
            $result = $this->colorGenerator->resolveComponentColor($invalid, null, 'button');
            $this->assertEmpty($result, "Should return empty array for invalid color: {$invalid}");
        }
    }

    public function test_handles_null_color_input()
    {
        $result = $this->colorGenerator->resolveComponentColor(null, null, 'button');
        $this->assertEmpty($result);
    }

    public function test_variant_adjustments_work_correctly()
    {
        // Test variant with adjustment
        $result = $this->colorGenerator->resolveComponentColor('primary', 'lighter', 'button');
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('bg', $result);
        $this->assertArrayHasKey('border', $result);
        $this->assertArrayHasKey('text', $result);
    }

    public function test_contrasting_text_calculation()
    {
        // Light colors should get dark text
        $result = $this->colorGenerator->resolveComponentColor('yellow-200', null, 'button');
        $this->assertEquals('text-yellow-900', $result['text']);

        // Dark colors should get light text
        $result = $this->colorGenerator->resolveComponentColor('blue-800', null, 'button');
        $this->assertEquals('text-white', $result['text']);
    }

    public function test_ghost_and_outline_variants()
    {
        $result = $this->colorGenerator->resolveComponentColor('ghost', null, 'button');
        $this->assertEquals('bg-transparent', $result['bg']);
        $this->assertEquals('border-transparent', $result['border']);
        $this->assertEquals('text-current', $result['text']);

        $result = $this->colorGenerator->resolveComponentColor('outline', null, 'button');
        $this->assertEquals('bg-transparent', $result['bg']);
        $this->assertEquals('border-current', $result['border']);
        $this->assertEquals('text-current', $result['text']);
    }

    public function test_component_context_parameter()
    {
        // Test that component parameter is accepted (functionality is the same regardless)
        $result1 = $this->colorGenerator->resolveComponentColor('primary', null, 'button');
        $result2 = $this->colorGenerator->resolveComponentColor('primary', null, 'alert');
        $result3 = $this->colorGenerator->resolveComponentColor('primary', null, 'badge');
        
        // Results should be the same regardless of component
        $this->assertEquals($result1, $result2);
        $this->assertEquals($result2, $result3);
    }
}