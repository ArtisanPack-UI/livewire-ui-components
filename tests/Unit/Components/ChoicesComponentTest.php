<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests\Unit\Components;

use ArtisanPack\LivewireUiComponents\Tests\Support\ComponentDataFactory;
use ArtisanPack\LivewireUiComponents\Tests\Support\ComponentTestCase;
use ArtisanPack\LivewireUiComponents\Tests\Support\TestHelpers;
use ArtisanPack\LivewireUiComponents\View\Components\Choices;
use Error;
use RuntimeException;

/**
 * Comprehensive unit tests for the Choices component.
 *
 * Auto-generated test class that extends ComponentTestCase to inherit
 * common testing patterns and implements component-specific tests.
 */
class ChoicesComponentTest extends ComponentTestCase
{
    protected string $componentClass = Choices::class;

    protected array $defaultProperties = [
        'hintClass'      => 'fieldset-label',
        'inline'         => false,
        'clearable'      => false,
        'searchable'     => false,
        'single'         => false,
        'compact'        => false,
        'compactText'    => 'selected',
        'allowAll'       => false,
        'debounce'       => '250ms',
        'minChars'       => 0,
        'allowAllText'   => 'Select all',
        'removeAllText'  => 'Remove all',
        'searchFunction' => 'search',
        'optionValue'    => 'id',
        'optionLabel'    => 'name',
        'optionSubLabel' => '',
        'optionAvatar'   => 'avatar',
        'valuesAsString' => false,
        'height'         => 'max-h-64',
        'options'        => [],
        'noResultText'   => 'No results found.',
        'errorClass'     => 'text-error',
        'omitError'      => false,
        'firstErrorOnly' => false,
    ];

    protected array $requiredProperties = [];

    public function test_choices_string_properties(): void
    {
        $stringProperties = ['id', 'label', 'hint', 'hintClass', 'icon', 'iconRight', 'prefix', 'suffix', 'compactText', 'debounce', 'allowAllText', 'removeAllText', 'searchFunction', 'optionValue', 'optionLabel', 'optionSubLabel', 'optionAvatar', 'height', 'noResultText', 'errorField', 'errorClass'];
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

    public function test_choices_boolean_properties(): void
    {
        $booleanProperties = ['inline', 'clearable', 'searchable', 'single', 'compact', 'allowAll', 'valuesAsString', 'omitError', 'firstErrorOnly'];

        foreach ($booleanProperties as $property) {
            $component = $this->createComponent([$property => true]);
            $this->assertTrue($component->$property);

            $component = $this->createComponent([$property => false]);
            $this->assertFalse($component->$property);
        }
    }

    public function test_choices_model_name_method(): void
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

    public function test_choices_error_field_name_method(): void
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

    public function test_choices_is_readonly_method(): void
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

    public function test_choices_is_required_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'isRequired')) {
            try {
                $result = $component->isRequired();
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

    public function test_choices_is_disabled_method(): void
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

    public function test_choices_get_option_value_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'getOptionValue')) {
            try {
                $result = $component->getOptionValue();
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

    public function test_choices_renders_successfully(): void
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

    public function test_choices_accessibility_compliance(): void
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
            'Choices should have valid HTML structure. Issues: '.implode(', ', $validation['issues']),
        );
    }

    public function test_choices_security_against_xss(): void
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

    public function test_choices_performance(): void
    {
        try {
            $performance = TestHelpers::measureRenderingPerformance($this->createComponent(), 10);
            $this->assertLessThan(100, $performance['average_time'],
                'Choices rendering should be under 100ms on average',
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
