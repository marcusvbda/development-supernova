<div class="text-gray-700 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-600 dark:border-gray-700 dark:text-gray-50 overflow-x-auto mb-20 relative"
    wire:loading.class="opacity-50 overflow-x-hidden">
    <div wire:loading>
        <div class="flex items-center justify-center w-full cursor-wait"
            style="position: absolute;inset: 0;background-color: #77777729;z-index=9;display:flex;align-items-center;justify-content:center;z-index: 9;">
            <div class="flex flex-col items-center gap-10 my-20 justify-center">
                <svg class="animate-spin -ml-1 mr-3 h-8 w-8 opacity-30" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4">
                    </circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
            </div>
        </div>
    </div>
    <table class="w-full border-b border-gray-200 dark:border-gray-700">
        <thead>
            <tr class="bg-gray-100 dark:bg-gray-600">
                @php
                    $_sort = $sort;
                    $sort = explode('|', $sort);
                @endphp
                @foreach ($columns as $key => $value)
                    @php
                        $sortable = data_get($value, 'sortable', false);
                        $label = data_get($value, 'label', false);
                        $field = data_get($value, 'name');
                        $minWidth = data_get($value, 'minWidth');
                        $width = data_get($value, 'width');
                        $lastColumn = $key === count($columns) - 1;
                        $showBorder = $hasActions || !$lastColumn;
                        $align = data_get($value, 'align', 'justify-end');
                    @endphp
                    <th class="font-normal @if ($minWidth) min-w-[{{ $minWidth }}] @endif @if ($width) w-[{{ $width }}] @endif p-5 font-medium text-gray-700 @if ($showBorder) border-right border-r border-gray-200 @endif dark:text-gray-200 dark:border-gray-700 @if ($sortable) cursor-pointer @endif"
                        @if ($sortable) wire:click="reloadSort('{{ $field }}','{{ $sort[0] }}','{{ $sort[1] }}')" @endif>
                        <div class="flex items-center gap-5 w-full {{ $align }}">
                            {!! $label !!}
                            @if ($sortable && $sort[0] === $field)
                                <div class="flex gap-3 ml-auto">
                                    @if ($sort[1] === 'desc')
                                        <div class="relative w-[24px] h-[20px]">
                                            <svg class="h-5 w-5 stroke-current h-6 w-6 text-indigo-600 dark:text-indigo-200 stroke-current"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="relative w-[24px] h-[20px] ml-auto">
                                            <svg class="h-5 w-5 stroke-current h-6 w-6 text-indigo-600 dark:text-indigo-200 stroke-current"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </th>
                @endforeach
                @if ($hasActions)
                    <th
                        class="w-[100px] p-5 font-medium text-gray-700 border-right border-r border-gray-200 dark:text-gray-200 dark:border-gray-700">
                        {{-- action here --}}
                    </th>
                @endif
            </tr>
            @if ($filterable)
                <tr class="bg-indigo-100 dark:bg-indigo-300">
                    @foreach ($columns as $key => $value)
                        @php
                            $lastColumn = $key === count($columns) - 1;
                            $showBorder = $hasActions || !$lastColumn;
                            $filterType = data_get($value, 'filter_type');
                            $filterBlade = "supernova-livewire-views::datatable.filters.$filterType";
                            $field = data_get($value, 'name');
                        @endphp
                        <th
                            class="@if ($showBorder) border-r border-indigo-200 dark:border-indigo-500 align-top @endif p-1">
                            @if (View::exists($filterBlade))
                                @include($filterBlade, ['field' => $field])
                            @endif
                        </th>
                    @endforeach
                    @if ($hasActions)
                        <th
                            class="min-w-[100px] p-5 font-medium text-gray-700 border-r border-gray-200 dark:text-gray-200 dark:border-gray-700">
                            {{--  --}}
                        </th>
                    @endif
                </tr>
            @endif
        </thead>
        <tbody>
            @if (!$hasItems)
                <tr class="bg-white dark:bg-gray-500">
                    @php
                        $colspan = count($columns) + ($hasActions ? 1 : 0);
                    @endphp
                    <td class="p-4 px-5 text-right font-light text-gray-600 dark:text-gray-300"
                        colspan="{{ $colspan }}">
                        <div class="w-full flex">
                            @include('supernova-livewire-views::datatable.no-result')
                        </div>
                    </td>
                </tr>
            @else
                @foreach ($itemsPage as $i => $item)
                    <tr class="{{ $i % 2 === 1 ? 'bg-gray-100 dark:bg-gray-600' : 'bg-white dark:bg-gray-500' }}">
                        @foreach ($columns as $key => $value)
                            @php
                                $sortable = data_get($value, 'sortable', false);
                                $label = data_get($value, 'label', false);
                                $field = data_get($value, 'name');
                                $minWidth = data_get($value, 'minWidth', '100px');
                                $width = data_get($value, 'width');
                                $lastColumn = $key === count($columns) - 1;
                                $showBorder = $hasActions || !$lastColumn;
                                $align = data_get($value, 'align', 'justify-end');
                            @endphp
                            <td
                                class="p-4 px-5 text-right font-light text-sm text-gray-600 @if ($showBorder) border-r border-gray-200 dark:border-gray-700 @endif dark:text-gray-300">
                                <div class="w-full flex {{ $align }}">
                                    {!! $item[$field] !!}
                                </div>
                            </td>
                        @endforeach

                        @if ($hasActions)
                            <td class="p-4 px-5 text-right font-light text-gray-600 dark:text-gray-300">
                                <div class="inline-flex rounded-md shadow-sm" role="group">

                                    <button type="button" @if (!data_get($item, 'canEdit', false)) disabled @endif
                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white disabled:opacity-20 disabled:cursor-not-allowed">
                                        <div
                                            class="w-[15px] h-[15px] relative stroke-indigo-800 dark:stroke-indigo-300">
                                            <svg viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M21.2799 6.40005L11.7399 15.94C10.7899 16.89 7.96987 17.33 7.33987 16.7C6.70987 16.07 7.13987 13.25 8.08987 12.3L17.6399 2.75002C17.8754 2.49308 18.1605 2.28654 18.4781 2.14284C18.7956 1.99914 19.139 1.92124 19.4875 1.9139C19.8359 1.90657 20.1823 1.96991 20.5056 2.10012C20.8289 2.23033 21.1225 2.42473 21.3686 2.67153C21.6147 2.91833 21.8083 3.21243 21.9376 3.53609C22.0669 3.85976 22.1294 4.20626 22.1211 4.55471C22.1128 4.90316 22.0339 5.24635 21.8894 5.5635C21.7448 5.88065 21.5375 6.16524 21.2799 6.40005V6.40005Z"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path
                                                    d="M11 4H6C4.93913 4 3.92178 4.42142 3.17163 5.17157C2.42149 5.92172 2 6.93913 2 8V18C2 19.0609 2.42149 20.0783 3.17163 20.8284C3.92178 21.5786 4.93913 22 6 22H17C19.21 22 20 20.2 20 18V13"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                    </button>

                                    <button type="button" @if (!data_get($item, 'canDelete', false)) disabled @endif
                                        wire:click="deleteItem('{{ $item['id'] }}')"
                                        wire:confirm="Excluir este registro ?"
                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white disabled:opacity-20 disabled:cursor-not-allowed">
                                        <div class="w-[15px] h-[15px] relative stroke-red-700 dark:stroke-red-400">
                                            <svg viewBox="0 0 24 24" fill="none">
                                                <path d="M10 12V17" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M14 12V17" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M4 7H20" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M6 10V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18V10"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                <path
                                                    d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7H9V5Z"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    @include('supernova-livewire-views::datatable.pagination')
</div>
