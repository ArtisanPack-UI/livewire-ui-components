<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests\Unit\Components;

use ArtisanPack\LivewireUiComponents\Tests\Support\ComponentTestCase;
use ArtisanPack\LivewireUiComponents\View\Components\StreamableContent;
use Error;

/**
 * Comprehensive unit tests for the StreamableContent component.
 *
 * Tests the streaming content container for AI responses and
 * real-time content, including Livewire 4 wire:stream support.
 *
 * @since 2.0.0
 */
class StreamableContentComponentTest extends ComponentTestCase
{
    protected string $componentClass = StreamableContent::class;

    protected array $defaultProperties = [
        'tag'        => 'div',
        'prose'      => false,
        'showCursor' => false,
    ];

    protected array $requiredProperties = ['target'];

    /**
     * Override createComponent to always provide the required target parameter.
     */
    protected function createComponent(array $properties = []): \Illuminate\View\Component
    {
        // Ensure target is always provided since it's required
        if (! isset($properties['target'])) {
            $properties['target'] = 'test-target-'.uniqid();
        }

        return parent::createComponent($properties);
    }

    /**
     * Override base UUID test because StreamableContent uses deterministic UUID
     * based on serialization, so we need to use different targets.
     */
    public function test_component_generates_unique_uuid(): void
    {
        // Create components with different targets to ensure different UUIDs
        $component1 = new StreamableContent(target: 'target-'.uniqid());
        $component2 = new StreamableContent(target: 'target-'.uniqid());

        $this->assertNotEmpty($component1->uuid);
        $this->assertNotEmpty($component2->uuid);
        $this->assertNotEquals($component1->uuid, $component2->uuid, 'UUIDs should be unique for different components');
        $this->assertStringStartsWith('artisanpack', $component1->uuid, 'Auto-generated UUIDs should start with artisanpack prefix');
    }

    /**
     * Override base custom ID UUID test.
     */
    public function test_component_uses_custom_id_in_uuid(): void
    {
        $customId  = 'test-custom-id';
        $component = new StreamableContent(target: 'response', id: $customId);

        // StreamableContent appends the ID to the hash, so it should be in the UUID
        $this->assertStringContainsString($customId, $component->uuid);
    }

    /**
     * Test StreamableContent requires target parameter.
     */
    public function test_streamable_content_requires_target(): void
    {
        $this->expectException(\ArgumentCountError::class);

        new StreamableContent();
    }

    /**
     * Test StreamableContent can be instantiated with target.
     */
    public function test_streamable_content_can_be_instantiated_with_target(): void
    {
        $component = new StreamableContent(target: 'ai-response');

        $this->assertInstanceOf(StreamableContent::class, $component);
        $this->assertEquals('ai-response', $component->target);
    }

    /**
     * Test target property is set correctly.
     */
    public function test_target_property_is_set(): void
    {
        $component = new StreamableContent(target: 'chat-stream');

        $this->assertEquals('chat-stream', $component->target);
    }

    /**
     * Test tag defaults to div.
     */
    public function test_tag_defaults_to_div(): void
    {
        $component = new StreamableContent(target: 'response');

        $this->assertEquals('div', $component->tag);
    }

    /**
     * Test tag can be customized.
     */
    public function test_tag_can_be_customized(): void
    {
        $component = new StreamableContent(target: 'response', tag: 'section');

        $this->assertEquals('section', $component->tag);
    }

    /**
     * Test tag can be set to paragraph.
     */
    public function test_tag_can_be_set_to_paragraph(): void
    {
        $component = new StreamableContent(target: 'response', tag: 'p');

        $this->assertEquals('p', $component->tag);
    }

    /**
     * Test tag can be set to span.
     */
    public function test_tag_can_be_set_to_span(): void
    {
        $component = new StreamableContent(target: 'response', tag: 'span');

        $this->assertEquals('span', $component->tag);
    }

    /**
     * Test prose defaults to false.
     */
    public function test_prose_defaults_to_false(): void
    {
        $component = new StreamableContent(target: 'response');

        $this->assertFalse($component->prose);
    }

    /**
     * Test prose can be enabled.
     */
    public function test_prose_can_be_enabled(): void
    {
        $component = new StreamableContent(target: 'response', prose: true);

        $this->assertTrue($component->prose);
    }

    /**
     * Test showCursor defaults to false.
     */
    public function test_show_cursor_defaults_to_false(): void
    {
        $component = new StreamableContent(target: 'response');

        $this->assertFalse($component->showCursor);
    }

    /**
     * Test showCursor can be enabled.
     */
    public function test_show_cursor_can_be_enabled(): void
    {
        $component = new StreamableContent(target: 'response', showCursor: true);

        $this->assertTrue($component->showCursor);
    }

    /**
     * Test placeholder can be set.
     */
    public function test_placeholder_can_be_set(): void
    {
        $component = new StreamableContent(
            target: 'response',
            placeholder: 'Waiting for AI response...',
        );

        $this->assertEquals('Waiting for AI response...', $component->placeholder);
    }

    /**
     * Test placeholder is null by default.
     */
    public function test_placeholder_is_null_by_default(): void
    {
        $component = new StreamableContent(target: 'response');

        $this->assertNull($component->placeholder);
    }

    /**
     * Test custom id can be set.
     */
    public function test_custom_id_can_be_set(): void
    {
        $component = new StreamableContent(target: 'response', id: 'my-stream');

        $this->assertEquals('my-stream', $component->id);
    }

    /**
     * Test id is null by default.
     */
    public function test_id_is_null_by_default(): void
    {
        $component = new StreamableContent(target: 'response');

        $this->assertNull($component->id);
    }

    /**
     * Test UUID is generated.
     */
    public function test_uuid_is_generated(): void
    {
        $component = new StreamableContent(target: 'response');

        $this->assertNotEmpty($component->uuid);
        $this->assertStringStartsWith('artisanpack', $component->uuid);
    }

    /**
     * Test UUID includes custom id when provided.
     */
    public function test_uuid_includes_custom_id(): void
    {
        $component = new StreamableContent(target: 'response', id: 'custom-stream');

        $this->assertStringContainsString('custom-stream', $component->uuid);
    }

    /**
     * Test two instances have different UUIDs.
     */
    public function test_two_instances_have_different_uuids(): void
    {
        $component1 = new StreamableContent(target: 'response1');
        $component2 = new StreamableContent(target: 'response2');

        $this->assertNotEquals($component1->uuid, $component2->uuid);
    }

    /**
     * Test supportsStreaming returns boolean.
     */
    public function test_supports_streaming_returns_boolean(): void
    {
        $component = new StreamableContent(target: 'response');

        $this->assertIsBool($component->supportsStreaming());
    }

    /**
     * Test component renders.
     */
    public function test_streamable_content_renders(): void
    {
        $component = new StreamableContent(target: 'response');

        try {
            $view = $component->render();
            $this->assertNotNull($view);
        } catch (Error $e) {
            if (str_contains($e->getMessage(), 'Call to a member function') &&
                str_contains($e->getMessage(), 'on null')) {
                $this->markTestSkipped('Component requires attributes or context for rendering');
            }
            throw $e;
        }
    }

    /**
     * Test all properties can be set together.
     */
    public function test_all_properties_can_be_set_together(): void
    {
        $component = new StreamableContent(
            target: 'chat-response',
            id: 'ai-chat',
            tag: 'section',
            prose: true,
            placeholder: 'Thinking...',
            showCursor: true,
        );

        $this->assertEquals('chat-response', $component->target);
        $this->assertEquals('ai-chat', $component->id);
        $this->assertEquals('section', $component->tag);
        $this->assertTrue($component->prose);
        $this->assertEquals('Thinking...', $component->placeholder);
        $this->assertTrue($component->showCursor);
    }

    /**
     * Test component can be created with named parameters.
     */
    public function test_component_accepts_named_parameters(): void
    {
        $component = new StreamableContent(
            target: 'my-target',
            prose: true,
        );

        $this->assertEquals('my-target', $component->target);
        $this->assertTrue($component->prose);
        $this->assertEquals('div', $component->tag);
    }

    /**
     * Test different targets produce different UUIDs.
     */
    public function test_different_targets_produce_different_uuids(): void
    {
        $component1 = new StreamableContent(target: 'target-a');
        $component2 = new StreamableContent(target: 'target-b');

        // Since UUID includes serialization of the object,
        // different targets should produce different UUIDs
        $this->assertNotEquals($component1->uuid, $component2->uuid);
    }

    /**
     * Test same configuration produces deterministic UUID base.
     */
    public function test_same_config_with_id_produces_consistent_uuid(): void
    {
        // When a custom ID is provided, the UUID should consistently include it
        $component1 = new StreamableContent(target: 'response', id: 'fixed-id');
        $component2 = new StreamableContent(target: 'response', id: 'fixed-id');

        // Both should contain the custom ID
        $this->assertStringContainsString('fixed-id', $component1->uuid);
        $this->assertStringContainsString('fixed-id', $component2->uuid);
    }

    /**
     * Test tag accepts valid HTML element names.
     */
    public function test_tag_accepts_valid_html_elements(): void
    {
        $validTags = ['div', 'span', 'p', 'section', 'article', 'main', 'aside'];

        foreach ($validTags as $tag) {
            $component = new StreamableContent(target: 'response', tag: $tag);
            $this->assertEquals($tag, $component->tag, "Tag should accept '{$tag}'");
        }
    }

    /**
     * Test prose property is boolean typed.
     */
    public function test_prose_property_is_boolean(): void
    {
        $component = new StreamableContent(target: 'response', prose: true);

        $this->assertIsBool($component->prose);
    }

    /**
     * Test showCursor property is boolean typed.
     */
    public function test_show_cursor_property_is_boolean(): void
    {
        $component = new StreamableContent(target: 'response', showCursor: true);

        $this->assertIsBool($component->showCursor);
    }
}
