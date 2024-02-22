@php
    $fieldSelected = [];
    $fieldSelected = data_get($values, $field->field, []);
    $field_limit = data_get($field, 'limit', 1);
    $formIndex = 'values.' . data_get($field, 'field');
@endphp
<div class="relative" wire:init="loadInputOptions('{{ $field->field }}')">
    @if (!@$loaded_options[$field->field])
        @include('supernova-livewire-views::skeleton', ['size' => '38px', 'class' => 'rounded-md'])
    @else
        <div class="relative">
            @if ($field_limit && $field_limit > count($fieldSelected))
                <select
                    wire:change="setSelectOption($event.target.value,'{{ $field->field }}',$event.target.options[$event.target.selectedIndex].text);$event.target.value = '' "
                    class="lock pl-4 pr-10 w-full bg-white rounded-md border font-normal py-1.5 text-gray-900 shadow-sm placeholder:text-gray-400 sm:text-sm sm:leading-6 px-3 dark:bg-gray-800 dark:border-gray-800 dark:text-gray-50  @error($formIndex){{ 'dark:border-red-500' }} @enderror">
                    <option></option>
                    @foreach ($options[$field->field] as $option)
                        @if (!in_array($option['value'], array_column($fieldSelected, 'value')))
                            <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                        @endif
                    @endforeach
                </select>
            @endif
        </div>
        @if (count($fieldSelected) > 0)
            <div class="grid grid-cols-[repeat(auto-fill,minmax(150px,1fr))] gap-1 my-2">
                @foreach ($fieldSelected as $selected)
                    <span
                        class="relative flex items-center inline-flex items-center rounded-md bg-blue-300 border border-blue-400 px-2 py-1 text-xs font-medium text-white ring-1 ring-inset ring-gray-500/10 dark:bg-blue-600">
                        {{ $selected['label'] }}
                        <button type="button"
                            wire:click="removeOption('{{ $field->field }}', '{{ $selected['value'] }}')"
                            class="text-white hover:text-blue-500 dark:hover:text-blue-200 focus:outline-none ml-auto cursor-pointer">
                            <svg class="h-4 w-4 stroke-current" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </button>
                    </span>
                @endforeach
            </div>
        @endif
        @error($formIndex)
            <div class="mt-1 text-sm text-red-500 dark:text-red-400">
                {{ $message }}
            </div>
        @enderror
    @endif
</div>
