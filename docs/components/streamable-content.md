---
title: Streamable Content Component
---

# Streamable Content Component

Content that arrives over time, such as a generated summary, built on the ArtisanPack UI design system (`.apui-streamable-content`). It is Livewire only: it streams with `wire:stream`, which needs Livewire 4 (Livewire 3 renders the content statically).

## Basic Usage

```blade
<x-artisanpack-card>
	<x-artisanpack-streamable-content target="summary" :state="$state" wire:stop="cancel">
		{{ $summary }}

		<x-slot:actions>
			@if ( 'done' === $state )
				<x-artisanpack-button size="xs" variant="ghost" color="neutral" leading-icon="copy">{{ __( 'Copy' ) }}</x-artisanpack-button>
			@elseif ( 'error' === $state )
				<x-artisanpack-button size="xs" variant="outline" color="neutral" leading-icon="rotate-ccw" wire:click="continue">{{ __( 'Continue' ) }}</x-artisanpack-button>
			@endif
		</x-slot:actions>
	</x-artisanpack-streamable-content>
</x-artisanpack-card>
```

```php
public string $state = 'waiting';

public function generate(): void
{
	$this->state = 'streaming';
	foreach ( $chunks as $chunk ) {
		$this->summary .= $chunk;
		$this->stream( to: 'summary', content: $chunk );
	}
	$this->state = 'done';
}
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `target` | string | required | The `wire:stream` target. |
| `state` | `waiting` · `streaming` · `done` · `error` · `stopped` | `streaming` | Emitted as `data-state`. Waiting shows skeleton lines; streaming shows the caret and Stop. |
| `status` | string or slot | the state's text | `__( 'Waiting for a response…' )`, `__( 'Writing…' )`, `__( 'Done' )`, `__( 'The response was interrupted.' )` or `__( 'Stopped' )`. |
| `stop-label` | string | `__( 'Stop' )` | The Stop button's text. |
| `actions` | string or slot | none | Buttons under the content. |
| `classes` | array | `[]` | Extra classes per part: `root`, `status`, `stopTrigger`, `content`, `caret`, `actions`. |

## Events

Stop dispatches `stop`: listen with `wire:stop="cancel"` or `x-on:stop`.

## Accessibility

- The content is `aria-live=polite` and `aria-busy` while waiting or streaming, so it isn't announced token by token; the status line is `role=status`.
- Stop is always available while streaming. Keep partial content when a stream fails, and offer Continue.

## Upgrading from 2.x

- `tag`, `prose`, `placeholder` and `show-cursor` are gone: `state` draws the status line, the caret and the waiting skeleton.
