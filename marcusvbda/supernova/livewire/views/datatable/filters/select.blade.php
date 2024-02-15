@livewire('supernova::select-field', [
    'field' => $field,
    'onchange' => "filters[$field]:changed",
    'multiple' => true,
])
@php
    $selectedOptions = data_get($filters, $field, []);
@endphp
@if (count($selectedOptions) > 0)
    <div class="grid grid-cols-[repeat(auto-fill,minmax(100px,1fr))] gap-1 my-2">
        @foreach ($selectedOptions as $selected)
            <span
                class="relative flex items-center inline-flex items-center rounded-md bg-indigo-300 border border-indigo-400 px-2 py-1 text-xs font-medium text-white ring-1 ring-inset ring-gray-500/10 dark:bg-indigo-600">
                {{ $selected['label'] }}
                <button wire:click="removeFilter('{{ $field }}', '{{ $selected['value'] }}')" type="button"
                    class="text-white hover:text-indigo-500 dark:hover:text-indigo-200 focus:outline-none ml-auto cursor-pointer">
                    <svg class="h-4 w-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </button>
            </span>
        @endforeach
    </div>
@endif
