<div
    class="text-gray-700 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-600 dark:border-gray-700 dark:text-gray-50 overflow-x-auto mb-20">
    <table class="w-full border-b border-gray-200 dark:border-gray-700">
        <thead>
            <tr class="bg-gray-100 dark:bg-gray-600">
                <th
                    class="min-w-[100px] p-5 font-medium text-gray-700 border-right border-r border-gray-200 dark:text-gray-200 dark:border-gray-700 cursor-pointer">
                    <div class="flex items-center gap-5 justify-end">
                        #
                        <div class="flex gap-3">
                            @if (true)
                                <div class="relative w-[24px] h-[20px]">
                                    <svg class="h-5 w-5 stroke-current h-6 w-6 text-indigo-600 dark:text-indigo-200 stroke-current"
                                        wire:loading.remove="wire:loading.remove" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            @else
                                <div class="relative w-[24px] h-[20px]">
                                    <svg class="h-5 w-5 stroke-current h-6 w-6 text-indigo-600 dark:text-indigo-200 stroke-current"
                                        wire:loading.remove="wire:loading.remove" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 15l7-7 7 7"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>
                </th>
                <th
                    class="min-w-[100px] p-5 text-right font-medium text-gray-700 border-r border-gray-200 dark:text-gray-200 dark:border-gray-700">
                    Nome
                </th>
                <th
                    class="min-w-[100px] p-5 text-right font-medium text-gray-700 border-r border-gray-200 dark:text-gray-200 dark:border-gray-700">
                    Criado em
                </th>
                <th class="w-[100px] p-5 text-right font-medium text-gray-700">
                    {{--  --}}
                </th>
            </tr>
        </thead>
        <thead>
            <tr class="bg-indigo-100 dark:bg-indigo-300">
                <th class="border-right border-r border-indigo-200 dark:border-indigo-400 p-5">
                    {{-- FILTER --}}
                </th>
                <th class="border-right border-r border-indigo-200 dark:border-indigo-400 p-5">
                    {{-- FILTER --}}
                </th>
                <th class="border-right border-r border-indigo-200 dark:border-indigo-400 p-5">
                    {{-- FILTER --}}
                </th>
                <th class="p-5">
                    {{-- FILTER --}}
                </th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 0; $i < 10; $i++)
                <tr class="{{ $i % 2 === 1 ? 'bg-gray-100 dark:bg-gray-600' : 'bg-white dark:bg-gray-500' }}">
                    <td
                        class="p-4 px-5 text-right font-light text-gray-600 border-r border-gray-200 dark:border-gray-700 dark:text-gray-300">
                        200
                    </td>
                    <td
                        class="p-4 px-5 text-right font-light text-gray-600 border-r border-gray-200 dark:border-gray-700 dark:text-gray-300">
                        Teste
                    </td>
                    <td
                        class="p-4 px-5 text-right font-light text-gray-600 border-r border-gray-200 dark:border-gray-700 dark:text-gray-300">
                        20/04/2023
                    </td>
                    <td class="p-4 px-5 text-right font-light text-gray-600 dark:text-gray-300">
                        <div class="inline-flex rounded-md shadow-sm" role="group">
                            <button type="button"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                                <div class="w-[15px] h-[15px] relative stroke-indigo-800 dark:stroke-indigo-300">
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
                            <button type="button"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                                <div class="w-[15px] h-[15px] relative stroke-red-700 dark:stroke-red-400">
                                    <svg viewBox="0 0 24 24" fill="none">
                                        <path d="M10 12V17" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M14 12V17" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M4 7H20" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M6 10V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18V10"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7H9V5Z"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                        </div>

                    </td>
                </tr>
            @endfor
        </tbody>
    </table>
    @include('supernova-livewire-views::datatable.pagination')
</div>
