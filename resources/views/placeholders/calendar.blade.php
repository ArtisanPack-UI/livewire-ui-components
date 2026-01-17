{{--
    Calendar Placeholder View

    This placeholder is displayed while the Calendar component is lazy loading.
    It provides a skeleton UI that matches the calendar's structure.

    @since 2.0.0
--}}

<div class="w-full animate-pulse">
    <div class="bg-white dark:bg-base-200 border border-stroke dark:border-base-300 rounded-lg p-4">
        {{-- Header Skeleton --}}
        <div class="mb-[30px] flex items-center justify-between rounded-lg border border-stroke dark:border-base-300 bg-gray-2 dark:bg-base-200 py-3 pr-4 pl-[30px]">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-gray-200 dark:bg-base-300 rounded-md"></div>
                <div class="w-8 h-8 bg-gray-200 dark:bg-base-300 rounded-md"></div>
                <div class="w-16 h-8 bg-gray-200 dark:bg-base-300 rounded-md"></div>
            </div>
            <div class="w-40 h-6 bg-gray-200 dark:bg-base-300 rounded"></div>
            <div class="w-40 h-10 bg-gray-200 dark:bg-base-300 rounded"></div>
        </div>

        {{-- Calendar Grid Skeleton --}}
        <div class="w-full max-w-full bg-white dark:bg-neutral overflow-x-auto rounded-lg shadow-sm">
            <table class="w-full">
                <thead>
                    <tr class="rounded-t-lg bg-gray-200 dark:bg-base-300">
                        @for ($i = 0; $i < 7; $i++)
                            <th class="h-[60px] w-10 p-2 lg:w-28 2xl:w-40 {{ 0 === $i ? 'rounded-tl-lg' : '' }} {{ 6 === $i ? 'rounded-tr-lg' : '' }}">
                                <div class="w-full h-4 bg-gray-300 dark:bg-base-100 rounded mx-auto max-w-[80px]"></div>
                            </th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @for ($week = 0; $week < 5; $week++)
                        <tr>
                            @for ($day = 0; $day < 7; $day++)
                                <td class="ease relative h-[100px] w-10 cursor-pointer border border-stroke dark:border-base-300 p-2 transition duration-500 lg:w-28 2xl:w-40 hover:bg-gray dark:hover:bg-base-200">
                                    <div class="w-6 h-4 bg-gray-200 dark:bg-base-300 rounded mb-2"></div>
                                    @if ($week % 2 === 0 && $day % 3 === 0)
                                        <div class="w-full h-3 bg-gray-200 dark:bg-base-300 rounded mb-1"></div>
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
