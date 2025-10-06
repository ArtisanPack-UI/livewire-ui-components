<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Integration;

use ArtisanPack\LivewireUiComponents\Tests\Support\LivewireIntegrationTestCase;
use ArtisanPack\LivewireUiComponents\Tests\Support\ComponentDataFactory;
use ArtisanPack\LivewireUiComponents\Tests\Support\TestHelpers;
use Livewire\Component as LivewireComponent;

/**
 * Integration tests for Button component with Livewire.
 * 
 * Tests the Button component's behavior within Livewire contexts,
 * including event handling, property binding, and interactive features.
 */
class ButtonLivewireIntegrationTest extends LivewireIntegrationTestCase
{
    public function test_button_renders_in_livewire_context(): void
    {
        $this->assertUIComponentRenders('button', [
            'label' => 'Test Button',
            'id' => 'test-btn',
            'variant' => 'primary'
        ]);
    }

    public function test_button_with_wire_click_functionality(): void
    {
        $component = new class extends LivewireComponent {
            public $clicked = false;
            public $clickCount = 0;

            public function handleClick()
            {
                $this->clicked = true;
                $this->clickCount++;
            }

            public function render()
            {
                return view('test-button-click', [
                    'clicked' => $this->clicked,
                    'clickCount' => $this->clickCount
                ]);
            }
        };

        // Create test view
        if (!view()->exists('test-button-click')) {
            view()->addLocation(__DIR__ . '/../Support');
            file_put_contents(__DIR__ . '/../Support/test-button-click.blade.php', 
                '<x-button label="Click Me" wire:click="handleClick" />
                 @if($clicked)
                    <span>Clicked {{ $clickCount }} times</span>
                 @endif');
        }

        $testable = \Livewire\Livewire::test($component::class);
        
        // Initial state
        $testable->assertSet('clicked', false);
        $testable->assertSet('clickCount', 0);
        $testable->assertDontSee('Clicked');

        // Click the button
        $testable->call('handleClick');
        
        // Verify state change
        $testable->assertSet('clicked', true);
        $testable->assertSet('clickCount', 1);
        $testable->assertSee('Clicked 1 times');

        // Click again
        $testable->call('handleClick');
        $testable->assertSet('clickCount', 2);
        $testable->assertSee('Clicked 2 times');
    }

    public function test_button_with_loading_states(): void
    {
        $component = new class extends LivewireComponent {
            public $loading = false;
            
            public function slowAction()
            {
                $this->loading = true;
                // Simulate slow operation
                usleep(100000); // 100ms
                $this->loading = false;
            }

            public function render()
            {
                return view('test-button-loading', ['loading' => $this->loading]);
            }
        };

        // Create test view
        if (!view()->exists('test-button-loading')) {
            view()->addLocation(__DIR__ . '/../Support');
            file_put_contents(__DIR__ . '/../Support/test-button-loading.blade.php', 
                '<x-button label="Submit" wire:click="slowAction" wire:loading.attr="disabled" />
                 <div wire:loading wire:target="slowAction">
                    Processing...
                 </div>');
        }

        $testable = \Livewire\Livewire::test($component::class);
        
        // Initially not loading
        $testable->assertDontSee('Processing...');
        
        // Call slow action
        $testable->call('slowAction');
        
        // Should complete successfully
        $testable->assertStatus(200);
    }

    public function test_button_with_form_binding(): void
    {
        $component = new class extends LivewireComponent {
            public $formData = [
                'name' => '',
                'email' => '',
                'submitted' => false
            ];

            protected $rules = [
                'formData.name' => 'required|min:3',
                'formData.email' => 'required|email'
            ];

            public function submit()
            {
                $this->validate();
                $this->formData['submitted'] = true;
            }

            public function render()
            {
                return view('test-button-form', ['formData' => $this->formData]);
            }
        };

        // Create test view
        if (!view()->exists('test-button-form')) {
            view()->addLocation(__DIR__ . '/../Support');
            file_put_contents(__DIR__ . '/../Support/test-button-form.blade.php', 
                '<form wire:submit.prevent="submit">
                    <input wire:model="formData.name" placeholder="Name" />
                    <input wire:model="formData.email" placeholder="Email" />
                    <x-button type="submit" label="Submit Form" />
                 </form>
                 @if($formData["submitted"])
                    <div>Form submitted successfully!</div>
                 @endif');
        }

        $testable = \Livewire\Livewire::test($component::class);
        
        // Submit empty form (should fail validation)
        $testable->call('submit');
        $testable->assertHasErrors(['formData.name', 'formData.email']);
        $testable->assertSet('formData.submitted', false);
        
        // Fill form with valid data
        $testable->set('formData.name', 'John Doe');
        $testable->set('formData.email', 'john@example.com');
        
        // Submit valid form
        $testable->call('submit');
        $testable->assertHasNoErrors();
        $testable->assertSet('formData.submitted', true);
        $testable->assertSee('Form submitted successfully!');
    }

    public function test_button_with_dynamic_properties(): void
    {
        $component = new class extends LivewireComponent {
            public $buttonVariant = 'primary';
            public $buttonLabel = 'Dynamic Button';
            public $isDisabled = false;

            public function changeVariant()
            {
                $variants = ['primary', 'secondary', 'success', 'warning'];
                $currentIndex = array_search($this->buttonVariant, $variants);
                $this->buttonVariant = $variants[($currentIndex + 1) % count($variants)];
            }

            public function toggleDisabled()
            {
                $this->isDisabled = !$this->isDisabled;
            }

            public function render()
            {
                return view('test-button-dynamic', [
                    'variant' => $this->buttonVariant,
                    'label' => $this->buttonLabel,
                    'disabled' => $this->isDisabled
                ]);
            }
        };

        // Create test view
        if (!view()->exists('test-button-dynamic')) {
            view()->addLocation(__DIR__ . '/../Support');
            file_put_contents(__DIR__ . '/../Support/test-button-dynamic.blade.php', 
                '<x-button :label="$label" :variant="$variant" :disabled="$disabled" />
                 <x-button label="Change Variant" wire:click="changeVariant" />
                 <x-button label="Toggle Disabled" wire:click="toggleDisabled" />
                 <div>Current variant: {{ $variant }}</div>');
        }

        $testable = \Livewire\Livewire::test($component::class);
        
        // Initial state
        $testable->assertSet('buttonVariant', 'primary');
        $testable->assertSet('isDisabled', false);
        $testable->assertSee('Current variant: primary');
        
        // Change variant
        $testable->call('changeVariant');
        $testable->assertSet('buttonVariant', 'secondary');
        $testable->assertSee('Current variant: secondary');
        
        // Toggle disabled state
        $testable->call('toggleDisabled');
        $testable->assertSet('isDisabled', true);
    }

    public function test_button_with_event_emission(): void
    {
        $component = new class extends LivewireComponent {
            public $eventReceived = false;
            public $eventData = null;

            protected $listeners = ['buttonClicked' => 'handleButtonClicked'];

            public function emitButtonClick()
            {
                $this->dispatch('buttonClicked', ['timestamp' => now()->toISOString()]);
            }

            public function handleButtonClicked($data)
            {
                $this->eventReceived = true;
                $this->eventData = $data;
            }

            public function render()
            {
                return view('test-button-events', [
                    'eventReceived' => $this->eventReceived,
                    'eventData' => $this->eventData
                ]);
            }
        };

        // Create test view
        if (!view()->exists('test-button-events')) {
            view()->addLocation(__DIR__ . '/../Support');
            file_put_contents(__DIR__ . '/../Support/test-button-events.blade.php', 
                '<x-button label="Emit Event" wire:click="emitButtonClick" />
                 @if($eventReceived)
                    <div>Event received with data: {{ json_encode($eventData) }}</div>
                 @endif');
        }

        $testable = \Livewire\Livewire::test($component::class);
        
        // Initial state
        $testable->assertSet('eventReceived', false);
        $testable->assertDontSee('Event received');
        
        // Emit event
        $testable->call('emitButtonClick');
        
        // Verify event was handled
        $testable->assertSet('eventReceived', true);
        $testable->assertSee('Event received with data:');
    }

    public function test_button_accessibility_in_livewire(): void
    {
        $component = new class extends LivewireComponent {
            public $ariaLabel = 'Accessible Button';
            public $ariaPressed = false;

            public function togglePressed()
            {
                $this->ariaPressed = !$this->ariaPressed;
            }

            public function render()
            {
                return view('test-button-accessibility', [
                    'ariaLabel' => $this->ariaLabel,
                    'ariaPressed' => $this->ariaPressed
                ]);
            }
        };

        // Create test view
        if (!view()->exists('test-button-accessibility')) {
            view()->addLocation(__DIR__ . '/../Support');
            file_put_contents(__DIR__ . '/../Support/test-button-accessibility.blade.php', 
                '<x-button 
                    :aria-label="$ariaLabel" 
                    :aria-pressed="$ariaPressed ? \'true\' : \'false\'"
                    wire:click="togglePressed"
                    label="Toggle Button" />');
        }

        $testable = \Livewire\Livewire::test($component::class);
        $html = $testable->get()->html();
        
        // Verify accessibility attributes
        $accessibility = TestHelpers::hasAccessibilityAttributes($html);
        $this->assertTrue($accessibility['aria_labels'] || 
                         str_contains($html, 'aria-label') ||
                         str_contains($html, 'aria-pressed'));
        
        // Test state changes
        $testable->assertSet('ariaPressed', false);
        $testable->call('togglePressed');
        $testable->assertSet('ariaPressed', true);
    }

    public function test_button_performance_with_large_datasets(): void
    {
        $component = new class extends LivewireComponent {
            public $items = [];
            public $processingTime = 0;

            public function mount()
            {
                $this->items = ComponentDataFactory::performanceTestData()['large_dataset'];
            }

            public function processItems()
            {
                $start = microtime(true);
                
                // Simulate processing
                foreach ($this->items as $item) {
                    // Simple processing
                    $processed = strtoupper($item);
                }
                
                $this->processingTime = (microtime(true) - $start) * 1000;
            }

            public function render()
            {
                return view('test-button-performance', [
                    'itemCount' => count($this->items),
                    'processingTime' => $this->processingTime
                ]);
            }
        };

        // Create test view
        if (!view()->exists('test-button-performance')) {
            view()->addLocation(__DIR__ . '/../Support');
            file_put_contents(__DIR__ . '/../Support/test-button-performance.blade.php', 
                '<div>Items: {{ $itemCount }}</div>
                 <x-button label="Process Items" wire:click="processItems" />
                 @if($processingTime > 0)
                    <div>Processing took: {{ $processingTime }}ms</div>
                 @endif');
        }

        $testable = \Livewire\Livewire::test($component::class);
        
        // Verify large dataset is loaded
        $testable->assertSee('Items: 1000');
        
        // Process items and verify performance
        $testable->call('processItems');
        $testable->assertStatus(200);
        
        // Processing should complete within reasonable time
        $processingTime = $testable->get('processingTime');
        $this->assertLessThan(1000, $processingTime, 'Processing should complete within 1 second');
    }

    public function test_button_xss_protection_in_livewire(): void
    {
        $component = new class extends LivewireComponent {
            public $userInput = '';
            public $buttonLabel = 'Safe Button';

            public function updateLabel()
            {
                $this->buttonLabel = $this->userInput;
            }

            public function render()
            {
                return view('test-button-xss', [
                    'userInput' => $this->userInput,
                    'buttonLabel' => $this->buttonLabel
                ]);
            }
        };

        // Create test view
        if (!view()->exists('test-button-xss')) {
            view()->addLocation(__DIR__ . '/../Support');
            file_put_contents(__DIR__ . '/../Support/test-button-xss.blade.php', 
                '<input wire:model="userInput" placeholder="Enter button label" />
                 <x-button wire:click="updateLabel" label="Update Label" />
                 <x-button :label="$buttonLabel" />');
        }

        $testable = \Livewire\Livewire::test($component::class);
        
        $xssPayloads = TestHelpers::securityTestPayloads();
        
        foreach ($xssPayloads as $payload) {
            $testable->set('userInput', $payload);
            $testable->call('updateLabel');
            
            $html = $testable->get()->html();
            
            // Verify XSS protection
            $this->assertStringNotContainsString('<script', $html);
            $this->assertStringNotContainsString('javascript:', $html);
            $this->assertStringNotContainsString('onerror=', $html);
        }
    }

    protected function tearDown(): void
    {
        // Clean up test views
        $testViews = [
            'test-button-click.blade.php',
            'test-button-loading.blade.php', 
            'test-button-form.blade.php',
            'test-button-dynamic.blade.php',
            'test-button-events.blade.php',
            'test-button-accessibility.blade.php',
            'test-button-performance.blade.php',
            'test-button-xss.blade.php'
        ];

        foreach ($testViews as $view) {
            $path = __DIR__ . '/../Support/' . $view;
            if (file_exists($path)) {
                unlink($path);
            }
        }

        parent::tearDown();
    }
}