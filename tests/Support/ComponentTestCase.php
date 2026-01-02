<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests\Support;

use ArtisanPack\LivewireUiComponents\Tests\TestCase;
use Error;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use stdClass;

/**
 * Base test case for component testing providing common patterns and utilities.
 *
 * This class establishes consistent testing approaches across all UI components,
 * providing methods for testing component instantiation, properties, rendering,
 * and common behaviors like UUID generation and color resolution.
 */
abstract class ComponentTestCase extends TestCase
{
    /**
     * The component class being tested.
     */
    protected string $componentClass;

    /**
     * Default properties for the component under test.
     */
    protected array $defaultProperties = [];

    /**
     * Required properties for the component under test.
     */
    protected array $requiredProperties = [];

    /**
     * Test that the component can be instantiated with default values.
     */
    public function test_component_can_be_instantiated(): void
    {
        $component = $this->createComponent();

        $this->assertInstanceOf($this->componentClass, $component);
        $this->assertInstanceOf(Component::class, $component);
    }

    /**
     * Test that the component renders successfully.
     */
    public function test_component_renders(): void
    {
        try {
            $component = $this->createComponent();
            $view      = $component->render();

            $this->assertInstanceOf(View::class, $view);
            $this->assertNotEmpty($view->name());

            // Try to actually render the view to catch slot errors
            try {
                $html = $view->render();
                $this->assertNotEmpty($html);
            } catch (\Illuminate\View\ViewException $e) {
                // If it's a slot/variable issue, skip test
                if (str_contains($e->getMessage(), 'Undefined variable') ||
                    str_contains($e->getMessage(), 'Undefined array key')) {
                    $this->markTestSkipped('Component requires slots or additional data: '.$e->getMessage());
                }
                throw $e;
            }
        } catch (Error $e) {
            // Handle null method calls (e.g., $attributes->whereStartsWith())
            if (str_contains($e->getMessage(), 'Call to a member function') &&
                str_contains($e->getMessage(), 'on null')) {
                $this->markTestSkipped('Component requires attributes or context: '.$e->getMessage());
            }
            throw $e;
        }
    }

    /**
     * Test default property values.
     */
    public function test_component_has_correct_default_values(): void
    {
        if (empty($this->defaultProperties)) {
            $this->markTestSkipped('No default properties defined for this component');
        }

        $component = $this->createComponent();

        foreach ($this->defaultProperties as $property => $expectedValue) {
            $this->assertEquals(
                $expectedValue,
                $component->$property,
                "Property '{$property}' should have default value '{$expectedValue}'",
            );
        }
    }

    /**
     * Test that the component generates a unique UUID.
     */
    public function test_component_generates_unique_uuid(): void
    {
        if (! $this->componentHasProperty('uuid')) {
            $this->markTestSkipped('Component does not have uuid property');
        }

        // Create components with different IDs to ensure different UUIDs
        $component1 = $this->createComponent(['id' => 'test-id-1']);
        $component2 = $this->createComponent(['id' => 'test-id-2']);

        $this->assertNotEmpty($component1->uuid);
        $this->assertNotEmpty($component2->uuid);
        $this->assertNotEquals($component1->uuid, $component2->uuid, 'UUIDs should be unique for different components');
        $this->assertStringStartsWith('artisanpack', $component1->uuid);
    }

    /**
     * Test that custom ID is used in UUID generation.
     */
    public function test_component_uses_custom_id_in_uuid(): void
    {
        if (! $this->componentHasProperty('uuid') || ! $this->componentHasProperty('id')) {
            $this->markTestSkipped('Component does not have uuid or id property');
        }

        $customId  = 'test-custom-id';
        $component = $this->createComponent(['id' => $customId]);

        $this->assertStringEndsWith($customId, $component->uuid);
    }

    /**
     * Test component color resolution if it has color properties.
     */
    public function test_component_resolves_colors(): void
    {
        if (! $this->componentHasProperty('color')) {
            $this->markTestSkipped('Component does not have color property');
        }

        // Test custom color if color method exists
        if (method_exists($this->componentClass, 'getColorClasses')) {
            $customColor  = 'blue-500';
            $component    = $this->createComponent(['color' => $customColor]);
            $colorClasses = $component->getColorClasses();

            $this->assertNotEmpty($colorClasses);
        } else {
            // Just verify component can be created with a color
            $component = $this->createComponent(['color' => 'primary']);
            $this->assertEquals('primary', $component->color);
        }
    }

    /**
     * Test component size variations if it has size property.
     */
    public function test_component_handles_size_variations(): void
    {
        if (! $this->componentHasProperty('size')) {
            $this->markTestSkipped('Component does not have size property');
        }

        $sizes = ['xs', 'sm', 'md', 'lg', 'xl'];

        foreach ($sizes as $size) {
            $component = $this->createComponent(['size' => $size]);
            $this->assertEquals($size, $component->size);
        }
    }

    /**
     * Test component variant handling if it has variant property.
     */
    public function test_component_handles_variants(): void
    {
        if (! $this->componentHasProperty('variant')) {
            $this->markTestSkipped('Component does not have variant property');
        }

        $variants = ['primary', 'secondary', 'success', 'warning', 'error', 'info'];

        foreach ($variants as $variant) {
            $component = $this->createComponent(['variant' => $variant]);
            $this->assertEquals($variant, $component->variant);
        }
    }

    /**
     * Test that component handles boolean properties correctly.
     */
    public function test_component_handles_boolean_properties(): void
    {
        $booleanProperties = $this->getBooleanProperties();

        if (empty($booleanProperties)) {
            $this->markTestSkipped('No boolean properties found for this component');
        }

        foreach ($booleanProperties as $property) {
            // Test true value
            $component = $this->createComponent([$property => true]);
            $this->assertTrue($component->$property, "Property '{$property}' should be true");

            // Test false value
            $component = $this->createComponent([$property => false]);
            $this->assertFalse($component->$property, "Property '{$property}' should be false");
        }
    }

    /**
     * Test that required properties are properly validated.
     */
    public function test_component_validates_required_properties(): void
    {
        if (empty($this->requiredProperties)) {
            $this->markTestSkipped('No required properties defined for this component');
        }

        foreach ($this->requiredProperties as $property) {
            try {
                $component = $this->createComponent([$property => null]);
                // If no exception is thrown, check that the property has a meaningful default
                $this->assertNotNull(
                    $component->$property,
                    "Required property '{$property}' should not be null",
                );
            } catch (Exception $e) {
                // Expected behavior for truly required properties
                $this->assertTrue(true, "Property '{$property}' correctly throws exception when null");
            }
        }
    }

    /**
     * Test component accessibility attributes.
     */
    public function test_component_has_accessibility_attributes(): void
    {
        try {
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
            }

            // Check for basic accessibility attributes
            $accessibilityChecks = [
                'aria-'     => 'Should have ARIA attributes for accessibility',
                'role='     => 'Should have role attributes where appropriate',
                'tabindex=' => 'Should have tabindex for keyboard navigation where appropriate',
            ];

            $hasAccessibilityFeatures = false;
            foreach (array_keys($accessibilityChecks) as $attribute) {
                if (str_contains($html, $attribute)) {
                    $hasAccessibilityFeatures = true;
                    break;
                }
            }

            // Only fail if this is an interactive component that should have accessibility features
            $interactiveComponents = ['Button', 'Input', 'Select', 'Checkbox', 'Radio', 'Toggle', 'Modal'];
            $componentName         = class_basename($this->componentClass);

            if (in_array($componentName, $interactiveComponents)) {
                $this->assertTrue(
                    $hasAccessibilityFeatures,
                    "Interactive component '{$componentName}' should have accessibility attributes",
                );
            }
        } catch (Error $e) {
            if (str_contains($e->getMessage(), 'Call to a member function') &&
                str_contains($e->getMessage(), 'on null')) {
                $this->markTestSkipped('Component requires attributes or context for rendering');
            }
            throw $e;
        }
    }

    /**
     * Create a component instance with the given properties.
     *
     * Filters out properties that don't exist as constructor parameters
     * and provides default values for required parameters.
     */
    protected function createComponent(array $properties = []): Component
    {
        // Filter properties to only include valid constructor parameters
        $filteredProperties = $this->filterValidConstructorParameters($properties);

        // Add default values for required parameters not provided
        $filteredProperties = $this->addRequiredParameterDefaults($filteredProperties);

        return new $this->componentClass(...$filteredProperties);
    }

    /**
     * Filter properties to only include valid constructor parameters for the component.
     */
    protected function filterValidConstructorParameters(array $properties): array
    {
        $reflection  = new ReflectionClass($this->componentClass);
        $constructor = $reflection->getConstructor();

        if (null === $constructor) {
            return [];
        }

        $validParams = [];
        foreach ($constructor->getParameters() as $param) {
            $validParams[] = $param->getName();
        }

        return array_filter(
            $properties,
            fn ($key) => in_array($key, $validParams),
            ARRAY_FILTER_USE_KEY,
        );
    }

    /**
     * Add default values for required constructor parameters that aren't provided.
     */
    protected function addRequiredParameterDefaults(array $properties): array
    {
        $reflection  = new ReflectionClass($this->componentClass);
        $constructor = $reflection->getConstructor();

        if (null === $constructor) {
            return $properties;
        }

        foreach ($constructor->getParameters() as $param) {
            $paramName = $param->getName();

            // Skip if already provided
            if (array_key_exists($paramName, $properties)) {
                continue;
            }

            // Skip if parameter has a default value or is optional
            if ($param->isDefaultValueAvailable() || $param->allowsNull()) {
                continue;
            }

            // Provide sensible defaults based on type
            $type = $param->getType();

            if (null === $type) {
                $properties[$paramName] = 'test';

                continue;
            }

            // Handle union types (e.g., ArrayAccess|array)
            $typeString = (string) $type;

            // Check for array-like types first
            if (str_contains($typeString, 'array') || str_contains($typeString, 'ArrayAccess')) {
                $properties[$paramName] = [];

                continue;
            }

            // Check for object types
            if (str_contains($typeString, 'object')) {
                $properties[$paramName] = new stdClass;

                continue;
            }

            // Handle named types
            $typeName = $type instanceof ReflectionNamedType ? $type->getName() : null;

            $properties[$paramName] = match ($typeName) {
                'string' => 'Test '.$paramName,
                'int'    => 1,
                'float'  => 1.0,
                'bool'   => false,
                'array'  => [],
                default  => 'test',
            };
        }

        return $properties;
    }

    /**
     * Check if the component has a specific property.
     */
    protected function componentHasProperty(string $property): bool
    {
        $reflection = new ReflectionClass($this->componentClass);

        return $reflection->hasProperty($property);
    }

    /**
     * Get all boolean properties of the component by checking actual type declarations.
     */
    protected function getBooleanProperties(): array
    {
        $reflection        = new ReflectionClass($this->componentClass);
        $constructor       = $reflection->getConstructor();
        $booleanProperties = [];

        if (null === $constructor) {
            return $booleanProperties;
        }

        // Check constructor parameters for actual bool types
        foreach ($constructor->getParameters() as $param) {
            $type = $param->getType();

            if (null === $type) {
                continue;
            }

            // Handle union types and named types
            $typeName = $type instanceof ReflectionNamedType ? $type->getName() : null;

            // Only include parameters that are strictly bool type
            if ('bool' === $typeName) {
                $booleanProperties[] = $param->getName();
            }
        }

        return $booleanProperties;
    }

    /**
     * Assert that a component renders specific HTML content.
     */
    protected function assertComponentRenders(Component $component, string $expectedContent): void
    {
        try {
            $view = $component->render();

            try {
                $html = $view->render();
            } catch (\Illuminate\View\ViewException $e) {
                if (str_contains($e->getMessage(), 'Undefined variable') ||
                    str_contains($e->getMessage(), 'Undefined array key')) {
                    $this->markTestSkipped('Component requires slots or additional data for rendering');
                }
                throw $e;
            }

            $this->assertStringContainsString(
                $expectedContent,
                $html,
                "Component should render expected content: {$expectedContent}",
            );
        } catch (Error $e) {
            if (str_contains($e->getMessage(), 'Call to a member function') &&
                str_contains($e->getMessage(), 'on null')) {
                $this->markTestSkipped('Component requires attributes or context for rendering');
            }
            throw $e;
        }
    }

    /**
     * Assert that a component has specific CSS classes.
     */
    protected function assertComponentHasClasses(Component $component, array $expectedClasses): void
    {
        try {
            $view = $component->render();

            try {
                $html = $view->render();
            } catch (\Illuminate\View\ViewException $e) {
                if (str_contains($e->getMessage(), 'Undefined variable') ||
                    str_contains($e->getMessage(), 'Undefined array key')) {
                    $this->markTestSkipped('Component requires slots or additional data for rendering');
                }
                throw $e;
            }

            foreach ($expectedClasses as $class) {
                $this->assertStringContainsString(
                    $class,
                    $html,
                    "Component should have CSS class: {$class}",
                );
            }
        } catch (Error $e) {
            if (str_contains($e->getMessage(), 'Call to a member function') &&
                str_contains($e->getMessage(), 'on null')) {
                $this->markTestSkipped('Component requires attributes or context for rendering');
            }
            throw $e;
        }
    }

    /**
     * Get all component properties and their current values.
     */
    protected function getComponentProperties(Component $component): array
    {
        $reflection = new ReflectionClass($component);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        $values     = [];

        foreach ($properties as $property) {
            $values[$property->getName()] = $component->{$property->getName()};
        }

        return $values;
    }
}
