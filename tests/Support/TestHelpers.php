<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests\Support;

use DOMDocument;
use DOMXPath;
use Error;
use Illuminate\View\Component;
use RuntimeException;

/**
 * Test helpers and utilities for component testing.
 *
 * This class provides helper functions for common testing operations,
 * HTML parsing, accessibility testing, and performance measurements.
 */
class TestHelpers
{
    /**
     * Parse HTML content and return a DOMDocument for analysis.
     */
    public static function parseHtml(string $html): DOMDocument
    {
        $dom = new DOMDocument;

        // Suppress warnings for malformed HTML
        $previousSetting = libxml_use_internal_errors(true);

        // Load HTML content
        $dom->loadHTML('<?xml encoding="utf-8" ?>'.$html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        // Restore previous libxml setting
        libxml_use_internal_errors($previousSetting);

        return $dom;
    }

    /**
     * Extract all CSS classes from HTML content.
     */
    public static function extractCssClasses(string $html): array
    {
        $classes  = [];
        $dom      = self::parseHtml($html);
        $xpath    = new DOMXPath($dom);
        $elements = $xpath->query('//*[@class]');

        foreach ($elements as $element) {
            $elementClasses = explode(' ', $element->getAttribute('class'));
            $classes        = array_merge($classes, array_filter($elementClasses));
        }

        return array_unique($classes);
    }

    /**
     * Check if HTML contains specific accessibility attributes.
     */
    public static function hasAccessibilityAttributes(string $html): array
    {
        $dom   = self::parseHtml($html);
        $xpath = new DOMXPath($dom);

        return [
            'aria_labels'       => $xpath->query('//*[@aria-label]')->length > 0,
            'aria_described_by' => $xpath->query('//*[@aria-describedby]')->length > 0,
            'aria_expanded'     => $xpath->query('//*[@aria-expanded]')->length > 0,
            'aria_hidden'       => $xpath->query('//*[@aria-hidden]')->length > 0,
            'role_attributes'   => $xpath->query('//*[@role]')->length > 0,
            'tabindex'          => $xpath->query('//*[@tabindex]')->length > 0,
            'alt_text'          => $xpath->query('//img[@alt]')->length > 0,
            'form_labels'       => $xpath->query('//label[@for] | //input[@aria-labelledby]')->length > 0,
        ];
    }

    /**
     * Validate HTML structure and semantic correctness.
     */
    public static function validateHtmlStructure(string $html): array
    {
        $dom    = self::parseHtml($html);
        $xpath  = new DOMXPath($dom);
        $issues = [];

        // Check for missing alt attributes on images
        $imagesWithoutAlt = $xpath->query('//img[not(@alt)]');
        if ($imagesWithoutAlt->length > 0) {
            $issues[] = "Found {$imagesWithoutAlt->length} images without alt attributes";
        }

        // Check for form inputs without labels
        $inputsWithoutLabels = $xpath->query('//input[not(@aria-label) and not(@aria-labelledby) and not(@id = //label/@for)]');
        if ($inputsWithoutLabels->length > 0) {
            $issues[] = "Found {$inputsWithoutLabels->length} form inputs without proper labels";
        }

        // Check for buttons without accessible names
        $buttonsWithoutNames = $xpath->query('//button[not(text()) and not(@aria-label) and not(@aria-labelledby)]');
        if ($buttonsWithoutNames->length > 0) {
            $issues[] = "Found {$buttonsWithoutNames->length} buttons without accessible names";
        }

        // Check for proper heading hierarchy
        $headings = $xpath->query('//h1 | //h2 | //h3 | //h4 | //h5 | //h6');
        if ($headings->length > 0) {
            $headingLevels = [];
            foreach ($headings as $heading) {
                $level           = (int) substr($heading->tagName, 1);
                $headingLevels[] = $level;
            }

            // Check if heading levels skip (e.g., h1 to h3)
            for ($i = 1; $i < count($headingLevels); $i++) {
                if ($headingLevels[$i] > $headingLevels[$i - 1] + 1) {
                    $issues[] = "Heading hierarchy skips levels (found h{$headingLevels[$i - 1]} followed by h{$headingLevels[$i]})";
                    break;
                }
            }
        }

        return [
            'is_valid' => empty($issues),
            'issues'   => $issues,
        ];
    }

    /**
     * Measure rendering performance of a component.
     */
    public static function measureRenderingPerformance(Component $component, int $iterations = 100): array
    {
        $times = [];

        for ($i = 0; $i < $iterations; $i++) {
            try {
                $start = microtime(true);
                $component->render()->render();
                $end     = microtime(true);
                $times[] = ($end - $start) * 1000; // Convert to milliseconds
            } catch (\Illuminate\View\ViewException|Error $e) {
                // Skip iterations that fail due to missing slots or context
                if (str_contains($e->getMessage(), 'Undefined variable') ||
                    str_contains($e->getMessage(), 'Undefined array key') ||
                    str_contains($e->getMessage(), 'Call to a member function')) {
                    throw new RuntimeException('Component requires slots or additional context for rendering', 0, $e);
                }
                throw $e;
            }
        }

        return [
            'iterations'   => $iterations,
            'total_time'   => array_sum($times),
            'average_time' => array_sum($times) / count($times),
            'min_time'     => min($times),
            'max_time'     => max($times),
            'median_time'  => self::calculateMedian($times),
        ];
    }

    /**
     * Test component memory usage.
     */
    public static function measureMemoryUsage(callable $componentCreator, int $iterations = 100): array
    {
        $initialMemory = memory_get_usage();
        $components    = [];

        for ($i = 0; $i < $iterations; $i++) {
            $components[] = $componentCreator();
        }

        $afterCreationMemory = memory_get_usage();

        // Force rendering
        foreach ($components as $component) {
            $component->render();
        }

        $afterRenderingMemory = memory_get_usage();

        // Clean up
        unset($components);

        $finalMemory = memory_get_usage();

        return [
            'initial_memory'       => $initialMemory,
            'after_creation'       => $afterCreationMemory - $initialMemory,
            'after_rendering'      => $afterRenderingMemory - $afterCreationMemory,
            'final_memory'         => $finalMemory,
            'memory_per_component' => ($afterCreationMemory - $initialMemory) / $iterations,
            'total_memory_used'    => $afterRenderingMemory - $initialMemory,
        ];
    }

    /**
     * Generate responsive design test cases.
     */
    public static function responsiveBreakpoints(): array
    {
        return [
            'mobile'        => ['width' => 320, 'height' => 568],
            'mobile_large'  => ['width' => 375, 'height' => 667],
            'tablet'        => ['width' => 768, 'height' => 1024],
            'tablet_large'  => ['width' => 1024, 'height' => 768],
            'desktop'       => ['width' => 1280, 'height' => 720],
            'desktop_large' => ['width' => 1920, 'height' => 1080],
            'ultra_wide'    => ['width' => 2560, 'height' => 1080],
        ];
    }

    /**
     * Simulate user interactions for testing.
     */
    public static function userInteractions(): array
    {
        return [
            'click'          => ['type' => 'click', 'target' => 'button, a, [role="button"]'],
            'focus'          => ['type' => 'focus', 'target' => 'input, button, select, textarea, [tabindex]'],
            'blur'           => ['type' => 'blur', 'target' => 'input, button, select, textarea'],
            'keydown_enter'  => ['type' => 'keydown', 'key' => 'Enter', 'target' => '[role="button"], button'],
            'keydown_space'  => ['type' => 'keydown', 'key' => ' ', 'target' => '[role="button"], button'],
            'keydown_escape' => ['type' => 'keydown', 'key' => 'Escape', 'target' => '*'],
            'keydown_tab'    => ['type' => 'keydown', 'key' => 'Tab', 'target' => '*'],
            'input_change'   => ['type' => 'input', 'target' => 'input, textarea, select'],
            'mouse_hover'    => ['type' => 'mouseenter', 'target' => '*'],
            'mouse_leave'    => ['type' => 'mouseleave', 'target' => '*'],
        ];
    }

    /**
     * Validate color contrast for accessibility.
     */
    public static function validateColorContrast(string $foreground, string $background): array
    {
        // Convert colors to RGB values
        $fgRgb = self::hexToRgb($foreground);
        $bgRgb = self::hexToRgb($background);

        if (! $fgRgb || ! $bgRgb) {
            return ['valid' => false, 'error' => 'Invalid color format'];
        }

        // Calculate relative luminance
        $fgLuminance = self::relativeLuminance($fgRgb);
        $bgLuminance = self::relativeLuminance($bgRgb);

        // Calculate contrast ratio
        $contrastRatio = ($fgLuminance > $bgLuminance)
            ? ($fgLuminance + 0.05) / ($bgLuminance + 0.05)
            : ($bgLuminance + 0.05) / ($fgLuminance + 0.05);

        return [
            'contrast_ratio'  => $contrastRatio,
            'wcag_aa_normal'  => $contrastRatio >= 4.5,
            'wcag_aa_large'   => $contrastRatio >= 3.0,
            'wcag_aaa_normal' => $contrastRatio >= 7.0,
            'wcag_aaa_large'  => $contrastRatio >= 4.5,
        ];
    }

    /**
     * Generate test scenarios for cross-browser compatibility.
     */
    public static function browserTestScenarios(): array
    {
        return [
            'chrome_desktop' => [
                'browser'  => 'chrome',
                'platform' => 'desktop',
                'version'  => 'latest',
            ],
            'firefox_desktop' => [
                'browser'  => 'firefox',
                'platform' => 'desktop',
                'version'  => 'latest',
            ],
            'safari_desktop' => [
                'browser'  => 'safari',
                'platform' => 'desktop',
                'version'  => 'latest',
            ],
            'chrome_mobile' => [
                'browser'  => 'chrome',
                'platform' => 'mobile',
                'version'  => 'latest',
            ],
            'safari_mobile' => [
                'browser'  => 'safari',
                'platform' => 'mobile',
                'version'  => 'latest',
            ],
            'edge_desktop' => [
                'browser'  => 'edge',
                'platform' => 'desktop',
                'version'  => 'latest',
            ],
        ];
    }

    /**
     * Create test data with internationalization scenarios.
     */
    public static function i18nTestData(): array
    {
        return [
            'english' => [
                'locale'    => 'en',
                'text'      => 'Hello World',
                'direction' => 'ltr',
            ],
            'arabic' => [
                'locale'    => 'ar',
                'text'      => 'مرحبا بالعالم',
                'direction' => 'rtl',
            ],
            'chinese' => [
                'locale'    => 'zh',
                'text'      => '你好世界',
                'direction' => 'ltr',
            ],
            'german' => [
                'locale'    => 'de',
                'text'      => 'Hallo Welt',
                'direction' => 'ltr',
            ],
            'french' => [
                'locale'    => 'fr',
                'text'      => 'Bonjour le monde',
                'direction' => 'ltr',
            ],
            'hebrew' => [
                'locale'    => 'he',
                'text'      => 'שלום עולם',
                'direction' => 'rtl',
            ],
        ];
    }

    /**
     * Assert that HTML is valid and well-formed.
     */
    public static function assertValidHtml(string $html): bool
    {
        $dom             = new DOMDocument;
        $previousSetting = libxml_use_internal_errors(true);
        libxml_clear_errors();

        $result = $dom->loadHTML('<?xml encoding="utf-8" ?>'.$html);
        $errors = libxml_get_errors();

        libxml_use_internal_errors($previousSetting);

        return $result && empty($errors);
    }

    /**
     * Generate security test payloads.
     */
    public static function securityTestPayloads(): array
    {
        return [
            'xss_script'          => '<script>alert("XSS")</script>',
            'xss_img'             => '<img src="x" onerror="alert(\'XSS\')">',
            'xss_svg'             => '<svg onload="alert(\'XSS\')">',
            'sql_injection'       => "'; DROP TABLE users; --",
            'path_traversal'      => '../../../etc/passwd',
            'html_injection'      => '<div onclick="alert(\'Clicked\')">Click me</div>',
            'javascript_protocol' => 'javascript:alert("XSS")',
            'data_uri'            => 'data:text/html,<script>alert("XSS")</script>',
            'null_byte'           => "test\0injection",
            'unicode_bypass'      => '\u003cscript\u003ealert("XSS")\u003c/script\u003e',
        ];
    }

    /**
     * Calculate median of an array of numbers.
     */
    private static function calculateMedian(array $numbers): float
    {
        sort($numbers);
        $count = count($numbers);

        if (0 === $count % 2) {
            return ($numbers[$count / 2 - 1] + $numbers[$count / 2]) / 2;
        } else {
            return $numbers[floor($count / 2)];
        }
    }

    /**
     * Convert hex color to RGB array.
     */
    private static function hexToRgb(string $hex): ?array
    {
        $hex = ltrim($hex, '#');

        if (3 === strlen($hex)) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        if (6 !== strlen($hex)) {
            return null;
        }

        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2)),
        ];
    }

    /**
     * Calculate relative luminance for WCAG contrast calculations.
     */
    private static function relativeLuminance(array $rgb): float
    {
        $colors = [];

        foreach ($rgb as $color) {
            $color    = $color / 255;
            $colors[] = ($color <= 0.03928)
                ? $color / 12.92
                : pow(($color + 0.055) / 1.055, 2.4);
        }

        return 0.2126 * $colors[0] + 0.7152 * $colors[1] + 0.0722 * $colors[2];
    }
}
