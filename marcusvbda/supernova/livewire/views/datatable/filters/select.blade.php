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
                class="inline-flex items-center rounded-md bg-indigo-300 border border-indigo-400 px-2 py-1 text-xs font-medium text-white ring-1 ring-inset ring-gray-500/10 cursor-pointer">
                {{ $selected['label'] }}
            </span>
        @endforeach
    </div>
@endif
