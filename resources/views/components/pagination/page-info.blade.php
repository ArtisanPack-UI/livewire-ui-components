{{-- Page information component --}}
@if($rows instanceof LengthAwarePaginator && $rows->total() > 0)
<div class="pagination-page-info flex justify-center md:justify-end items-center mt-4 text-sm text-base-content/70">
    @php
        $from = $rows->firstItem();
        $to = $rows->lastItem();
        $total = $rows->total();
        
        // Replace template placeholders with actual values
        $pageInfo = str_replace(['{from}', '{to}', '{total}', '{current}', '{last}'], 
                               [$from, $to, $total, $rows->currentPage(), $rows->lastPage()], 
                               $pageInfoTemplate);
    @endphp
    
    <span class="px-3 py-1 bg-base-200 rounded-md">
        {{ $pageInfo }}
    </span>
</div>
@endif