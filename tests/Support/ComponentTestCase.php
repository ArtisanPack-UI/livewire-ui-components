<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Support;

use ArtisanPack\LivewireUiComponents\Tests\TestCase;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use ReflectionClass;
use ReflectionProperty;

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
        $component = $this->createComponent();
        $view = $component->render();

        $this->assertInstanceOf(View::class, $view);
        $this->assertNotEmpty($view->name());
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
                "Property '{$property}' should have default value '{$expectedValue}'"
            );
        }
    }

    /**
     * Test that the component generates a unique UUID.
     */
    public function test_component_generates_unique_uuid(): void
    {
        if (!$this->componentHasProperty('uuid')) {
            $this->markTestSkipped('Component does not have uuid property');
        }

        $component1 = $this->createComponent();
        $component2 = $this->createComponent();

        $this->assertNotEmpty($component1->uuid);
        $this->assertNotEmpty($component2->uuid);
        $this->assertNotEquals($component1->uuid, $component2->uuid);
        $this->assertStringStartsWith('artisanpack', $component1->uuid);
    }

    /**
     * Test that custom ID is used in UUID generation.
     */
    public function test_component_uses_custom_id_in_uuid(): void
    {
        if (!$this->componentHasProperty('uuid') || !$this->componentHasProperty('id')) {
            $this->markTestSkipped('Component does not have uuid or id property');
        }

        $customId = 'test-custom-id';
        $component = $this->createComponent(['id' => $customId]);

        $this->assertStringEndsWith($customId, $component->uuid);
    }

    /**
     * Test component color resolution if it has color properties.
     */
    public function test_component_resolves_colors(): void
    {
        if (!$this->componentHasProperty('color')) {
            $this->markTestSkipped('Component does not have color property');
        }

        // Test default color
        $component = $this->createComponent();
        $this->assertNotNull($component->resolvedColor ?? $component->color);

        // Test custom color if color method exists
        if (method_exists($component, 'getColorClasses')) {
            $customColor = 'blue-500';
            $component = $this->createComponent(['color' => $customColor]);
            $colorClasses = $component->getColorClasses();
            
            $this->assertNotEmpty($colorClasses);
        }
    }

    /**
     * Test component size variations if it has size property.
     */
    public function test_component_handles_size_variations(): void
    {
        if (!$this->componentHasProperty('size')) {
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
        if (!$this->componentHasProperty('variant')) {
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
                    "Required property '{$property}' should not be null"
                );
            } catch (\Exception $e) {
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
        $component = $this->createComponent();
        $view = $component->render();
        $html = $view->render();

        // Check for basic accessibility attributes
        $accessibilityChecks = [
            'aria-' => 'Should have ARIA attributes for accessibility',
            'role=' => 'Should have role attributes where appropriate',
            'tabindex=' => 'Should have tabindex for keyboard navigation where appropriate'
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
        $componentName = class_basename($this->componentClass);
        
        if (in_array($componentName, $interactiveComponents)) {
            $this->assertTrue(
                $hasAccessibilityFeatures,
                "Interactive component '{$componentName}' should have accessibility attributes"
            );
        }
    }

    /**
     * Create a component instance with the given properties.
     */
    protected function createComponent(array $properties = []): Component
    {
        return new $this->componentClass(...$properties);
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
     * Get all boolean properties of the component.
     */
    protected function getBooleanProperties(): array
    {
        $reflection = new ReflectionClass($this->componentClass);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        $booleanProperties = [];

        foreach ($properties as $property) {
            $propertyName = $property->getName();
            
            // Skip certain non-boolean properties
            if (in_array($propertyName, ['uuid', 'id', 'class', 'style', 'attributes'])) {
                continue;
            }

            // Check if property name suggests boolean (starts with 'is', 'has', 'can', etc.)
            $booleanPrefixes = ['is', 'has', 'can', 'should', 'will', 'does'];
            $startsWithBooleanPrefix = false;
            
            foreach ($booleanPrefixes as $prefix) {
                if (str_starts_with(strtolower($propertyName), $prefix)) {
                    $startsWithBooleanPrefix = true;
                    break;
                }
            }

            // Common boolean property names
            $commonBooleanProperties = [
                'disabled', 'readonly', 'required', 'multiple', 'checked', 
                'selected', 'active', 'visible', 'hidden', 'loading',
                'clearable', 'searchable', 'sortable', 'filterable',
                'draggable', 'resizable', 'collapsible', 'expandable'
            ];

            if ($startsWithBooleanPrefix || in_array(strtolower($propertyName), $commonBooleanProperties)) {
                $booleanProperties[] = $propertyName;
            }
        }

        return $booleanProperties;
    }

    /**
     * Assert that a component renders specific HTML content.
     */
    protected function assertComponentRenders(Component $component, string $expectedContent): void
    {
        $view = $component->render();
        $html = $view->render();
        
        $this->assertStringContainsString(
            $expectedContent,
            $html,
            "Component should render expected content: {$expectedContent}"
        );
    }

    /**
     * Assert that a component has specific CSS classes.
     */
    protected function assertComponentHasClasses(Component $component, array $expectedClasses): void
    {
        $view = $component->render();
        $html = $view->render();
        
        foreach ($expectedClasses as $class) {
            $this->assertStringContainsString(
                $class,
                $html,
                "Component should have CSS class: {$class}"
            );
        }
    }

    /**
     * Get all component properties and their current values.
     */
    protected function getComponentProperties(Component $component): array
    {
        $reflection = new ReflectionClass($component);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        $values = [];

        foreach ($properties as $property) {
            $values[$property->getName()] = $component->{$property->getName()};
        }

        return $values;
    }
}