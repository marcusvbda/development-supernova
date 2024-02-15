<div wire:init="loadData">
    @if ($loaded)
        <select wire:model.live="value"
            class="lock pl-4 pr-10 w-full bg-white rounded-md border font-normal py-1.5 text-gray-900 shadow-sm placeholder:text-gray-400 sm:text-sm sm:leading-6 px-3 dark:bg-gray-800 dark:border-gray-800 dark:text-gray-50">
            @foreach ($options as $option)
                <option value="{{ data_get($option, 'value') }}">
                    {{ data_get($option, 'label') }}
                </option>
            @endforeach
        </select>
    @else
        @include('supernova-livewire-views::skeleton', ['size' => '38px', 'class' => 'rounded-md'])
    @endif

</div>
