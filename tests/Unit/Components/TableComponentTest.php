<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Table;
use Illuminate\Support\Facades\Blade;

/*
 * Table (#62): the design-system markup contract, sorting, selection,
 * expansion, loading and empty states, the footer, and the 2.x API
 * downstream code relies on (headers, rows, key-by, compact,
 * @scope('cell_<key>', $row)). The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php; paginator rows are in
 * tests/Feature/TablePaginationTest.php.
 */

/** @return list<array<string, mixed>> */
function tableHeaders(): array
{
    return [
        ['key' => 'symbol', 'label' => 'Product name', 'secondary' => 'company', 'sortable' => true],
        ['key' => 'amount', 'label' => 'Order amount', 'numeric' => true],
        ['key' => 'date', 'label' => 'Date', 'sortable' => true],
        ['key' => 'owner.name', 'label' => 'Executed by'],
    ];
}

/** @return list<array<string, mixed>> */
function tableRows(): array
{
    return [
        ['id' => 1, 'symbol' => 'TSLA', 'company' => 'Tesla, Inc.', 'amount' => '$30,021.23', 'date' => 'Dec 13, 2023', 'owner' => ['name' => 'Olivia Rhye']],
        ['id' => 2, 'symbol' => 'MTCH', 'company' => 'Match Group, Inc.', 'amount' => '$10,045.00', 'date' => 'Dec 13, 2023', 'owner' => ['name' => 'Phoenix Baker']],
        ['id' => 3, 'symbol' => 'DDOG', 'company' => 'Datadog Inc.', 'amount' => '$40,132.16', 'date' => 'Dec 13, 2023', 'owner' => ['name' => 'Lana Steiner']],
    ];
}

function tableHtml(string $props = '', string $body = '', array $data = []): string
{
    $template = '<x-artisanpack-table :headers="$headers" :rows="$rows" '.$props.'>'.$body.'</x-artisanpack-table>';

    return Blade::render($template, $data + ['headers' => tableHeaders(), 'rows' => tableRows()]);
}

function tableDom(string $props = '', string $body = '', array $data = []): DOMXPath
{
    return designSystemDom(tableHtml($props, $body, $data));
}

test('renders the frame, the scroll part and a real table', function (): void {
    $dom  = tableDom();
    $root = $dom->query('//div[contains(@class, "apui-table")]')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-table')
        ->and($root->getAttribute('data-surface'))->toBe('default')
        ->and($root->hasAttribute('data-sticky-header'))->toBeTrue()
        ->and($root->hasAttribute('data-density'))->toBeFalse()
        ->and($dom->query('//div[@data-part="scroll"]/table[@data-part="table"]'))->toHaveCount(1)
        ->and($dom->query('//thead/tr/th[@data-part="head-cell"][@scope="col"]'))->toHaveCount(4)
        ->and($dom->query('//tbody/tr[@data-part="row"]'))->toHaveCount(3)
        ->and($dom->query('//tbody/tr[1]/td[@data-part="cell"]'))->toHaveCount(4)
        ->and($dom->query('//*[@data-part="toolbar"] | //*[@data-part="footer"]'))->toHaveCount(0)
        ->and(tableHtml())->not->toMatch('/\b(table-zebra|btn|checkbox-sm|bg-base-\d+)\b/');
});

test('the title fills the toolbar and names the table; label overrides it', function (): void {
    $dom   = tableDom('title="Transaction history" toolbar="Filters"');
    $title = $dom->query('//div[@data-part="toolbar"]/h3[@data-part="title"]')->item(0);

    expect(trim($title->textContent))->toBe('Transaction history')
        ->and($dom->query('//table')->item(0)->getAttribute('aria-labelledby'))->toBe($title->getAttribute('id'))
        ->and(trim($dom->query('//div[@data-part="toolbar"]')->item(0)->textContent))->toContain('Filters');

    $labelled = tableDom('title="Transaction history" label="Transactions"')->query('//table')->item(0);

    expect($labelled->getAttribute('aria-label'))->toBe('Transactions')
        ->and($labelled->hasAttribute('aria-labelledby'))->toBeFalse();
});

test('a column with secondary renders primary and secondary lines; numeric columns end-align', function (): void {
    $dom = tableDom();

    expect(trim($dom->query('//tbody/tr[1]/td[1]/span[@data-part="primary"]')->item(0)->textContent))->toBe('TSLA')
        ->and(trim($dom->query('//tbody/tr[1]/td[1]/span[@data-part="secondary"]')->item(0)->textContent))->toBe('Tesla, Inc.')
        ->and($dom->query('//thead//th[2]')->item(0)->hasAttribute('data-numeric'))->toBeTrue()
        ->and($dom->query('//tbody/tr[1]/td[2]')->item(0)->hasAttribute('data-numeric'))->toBeTrue()
        ->and(trim($dom->query('//tbody/tr[2]/td[4]')->item(0)->textContent))->toBe('Phoenix Baker');
});

test('sortable headers carry aria-sort and a named sort button with the order icon', function (): void {
    $dom     = tableDom(':sort-by="[ \'column\' => \'date\', \'direction\' => \'desc\' ]"');
    $headers = $dom->query('//thead//th[@data-part="head-cell"]');

    expect($headers->item(0)->getAttribute('aria-sort'))->toBe('none')
        ->and($headers->item(1)->hasAttribute('aria-sort'))->toBeFalse()
        ->and($headers->item(2)->getAttribute('aria-sort'))->toBe('descending')
        ->and($dom->query('//th[3]/button[@data-part="sort"]')->item(0)->getAttribute('aria-label'))->toBe('Sort by Date')
        ->and($dom->query('//th[3]/button[@data-part="sort"]')->item(0)->getAttribute('type'))->toBe('button')
        ->and($dom->query('//th[3]/button/svg/path[@d="M12 5v14"]'))->toHaveCount(1)
        ->and($dom->query('//th[1]/button/svg/path[@d="m7 15 5 5 5-5"]'))->toHaveCount(1)
        ->and($dom->query('//th[3]/button')->item(0)->getAttribute('x-on:click'))->toContain('direction\\u0022:\\u0022asc')
        ->and($dom->query('//th[1]/button')->item(0)->getAttribute('x-on:click'))->toContain('column\\u0022:\\u0022symbol');
});

test('2.x headers sort once sortBy is set, unless a column opts out', function (): void {
    $headers = [['key' => 'name', 'label' => 'Name'], ['key' => 'email', 'label' => 'Email', 'sortable' => false]];
    $dom     = designSystemDom(Blade::render(
        '<x-artisanpack-table :headers="$headers" :rows="$rows" :sort-by="[ \'column\' => \'name\', \'direction\' => \'asc\' ]" />',
        ['headers' => $headers, 'rows' => [['id' => 1, 'name' => 'Ada', 'email' => 'ada@example.com']]],
    ));

    expect($dom->query('//th[@aria-sort]'))->toHaveCount(1)
        ->and($dom->query('//th[1]')->item(0)->getAttribute('aria-sort'))->toBe('ascending');

    $legacy = designSystemDom(Blade::render(
        '<x-artisanpack-table :headers="$headers" :rows="$rows" :sort-by="[ \'column\' => \'name\', \'direction\' => \'asc\' ]" />',
        ['headers' => [['key' => 'name', 'label' => 'Name'], ['key' => 'email', 'label' => 'Email']], 'rows' => []],
    ));

    expect($legacy->query('//th[@aria-sort]'))->toHaveCount(2);

    // `sortable => true` on any column switches to explicit mode: only those sort.
    $explicit = designSystemDom(Blade::render(
        '<x-artisanpack-table :headers="$headers" :rows="[]" :sort-by="[ \'column\' => \'name\', \'direction\' => \'asc\' ]" />',
        ['headers' => [['key' => 'name', 'label' => 'Name'], ['key' => 'email', 'label' => 'Email', 'sortable' => true]]],
    ));

    expect($explicit->query('//th[@aria-sort]'))->toHaveCount(1)
        ->and($explicit->query('//th[2]')->item(0)->getAttribute('aria-sort'))->toBe('none');
});

test('sort labels are translatable', function (): void {
    app('translator')->addLines(['*.Sort by {column}' => 'Ordenar por {column}'], 'es');
    app()->setLocale('es');

    expect(tableDom()->query('//button[@data-part="sort"]')->item(0)->getAttribute('aria-label'))->toBe('Ordenar por Product name');

    app()->setLocale('en');
});

test('selectable rows hold a named Checkbox and aria-selected; select all is mixed', function (): void {
    $dom = tableDom('selectable :value="[ 2 ]"');

    $all = $dom->query('//thead//th[@data-part="select"]/div[contains(@class, "apui-checkbox")]')->item(0);

    expect($all->getAttribute('data-state'))->toBe('indeterminate')
        ->and($dom->query('//thead//input[@type="checkbox"]')->item(0)->getAttribute('aria-label'))->toBe('Select all')
        ->and($dom->query('//tbody/tr[1]')->item(0)->getAttribute('aria-selected'))->toBe('false')
        ->and($dom->query('//tbody/tr[2]')->item(0)->getAttribute('aria-selected'))->toBe('true')
        ->and($dom->query('//tbody/tr[2]//input')->item(0)->hasAttribute('checked'))->toBeTrue()
        ->and($dom->query('//tbody/tr[1]//input')->item(0)->getAttribute('aria-label'))->toBe('Select TSLA')
        ->and($dom->query('//tbody/tr[1]//input')->item(0)->getAttribute('value'))->toBe('1')
        ->and($dom->query('//tbody/tr[1]/td[@data-part="select"]'))->toHaveCount(1);

    expect(tableDom('selectable :value="[ 1, 2, 3 ]"')->query('//thead//div[contains(@class, "apui-checkbox")]')->item(0)->getAttribute('data-state'))->toBe('checked');
});

test('wire:model entangles the selection', function (): void {
    $html = tableHtml('selectable wire:model.live="selected"');

    expect($html)->toContain('$wire.entangle(&quot;selected&quot;).live')
        ->and(designSystemDom($html)->query('//div[contains(@class, "apui-table")]')->item(0)->hasAttribute('wire:model.live'))->toBeFalse();
});

test('expandable rows get a named expand button and a hidden full-width expansion row', function (): void {
    $dom = tableDom('expandable :expanded="[ 1 ]" selectable', '@scope(\'expansion\', $row) Details for {{ $row[\'symbol\'] }} @endscope');

    $trigger = $dom->query('//tbody/tr[1]//button[@data-part="expand-trigger"]')->item(0);
    $first   = $dom->query('//tr[@data-part="expansion"]')->item(0);
    $second  = $dom->query('//tr[@data-part="expansion"]')->item(1);

    expect($trigger->getAttribute('aria-label'))->toBe('Show details for TSLA')
        ->and($trigger->getAttribute('aria-expanded'))->toBe('true')
        ->and($trigger->getAttribute('aria-controls'))->toBe($first->getAttribute('id'))
        ->and($trigger->hasAttribute('data-mirror'))->toBeTrue()
        ->and($first->hasAttribute('hidden'))->toBeFalse()
        ->and($second->hasAttribute('hidden'))->toBeTrue()
        ->and(trim($first->textContent))->toBe('Details for TSLA')
        ->and($first->getElementsByTagName('td')->item(0)->getAttribute('colspan'))->toBe('6')
        ->and($dom->query('//thead/tr/td[@data-shrink]'))->toHaveCount(1);
});

test('expandable-condition and expandable-key still work', function (): void {
    $rows = [['id' => 1, 'code' => 'a', 'more' => true, 'name' => 'A'], ['id' => 2, 'code' => 'b', 'more' => false, 'name' => 'B']];
    $dom  = designSystemDom(Blade::render(
        '<x-artisanpack-table :headers="[ [ \'key\' => \'name\', \'label\' => \'Name\' ] ]" :rows="$rows" expandable expandable-condition="more" expandable-key="code" :expanded="[ \'a\' ]" />',
        ['rows' => $rows],
    ));

    expect($dom->query('//button[@data-part="expand-trigger"]'))->toHaveCount(1)
        ->and($dom->query('//button[@data-part="expand-trigger"]')->item(0)->getAttribute('aria-expanded'))->toBe('true')
        ->and($dom->query('//tr[@data-part="expansion"]'))->toHaveCount(1);
});

test('loading shows aria-hidden skeleton rows under aria-busy', function (): void {
    $dom = tableDom('loading selectable');

    expect($dom->query('//div[contains(@class, "apui-table")]')->item(0)->getAttribute('aria-busy'))->toBe('true')
        ->and($dom->query('//tbody/tr[@aria-hidden="true"]'))->toHaveCount(3)
        ->and($dom->query('//tbody//span[@class="apui-skeleton"][@data-shape="text"]'))->toHaveCount(12)
        ->and(tableHtml('loading'))->not->toContain('TSLA');
});

test('no rows shows an EmptyState; the empty slot replaces it', function (): void {
    $dom   = tableDom('', '', ['rows' => []]);
    $empty = $dom->query('//tbody/tr/td[@data-empty]')->item(0);

    expect($empty->getAttribute('colspan'))->toBe('4')
        ->and(trim($dom->query('//td[@data-empty]/div[contains(@class, "apui-empty-state")]/*[@data-part="title"]')->item(0)->textContent))->toBe('No records found.');

    $custom = tableDom('empty-text="Nothing yet"', '<x-slot:empty><a href="/new">Add one</a></x-slot:empty>', ['rows' => []]);

    expect($custom->query('//td[@data-empty]/a[@href="/new"]'))->toHaveCount(1)
        ->and(tableHtml('empty-text="Nothing yet"', '', ['rows' => []]))->toContain('Nothing yet');
});

test('cell and header scopes, including dotted keys, render in place', function (): void {
    $body = '@scope(\'cell_amount\', $row) <strong>{{ $row[\'amount\'] }}</strong> @endscope '
        .'@scope(\'cell_owner.name\', $row) <em>{{ $row[\'owner\'][\'name\'] }}</em> @endscope '
        .'@scope(\'header_date\', $header) <u>{{ $header[\'label\'] }}</u> @endscope';
    $dom = tableDom('', $body);

    expect(trim($dom->query('//tbody/tr[1]/td[2]/strong')->item(0)->textContent))->toBe('$30,021.23')
        ->and(trim($dom->query('//tbody/tr[3]/td[4]/em')->item(0)->textContent))->toBe('Lana Steiner')
        ->and(trim($dom->query('//thead//th[3]/button/u')->item(0)->textContent))->toBe('Date');
});

test('the actions scope adds an end column with a screen-reader header', function (): void {
    $dom = tableDom('', '@scope(\'actions\', $row) <button type="button">Edit {{ $row[\'symbol\'] }}</button> @endscope');

    expect(trim($dom->query('//thead//th[last()]/span[@class="apui-sr-only"]')->item(0)->textContent))->toBe('Actions')
        ->and($dom->query('//tbody/tr[1]/td[last()]')->item(0)->hasAttribute('data-actions'))->toBeTrue()
        ->and(trim($dom->query('//tbody/tr[2]/td[last()]/button')->item(0)->textContent))->toBe('Edit MTCH');
});

test('density, compact and hidden headers', function (): void {
    expect(tableDom('density="comfortable"')->query('//div[contains(@class, "apui-table")]')->item(0)->getAttribute('data-density'))->toBe('comfortable')
        ->and(tableDom('compact')->query('//div[contains(@class, "apui-table")]')->item(0)->getAttribute('data-density'))->toBe('compact')
        ->and(tableDom('compact')->query('//div[contains(@class, "apui-table")]')->item(0)->hasAttribute('compact'))->toBeFalse()
        ->and(tableDom('hide-headers')->query('//thead')->item(0)->getAttribute('class'))->toBe('apui-sr-only')
        ->and(tableDom('no-headers')->query('//thead')->item(0)->getAttribute('class'))->toBe('apui-sr-only')
        ->and(tableDom(':sticky-header="false"')->query('//div[contains(@class, "apui-table")]')->item(0)->hasAttribute('data-sticky-header'))->toBeFalse()
        ->and(tableDom('surface="glass"')->query('//div[contains(@class, "apui-table")]')->item(0)->getAttribute('data-surface'))->toBe('glass');
});

test('class and key-by land as before, and classes reach the parts', function (): void {
    $dom  = tableDom('class="ap-psi-urls__table" key-by="symbol" :classes="[ \'cell\' => \'c\', \'headCell\' => \'h\', \'row\' => \'r\' ]" selectable :value="[ \'MTCH\' ]"');
    $root = $dom->query('//div[contains(@class, "apui-table")]')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-table ap-psi-urls__table')
        ->and($dom->query('//tbody/tr[2]')->item(0)->getAttribute('aria-selected'))->toBe('true')
        ->and($dom->query('//tbody/tr[1]')->item(0)->getAttribute('class'))->toBe('r')
        ->and($dom->query('//tbody/tr[1]/td[2]')->item(0)->getAttribute('class'))->toBe('c')
        ->and($dom->query('//thead//th[@data-part="head-cell"]')->item(0)->getAttribute('class'))->toBe('h');
});

test('the footer holds the footer content and Pagination', function (): void {
    $dom    = tableDom('footer="1 selected" :pagination="[ \'page\' => 3, \'total\' => 128, \'perPage\' => 4 ]"');
    $footer = $dom->query('//div[@data-part="footer"]')->item(0);

    expect(trim($footer->firstElementChild->textContent))->toBe('1 selected')
        ->and($dom->query('//div[@data-part="footer"]/nav[contains(@class, "apui-pagination")]'))->toHaveCount(1)
        ->and($dom->query('//nav//*[@aria-current="page"]')->item(0)->textContent)->toContain('3');
});

test('a 2.x footer slot of rows still renders in a tfoot', function (): void {
    $dom = tableDom('', '<x-slot:footer><tr><td colspan="4">Total</td></tr></x-slot:footer>');

    expect($dom->query('//table/tfoot/tr/td'))->toHaveCount(1)
        ->and($dom->query('//div[@data-part="footer"]'))->toHaveCount(0);
});

test('row-click dispatches only while something listens', function (): void {
    expect(tableDom()->query('//tbody/tr[@data-clickable]'))->toHaveCount(0);

    $row = tableDom('x-on:row-click="open( $event.detail )"')->query('//tbody/tr[1]')->item(0);

    expect($row->hasAttribute('data-clickable'))->toBeTrue()
        ->and($row->getAttribute('x-on:click'))->toContain('rowClick( $event');
});

test('2.x link, format and decorations', function (): void {
    $headers = [
        ['key' => 'name', 'label' => 'Name'],
        ['key' => 'price', 'label' => 'Price', 'format' => ['currency', '2.,', '$'], 'disableLink' => true],
    ];
    $dom = designSystemDom(Blade::render(
        '<x-artisanpack-table :headers="$headers" :rows="$rows" link="/items/{id}" :row-decoration="$rowDecoration" :cell-decoration="$cellDecoration" />',
        [
            'headers'        => $headers,
            'rows'           => [['id' => 7, 'name' => 'Lamp', 'price' => 1234.5, 'sale' => true]],
            'rowDecoration'  => ['is-sale' => fn (array $row): bool => $row['sale']],
            'cellDecoration' => ['name' => ['is-bold' => fn (array $row): bool => $row['sale']]],
        ],
    ));

    expect($dom->query('//tbody/tr/td[1]/a[@href="/items/7"]'))->toHaveCount(1)
        ->and($dom->query('//tbody/tr/td[2]/a'))->toHaveCount(0)
        ->and(trim($dom->query('//tbody/tr/td[2]')->item(0)->textContent))->toBe('$1,234.50')
        ->and($dom->query('//tbody/tr')->item(0)->getAttribute('class'))->toBe('is-sale')
        ->and($dom->query('//tbody/tr/td[1]')->item(0)->getAttribute('class'))->toBe('is-bold');
});

test('a javascript: row link never renders', function (): void {
    $dom = designSystemDom(Blade::render(
        '<x-artisanpack-table :headers="[ [ \'key\' => \'name\', \'label\' => \'Name\' ] ]" :rows="[ [ \'id\' => 1, \'name\' => \'A\' ] ]" link="javascript:alert({id})" />',
    ));

    expect($dom->query('//a'))->toHaveCount(0);
});

test('escapes row values', function (): void {
    $html = Blade::render(
        '<x-artisanpack-table :headers="[ [ \'key\' => \'name\', \'label\' => \'Name\' ] ]" :rows="$rows" selectable />',
        ['rows' => [['id' => 1, 'name' => '<script>alert(1)</script>']]],
    );

    expect($html)->not->toContain('<script>alert(1)</script>')
        ->and($html)->toContain('&lt;script&gt;');
});

test('the tableColumns filter receives the table id', function (): void {
    $table = (new Table(headers: [['key' => 'name', 'label' => 'Name'], 'broken'], rows: []))
        ->withAttributes(['id' => 'users-table']);

    expect($table->columns())->toBe([['key' => 'name', 'label' => 'Name']]);
});

test('format handles closures, currency and dates', function (): void {
    $table = new Table(headers: [], rows: []);

    expect($table->format([], 1234.5, ['key' => 'price']))->toBe(1234.5)
        ->and($table->format([], 1234.5, ['key' => 'price', 'format' => ['currency', '2.,', '$']]))->toBe('$1,234.50')
        ->and($table->format([], 1, ['key' => 'id', 'format' => fn (array $row, int $field): string => '#'.$field]))->toBe('#1')
        ->and($table->format([], '2024-01-02', ['key' => 'd', 'format' => ['date', 'Y']]))->toBe('2024');
});
