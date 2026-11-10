<?php

/**
 * Theme generator exception.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Exceptions;

use InvalidArgumentException;

/**
 * Thrown when the token-based theme generator gets input it can't use, or
 * when the design tokens it reads are missing or malformed.
 *
 * @since 3.0.0
 */
class ThemeGeneratorException extends InvalidArgumentException {}
