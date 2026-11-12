<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\DiffBuilder;
use Illuminate\Support\Facades\Blade;

/*
 * Diff (#61): the design-system markup contract, and DiffBuilder, the PHP
 * port of the behavior package's diffText. Its expectations match
 * test/data-helpers.test.ts in the design system, so Blade renders the rows
 * React and Vue render. The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

const DIFF_OLD = "{\n  price: 220000,\n  rent: 1600,\n  currency: \"USD\",\n  available: \"2027-01-01\",\n}\n";
const DIFF_NEW = "{\n  price: 220000,\n  rent: 1650,\n  deposit: 3300,\n  currency: \"USD\",\n  available: \"2027-01-15\",\n}\n";

function diffDom(string $props = ''): DOMXPath
{
    return designSystemDom(Blade::render('<x-artisanpack-diff :old-content="$old" :new-content="$new" '.$props.' />', ['old' => DIFF_OLD, 'new' => DIFF_NEW]));
}

test('DiffBuilder matches the TypeScript helper', function (): void {
    expect(DiffBuilder::lines("a\r\nb\n"))->toBe(['a', 'b'])
        ->and(DiffBuilder::lines(''))->toBe([]);

    $result = DiffBuilder::diff(DIFF_OLD, DIFF_NEW, 1);
    expect($result['additions'])->toBe(3)
        ->and($result['deletions'])->toBe(2)
        ->and($result['hunks'])->toHaveCount(1)
        ->and($result['hunks'][0]['header'])->toBe('@@ -2,5 +2,6 @@')
        ->and(array_column($result['hunks'][0]['lines'], 'type'))->toBe(['ctx', 'del', 'add', 'add', 'ctx', 'del', 'add', 'ctx']);

    $lines = DiffBuilder::diff(DIFF_OLD, DIFF_NEW)['hunks'][0]['lines'];
    expect($lines[2]['segments'])->toBe([['text' => '  rent: ', 'changed' => false], ['text' => '1600', 'changed' => true], ['text' => ',', 'changed' => false]])
        ->and($lines[3]['segments'])->toBe([['text' => '  rent: ', 'changed' => false], ['text' => '1650', 'changed' => true], ['text' => ',', 'changed' => false]])
        ->and($lines[4]['segments'])->toBe([['text' => '  deposit: 3300,', 'changed' => false]])
        ->and(DiffBuilder::words('abc', 'xyz'))->toBeNull();

    $before = implode("\n", array_map(static fn (int $n): string => 'line '.$n, range(1, 20)));
    $after  = str_replace(['line 2'."\n", 'line 18'], ['line two'."\n", 'line eighteen'], $before);
    expect(array_column(DiffBuilder::diff($before, $after, 2)['hunks'], 'header'))->toBe(['@@ -1,4 +1,4 @@', '@@ -16,5 +16,5 @@'])
        ->and(DiffBuilder::diff("a\nb", "a\nb")['hunks'])->toBe([])
        ->and(DiffBuilder::diff('', 'one')['hunks'][0]['header'])->toBe('@@ -0,0 +1 @@');

    $rows = DiffBuilder::splitRows($lines);
    expect(array_map(static fn (array $row): array => [$row['old']['type'], $row['new']['type']], $rows))->toBe([
        ['ctx', 'ctx'], ['ctx', 'ctx'], ['del', 'add'], ['empty', 'add'], ['ctx', 'ctx'], ['del', 'add'], ['ctx', 'ctx'],
    ]);
});

test('DiffBuilder matches the common suffix before the table, as the TypeScript helper does', function (): void {
    $hunk = DiffBuilder::diff("x\nb", "b\ny\nb")['hunks'][0];

    expect($hunk['header'])->toBe('@@ -1,2 +1,3 @@')
        ->and(array_map(static fn (array $line): array => [$line['type'], $line['oldNumber'], $line['newNumber']], $hunk['lines']))->toBe([
            ['del', 1, null],
            ['add', null, 1],
            ['add', null, 2],
            ['ctx', 2, 3],
        ]);
});

test('DiffBuilder deletes all, then adds all, when the change is too big for the table', function (): void {
    $side = static fn (string $name): string => implode("\n", [
        'head',
        ...array_map(static fn (int $i): string => $name.' '.$i, range(0, 499)),
        'shared',
        ...array_map(static fn (int $i): string => $name.' '.$i, range(500, 999)),
        'tail',
    ]);

    expect(1001 * 1001)->toBeGreaterThan(DiffBuilder::MAX_CELLS);

    $result = DiffBuilder::diff($side('old'), $side('new'));
    $lines  = $result['hunks'][0]['lines'];
    $types  = array_column($lines, 'type');

    expect($result['additions'])->toBe(1001)
        ->and($result['deletions'])->toBe(1001)
        ->and($result['hunks'])->toHaveCount(1)
        ->and($result['hunks'][0]['header'])->toBe('@@ -1,1003 +1,1003 @@')
        ->and($types[0])->toBe('ctx')
        ->and(array_unique(array_slice($types, 1, 1001)))->toBe(['del'])
        ->and(array_values(array_unique(array_slice($types, 1002, 1001))))->toBe(['add'])
        ->and($types[2003])->toBe('ctx')
        ->and([$lines[1002]['oldNumber'], $lines[1002]['newNumber']])->toBe([null, 2])
        ->and([$lines[2003]['oldNumber'], $lines[2003]['newNumber']])->toBe([1003, 1003]);
});

test('renders the header with hidden counts in words', function (): void {
    $dom    = diffDom('file-name="listings/grove.json"');
    $root   = $dom->query('//div')->item(0);
    $counts = $dom->query('//*[@data-part="counts"]')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-diff')
        ->and($root->getAttribute('data-view'))->toBe('unified')
        ->and($dom->query('//*[@data-part="header"]/*[@data-part="icon"][@aria-hidden="true"]/svg'))->toHaveCount(1)
        ->and(trim($dom->query('//*[@data-part="file"]')->item(0)->textContent))->toBe('listings/grove.json')
        ->and($dom->query('.//*[@class="apui-sr-only"]', $counts)->item(0)->textContent)->toBe('3 additions, 2 deletions')
        ->and($dom->query('.//*[@data-type="add"][@aria-hidden="true"]', $counts)->item(0)->textContent)->toBe('+3')
        ->and($dom->query('.//*[@data-type="del"][@aria-hidden="true"]', $counts)->item(0)->textContent)->toBe("\u{2212}2");
});

test('unified rows carry both line numbers, a sign and word marks', function (): void {
    $dom  = diffDom();
    $rows = $dom->query('//table[@data-part="table"]/tbody/tr');

    expect($rows->item(0)->getAttribute('data-part'))->toBe('hunk')
        ->and(trim($rows->item(0)->textContent))->toBe('@@ -1,6 +1,7 @@')
        ->and($rows->item(0)->getElementsByTagName('td')->item(0)->getAttribute('colspan'))->toBe('4');

    $removed = $dom->query('//tr[@data-part="line"][@data-type="del"]')->item(0);
    $cells   = $removed->getElementsByTagName('td');
    expect($cells)->toHaveCount(4)
        ->and($cells->item(0)->textContent)->toBe('3')
        ->and($cells->item(1)->textContent)->toBe('')
        ->and($cells->item(2)->textContent)->toBe("\u{2212}")
        ->and($cells->item(3)->textContent)->toBe('  rent: 1600,')
        ->and($cells->item(3)->getAttribute('data-type'))->toBe('del')
        ->and($dom->query('.//del[@data-part="del"]', $cells->item(3))->item(0)->textContent)->toBe('1600')
        ->and($dom->query('//ins[@data-part="ins"]')->item(0)->textContent)->toBe('1650')
        ->and($dom->query('//tr[@data-type="add"]/td[@data-part="sign"]')->item(0)->textContent)->toBe('+');
});

test('split rows pair the old side with the new', function (): void {
    $dom   = diffDom('view="split"');
    $line  = $dom->query('//tr[@data-part="line"]')->item(3);
    $types = [];
    foreach ($line->getElementsByTagName('td') as $cell) {
        $types[] = $cell->getAttribute('data-part').':'.$cell->getAttribute('data-type');
    }

    expect($dom->query('//div')->item(0)->getAttribute('data-view'))->toBe('split')
        ->and($dom->query('//tr[@data-part="hunk"]/td')->item(0)->getAttribute('colspan'))->toBe('6')
        ->and($types)->toBe(['lineNumber:empty', 'sign:empty', 'code:empty', 'lineNumber:add', 'sign:add', 'code:add']);
});

test('wordLevel off and context change the rows', function (): void {
    expect(diffDom(':word-level="false"')->query('//ins | //del'))->toHaveCount(0)
        ->and(diffDom(':context="0"')->query('//tr[@data-part="hunk"]'))->toHaveCount(2);
});

test('identical versions show the empty label', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-diff old-content="a" new-content="a" empty-label="Sin cambios" />'));

    expect(trim($dom->query('//tr[@data-part="hunk"]')->item(0)->textContent))->toBe('Sin cambios')
        ->and($dom->query('//tr[@data-part="line"]'))->toHaveCount(0);
});

test('escapes the content', function (): void {
    $html = Blade::render('<x-artisanpack-diff old-content="<b>a</b>" new-content="<b>b</b>" />');

    expect($html)->not->toContain('<b>')
        ->and($html)->toContain('&lt;b&gt;');
});

test('the actions slot sits in the header, and 2.x old and new still work', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-diff old="a" new="b"><x-slot:actions><button type="button">Copy</button></x-slot:actions></x-artisanpack-diff>'));

    expect($dom->query('//*[@data-part="header"]/*[@data-part="actions"]/button'))->toHaveCount(1)
        ->and($dom->query('//tr[@data-part="line"]'))->toHaveCount(2)
        ->and($dom->query('//div')->item(0)->hasAttribute('old'))->toBeFalse();
});

test('needs no script', function (): void {
    expect(Blade::render('<x-artisanpack-diff old-content="a" new-content="b" />'))->not->toContain('x-data')
        ->and(Blade::render('<x-artisanpack-diff old-content="a" new-content="b" />'))->not->toContain('Diff2HtmlUI');
});
