<?php

require_once __DIR__ . '/vendor/autoload.php';

use ArtisanPack\LivewireUiComponents\Tests\Support\GenerateComponentTests;

echo "Starting component test generation...\n";
echo "=====================================\n\n";

$result = GenerateComponentTests::run();

echo "\n=====================================\n";
echo "Summary:\n";
echo "  Components found: {$result['components_found']}\n";
echo "  Tests generated: {$result['tests_generated']}\n";
echo "\nTest generation complete!\n";
