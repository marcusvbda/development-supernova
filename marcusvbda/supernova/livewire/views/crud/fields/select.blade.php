@php
    $fieldSelected = [];
    $fieldSelected = data_get($values, $field->field, []);
    $field_limit = 1;
@endphp
<div class="relative" wire:init="loadInputOptions('{{ $field->field }}')">
    @if ($field_limit && $field_limit <= count($fieldSelected))
        <div class="absolute inset-0 cursor-not-allowed z-1 bg-black opacity-5 rounded-md"></div>
    @endif
    @if (!@$loaded_options[$field->field])
        @include('supernova-livewire-views::skeleton', ['size' => '38px', 'class' => 'rounded-md'])
    @else
        <select
            class="lock pl-4 pr-10 w-full bg-white rounded-md border font-normal py-1.5 text-gray-900 shadow-sm placeholder:text-gray-400 sm:text-sm sm:leading-6 px-3 dark:bg-gray-800 dark:border-gray-800 dark:text-gray-50">
            <option></option>
            @foreach ($options[$field->field] as $option)
                <option value="{{ data_get($option, 'value') }}">
                    {{ data_get($option, 'label') }}
                </option>
            @endforeach
        </select>
        @if (count($fieldSelected) > 0)
            <div class="grid grid-cols-[repeat(auto-fill,minmax(100px,1fr))] gap-1 my-2">
                @foreach ($fieldSelected as $selected)
                    <span
                        class="relative flex items-center inline-flex items-center rounded-md bg-blue-300 border border-blue-400 px-2 py-1 text-xs font-medium text-white ring-1 ring-inset ring-gray-500/10 dark:bg-blue-600">
                        {{ $selected['label'] }}
                        <button type="button"
                            class="text-white hover:text-blue-500 dark:hover:text-blue-200 focus:outline-none ml-auto cursor-pointer">
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
    @endif
</div>
