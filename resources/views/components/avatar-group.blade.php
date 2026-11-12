{{--
    AvatarGroup (#61). Markup contract: design-system wave, Avatar (group).
    A labelled group of overlapping avatars: the first `max` of `avatars`, a +N
    avatar for the rest, then any avatar children.
--}}
@php $hidden = $hiddenCount(); @endphp
<span
    {{ $attributes->class( $rootClasses( 'apui-avatar-group' ) ) }}
    role="group"
    @if ( $filled( $label ) ) aria-label="{{ $label }}" @endif
>@foreach ( $shown() as $index => $avatar )<x-artisanpack-avatar
        wire:key="avatar-{{ $index }}"
        :name="$avatar['name'] ?? null"
        :src="$avatar['src'] ?? null"
        :alt="$avatar['alt'] ?? null"
        :initials="$avatar['initials'] ?? null"
        :icon="$avatar['icon'] ?? null"
        :color="$avatar['color'] ?? null"
        :status="$avatar['status'] ?? null"
        :size="$size"
    />@endforeach @if ( $hidden > 0 )<x-artisanpack-avatar :initials="'+' . $hidden" :alt="str_replace( '{count}', (string) $hidden, $moreLabel )" color="neutral" :size="$size" />@endif{{ $slot ?? '' }}</span>
