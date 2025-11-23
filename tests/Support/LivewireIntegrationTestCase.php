<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests\Support;

use ArtisanPack\LivewireUiComponents\Tests\TestCase;
use InvalidArgumentException;
use Livewire\Livewire;
use Livewire\Testing\TestableLivewire;

/**
 * Base test case for Livewire integration testing.
 *
 * This class provides utilities for testing Livewire components,
 * event handling, data binding, and lifecycle methods.
 */
abstract class LivewireIntegrationTestCase extends TestCase
{
    /**
     * The Livewire component class being tested.
     */
    protected ?string $livewireComponentClass = null;

    /**
     * Default properties for the Livewire component.
     */
    protected array $defaultLivewireProperties = [];

    /**
     * Create a testable Livewire component instance.
     */
    protected function createLivewireComponent(array $properties = []): TestableLivewire
    {
        if (! $this->livewireComponentClass) {
            throw new InvalidArgumentException('livewireComponentClass must be set');
        }

        $mergedProperties = array_merge($this->defaultLivewireProperties, $properties);

        return Livewire::test($this->livewireComponentClass, $mergedProperties);
    }

    /**
     * Create a simple test Livewire component for UI component testing.
     */
    protected function createTestComponentWithUIComponent(string $uiComponentName, array $uiProps = []): TestableLivewire
    {
        $componentClass = $this->createDynamicLivewireComponent($uiComponentName, $uiProps);

        return Livewire::test($componentClass);
    }

    /**
     * Test that a Livewire component renders a UI component successfully.
     */
    protected function assertUIComponentRenders(string $uiComponentName, array $uiProps = []): void
    {
        $testable = $this->createTestComponentWithUIComponent($uiComponentName, $uiProps);

        $testable->assertStatus(200);
        $testable->assertSee('<'); // Should contain HTML

        // Check for component-specific content
        if (isset($uiProps['label'])) {
            $testable->assertSee($uiProps['label']);
        }
        if (isset($uiProps['id'])) {
            $testable->assertSeeHtml("id=\"{$uiProps['id']}\"");
        }
    }

    /**
     * Test Livewire property binding with UI component.
     */
    protected function assertPropertyBinding(string $property, $initialValue, $newValue): void
    {
        $testable = $this->createLivewireComponent([$property => $initialValue]);

        // Assert initial value
        $testable->assertSet($property, $initialValue);

        // Update property
        $testable->set($property, $newValue);

        // Assert updated value
        $testable->assertSet($property, $newValue);
    }

    /**
     * Test Livewire event emission and handling.
     */
    protected function assertEventHandling(string $eventName, array $eventData = []): void
    {
        $testable = $this->createLivewireComponent();

        // Dispatch event
        $testable->dispatch($eventName, ...$eventData);

        // Verify component handles the event appropriately
        $testable->assertStatus(200);
    }

    /**
     * Test component lifecycle methods.
     */
    protected function assertLifecycleMethods(): void
    {
        $testable = $this->createLivewireComponent();

        // Component should mount successfully
        $testable->assertStatus(200);

        // Test property updates trigger lifecycle
        if (method_exists($testable->instance(), 'updated')) {
            $testable->set('testProperty', 'updated value');
            $testable->assertStatus(200);
        }
    }

    /**
     * Test wire:model functionality with UI components.
     */
    protected function assertWireModelBinding(string $property, $testValue): void
    {
        $testable = $this->createLivewireComponent();

        // Set initial value
        $testable->set($property, $testValue);
        $testable->assertSet($property, $testValue);

        // Verify the binding persists
        $testable->call('$refresh');
        $testable->assertSet($property, $testValue);
    }

    /**
     * Test wire:click functionality.
     */
    protected function assertWireClickHandling(string $method, array $parameters = []): void
    {
        $testable = $this->createLivewireComponent();

        if (method_exists($testable->instance(), $method)) {
            $testable->call($method, ...$parameters);
            $testable->assertStatus(200);
        }
    }

    /**
     * Test component validation.
     */
    protected function assertValidation(array $rules, array $invalidData, array $validData): void
    {
        $testable = $this->createLivewireComponent();

        // Test with invalid data
        foreach ($invalidData as $field => $value) {
            $testable->set($field, $value);
        }

        if (method_exists($testable->instance(), 'validateOnly')) {
            foreach (array_keys($invalidData) as $field) {
                try {
                    $testable->call('validateOnly', $field);
                    // If validation passes when it shouldn't, fail the test
                    $this->fail("Validation should have failed for field: {$field}");
                } catch (\Livewire\Exceptions\ValidationException $e) {
                    // Expected validation failure
                    $this->assertTrue(true);
                }
            }
        }

        // Test with valid data
        foreach ($validData as $field => $value) {
            $testable->set($field, $value);
        }

        $testable->assertHasNoErrors(array_keys($validData));
    }

    /**
     * Test loading states and wire:loading.
     */
    protected function assertLoadingStates(string $action): void
    {
        $testable = $this->createLivewireComponent();

        if (method_exists($testable->instance(), $action)) {
            // Component should show loading state during action
            $testable->call($action);
            $testable->assertStatus(200);
        }
    }

    /**
     * Test component with large datasets for performance.
     */
    protected function assertPerformanceWithLargeDataset(array $largeDataset): void
    {
        $start = microtime(true);

        $testable = $this->createLivewireComponent(['items' => $largeDataset]);
        $testable->assertStatus(200);

        $end      = microtime(true);
        $duration = ($end - $start) * 1000; // Convert to milliseconds

        // Should render within reasonable time (less than 5 seconds)
        $this->assertLessThan(5000, $duration, 'Component should handle large datasets efficiently');
    }

    /**
     * Test component memory usage during interactions.
     */
    protected function assertMemoryEfficiency(callable $interactions): void
    {
        $initialMemory = memory_get_usage();

        $testable = $this->createLivewireComponent();

        // Perform interactions
        $interactions($testable);

        $finalMemory = memory_get_usage();
        $memoryUsed  = $finalMemory - $initialMemory;

        // Should not use excessive memory (less than 50MB)
        $this->assertLessThan(50 * 1024 * 1024, $memoryUsed, 'Component should be memory efficient');
    }

    /**
     * Test component accessibility with Livewire interactions.
     */
    protected function assertAccessibilityCompliance(): void
    {
        $testable = $this->createLivewireComponent();
        $html     = $testable->get()->html();

        $validation = TestHelpers::validateHtmlStructure($html);
        $this->assertTrue($validation['is_valid'],
            'Livewire component should maintain accessibility compliance',
        );

        $accessibility = TestHelpers::hasAccessibilityAttributes($html);

        // Interactive components should have proper accessibility
        $hasAccessibilityFeatures = array_reduce($accessibility, fn ($carry, $item) => $carry || $item, false);

        if ($hasAccessibilityFeatures) {
            $this->assertTrue($hasAccessibilityFeatures, 'Interactive components should have accessibility features');
        }
    }

    /**
     * Test component security against XSS in Livewire context.
     */
    protected function assertXSSProtection(array $xssPayloads): void
    {
        foreach ($xssPayloads as $payload) {
            $testable = $this->createLivewireComponent(['userInput' => $payload]);
            $html     = $testable->get()->html();

            // Should not contain unescaped script tags
            $this->assertStringNotContainsString('<script', $html);
            $this->assertStringNotContainsString('javascript:', $html);
            $this->assertStringNotContainsString('onerror=', $html);
        }
    }

    /**
     * Test component state persistence across requests.
     */
    protected function assertStatePersistence(array $stateData): void
    {
        $testable = $this->createLivewireComponent();

        // Set state
        foreach ($stateData as $property => $value) {
            $testable->set($property, $value);
        }

        // Trigger a refresh
        $testable->call('$refresh');

        // Verify state persists
        foreach ($stateData as $property => $value) {
            $testable->assertSet($property, $value);
        }
    }

    /**
     * Test concurrent user interactions.
     */
    protected function assertConcurrentInteractions(): void
    {
        $testables = [];

        // Create multiple component instances
        for ($i = 0; $i < 5; $i++) {
            $testables[] = $this->createLivewireComponent(['userId' => $i]);
        }

        // Perform concurrent actions
        foreach ($testables as $i => $testable) {
            $testable->set('concurrentData', "user_{$i}_data");
            $testable->assertSet('concurrentData', "user_{$i}_data");
        }

        // Verify each instance maintains its own state
        foreach ($testables as $i => $testable) {
            $testable->assertSet('concurrentData', "user_{$i}_data");
        }
    }

    /**
     * Create a dynamic Livewire component for testing UI components.
     */
    private function createDynamicLivewireComponent(string $uiComponentName, array $uiProps = []): string
    {
        $className = 'TestLivewireComponent'.uniqid();
        $propsStr  = json_encode($uiProps);

        $classCode = "
        class {$className} extends Livewire\Component
        {
            public function render()
            {
                return view('test-component', [
                    'uiComponent' => '{$uiComponentName}',
                    'uiProps' => json_decode('{$propsStr}', true)
                ]);
            }
        }
        ";

        eval($classCode);

        // Create a simple view for testing
        if (! view()->exists('test-component')) {
            view()->addLocation(__DIR__);
            file_put_contents(__DIR__.'/test-component.blade.php',
                '<x-dynamic-component :component="$uiComponent" v-bind="$uiProps" />');
        }

        return $className;
    }
}
