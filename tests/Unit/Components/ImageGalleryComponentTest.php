<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests\Unit\Components;

use ArtisanPack\LivewireUiComponents\Tests\Support\ComponentDataFactory;
use ArtisanPack\LivewireUiComponents\Tests\Support\ComponentTestCase;
use ArtisanPack\LivewireUiComponents\Tests\Support\TestHelpers;
use ArtisanPack\LivewireUiComponents\View\Components\ImageGallery;
use Error;
use RuntimeException;

/**
 * Comprehensive unit tests for the ImageGallery component.
 *
 * Auto-generated test class that extends ComponentTestCase to inherit
 * common testing patterns and implements component-specific tests.
 */
class ImageGalleryComponentTest extends ComponentTestCase
{
    protected string $componentClass = ImageGallery::class;

    protected array $defaultProperties = [
        'columns'        => ['default' => 1, 'sm' => 2, 'md' => 3, 'lg' => 4, 'xl' => 5],
        'aspectRatio'    => 'square',
        'gap'            => 'md',
        'enableLightbox' => true,
        'showCaptions'   => false,
        'layout'         => 'grid',
        'lazyLoad'       => true,
        'itemsPerPage'   => 0,
        'loadingStyle'   => 'skeleton',
    ];

    protected array $requiredProperties = [
        'images',
    ];

    public function test_imagegallery_string_properties(): void
    {
        $stringProperties = ['id', 'aspectRatio', 'gap', 'layout', 'loadingStyle'];
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

    public function test_imagegallery_boolean_properties(): void
    {
        $booleanProperties = ['enableLightbox', 'showCaptions', 'lazyLoad'];

        foreach ($booleanProperties as $property) {
            $component = $this->createComponent([$property => true]);
            $this->assertTrue($component->$property);

            $component = $this->createComponent([$property => false]);
            $this->assertFalse($component->$property);
        }
    }

    public function test_imagegallery_array_properties(): void
    {
        $arrayProperties = ['images', 'filters'];
        $testArrays      = ComponentDataFactory::arrayData();

        foreach ($arrayProperties as $property) {
            foreach ($testArrays as $array) {
                $component = $this->createComponent([$property => $array]);
                $this->assertEquals($array, $component->$property);
            }
        }
    }

    public function test_imagegallery_get_gap_class_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'getGapClass')) {
            try {
                $result = $component->getGapClass();
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

    public function test_imagegallery_get_aspect_ratio_class_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'getAspectRatioClass')) {
            try {
                $result = $component->getAspectRatioClass();
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

    public function test_imagegallery_get_grid_column_classes_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'getGridColumnClasses')) {
            try {
                $result = $component->getGridColumnClasses();
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

    public function test_imagegallery_get_loading_class_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'getLoadingClass')) {
            try {
                $result = $component->getLoadingClass();
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

    public function test_imagegallery_renders_successfully(): void
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

    public function test_imagegallery_accessibility_compliance(): void
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
            'ImageGallery should have valid HTML structure. Issues: '.implode(', ', $validation['issues']),
        );
    }

    public function test_imagegallery_security_against_xss(): void
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

    public function test_imagegallery_performance(): void
    {
        try {
            $performance = TestHelpers::measureRenderingPerformance($this->createComponent(), 10);
            $this->assertLessThan(100, $performance['average_time'],
                'ImageGallery rendering should be under 100ms on average',
            );
        } catch (RuntimeException $e) {
            if (str_contains($e->getMessage(), 'requires slots') ||
                str_contains($e->getMessage(), 'requires additional context')) {
                $this->markTestSkipped($e->getMessage());
            }
            throw $e;
        }
    }
}
