<div class="flex flex-col items-center justify-center">
    @if (!$hasResults)
        @include('supernova-livewire-views::datatable.no-result')
    @else
        <div class="flex flex-col gap-3 w-full">
            @if ($searchable)
                @include('supernova-livewire-views::datatable.filter')
            @endif
            @include('supernova-livewire-views::datatable.table')
        </div>
    @endif
</div>
