<?php

/**
 * The label around a task-list item's checkbox and first line.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Support\Markdown;

use League\CommonMark\Node\Inline\AbstractInline;

/**
 * Wraps a task-list item's checkbox and the rest of its first paragraph, so
 * the checkbox is named by its text. Rendered as `<label>`.
 *
 * @since 3.0.0
 */
final class TaskLabel extends AbstractInline {}
