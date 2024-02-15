<div class="flex flex-col items-center justify-center">
    <div class="flex flex-col gap-3 w-full">
        @if ($searchable)
            @include('supernova-livewire-views::datatable.filter')
        @endif
        @include('supernova-livewire-views::datatable.table')
    </div>
</div>
