<div class="flex flex-col items-center justify-center">
    <div class="flex flex-col gap-3 w-full">
        <div class="flex flex-row flex-wrap gap-3">
            @include('supernova-livewire-views::datatable.filter')
            <div class="ml-auto">
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded">
                    Button
                </button>
            </div>
        </div>
        @include('supernova-livewire-views::datatable.table')
    </div>
</div>
