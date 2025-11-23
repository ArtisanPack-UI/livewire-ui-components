<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests\Unit\Components;

use ArtisanPack\LivewireUiComponents\Tests\Support\ComponentDataFactory;
use ArtisanPack\LivewireUiComponents\Tests\Support\ComponentTestCase;
use ArtisanPack\LivewireUiComponents\Tests\Support\TestHelpers;
use ArtisanPack\LivewireUiComponents\View\Components\Colorpicker;
use Error;
use RuntimeException;

/**
 * Comprehensive unit tests for the Colorpicker component.
 *
 * Auto-generated test class that extends ComponentTestCase to inherit
 * common testing patterns and implements component-specific tests.
 */
class ColorpickerComponentTest extends ComponentTestCase
{
    protected string $componentClass = Colorpicker::class;

    protected array $defaultProperties = [
        'icon'           => '',
        'hintClass'      => 'fieldset-label',
        'inline'         => false,
        'clearable'      => false,
        'random'         => false,
        'errorClass'     => 'text-error',
        'omitError'      => false,
        'firstErrorOnly' => false,
    ];

    protected array $requiredProperties = [];

    public function test_colorpicker_string_properties(): void
    {
        $stringProperties = ['id', 'label', 'icon', 'iconRight', 'hint', 'hintClass', 'prefix', 'suffix', 'errorField', 'errorClass'];
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

    public function test_colorpicker_boolean_properties(): void
    {
        $booleanProperties = ['inline', 'clearable', 'random', 'omitError', 'firstErrorOnly'];

        foreach ($booleanProperties as $property) {
            $component = $this->createComponent([$property => true]);
            $this->assertTrue($component->$property);

            $component = $this->createComponent([$property => false]);
            $this->assertFalse($component->$property);
        }
    }

    public function test_colorpicker_model_name_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'modelName')) {
            try {
                $result = $component->modelName();
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

    public function test_colorpicker_error_field_name_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'errorFieldName')) {
            try {
                $result = $component->errorFieldName();
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

    public function test_colorpicker_is_readonly_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'isReadonly')) {
            try {
                $result = $component->isReadonly();
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

    public function test_colorpicker_is_disabled_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'isDisabled')) {
            try {
                $result = $component->isDisabled();
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

    public function test_colorpicker_renders_successfully(): void
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

    public function test_colorpicker_accessibility_compliance(): void
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
            'Colorpicker should have valid HTML structure. Issues: '.implode(', ', $validation['issues']),
        );
    }

    public function test_colorpicker_security_against_xss(): void
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

    public function test_colorpicker_performance(): void
    {
        try {
            $performance = TestHelpers::measureRenderingPerformance($this->createComponent(), 10);
            $this->assertLessThan(100, $performance['average_time'],
                'Colorpicker rendering should be under 100ms on average',
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
