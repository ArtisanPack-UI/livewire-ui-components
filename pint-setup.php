<?php

declare(strict_types=1);
require __DIR__.'/vendor/autoload.php';

use ArtisanPackUI\CodeStylePint\Config\PintConfigBuilder;

PintConfigBuilder::create()
    ->withArtisanPackUIPreset()
    ->save(__DIR__.'/pint.json');

echo "pint.json created successfully!\n";
