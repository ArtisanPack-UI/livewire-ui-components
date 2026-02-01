---
title: StreamableContent Component
---

# StreamableContent Component

The StreamableContent component provides a container for displaying streaming content, such as AI-generated text responses. It leverages Livewire 4's `wire:stream` directive to display content in real-time as it's generated.

**Note:** This component was added in version 2.0.0. Streaming functionality requires Livewire 4 or higher. On Livewire 3, content is displayed statically.

## Basic Usage

```php
<?php

use Livewire\Volt\Component;

new class extends Component {
    public string $aiResponse = '';

    public function generateResponse(): void
    {
        // Stream content to the component
        $this->stream('ai-response', 'Hello, ');
        $this->stream('ai-response', 'how can I help you today?');
    }
}; ?>

<x-artisanpack-streamable-content target="ai-response" />

<x-artisanpack-button wire:click="generateResponse">
    Generate Response
</x-artisanpack-button>
```

## Examples

### Streaming AI Responses

```php
<?php

use Livewire\Volt\Component;
use OpenAI\Laravel\Facades\OpenAI;

new class extends Component {
    public string $prompt = '';
    public bool $isStreaming = false;

    public function chat(): void
    {
        $this->isStreaming = true;

        $stream = OpenAI::chat()->createStreamed([
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'user', 'content' => $this->prompt],
            ],
        ]);

        foreach ($stream as $response) {
            $this->stream(
                'chat-response',
                $response->choices[0]->delta->content ?? ''
            );
        }

        $this->isStreaming = false;
    }
}; ?>

<div class="space-y-4">
    <x-artisanpack-input
        wire:model="prompt"
        label="Your message"
        placeholder="Ask me anything..." />

    <x-artisanpack-button wire:click="chat" wire:loading.attr="disabled">
        <span wire:loading.remove>Send</span>
        <span wire:loading>Generating...</span>
    </x-artisanpack-button>

    <x-artisanpack-streamable-content
        target="chat-response"
        prose
        placeholder="AI response will appear here..."
        show-cursor />
</div>
```

### With Prose Styling

Enable typography styling for rich text content:

```php
<x-artisanpack-streamable-content
    target="markdown-response"
    prose />
```

This applies Tailwind's prose classes for proper formatting of headings, lists, code blocks, and other markdown-generated content.

### With Blinking Cursor

Show a blinking cursor animation during streaming:

```php
<x-artisanpack-streamable-content
    target="ai-response"
    show-cursor />
```

The cursor automatically hides when streaming is complete by adding the `data-streaming-complete` attribute to the element.

### With Placeholder Text

Display placeholder text before content starts streaming:

```php
<x-artisanpack-streamable-content
    target="response"
    placeholder="Waiting for response..." />
```

### Custom HTML Tag

Use a different HTML element instead of `div`:

```php
{{-- Use a paragraph element --}}
<x-artisanpack-streamable-content
    target="response"
    tag="p"
    class="text-lg" />

{{-- Use a section element --}}
<x-artisanpack-streamable-content
    target="response"
    tag="section"
    class="bg-base-200 p-4 rounded-lg" />

{{-- Use a span for inline content --}}
<x-artisanpack-streamable-content
    target="inline-response"
    tag="span" />
```

### Chat Interface Example

```php
<?php

use Livewire\Volt\Component;

new class extends Component {
    public array $messages = [];
    public string $input = '';
    public bool $isStreaming = false;

    public function sendMessage(): void
    {
        if (empty($this->input)) return;

        // Add user message
        $this->messages[] = [
            'role' => 'user',
            'content' => $this->input,
        ];

        $userInput = $this->input;
        $this->input = '';
        $this->isStreaming = true;

        // Simulate AI response streaming
        $response = "I received your message: \"{$userInput}\". ";
        $response .= "This is a simulated streaming response. ";
        $response .= "In a real application, you would integrate with an AI API.";

        foreach (str_split($response) as $char) {
            $this->stream('ai-response', $char);
            usleep(20000); // 20ms delay for effect
        }

        // Add AI response to messages
        $this->messages[] = [
            'role' => 'assistant',
            'content' => $response,
        ];

        $this->isStreaming = false;
    }
}; ?>

<div class="flex flex-col h-96">
    {{-- Message History --}}
    <div class="flex-1 overflow-y-auto space-y-4 p-4">
        @foreach($messages as $message)
            <div class="{{ $message['role'] === 'user' ? 'text-right' : 'text-left' }}">
                <div class="inline-block p-3 rounded-lg {{ $message['role'] === 'user' ? 'bg-primary text-primary-content' : 'bg-base-200' }}">
                    {{ $message['content'] }}
                </div>
            </div>
        @endforeach

        {{-- Streaming Response --}}
        @if($isStreaming)
            <div class="text-left">
                <div class="inline-block p-3 rounded-lg bg-base-200">
                    <x-artisanpack-streamable-content
                        target="ai-response"
                        show-cursor />
                </div>
            </div>
        @endif
    </div>

    {{-- Input Area --}}
    <div class="border-t p-4">
        <form wire:submit="sendMessage" class="flex gap-2">
            <x-artisanpack-input
                wire:model="input"
                placeholder="Type your message..."
                class="flex-1" />
            <x-artisanpack-button type="submit" :disabled="$isStreaming">
                Send
            </x-artisanpack-button>
        </form>
    </div>
</div>
```

### With Initial Content

Provide initial content using the default slot:

```php
<x-artisanpack-streamable-content target="response">
    <p>This is the initial content that will be replaced when streaming begins.</p>
</x-artisanpack-streamable-content>
```

### Marking Streaming Complete

To hide the cursor when streaming is complete, add the `data-streaming-complete` attribute via JavaScript:

```php
<x-artisanpack-streamable-content
    id="my-stream"
    target="response"
    show-cursor />

<script>
    // When streaming is complete
    document.getElementById('my-stream').setAttribute('data-streaming-complete', 'true');
</script>
```

Or dispatch a Livewire event to handle it:

```php
// In your Livewire component
$this->dispatch('streaming-complete');

// In your Blade template
<div
    x-data
    @streaming-complete.window="$el.querySelector('[data-streamable-content]').setAttribute('data-streaming-complete', 'true')">
    <x-artisanpack-streamable-content target="response" show-cursor />
</div>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `target` | string | required | The wire:stream target identifier |
| `id` | string\|null | `null` | Optional custom ID for the element |
| `tag` | string | `'div'` | The HTML tag to use for the container |
| `prose` | bool | `false` | Whether to apply prose styling for rich text |
| `placeholder` | string\|null | `null` | Placeholder text shown before content |
| `show-cursor` | bool | `false` | Whether to show a blinking cursor during streaming |

## Livewire Streaming

To stream content from your Livewire component, use the `stream()` method:

```php
// Stream text to the target
$this->stream('target-name', 'Some text content');

// Stream multiple times to append content
$this->stream('response', 'Hello ');
$this->stream('response', 'World!');
// Result: "Hello World!"
```

**Important:** The `stream()` method is only available in Livewire 4+. In Livewire 3, you should use traditional property updates or polling instead.

## Livewire 3 Fallback

When using Livewire 3, the component renders without the `wire:stream` directive. You can still use it as a container for content that updates via traditional Livewire property binding:

```php
<?php
// Livewire 3 approach
use Livewire\Volt\Component;

new class extends Component {
    public string $response = '';

    public function generateResponse(): void
    {
        // Build response (no streaming in LW3)
        $this->response = 'This is the complete response.';
    }
}; ?>

{{-- In Livewire 3, just use a regular element --}}
<div>{{ $response }}</div>

{{-- Or use the component without streaming --}}
<x-artisanpack-streamable-content target="unused">
    {{ $response }}
</x-artisanpack-streamable-content>
```

## CSS Customization

The blinking cursor animation can be customized by overriding the CSS:

```css
/* Custom cursor character */
.streaming-cursor::after {
    content: '|'; /* Change from block to pipe */
}

/* Custom animation speed */
@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0; }
}
```

## Notes

- Streaming functionality (`wire:stream`) requires Livewire 4 or higher
- The component gracefully degrades in Livewire 3 by rendering content statically
- The `prose` prop applies Tailwind Typography plugin classes for rich text
- When using `show-cursor`, add `data-streaming-complete` attribute to hide the cursor when done
- Content is appended to the element, not replaced, matching `wire:stream` behavior

## Related Components

- [Loading](Loading) - Loading indicators
- [Skeleton](Skeleton) - Placeholder loading states
- [Markdown](Markdown) - Markdown rendering component
