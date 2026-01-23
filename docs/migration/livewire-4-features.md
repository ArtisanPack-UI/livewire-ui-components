---
title: Livewire 4 Features (v2.0)
---

# Livewire 4 Features

Version 2.0 of ArtisanPack UI Livewire Components introduces support for several Livewire 4-specific features. These features are designed to work alongside existing Livewire 3 functionality, providing enhanced capabilities for users on Livewire 4 while maintaining full backward compatibility.

## Overview

The following Livewire 4 features are now supported:

| Feature | Description | Components |
|---------|-------------|------------|
| `#[Computed]` | Attribute-based computed properties | Calendar |
| `#[Lazy]` | Deferred component loading with placeholders | Calendar |
| `#[Renderless]` | Skip re-render for non-UI operations | Custom components |
| Islands | Isolated component regions for partial updates | Calendar |
| `wire:stream` | Real-time content streaming for AI integration | StreamableContent |
| `wire:intersect` | Infinite scroll and lazy loading | Table, Choices |
| `data-loading` | CSS-based loading states | Button, MenuItem, Header, Card, Separator, Tags, Choices |
| `wire:sort` | Native drag-and-drop sorting | Table |

## Backward Compatibility

All Livewire 4 features are implemented with full backward compatibility:

- **Livewire 3 users**: Features are gracefully ignored or work through existing `wire:loading` directives
- **Livewire 4 users**: Get enhanced functionality through new directives and CSS variants
- **No breaking changes**: Existing code continues to work without modification

## #[Computed] Attribute

The `#[Computed]` attribute provides a cleaner syntax for defining computed properties in Livewire components. This attribute is available in both Livewire 3 and Livewire 4, making it fully backward compatible.

### Migration from Legacy Syntax

**Before (Legacy getter syntax):**
```php
public function getWeekdaysProperty(): Collection
{
    return collect(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']);
}

public function getWeeksProperty(): Collection
{
    // Complex calculation...
    return $weeks;
}
```

**After (Attribute syntax):**
```php
use Livewire\Attributes\Computed;

#[Computed]
public function weekdays(): Collection
{
    return collect(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']);
}

#[Computed(persist: true, seconds: 60)]
public function weeks(): Collection
{
    // Complex calculation...
    return $weeks;
}
```

### Caching with #[Computed]

The `#[Computed]` attribute supports optional caching parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| `persist` | bool | Whether to cache the computed value across requests |
| `seconds` | int | How long to cache the value in seconds |

```php
// No caching - recalculated on each access
#[Computed]
public function simpleData(): array { }

// Cached for 60 seconds across requests
#[Computed(persist: true, seconds: 60)]
public function expensiveData(): array { }
```

### Components Using #[Computed]

#### Calendar Component

The Calendar component uses `#[Computed]` for its weekdays and weeks calculations:

```php
use Livewire\Attributes\Computed;

#[Computed]
public function weekdays(): Collection
{
    // Returns weekday names based on locale and start day
}

#[Computed]
public function weeks(): Collection
{
    // Returns the calendar grid with events
    // Recalculated per-request since it depends on dynamic state
    // (gridStartsAt, events) that changes during navigation
}
```

### Accessing Computed Properties

Computed properties are accessed the same way regardless of which syntax you use:

```blade
{{-- In Blade templates --}}
@foreach ($this->weekdays as $weekday)
    {{ $weekday->format('l') }}
@endforeach

@foreach ($this->weeks as $week)
    {{-- Render week --}}
@endforeach
```

### Feature Detection

You can check if the `#[Computed]` attribute is available:

```php
use ArtisanPack\LivewireUiComponents\Support\LivewireHelper;

if (LivewireHelper::supportsComputedAttribute()) {
    // #[Computed] attribute is available
}
```

## #[Lazy] Attribute - Deferred Loading

The `#[Lazy]` attribute enables deferred loading for heavy Livewire components. When applied, the component displays a placeholder until it becomes visible in the viewport, then hydrates and renders the full component. This is available in both Livewire 3 and Livewire 4.

### How It Works

1. The component is marked with `#[Lazy]` attribute
2. Initially, only a lightweight placeholder is rendered
3. When the placeholder enters the viewport (via Intersection Observer), the full component loads
4. The `placeholder()` method defines what users see while loading

### Components Using #[Lazy]

#### Calendar Component

The Calendar component uses `#[Lazy]` for deferred loading since it performs complex calculations:

```php
use Livewire\Attributes\Lazy;

#[Lazy]
class Calendar extends Component
{
    public function placeholder(array $params = []): View
    {
        return view('livewire-ui-components::placeholders.calendar', $params);
    }
}
```

### Placeholder Views

Components with `#[Lazy]` provide skeleton UI placeholders that match their structure:

```blade
{{-- resources/views/placeholders/calendar.blade.php --}}
<div class="w-full animate-pulse">
    <div class="bg-white dark:bg-base-200 border rounded-lg p-4">
        {{-- Header skeleton --}}
        <div class="mb-4 flex items-center justify-between">
            <div class="w-32 h-8 bg-gray-200 rounded"></div>
            <div class="w-40 h-6 bg-gray-200 rounded"></div>
        </div>
        {{-- Grid skeleton --}}
        <div class="grid grid-cols-7 gap-2">
            @for ($i = 0; $i < 35; $i++)
                <div class="h-20 bg-gray-100 rounded"></div>
            @endfor
        </div>
    </div>
</div>
```

### Usage

The `#[Lazy]` attribute is applied at the class level:

```blade
{{-- The Calendar component loads lazily by default --}}
<livewire:calendar :events="$events" />

{{-- You can disable lazy loading if needed --}}
<livewire:calendar :events="$events" lazy="false" />
```

### Benefits

- **Improved Initial Page Load**: Heavy components don't block initial render
- **Reduced Server Load**: Components below the fold don't hydrate until needed
- **Better UX**: Users see a skeleton placeholder instead of a blank space
- **Automatic**: Works transparently with existing component usage

### Feature Detection

You can check if the `#[Lazy]` attribute is available:

```php
use ArtisanPack\LivewireUiComponents\Support\LivewireHelper;

if (LivewireHelper::supportsLazyAttribute()) {
    // #[Lazy] attribute is available
}
```

## #[Renderless] Attribute - Non-UI Operations

The `#[Renderless]` attribute allows Livewire methods to execute without triggering a component re-render. This is ideal for operations that don't change the UI, such as analytics tracking, view count increments, and logging.

### How It Works

When a method is marked with `#[Renderless]`:

1. The method executes on the server
2. No re-render is triggered
3. The component state remains unchanged on the client
4. Network payload is minimized (no HTML returned)

### Use Cases

The `#[Renderless]` attribute is perfect for:

- **Analytics Tracking**: Log user interactions without UI updates
- **View Count Increments**: Track page/content views silently
- **Logging Operations**: Record actions for debugging or auditing
- **Background Notifications**: Send notifications without affecting the UI
- **Session Updates**: Update session data silently

### Implementation Example

```php
use Livewire\Attributes\Renderless;
use Livewire\Component;

class ArticleView extends Component
{
    public $article;

    /**
     * Track article view without re-rendering the component.
     */
    #[Renderless]
    public function trackView(): void
    {
        $this->article->increment('view_count');

        // Log the view for analytics
        Activity::log('article.viewed', [
            'article_id' => $this->article->id,
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Track social share without re-rendering.
     */
    #[Renderless]
    public function trackShare(string $platform): void
    {
        Analytics::track('social_share', [
            'platform' => $platform,
            'article_id' => $this->article->id,
        ]);
    }

    public function render()
    {
        return view('livewire.article-view');
    }
}
```

### Usage in Blade

```blade
<div>
    {{-- Track view on component load --}}
    <div wire:init="trackView">
        <h1>{{ $article->title }}</h1>
        <p>{{ $article->content }}</p>
    </div>

    {{-- Track shares without UI update --}}
    <div class="share-buttons">
        <button wire:click="trackShare('twitter')">Share on Twitter</button>
        <button wire:click="trackShare('facebook')">Share on Facebook</button>
    </div>
</div>
```

### Combining with Other Attributes

You can combine `#[Renderless]` with other attributes:

```php
use Livewire\Attributes\Renderless;
use Livewire\Attributes\On;

class Dashboard extends Component
{
    /**
     * Log when a notification is received (event listener, no re-render).
     */
    #[On('notification-received')]
    #[Renderless]
    public function logNotification(array $data): void
    {
        Log::info('Notification received', $data);
    }
}
```

### Benefits

- **Performance**: No unnecessary DOM updates
- **Reduced Bandwidth**: Server returns minimal response
- **Clean Separation**: UI logic stays separate from tracking/logging
- **User Experience**: No visual flicker for background operations

### Feature Detection

You can check if the `#[Renderless]` attribute is available:

```php
use ArtisanPack\LivewireUiComponents\Support\LivewireHelper;

if (LivewireHelper::supportsRenderlessAttribute()) {
    // #[Renderless] attribute is available
}
```

### Livewire 3 Fallback

In Livewire 3, if the `#[Renderless]` attribute is not available, the method will still execute but will trigger a re-render. To optimize for Livewire 3:

```php
public function trackView(): void
{
    $this->article->increment('view_count');

    // In Livewire 3, use skipRender() as fallback
    if (!LivewireHelper::supportsRenderlessAttribute()) {
        $this->skipRender();
    }
}
```

## Islands Architecture - Isolated Updates

Islands architecture allows different parts of a complex component to update independently without affecting other regions. This is particularly useful for components like calendars, dashboards, and data-heavy interfaces where you want header updates to not re-render the entire grid.

### How It Works

1. Components are divided into named "islands" using `@island` and `@endisland` directives
2. Each island gets a unique `wire:key` for Livewire's morphing algorithm
3. The directive outputs: `<div wire:key="island-{name}" data-island="{name}">`
4. This is compatible with both Livewire 3 and Livewire 4

### Compatibility

| Version | Behavior |
|---------|----------|
| Livewire 4+ | Optimized partial updates per-island for better performance |
| Livewire 3 | Standard rendering with `wire:key` isolation (fully functional) |

The `@island` directive uses standard Livewire features (`wire:key`) that work in all versions. The performance optimization is automatic in Livewire 4+ due to its improved morphing algorithm.

### Blade Directives

The package provides custom Blade directives for Islands:

| Directive | Description |
|-----------|-------------|
| `@island('name')` | Creates an isolated region with `wire:key` |
| `@island('name', ['defer' => true])` | Creates a deferred region (Livewire 4+) |
| `@endisland` | Closes the island region |
| `@placeholder` | Shows placeholder content while deferred island loads |
| `@endplaceholder` | Closes the placeholder region |

### Components Using Islands

#### Calendar Component

The Calendar component uses Islands to separate header and grid updates:

```blade
{{-- Header Island - navigation buttons and view selector --}}
@island('calendar-header')
<div class="flex items-center justify-between">
    <button wire:click="goToPreviousPeriod">Previous</button>
    <h2>{{ $headerText }}</h2>
    <button wire:click="goToNextPeriod">Next</button>
</div>
@endisland

{{-- Grid Island - the calendar grid with events --}}
@island('calendar-grid')
<div class="grid grid-cols-7">
    {{-- Calendar grid content --}}
</div>
@endisland
```

With this architecture:
- Clicking "Previous" or "Next" updates the header island independently
- The grid updates only when necessary (new month, events change)
- In Livewire 4+, the DOM morphing is optimized per-island

### Deferred Islands

For heavy content regions, you can defer loading until the island is visible:

```blade
@island('heavy-content', ['defer' => true])
    @placeholder
        <div class="animate-pulse h-64 bg-gray-200 rounded"></div>
    @endplaceholder

    {{-- Heavy content that loads when visible --}}
    <div class="chart-container">
        @foreach($data as $item)
            {{-- Complex rendering --}}
        @endforeach
    </div>
@endisland
```

In Livewire 4+:
- The placeholder shows initially
- When the island enters the viewport, `wire:intersect.once` triggers a refresh
- The full content loads and replaces the placeholder

In Livewire 3:
- The content renders immediately (no deferral)
- The placeholder is skipped
- Everything still works, just without the optimization

### Custom Implementation

You can use Islands in your own Livewire components:

```blade
<div>
    {{-- Sidebar island - filters and options --}}
    @island('sidebar')
    <aside class="sidebar">
        <x-artisanpack-select wire:model.live="filter" :options="$filters" />
        <x-artisanpack-checkbox wire:model.live="showArchived" label="Show Archived" />
    </aside>
    @endisland

    {{-- Main content island - data display --}}
    @island('main-content')
    <main class="content">
        @foreach($items as $item)
            <x-artisanpack-card>{{ $item->name }}</x-artisanpack-card>
        @endforeach
    </main>
    @endisland

    {{-- Pagination island --}}
    @island('pagination')
    <x-artisanpack-pagination :rows="$items" />
    @endisland
</div>
```

### Benefits

- **Performance**: Reduced DOM updates when only part of a component changes
- **User Experience**: Faster perceived updates, no full component flicker
- **Optimized Morphing**: Livewire 4's morphing algorithm works per-island
- **Backward Compatible**: Falls back gracefully in Livewire 3

### Feature Detection

You can check if Islands are fully supported:

```php
use ArtisanPack\LivewireUiComponents\Support\LivewireHelper;

if (LivewireHelper::supportsIslands()) {
    // Livewire 4+ with full Islands support
}
```

### Best Practices

1. **Use meaningful island names**: Names should describe the region's purpose (`calendar-header`, `user-list`, `sidebar-filters`)
2. **Don't over-partition**: Too many small islands can negate the benefits; group related elements
3. **Defer wisely**: Only defer heavy content that's below the fold or computationally expensive
4. **Test both versions**: Ensure your component works correctly in both Livewire 3 and 4

## wire:stream - Real-Time Content Streaming

Livewire 4's `wire:stream` directive enables real-time content streaming, particularly useful for AI-generated content that appears character by character or chunk by chunk. This is a **Livewire 4 only** feature.

### How It Works

1. An element is marked with `wire:stream="targetName"`
2. Server-side code calls `$this->stream('targetName', $content)` to send content
3. Content appears in real-time as it's streamed from the server
4. The stream can append or replace content

### StreamableContent Component

The package provides a `StreamableContent` component designed for streaming:

```blade
<x-artisanpack-streamable-content
    target="aiResponse"
    placeholder="Waiting for AI response..."
    :show-cursor="true"
    :prose="true"
/>
```

#### Component Properties

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `target` | string | (required) | The wire:stream target identifier |
| `id` | string | null | Custom element ID |
| `tag` | string | 'div' | HTML tag to use |
| `prose` | bool | false | Apply prose styling for rich text |
| `placeholder` | string | null | Placeholder text before content arrives |
| `show-cursor` | bool | false | Show blinking cursor during streaming |

### Use Case: AI Writing Assistant

A common use case is integrating AI content generation with the Editor component:

```php
use Livewire\Component;

class ArticleEditor extends Component
{
    public string $content = '';
    public string $aiSuggestion = '';

    public function generateAIContent(string $prompt): void
    {
        // Stream AI response chunk by chunk
        foreach ($this->aiService->streamResponse($prompt) as $chunk) {
            $this->stream(
                to: 'aiSuggestion',
                content: $chunk,
                replace: false, // Append to existing content
            );
        }
    }

    public function insertSuggestion(): void
    {
        $this->content .= $this->aiSuggestion;
        $this->aiSuggestion = '';
    }
}
```

```blade
<div>
    {{-- Main Editor --}}
    <x-artisanpack-editor wire:model="content" label="Article Content" />

    {{-- AI Suggestion Panel --}}
    <div class="mt-4 p-4 border rounded-lg bg-base-200">
        <h3 class="font-semibold mb-2">AI Suggestion</h3>

        <x-artisanpack-streamable-content
            target="aiSuggestion"
            placeholder="Click 'Generate' to get AI suggestions..."
            :show-cursor="true"
            :prose="true"
            class="min-h-[100px] p-3 bg-base-100 rounded"
        />

        <div class="mt-3 flex gap-2">
            <x-artisanpack-button
                wire:click="generateAIContent('Continue this article...')"
                color="primary"
            >
                Generate
            </x-artisanpack-button>

            <x-artisanpack-button
                wire:click="insertSuggestion"
                color="secondary"
            >
                Insert into Editor
            </x-artisanpack-button>
        </div>
    </div>
</div>
```

### Custom Implementation

You can use `wire:stream` with any element:

```blade
{{-- Simple streaming text --}}
<div wire:stream="responseText">{{ $responseText }}</div>

{{-- With the StreamableContent component --}}
<x-artisanpack-streamable-content
    target="chatResponse"
    tag="p"
    :show-cursor="true"
/>
```

Server-side streaming:

```php
public function chat(string $message): void
{
    $response = $this->openAI->chat($message);

    // Stream each chunk as it arrives
    foreach ($response->stream() as $chunk) {
        $this->stream('chatResponse', $chunk->content);
    }

    // Mark streaming complete (removes cursor)
    $this->dispatch('streaming-complete', target: 'chatResponse');
}
```

### Streaming Options

The `stream()` method accepts these parameters:

```php
$this->stream(
    to: 'targetName',      // Required: The wire:stream target
    content: $text,        // Required: Content to stream
    replace: false,        // Optional: Replace vs append (default: false = append)
);
```

### Feature Detection

Check if streaming is supported before using it:

```php
use ArtisanPack\LivewireUiComponents\Support\LivewireHelper;

if (LivewireHelper::supportsWireStream()) {
    // Use streaming for real-time updates
    $this->stream('output', $chunk);
} else {
    // Livewire 3 fallback: accumulate and update at once
    $this->output .= $chunk;
}
```

### Livewire 3 Behavior

In Livewire 3, the `StreamableContent` component renders normally without the `wire:stream` attribute. Content must be sent all at once rather than streamed:

```php
public function generateContent(string $prompt): void
{
    if (LivewireHelper::supportsWireStream()) {
        // Livewire 4+: Stream in real-time
        foreach ($this->aiService->streamResponse($prompt) as $chunk) {
            $this->stream('aiContent', $chunk);
        }
    } else {
        // Livewire 3: Get full response and update property
        $this->aiContent = $this->aiService->getFullResponse($prompt);
    }
}
```

### Best Practices

1. **Show loading state**: Use a placeholder or loading indicator before streaming starts
2. **Provide visual feedback**: The `show-cursor` option helps users know content is being streamed
3. **Handle errors gracefully**: Wrap streaming logic in try-catch and show error messages
4. **Consider rate limiting**: AI services may have rate limits; handle them appropriately
5. **Test without streaming**: Ensure your UI works when streaming isn't supported (Livewire 3)

## data-loading Attribute System

Livewire 4 introduces a CSS-based loading state system using the `data-loading` attribute. Components now support both the traditional `wire:loading` directive and the new CSS variant classes.

### How It Works

In Livewire 4, during a loading state, Livewire adds a `data-loading` attribute to elements. This enables CSS-based visibility control through Tailwind variants:

| CSS Class | Behavior |
|-----------|----------|
| `data-loading:block` | Shows element during loading |
| `data-loading:hidden` | Hides element during loading |
| `not-data-loading:hidden` | Hides element when NOT loading |

### Components with data-loading Support

#### Button Component

The Button component uses `data-loading` for spinner and loading content:

```blade
{{-- Spinner shows during loading --}}
<x-artisanpack-button spinner wire:click="save">
    Save
</x-artisanpack-button>

{{-- Custom loading content --}}
<x-artisanpack-button spinner loading="Saving..." wire:click="save">
    Save
</x-artisanpack-button>
```

Internal implementation (for reference):
```html
<!-- Spinner: hidden by default, shown during loading -->
<span class="loading loading-spinner hidden data-loading:block"
      wire:loading.class.remove="hidden">
</span>

<!-- Icon: shown by default, hidden during loading -->
<span class="data-loading:hidden"
      wire:loading.class="hidden">
    <x-artisanpack-icon name="o-check" />
</span>
```

#### MenuItem Component

Menu items with spinners use the same dual-support pattern:

```blade
<x-artisanpack-menu-item
    title="Save"
    icon="o-check"
    spinner
    wire:click="save" />
```

#### Header & Card Components

Progress indicators in Header and Card components support `data-loading`:

```blade
<x-artisanpack-header
    title="Page Title"
    separator
    progress-indicator />

<x-artisanpack-card
    title="Card Title"
    separator
    progress-indicator />
```

#### Separator Component

The Separator component's progress bar supports `data-loading`:

```blade
<x-artisanpack-separator progress wire:target="search" />
```

#### Tags & Choices Components

Search progress indicators support `data-loading`:

```blade
<x-artisanpack-tags
    wire:model="tags"
    searchable />

<x-artisanpack-choices
    wire:model="selection"
    :options="$options"
    searchable />
```

### Custom Implementation

If you're creating custom components, you can implement the same pattern:

```blade
{{-- Element that SHOWS during loading --}}
<div class="hidden data-loading:block"
     wire:loading.class.remove="hidden"
     wire:target="myAction">
    Loading...
</div>

{{-- Element that HIDES during loading --}}
<div class="data-loading:hidden"
     wire:loading.class="hidden"
     wire:target="myAction">
    Content
</div>
```

## wire:intersect - Infinite Scroll

Livewire 4's `wire:intersect` directive enables intersection observer-based lazy loading. This is used for infinite scroll in the Table component and lazy loading options in the Choices component.

### Table Component - Infinite Scroll

```blade
<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    infinite-scroll
    infinite-scroll-method="loadMore"
    :has-more-pages="$hasMorePages" />
```

See the [Table Component documentation](../components/table.md#infinite-scroll-livewire-4) for full details.

### Choices Component - Lazy Loading

```blade
<x-artisanpack-choices
    :options="$users"
    wire:model="selectedUser"
    lazy-load
    lazy-load-method="loadMoreOptions"
    :has-more-options="$hasMore" />
```

See the [Choices Component documentation](../components/choices.md#lazy-loading-options-livewire-4) for full details.

### Modifiers

Both components support Livewire 4's intersection modifiers:

| Modifier | Description |
|----------|-------------|
| `.once` | Only trigger once |
| `.half` | Trigger when element is 50% visible |
| `.full` | Trigger when element is fully visible |

```blade
<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    infinite-scroll
    infinite-scroll-modifier="once"
    :has-more-pages="$hasMorePages" />
```

## wire:sort - Drag-and-Drop Sorting

Livewire 4's native `wire:sort` directive enables drag-and-drop row reordering in tables without JavaScript libraries.

### Table Component - Sortable Rows

```blade
<x-artisanpack-table
    :headers="$headers"
    :rows="$items"
    sortable
    wire:sort="updateOrder" />
```

See the [Table Component documentation](../components/table.md#sortable-rows-livewire-4) for full details.

## Feature Detection

The package includes a `LivewireHelper` utility class that detects Livewire version and feature support:

```php
use ArtisanPack\LivewireUiComponents\Support\LivewireHelper;

// Check Livewire version
if (LivewireHelper::isLivewire4()) {
    // Livewire 4+ specific code
}

// Check specific feature support
if (LivewireHelper::supportsComputedAttribute()) {
    // #[Computed] attribute is available
}

if (LivewireHelper::supportsLazyAttribute()) {
    // #[Lazy] attribute is available
}

if (LivewireHelper::supportsRenderlessAttribute()) {
    // #[Renderless] attribute is available
}

if (LivewireHelper::supportsAsyncActions()) {
    // Livewire 4+ native async support is available
}

if (LivewireHelper::supportsIslands()) {
    // Livewire 4+ with full Islands support for isolated updates
}

if (LivewireHelper::supportsWireStream()) {
    // Livewire 4+ with wire:stream for real-time content streaming
}

if (LivewireHelper::supportsWireIntersect()) {
    // wire:intersect is available
}

if (LivewireHelper::supportsWireSort()) {
    // wire:sort is available
}
```

## Tailwind CSS Configuration

The `data-loading` CSS variants require Tailwind CSS 3.4+ with the variant plugin or custom configuration. The package includes a JIT safelist comment in components to ensure classes are generated.

If you're using a custom Tailwind configuration, ensure these variants are available:

```js
// tailwind.config.js (if needed)
module.exports = {
  plugins: [
    // The data-* variants are built-in to Tailwind CSS 3.4+
    // For older versions, you may need:
    plugin(function({ addVariant }) {
      addVariant('data-loading', '&[data-loading]')
      addVariant('not-data-loading', '&:not([data-loading])')
    })
  ]
}
```

## Troubleshooting

### data-loading classes not working

1. **Check Tailwind version**: Ensure you're using Tailwind CSS 3.4+ which includes built-in `data-*` variants
2. **JIT mode**: Ensure Tailwind JIT is scanning your component files
3. **Livewire version**: The `data-loading` attribute is only added by Livewire 4+

### wire:intersect not triggering

1. **Livewire version**: Requires Livewire 4+
2. **Element visibility**: Ensure the target element is visible in the viewport
3. **Method exists**: Verify the method specified exists on your Livewire component

### wire:sort not working

1. **Livewire version**: Requires Livewire 4+
2. **Row keys**: Ensure each row has a unique identifier
3. **Method signature**: The sort method should accept an array of ordered IDs

### #[Lazy] component not loading

1. **Placeholder view missing**: Ensure the placeholder view exists at the specified path
2. **Intersection Observer**: The component requires the placeholder to enter the viewport to trigger loading
3. **JavaScript disabled**: Lazy loading requires JavaScript; the component will not load without it
4. **Livewire version**: The `#[Lazy]` attribute is available in both Livewire 3 and 4

### #[Renderless] still re-rendering

1. **Attribute class missing**: Verify `Livewire\Attributes\Renderless` exists in your Livewire installation
2. **Method signature**: Ensure the method has the `#[Renderless]` attribute directly above the method
3. **Livewire 3 fallback**: Use `$this->skipRender()` inside the method as a fallback for older versions
4. **State changes**: If the method modifies public properties, Livewire may still re-render; keep renderless methods state-free
