<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Support;

/**
 * Component Data Factory for generating test data for various component states.
 * 
 * This factory provides consistent test data for different component scenarios,
 * including edge cases, common configurations, and stress testing data.
 */
class ComponentDataFactory
{
    /**
     * Common color variants used across components.
     */
    public static function colorVariants(): array
    {
        return [
            'primary',
            'secondary',
            'accent',
            'success',
            'warning',
            'error',
            'info',
            'neutral'
        ];
    }

    /**
     * Tailwind color variations for testing.
     */
    public static function tailwindColors(): array
    {
        return [
            'red-500',
            'blue-600',
            'green-400',
            'purple-700',
            'yellow-300',
            'indigo-800',
            'pink-200',
            'gray-900'
        ];
    }

    /**
     * Hex color values for testing.
     */
    public static function hexColors(): array
    {
        return [
            '#FF5733',
            '#33FF57',
            '#3357FF',
            '#F0F0F0',
            '#000000',
            '#FFFFFF',
            '#FFA500',
            '#800080'
        ];
    }

    /**
     * Size variants used across components.
     */
    public static function sizeVariants(): array
    {
        return ['xs', 'sm', 'md', 'lg', 'xl', '2xl'];
    }

    /**
     * Component variant types.
     */
    public static function componentVariants(): array
    {
        return [
            'default',
            'outline',
            'ghost',
            'solid',
            'soft',
            'subtle'
        ];
    }

    /**
     * Boolean property test cases.
     */
    public static function booleanCases(): array
    {
        return [true, false];
    }

    /**
     * Common icon names for testing.
     */
    public static function iconNames(): array
    {
        return [
            'home',
            'user',
            'settings',
            'search',
            'bell',
            'heart',
            'star',
            'check',
            'x',
            'plus',
            'minus',
            'arrow-right',
            'arrow-left',
            'arrow-up',
            'arrow-down'
        ];
    }

    /**
     * Heroicon icon names for testing.
     */
    public static function heroiconNames(): array
    {
        return [
            'o-home',
            's-home',
            'o-user',
            's-user',
            'o-cog-6-tooth',
            's-cog-6-tooth',
            'o-magnifying-glass',
            's-magnifying-glass',
            'o-bell',
            's-bell',
            'o-heart',
            's-heart',
            'o-star',
            's-star'
        ];
    }

    /**
     * Sample text content for testing.
     */
    public static function sampleTexts(): array
    {
        return [
            'Simple text',
            'Text with <strong>HTML</strong>',
            'Multi-line\nText content',
            'Special chars: !@#$%^&*()',
            'Unicode: 🎉 ⭐ 💜',
            'Very long text content that should test text wrapping and overflow handling in components',
            '', // Empty string
            '   ', // Whitespace only
            '123456789', // Numbers
            'https://example.com', // URL
            'user@example.com' // Email
        ];
    }

    /**
     * Form input values for testing.
     */
    public static function formInputValues(): array
    {
        return [
            'normal-value',
            'value with spaces',
            'special-chars-!@#$',
            '1234567890',
            'very-long-value-that-might-cause-issues-with-some-components-and-should-be-handled-gracefully',
            '', // Empty
            '0', // Zero
            'false', // String false
            'null', // String null
            'undefined' // String undefined
        ];
    }

    /**
     * Number values for testing numeric inputs.
     */
    public static function numberValues(): array
    {
        return [
            0,
            1,
            -1,
            42,
            -42,
            1.5,
            -1.5,
            0.1,
            999999,
            -999999,
            PHP_INT_MAX,
            PHP_INT_MIN
        ];
    }

    /**
     * Date values for testing date components.
     */
    public static function dateValues(): array
    {
        return [
            '2024-01-01',
            '2024-12-31',
            '2024-02-29', // Leap year
            '1900-01-01', // Very old date
            '2100-12-31', // Future date
            date('Y-m-d'), // Today
            date('Y-m-d', strtotime('+1 day')), // Tomorrow
            date('Y-m-d', strtotime('-1 day')), // Yesterday
        ];
    }

    /**
     * Array data for testing components that handle collections.
     */
    public static function arrayData(): array
    {
        return [
            [], // Empty array
            ['single-item'],
            ['item1', 'item2', 'item3'],
            array_fill(0, 100, 'stress-test-item'), // Large array
            [
                ['id' => 1, 'name' => 'Item 1'],
                ['id' => 2, 'name' => 'Item 2'],
                ['id' => 3, 'name' => 'Item 3']
            ], // Array of objects
            ['key1' => 'value1', 'key2' => 'value2'], // Associative array
        ];
    }

    /**
     * URL values for testing link components.
     */
    public static function urlValues(): array
    {
        return [
            'https://example.com',
            'http://example.com',
            '/relative/path',
            '#anchor',
            'mailto:test@example.com',
            'tel:+1234567890',
            'javascript:void(0)',
            '', // Empty
            '/', // Root
            '/very/long/path/that/might/cause/issues/with/some/components'
        ];
    }

    /**
     * CSS class combinations for testing.
     */
    public static function cssClasses(): array
    {
        return [
            'single-class',
            'multiple classes here',
            'btn btn-primary btn-lg',
            'text-red-500 bg-blue-100 p-4',
            '', // Empty
            '   ', // Whitespace
            'class-with-numbers-123',
            'class_with_underscores',
            'class-with-dashes',
            'ClassName' // PascalCase
        ];
    }

    /**
     * Accessibility attributes for testing.
     */
    public static function accessibilityAttributes(): array
    {
        return [
            ['aria-label' => 'Test Label'],
            ['aria-describedby' => 'description-id'],
            ['role' => 'button'],
            ['role' => 'link'],
            ['tabindex' => '0'],
            ['tabindex' => '-1'],
            ['aria-expanded' => 'true'],
            ['aria-expanded' => 'false'],
            ['aria-hidden' => 'true'],
            ['aria-disabled' => 'true']
        ];
    }

    /**
     * Generate test data for specific component properties.
     */
    public static function forComponent(string $componentName): array
    {
        $data = [
            'colors' => array_merge(
                self::colorVariants(),
                self::tailwindColors(),
                self::hexColors()
            ),
            'sizes' => self::sizeVariants(),
            'variants' => self::componentVariants(),
            'booleans' => self::booleanCases(),
            'texts' => self::sampleTexts(),
        ];

        // Component-specific data
        switch (strtolower($componentName)) {
            case 'button':
                $data['icons'] = self::iconNames();
                break;
                
            case 'input':
            case 'textarea':
                $data['values'] = self::formInputValues();
                break;
                
            case 'select':
                $data['options'] = self::arrayData();
                break;
                
            case 'rating':
                $data['values'] = array_filter(self::numberValues(), fn($n) => $n >= 0 && $n <= 5);
                break;
                
            case 'progress':
                $data['values'] = array_filter(self::numberValues(), fn($n) => $n >= 0 && $n <= 100);
                break;
                
            case 'calendar':
            case 'datepicker':
                $data['dates'] = self::dateValues();
                break;
                
            case 'link':
                $data['urls'] = self::urlValues();
                break;
        }

        return $data;
    }

    /**
     * Generate edge case scenarios for stress testing.
     */
    public static function edgeCases(): array
    {
        return [
            'empty_values' => [
                'text' => '',
                'array' => [],
                'number' => 0,
                'boolean' => false,
                'null' => null
            ],
            'extreme_values' => [
                'very_long_text' => str_repeat('A', 10000),
                'large_number' => PHP_INT_MAX,
                'negative_number' => PHP_INT_MIN,
                'large_array' => array_fill(0, 1000, 'item')
            ],
            'special_characters' => [
                'html_entities' => '&lt;script&gt;alert("test")&lt;/script&gt;',
                'unicode' => '🌟🎉💖✨🚀🔥💯⚡🌈🎯',
                'sql_injection' => "'; DROP TABLE users; --",
                'xss_attempt' => '<script>alert("xss")</script>',
                'null_bytes' => "test\0null\0bytes"
            ],
            'whitespace_variants' => [
                'leading_spaces' => '   text',
                'trailing_spaces' => 'text   ',
                'mixed_whitespace' => "  \t\n  text  \t\n  ",
                'only_whitespace' => "   \t\n   "
            ]
        ];
    }

    /**
     * Generate random test data for fuzzing.
     */
    public static function randomData(int $count = 10): array
    {
        $data = [];
        
        for ($i = 0; $i < $count; $i++) {
            $data[] = [
                'string' => bin2hex(random_bytes(rand(5, 50))),
                'number' => rand(-1000, 1000),
                'boolean' => (bool)rand(0, 1),
                'array' => array_fill(0, rand(0, 10), bin2hex(random_bytes(10)))
            ];
        }
        
        return $data;
    }

    /**
     * Performance test data sets.
     */
    public static function performanceTestData(): array
    {
        return [
            'small_dataset' => array_fill(0, 10, 'item'),
            'medium_dataset' => array_fill(0, 100, 'item'),
            'large_dataset' => array_fill(0, 1000, 'item'),
            'extra_large_dataset' => array_fill(0, 10000, 'item'),
        ];
    }
}