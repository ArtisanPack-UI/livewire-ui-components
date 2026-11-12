{{--
    Carousel's previous and next buttons (#65): outline in the header, soft
    over the viewport without one. Included by carousel.blade.php.
--}}
<x-artisanpack-button
    :variant="$navProps['variant']"
    color="neutral"
    size="sm"
    icon-only
    leading-icon="chevron-left"
    :disabled="! $navProps['canPrev']"
    :aria-label="$text( 'previous' )"
    :aria-controls="$partId( 'item-group' )"
    :id="$partId( 'prev-trigger' )"
    :class="$classes['prevTrigger'] ?? null"
    data-scope="carousel"
    data-part="prev-trigger"
    x-apui:prev-trigger=""
/>
<x-artisanpack-button
    :variant="$navProps['variant']"
    color="neutral"
    size="sm"
    icon-only
    leading-icon="chevron-right"
    :disabled="! $navProps['canNext']"
    :aria-label="$text( 'next' )"
    :aria-controls="$partId( 'item-group' )"
    :id="$partId( 'next-trigger' )"
    :class="$classes['nextTrigger'] ?? null"
    data-scope="carousel"
    data-part="next-trigger"
    x-apui:next-trigger=""
/>
