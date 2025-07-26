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