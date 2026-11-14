<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\DesignSystemAssets;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

/*
 * The committed dist/ is written by scripts/build-assets.mjs. These tests check
 * that dist/ and dist/manifest.json agree, so a hand edit or a partial copy
 * fails CI.
 */

beforeEach(function (): void {
    $this->dist     = DesignSystemAssets::packageDistPath();
    $this->manifest = json_decode((string) file_get_contents($this->dist.'/manifest.json'), true, flags: JSON_THROW_ON_ERROR);
});

test('the manifest lists the default and headless stylesheets and the Alpine adapter', function (): void {
    expect($this->manifest['styles'])->toHaveKeys(['default', 'headless'])
        ->and($this->manifest['styles'])->not->toHaveKey('tailwind')
        ->and($this->manifest['scripts'])->toBe(['alpine' => 'js/alpine.global.js'])
        ->and($this->manifest['packages'])->toHaveKeys(['@artisanpack-ui/css', '@artisanpack-ui/behavior', '@artisanpack-ui/tokens']);

    foreach ([...array_values($this->manifest['styles']), ...array_values($this->manifest['scripts'])] as $file) {
        expect($this->manifest['files'])->toHaveKey($file);
    }
});

test('every file hash in the manifest matches the committed file', function (): void {
    foreach ($this->manifest['files'] as $file => $hash) {
        $path = $this->dist.'/'.$file;

        expect(is_file($path))->toBeTrue("{$file} is missing from dist/")
            ->and($hash)->toMatch('/^[0-9a-f]{16}$/')
            ->and(substr(hash_file('sha256', $path), 0, 16))->toBe($hash, "{$file} does not match its manifest hash");
    }
});

test('dist/ holds only the files the manifest lists', function (): void {
    $files    = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->dist, FilesystemIterator::SKIP_DOTS));

    foreach ($iterator as $file) {
        $files[] = str_replace('\\', '/', substr($file->getPathname(), strlen($this->dist) + 1));
    }

    sort($files);
    $expected = [...array_keys($this->manifest['files']), 'manifest.json'];
    sort($expected);

    expect($files)->toBe($expected);
});

test('the Alpine adapter points at its source map', function (): void {
    expect(file_get_contents($this->dist.'/js/alpine.global.js'))
        ->toContain('alpine:init')
        ->toEndWith('//# sourceMappingURL=alpine.global.js.map');

    expect($this->manifest['files'])->toHaveKey('js/alpine.global.js.map');
});

test('the manifest is stable JSON', function (): void {
    $raw = (string) file_get_contents($this->dist.'/manifest.json');

    expect($raw)->toEndWith("}\n")
        ->and($raw)->not->toContain('/Users/')
        ->and(array_keys($this->manifest['files']))->toBe(collect($this->manifest['files'])->keys()->sort()->values()->all());
});

test('the committed assets match the pinned design-system packages', function (): void {
    $node     = (new ExecutableFinder)->find('node');
    $checkout = getenv('DESIGN_SYSTEM_PATH') ?: null;
    $modules  = dirname(__DIR__, 2).'/node_modules/@artisanpack-ui';
    $source   = null !== $checkout ? $checkout.'/packages' : $modules;

    if (! is_dir($source.'/css/dist') || ! is_dir($source.'/behavior/dist') || null === $node) {
        $this->markTestSkipped('No design-system packages (run npm ci, or set DESIGN_SYSTEM_PATH to a built checkout) or no node.');
    }

    $arguments = null !== $checkout ? ['--from', $checkout] : [];
    $process   = new Process([$node, dirname(__DIR__, 2).'/scripts/build-assets.mjs', '--check', ...$arguments]);
    $process->run();

    expect($process->getExitCode())->toBe(0, $process->getErrorOutput().$process->getOutput());
});

test('the build script rejects an empty --from instead of falling back to node_modules', function (array $args): void {
    $node = (new ExecutableFinder)->find('node');

    if (null === $node) {
        $this->markTestSkipped('No node.');
    }

    $process = new Process([$node, dirname(__DIR__, 2).'/scripts/build-assets.mjs', '--check', ...$args]);
    $process->run();

    expect($process->getExitCode())->toBe(1)
        ->and($process->getErrorOutput())->toContain('--from needs a path to a design-system checkout.');
})->with([
    'equals form'   => [['--from=']],
    'missing value' => [['--from']],
]);
