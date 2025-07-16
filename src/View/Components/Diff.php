<?php
/**
 * Diff
 *
 * This file contains the Diff class for the ArtisanPack UI Livewire UI Components package.
 *
 * @package    ArtisanPack\LivewireUiComponents\View
 * @subpackage Components
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 * @link       https://github.com/robsontenorio/mary Original MaryUI Repository
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */


namespace ArtisanPack\LivewireUiComponents\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Jfcherng\Diff\DiffHelper;
/**
 * Diff Class
 *
 * Provides functionality for the Diff component.
 *
 * @since 1.0.0
 */

class Diff extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public string $old = '',
        public string $new = '',
        public string $fileName = 'payload.json',
        public ?array $config = []
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
    }

    public function setup(): string
    {
        return json_encode(array_merge([
            'drawFileList' => false,
            'matching' => 'lines',
            'outputFormat' => 'side-by-side',
            'synchronisedScroll' => true,
            'fileContentToggle' => false,
        ], $this->config));
    }

    public function diff(): string
    {
        $diff = DiffHelper::calculate($this->old . PHP_EOL, $this->new . PHP_EOL);

        return "--- {$this->fileName}\n+++ {$this->fileName}\n" . $diff;
    }

    public function render(): View|Closure|string
    {
        return <<<'HTML'
            <div
                x-data="{
                        init(){
                           var diff = new Diff2HtmlUI($refs.diff{{ $uuid }}, `{{ $diff() }}`, {{ $setup() }});
                           diff.draw();
                        }
                }"
             >
                <div x-ref="diff{{ $uuid }}" class="[&_.d2h-diff-table]:!text-xs [&_.d2h-file-header]:!bg-base-100 [&_.d2h-file-wrapper]:!border-dashed [&_.d2h-file-wrapper]:!border-[length:var(--border)] [&_.d2h-file-wrapper]:!bg-base-100 [&_.d2h-del]:!bg-red-50 [&_.d2h-ins]:!bg-green-50 [&_.d2h-code-line-ctn]:!whitespace-pre-wrap [&_.d2h-code-side-line]:!w-auto">
                </div>
            </div>
        HTML;
    }
}
