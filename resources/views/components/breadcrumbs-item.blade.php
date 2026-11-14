{{--
    BreadcrumbsItem (#56). Registers its level with the parent Breadcrumbs,
    which renders the trail; outside one it renders nothing.
--}}
@php
    $trail?->addChild(
        isset( $slot ) && $slot->hasActualContent() ? $content( $slot ) : (string) $label,
        $linkHref(),
        $icon,
        $hideLabel,
        $attributes,
    );
@endphp
