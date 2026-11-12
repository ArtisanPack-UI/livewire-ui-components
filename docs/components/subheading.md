---
title: Subheading Component
---

# Subheading Component

**Deprecated.** `<x-artisanpack-subheading>` renders `<x-artisanpack-text size="lead">`: a 2.x subheading was a paragraph under a heading, not a heading. See [Text](text).

```blade
{{-- Before --}}
<x-artisanpack-subheading>{{ __( 'Track every listing.' ) }}</x-artisanpack-subheading>

{{-- After --}}
<x-artisanpack-text size="lead">{{ __( 'Track every listing.' ) }}</x-artisanpack-text>
```

It takes Text's `as`, `size`, `tone`, `weight` and `classes`. The 2.x `color`, `semibold`, `bold`, `center` and `muted` props are gone: lead text is muted by default.
