<?php

/**
 * Upgrades a Blade file from the 2.x components to 3.0.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Upgrade;

/**
 * Rewrites the library's tags in a Blade file: renamed components, renamed
 * and removed attributes, values that moved to another attribute, slots and
 * tooltips. What it can't decide is left alone and reported.
 *
 * It edits the source in place, by offset, so formatting, comments and every
 * other tag stay as written. Tags that packages register under the same
 * prefix (`x-artisanpack-ec-*`) have no rules and are never touched.
 *
 * @since 3.0.0
 */
final class BladeUpgrader
{
    /**
     * The source being upgraded.
     */
    private string $source = '';

    /**
     * Replacements as `[start, end, text]`, in the order they were made.
     *
     * @var array<int, array{0: int, 1: int, 2: string}>
     */
    private array $edits = [];

    /**
     * Findings keyed for sorting by position.
     *
     * @var array<int, array{0: int, 1: Finding}>
     */
    private array $findings = [];

    private LineIndex $lines;

    /**
     * Creates the upgrader.
     *
     * @since 3.0.0
     *
     * @param  UpgradeRules  $rules  The upgrade rules.
     */
    public function __construct(private readonly UpgradeRules $rules) {}

    /**
     * Upgrades one Blade file.
     *
     * @since 3.0.0
     *
     * @param  string  $source  The file's contents.
     * @param  bool  $classes  Also report raw DaisyUI classes and theme references.
     */
    public function upgrade(string $source, bool $classes = true): UpgradeResult
    {
        $this->source   = $source;
        $this->edits    = [];
        $this->findings = [];
        $this->lines    = new LineIndex($source);

        $tags = $this->pair($this->tokenize());

        foreach ($tags as $tag) {
            if ('close' === $tag['type']) {
                $this->closingTag($tag);
            } elseif (str_starts_with($tag['name'], 'slot')) {
                $this->slotTag($tag, $tags);
            } else {
                $this->componentTag($tag);
            }
        }

        $findings = $this->findings;

        if ($classes) {
            foreach ((new ClassScanner($this->rules))->scan($source) as $finding) {
                $findings[] = [PHP_INT_MAX, $finding];
            }
        }

        usort($findings, fn (array $a, array $b): int => [$a[1]->line, $a[1]->column] <=> [$b[1]->line, $b[1]->column]);

        return new UpgradeResult($source, $this->apply(), array_map(fn (array $item): Finding => $item[1], $findings));
    }

    /**
     * Applies the edits, last first, so earlier offsets stay valid.
     */
    private function apply(): string
    {
        $edits = $this->edits;
        $order = array_keys($edits);

        // Last edit first. Where two start at the same offset, the one that replaces text runs
        // before an insertion, so the insertion still lands at the offset it was made for.
        usort($order, fn (int $a, int $b): int => [$edits[$b][0], $edits[$b][1] - $edits[$b][0] > 0 ? 1 : 0, $b]
            <=> [$edits[$a][0], $edits[$a][1] - $edits[$a][0] > 0 ? 1 : 0, $a]);

        $code = $this->source;

        foreach ($order as $index) {
            [$start, $end, $text] = $edits[$index];
            $code                 = substr($code, 0, $start).$text.substr($code, $end);
        }

        return $code;
    }

    /**
     * Finds every `<x-…>` and `</x-…>` tag outside comments, `@verbatim` and PHP.
     *
     * @return array<int, array<string, mixed>>
     */
    private function tokenize(): array
    {
        preg_match_all('~\{\{--.*?--\}\}|@verbatim.*?@endverbatim|@php(?![\w(]).*?@endphp|<\?php.*?\?>~s', $this->source, $ignored, PREG_OFFSET_CAPTURE);
        $skip = array_map(fn (array $match): array => [$match[1], $match[1] + strlen($match[0])], $ignored[0]);

        preg_match_all('~<(/?)x[-:]([A-Za-z0-9_.:-]*[A-Za-z0-9_])~', $this->source, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);

        $tags = [];
        $from = 0;

        foreach ($matches as $match) {
            $start = $match[0][1];

            // Inside the previous tag's attributes, or in a comment.
            if ($start < $from || $this->within($start, $skip)) {
                continue;
            }

            $nameStart = $match[2][1];
            $nameEnd   = $nameStart + strlen($match[2][0]);

            if ('/' === $match[1][0]) {
                $end    = strpos($this->source, '>', $nameEnd);
                $tags[] = ['type' => 'close', 'name' => $match[2][0], 'start' => $start, 'nameStart' => $nameStart, 'nameEnd' => $nameEnd, 'end' => false === $end ? $nameEnd : $end + 1];

                continue;
            }

            $tag    = $this->attributes($nameEnd) + ['type' => 'open', 'name' => $match[2][0], 'start' => $start, 'nameStart' => $nameStart, 'nameEnd' => $nameEnd, 'elementEnd' => null, 'parent' => null, 'close' => null];
            $from   = $tag['end'];
            $tags[] = $tag;
        }

        return $tags;
    }

    /**
     * Whether an offset falls in one of the ranges.
     *
     * @param  array<int, array{0: int, 1: int}>  $ranges
     */
    private function within(int $offset, array $ranges): bool
    {
        foreach ($ranges as [$start, $end]) {
            if ($offset >= $start && $offset < $end) {
                return true;
            }
        }

        return false;
    }

    /**
     * Reads a tag's attributes, from the end of its name to its `>`.
     *
     * Blade echoes (`{{ $attributes }}`) and directives (`@class([...])`,
     * `@if … @endif`) inside the tag are skipped and noted as `opaque`.
     *
     * @return array{attributes: array<int, array<string, mixed>>, end: int, selfClosing: bool, opaque: bool}
     */
    private function attributes(int $position): array
    {
        $source     = $this->source;
        $length     = strlen($source);
        $attributes = [];
        $opaque     = false;

        while ($position < $length) {
            $position += strspn($source, " \t\r\n", $position);

            if ($position >= $length) {
                break;
            }

            if ('/>' === substr($source, $position, 2)) {
                return ['attributes' => $attributes, 'end' => $position + 2, 'selfClosing' => true, 'opaque' => $opaque];
            }

            if ('>' === $source[$position]) {
                return ['attributes' => $attributes, 'end' => $position + 1, 'selfClosing' => false, 'opaque' => $opaque];
            }

            if ('{{' === substr($source, $position, 2) || '{!!' === substr($source, $position, 3)) {
                $close    = '{{' === substr($source, $position, 2) ? '}}' : '!!}';
                $end      = strpos($source, $close, $position);
                $position = false === $end ? $length : $end + strlen($close);
                $opaque   = true;

                continue;
            }

            // A Blade directive, unless it is an Alpine `@click="…"`.
            if ('@' === $source[$position] && 1 === preg_match('~\G@[A-Za-z]+\s*(\(|(?=[\s/>]))~', $source, $match, 0, $position)) {
                $position += strlen($match[0]);

                if ('(' === $match[1]) {
                    $position = $this->closingParenthesis($position);
                }

                $opaque = true;

                continue;
            }

            if (1 !== preg_match('~\G[^\s=>/]+~', $source, $match, 0, $position)) {
                // A stray `/` or `=`.
                $position++;

                continue;
            }

            $attribute = ['name' => $match[0], 'start' => $position, 'nameEnd' => $position + strlen($match[0]), 'value' => null, 'valueStart' => null, 'valueEnd' => null];
            $position  = $attribute['nameEnd'];

            if (1 === preg_match('~\G\s*=\s*~', $source, $match, 0, $position)) {
                $position += strlen($match[0]);
                $quote = $source[$position] ?? '';

                if ('"' === $quote || "'" === $quote) {
                    $end                     = strpos($source, $quote, $position + 1);
                    $end                     = false === $end ? $length : $end;
                    $attribute['valueStart'] = $position + 1;
                    $attribute['valueEnd']   = $end;
                    $position                = min($length, $end + 1);
                } else {
                    preg_match('~\G[^\s>]*~', $source, $match, 0, $position);
                    $attribute['valueStart'] = $position;
                    $attribute['valueEnd']   = $position + strlen($match[0]);
                    $position                = $attribute['valueEnd'];
                }

                $attribute['value'] = substr($source, $attribute['valueStart'], $attribute['valueEnd'] - $attribute['valueStart']);
            }

            $attribute['end'] = $position;
            $attributes[]     = $attribute;
        }

        return ['attributes' => $attributes, 'end' => $length, 'selfClosing' => false, 'opaque' => $opaque];
    }

    /**
     * The offset after the `)` matching an already-open `(`.
     */
    private function closingParenthesis(int $position): int
    {
        $length = strlen($this->source);
        $depth  = 1;

        while ($position < $length && $depth > 0) {
            $char = $this->source[$position];

            if ('"' === $char || "'" === $char) {
                $position++;

                while ($position < $length && $this->source[$position] !== $char) {
                    $position += '\\' === $this->source[$position] ? 2 : 1;
                }
            } elseif ('(' === $char) {
                $depth++;
            } elseif (')' === $char) {
                $depth--;
            }

            $position++;
        }

        return $position;
    }

    /**
     * Gives each open tag its parent and the offset where its element ends.
     *
     * @param  array<int, array<string, mixed>>  $tags
     *
     * @return array<int, array<string, mixed>>
     */
    private function pair(array $tags): array
    {
        $stack = [];

        foreach ($tags as $index => $tag) {
            if ('open' === $tag['type']) {
                $tags[$index]['parent'] = [] === $stack ? null : end($stack);

                if ($tag['selfClosing']) {
                    $tags[$index]['elementEnd'] = $tag['end'];
                } else {
                    $stack[] = $index;
                }

                continue;
            }

            // `</x-slot>` closes `<x-slot:name>`.
            $closes = fn (int $open): bool => $tags[$open]['name'] === $tag['name']
                || ('slot' === $tag['name'] && str_starts_with($tags[$open]['name'], 'slot'));

            for ($depth = count($stack) - 1; $depth >= 0; $depth--) {
                if ($closes($stack[$depth])) {
                    $tags[$stack[$depth]]['elementEnd'] = $tag['end'];
                    $tags[$stack[$depth]]['close']      = $index;
                    $stack                              = array_slice($stack, 0, $depth);

                    break;
                }
            }
        }

        return $tags;
    }

    /**
     * The 2.x component a tag names and its rules, or null when it isn't the library's.
     *
     * @return array{0: string, 1: array<string, mixed>}|null
     */
    private function component(string $tag): ?array
    {
        if (! str_starts_with($tag, UpgradeRules::PREFIX)) {
            return null;
        }

        $name = substr($tag, strlen(UpgradeRules::PREFIX));
        $rule = $this->rules->component($name);

        return null === $rule ? null : [$name, $rule];
    }

    /**
     * Records a finding at an offset.
     */
    private function report(string $kind, int $offset, string $message, ?string $component = null, ?string $member = null): void
    {
        [$line, $column]  = $this->lines->locate($offset);
        $this->findings[] = [$offset, new Finding($kind, $line, $column, $message, $component, $member)];
    }

    /**
     * Renames a closing tag whose component was renamed.
     *
     * @param  array<string, mixed>  $tag
     */
    private function closingTag(array $tag): void
    {
        $component = $this->component($tag['name']);

        if (null !== $component && $component[1]['to'] !== $component[0]) {
            $this->edits[] = [$tag['nameStart'], $tag['nameEnd'], UpgradeRules::PREFIX.$component[1]['to']];
        }
    }

    /**
     * Renames `<x-slot:actions>` or `<x-slot name="actions">` when the parent's slot moved.
     *
     * @param  array<string, mixed>  $tag
     * @param  array<int, array<string, mixed>>  $tags
     */
    private function slotTag(array $tag, array $tags): void
    {
        $parent = $tag['parent'];

        while (null !== $parent && str_starts_with($tags[$parent]['name'], 'slot')) {
            $parent = $tags[$parent]['parent'];
        }

        $component = null === $parent ? null : $this->component($tags[$parent]['name']);

        if (null === $component) {
            return;
        }

        [$start, $end] = [null, null];

        if (str_starts_with($tag['name'], 'slot:')) {
            [$start, $end] = [$tag['nameStart'] + 5, $tag['nameEnd']];
        } else {
            foreach ($tag['attributes'] as $attribute) {
                if ('name' === $attribute['name'] && null !== $attribute['value']) {
                    [$start, $end] = [$attribute['valueStart'], $attribute['valueEnd']];
                }
            }
        }

        if (null === $start) {
            return;
        }

        $name = substr($this->source, $start, $end - $start);
        $rule = $component[1]['slots'][UpgradeRules::camel($name)] ?? null;

        if (null === $rule) {
            return;
        }

        if ('rename' === $rule['kind']) {
            $to            = str_contains($name, '-') || ! preg_match('/[A-Z]/', $name) ? UpgradeRules::kebab($rule['to']) : $rule['to'];
            $this->edits[] = [$start, $end, $to];

            // `</x-slot:actions>` names the slot too.
            $close = isset($tag['close']) ? $tags[$tag['close']] : null;

            if (null !== $close && str_starts_with($close['name'], 'slot:')) {
                $this->edits[] = [$close['nameStart'] + 5, $close['nameEnd'], $to];
            }
            $this->report(Finding::REWROTE, $tag['start'], __('The `:name` slot is now `:to`.', ['name' => $name, 'to' => $to]), $component[0], $name);

            return;
        }

        $this->report(Finding::MANUAL, $tag['start'], $rule['note'] ?? __('The `:name` slot changed.', ['name' => $name]), $component[0], $name);
    }

    /**
     * How an attribute's value was written, and its PHP expression when bound.
     *
     * @param  array<string, mixed>  $attribute
     *
     * @return array{type: string, value?: string, string?: bool, expression?: string}
     */
    private function value(array $attribute, bool $bound): array
    {
        if (null === $attribute['value']) {
            return ['type' => 'flag'];
        }

        $value = $attribute['value'];

        if (! $bound) {
            return str_contains($value, '{{') || str_contains($value, '{!!')
                ? ['type' => 'dynamic']
                : ['type' => 'literal', 'value' => $value, 'string' => true];
        }

        $expression = trim($value);

        if (1 === preg_match('~^([\'"])((?:(?!\1)[^\\\\$])*)\1$~', $expression, $match)) {
            return ['type' => 'literal', 'value' => $match[2], 'string' => true, 'expression' => $expression];
        }

        if (1 === preg_match('~^(?:true|false|-?\d+(?:\.\d+)?)$~i', $expression)) {
            return ['type' => 'literal', 'value' => strtolower($expression), 'string' => false, 'expression' => $expression];
        }

        return ['type' => 'dynamic', 'expression' => $expression];
    }

    /**
     * Removes an attribute and the whitespace before it.
     *
     * @param  array<string, mixed>  $attribute
     */
    private function remove(array $attribute): void
    {
        $start = $attribute['start'];

        while ($start > 0 && ctype_space($this->source[$start - 1])) {
            $start--;
        }

        $this->edits[] = [$start, $attribute['end'], ''];
    }

    /**
     * Upgrades one opening tag of a library component.
     *
     * @param  array<string, mixed>  $tag
     */
    private function componentTag(array $tag): void
    {
        $component = $this->component($tag['name']);

        if (null === $component) {
            return;
        }

        [$name, $rule] = $component;
        $props         = $rule['props'] ?? [];

        if ($rule['to'] !== $name) {
            $this->edits[] = [$tag['nameStart'], $tag['nameEnd'], UpgradeRules::PREFIX.$rule['to']];
            $this->report(
                Finding::REWROTE,
                $tag['start'],
                __('`x-artisanpack-:name` is now `x-artisanpack-:to`.', ['name' => $name, 'to' => $rule['to']]).(isset($rule['note']) ? ' '.$rule['note'] : ''),
                $name,
            );
        }

        if ($tag['opaque'] && [] !== $props) {
            $this->report(Finding::MANUAL, $tag['start'], __('This tag builds its attributes with Blade. Check them for attributes that changed.'), $name);
        }

        // Every attribute's rule key: `:no-wire-navigate` and `noWireNavigate` are both `noWireNavigate`.
        $parsed = [];

        foreach ($tag['attributes'] as $attribute) {
            $written = $attribute['name'];
            $bound   = str_starts_with($written, ':') && ! str_starts_with($written, '::');
            $base    = $bound ? substr($written, 1) : $written;
            $short   = $bound && str_starts_with($base, '$');
            $base    = $short ? substr($base, 1) : $base;

            // `wire:model`, `x-on:click`, `@click` and `::escaped` are not props.
            if (1 !== preg_match('~^[A-Za-z][A-Za-z0-9_-]*$~', $base)) {
                continue;
            }

            if ($short) {
                $attribute['value'] = '$'.$base;
            }

            $parsed[] = ['key' => UpgradeRules::camel($base), 'written' => $base, 'bound' => $bound, 'short' => $short] + $attribute;
        }

        $taken       = array_fill_keys(array_column($parsed, 'key'), true);
        $partClasses = [];
        $wraps       = [];

        foreach ($parsed as $attribute) {
            $member = $props[$attribute['key']] ?? null;

            if (null === $member) {
                continue;
            }

            $written = $attribute['written'];
            $value   = $this->value($attribute, $attribute['bound']);
            $planned = AttributePlanner::plan($written, $member, $value);
            $plan    = $planned['plan'];
            $prefix  = $attribute['bound'] ? ':' : '';

            if (isset($planned['manual'])) {
                $this->report(Finding::MANUAL, $attribute['start'], $planned['manual'], $name, $written);
            }

            // Never write an attribute the tag already has, or negate a value that isn't an expression.
            $target = isset($plan['to']) ? UpgradeRules::camel($plan['to']) : null;

            if (null !== $target && $target !== $attribute['key'] && isset($taken[$target])) {
                $this->report(Finding::MANUAL, $attribute['start'], __('`:name` is now `:to`, which is already set.', ['name' => $written, 'to' => $plan['to']]), $name, $written);

                continue;
            }

            if ('negate' === $plan['type'] && ! isset($value['expression'])) {
                $this->report(Finding::MANUAL, $attribute['start'], __('`:name` is now `:to`, with the opposite meaning.', ['name' => $written, 'to' => $plan['to']]), $name, $written);

                continue;
            }

            switch ($plan['type']) {
                case 'none':
                    continue 2;

                case 'rename':

                    if ($attribute['short']) {
                        $this->edits[] = [$attribute['start'], $attribute['end'], ':'.$plan['to'].'="$'.$written.'"'];
                    } else {
                        $this->edits[] = [$attribute['start'], $attribute['nameEnd'], $prefix.$plan['to']];

                        if (isset($plan['value'])) {
                            $this->edits[] = [$attribute['valueStart'], $attribute['valueEnd'], $attribute['bound'] ? "'".$plan['value']."'" : $plan['value']];
                        }
                    }

                    break;

                case 'set':
                    $this->edits[] = [$attribute['start'], $attribute['end'], $plan['to'].'="'.$plan['value'].'"'];
                    $plan['to']    = $plan['to'].'="'.$plan['value'].'"';

                    break;

                case 'boolean':
                    $this->edits[] = [$attribute['start'], $attribute['end'], $plan['value'] ? $plan['to'] : ':'.$plan['to'].'="false"'];

                    break;

                case 'number':
                    $this->edits[] = [$attribute['start'], $attribute['end'], ':'.$plan['to'].'="'.$plan['value'].'"'];
                    $plan['to']    = ':'.$plan['to'].'="'.$plan['value'].'"';

                    break;

                case 'negate':
                    $this->edits[] = [$attribute['start'], $attribute['end'], ':'.$plan['to'].'="! ('.$value['expression'].')"'];

                    break;

                case 'remove':
                    $this->remove($attribute);
                    $this->report(Finding::REMOVED, $attribute['start'], __('Removed `:name`.', ['name' => $written]).(isset($member['note']) ? ' '.$member['note'] : ''), $name, $written);

                    continue 2;

                case 'part-class':
                    $partClasses[] = [$attribute, $plan['part'], $value];

                    continue 2;

                case 'wrap':
                    $wraps[] = [$attribute, $member, $value];

                    continue 2;

                case 'content':
                    $this->content($attribute, $member, $tag, $name, UpgradeRules::PREFIX.$rule['to']);

                    continue 2;
            }

            $taken[UpgradeRules::camel(ltrim((string) strtok($plan['to'], '='), ':'))] = true;

            $message = __('`:name` is now `:to`.', ['name' => $written, 'to' => $plan['to']]);
            $this->report(
                isset($planned['review']) ? Finding::REVIEW : Finding::REWROTE,
                $attribute['start'],
                isset($planned['review']) ? $message.' '.$planned['review'] : $message,
                $name,
                $written,
            );
        }

        $this->partClasses($partClasses, $name, isset($taken['classes']));
        $this->wrap($wraps, $tag, $name);

        $added = array_filter(
            $rule['add'] ?? [],
            fn (array $item): bool => ! isset($taken[$item['name']]) && ! (isset($item['unless']) && isset($taken[$item['unless']])),
        );

        if ([] !== $added) {
            $text = '';
            foreach ($added as $item) {
                $text .= ' '.UpgradeRules::kebab($item['name']).(isset($item['value']) ? '="'.$item['value'].'"' : '');
            }

            $this->edits[] = [$tag['nameEnd'], $tag['nameEnd'], $text];
            $this->report(Finding::REWROTE, $tag['start'], __('Added `:attributes`.', ['attributes' => trim($text)]), $name);
        }
    }

    /**
     * Moves an attribute's text into the default slot of a self-closing tag:
     * `<x-artisanpack-button label="Save" />` becomes
     * `<x-artisanpack-button>Save</x-artisanpack-button>`.
     *
     * @param  array<string, mixed>  $attribute
     * @param  array<string, mixed>  $member
     * @param  array<string, mixed>  $tag
     */
    private function content(array $attribute, array $member, array $tag, string $component, string $closing): void
    {
        $text = match (true) {
            null === $attribute['value'] => null,
            $attribute['bound']          => '{{ '.trim($attribute['value']).' }}',
            default                      => $attribute['value'],
        };

        // A literal attribute was escaped when the component echoed it; slot content is not.
        $unsafe = null !== $text && ! $attribute['bound'] && 1 === preg_match('~[<>&]|\{!!~', (string) preg_replace('~\{\{(?!--).*?\}\}~s', '', $text));

        // A tag with a body already has a default slot, so a person merges the two.
        if (null === $text || $unsafe || ! $tag['selfClosing']) {
            $this->report(Finding::MANUAL, $attribute['start'], $member['note'] ?? __('`:name` is now the default slot.', ['name' => $attribute['written']]), $component, $attribute['written']);

            return;
        }

        $start = $tag['end'] - 2;

        while ($start > 0 && ctype_space($this->source[$start - 1])) {
            $start--;
        }

        $this->remove($attribute);
        $this->edits[] = [$start, $tag['end'], '>'.$text.'</x-'.$closing.'>'];
        $this->report(Finding::REWROTE, $attribute['start'], __('`:name` is now the default slot.', ['name' => $attribute['written']]), $component, $attribute['written']);
    }

    /**
     * Merges per-part class attributes into one `:classes` array.
     *
     * @param  array<int, array{0: array<string, mixed>, 1: string, 2: array<string, mixed>}>  $attributes
     */
    private function partClasses(array $attributes, string $component, bool $hasClasses): void
    {
        $entries = [];

        foreach ($attributes as [$attribute, $part, $value]) {
            $written    = $attribute['written'];
            $expression = match (true) {
                'flag' === $value['type']    => null,
                $attribute['bound']          => $value['expression'],
                'literal' === $value['type'] => "'".str_replace(['\\', "'"], ['\\\\', "\\'"], $value['value'])."'",
                default                      => null,
            };

            if ($hasClasses || null === $expression || str_contains($expression, '"')) {
                $this->report(Finding::MANUAL, $attribute['start'], __('`:name` is now the `:part` key of `classes`. Move it by hand.', ['name' => $written, 'part' => $part]), $component, $written);

                continue;
            }

            $entries[] = [$attribute, "'{$part}' => {$expression}"];
            $this->report(Finding::REWROTE, $attribute['start'], __('`:name` is now `classes.:part`.', ['name' => $written, 'part' => $part]), $component, $written);
        }

        foreach ($entries as $index => [$attribute]) {
            if (0 === $index) {
                $this->edits[] = [$attribute['start'], $attribute['end'], ':classes="['.implode(', ', array_column($entries, 1)).']"'];
            } else {
                $this->remove($attribute);
            }
        }
    }

    /**
     * Moves tooltip attributes onto a Tooltip wrapped around the element.
     *
     * @param  array<int, array{0: array<string, mixed>, 1: array<string, mixed>, 2: array<string, mixed>}>  $wraps
     * @param  array<string, mixed>  $tag
     */
    private function wrap(array $wraps, array $tag, string $component): void
    {
        if ([] === $wraps) {
            return;
        }

        $primary = array_filter($wraps, fn (array $wrap): bool => (bool) ($wrap[1]['primary'] ?? false));
        $wrapper = $wraps[0][1]['tag'];

        // Without the tooltip's text or the element's end there is nothing to wrap.
        if ([] === $primary || null === $tag['elementEnd']) {
            foreach ($wraps as [$attribute, $member]) {
                $this->report(Finding::MANUAL, $attribute['start'], $member['note'] ?? __('Wrap the element in `<x-artisanpack-:tag>`.', ['tag' => $wrapper]), $component, $attribute['written']);
            }

            return;
        }

        $attributes = [];

        foreach ($wraps as [$attribute, $member, $value]) {
            if (isset($member['to']) && null !== $attribute['value']) {
                $to   = UpgradeRules::kebab($member['to']);
                $text = $attribute['value'];

                if ('literal' === $value['type'] && isset($member['values'][$value['value']])) {
                    $text = $attribute['bound'] ? "'".$member['values'][$value['value']]."'" : $member['values'][$value['value']];
                }

                $quote           = str_contains($text, '"') ? "'" : '"';
                $attributes[$to] = ($attribute['bound'] ? ':' : '').$to.'='.$quote.$text.$quote;
            }

            foreach ($member['set'] ?? [] as $key => $text) {
                $attributes[$key] ??= UpgradeRules::kebab($key).'="'.$text.'"';
            }

            $this->remove($attribute);
            $this->report(Finding::REWROTE, $attribute['start'], __('`:name` is now a `<x-artisanpack-:tag>` around the element.', ['name' => $attribute['written'], 'tag' => $wrapper]), $component, $attribute['written']);
        }

        $this->edits[] = [$tag['start'], $tag['start'], '<x-'.UpgradeRules::PREFIX.$wrapper.' '.implode(' ', $attributes).'>'];
        $this->edits[] = [$tag['elementEnd'], $tag['elementEnd'], '</x-'.UpgradeRules::PREFIX.$wrapper.'>'];
    }
}
