<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Performance;

use ArtisanPack\LivewireUiComponents\Tests\TestCase;
use ArtisanPack\LivewireUiComponents\Tests\Support\TestHelpers;
use PHPUnit\Framework\Attributes\Test;

/**
 * Performance tests for UI components.
 *
 * Tests rendering speed, memory usage, and scalability.
 */
class ComponentPerformanceTest extends TestCase
{
    /**
     * Maximum acceptable rendering time in milliseconds.
     */
    private const MAX_RENDER_TIME = 100;

    /**
     * Maximum acceptable memory usage in bytes (5MB).
     */
    private const MAX_MEMORY_USAGE = 5 * 1024 * 1024;

    #[Test]
    public function button_component_renders_quickly()
    {
        $component = new \ArtisanPack\LivewireUiComponents\View\Components\Button(
            label: 'Test Button',
            color: 'primary'
        );

        $performance = TestHelpers::measureRenderingPerformance($component, 100);

        $this->assertLessThan(self::MAX_RENDER_TIME, $performance['average_time'],
            "Button rendering took {$performance['average_time']}ms on average, should be under " . self::MAX_RENDER_TIME . "ms"
        );

        $this->assertLessThan(self::MAX_MEMORY_USAGE, $performance['peak_memory'],
            "Button used {$performance['peak_memory']} bytes, should be under " . self::MAX_MEMORY_USAGE . " bytes"
        );
    }

    #[Test]
    public function repeated_rendering_does_not_leak_memory()
    {
        $component = new \ArtisanPack\LivewireUiComponents\View\Components\Button(
            label: 'Test Button'
        );

        // Warm up
        for ($i = 0; $i < 10; $i++) {
            $component->render()->render();
        }

        $initialMemory = memory_get_usage();

        // Render 100 times
        for ($i = 0; $i < 100; $i++) {
            $component->render()->render();
        }

        $finalMemory = memory_get_usage();
        $memoryGrowth = $finalMemory - $initialMemory;

        // Allow for some growth, but not excessive (1MB max)
        $this->assertLessThan(1024 * 1024, $memoryGrowth,
            "Memory grew by {$memoryGrowth} bytes after 100 renders, possible memory leak"
        );
    }
}
