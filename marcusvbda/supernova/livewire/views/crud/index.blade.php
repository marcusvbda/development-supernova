@php
    use App\Http\Supernova\Application;
    $application = app()->make(config('supernova.application', Application::class));
    $appModule = $application->getModule($module);
    $panels = $appModule->getVisibleFieldPanels('Cadastro de');
@endphp
<div class="flex flex-col pb-10">
    @foreach ($panels as $key => $panel)
        <h4
            class="text-2xl md:text-3xl text-neutral-800 font-bold dark:text-neutral-200 flex items-center gap-3 gap-2 md:gap-3 mt-6 mb-2">
            <span class="order-2 md:order-1">{{ data_get($panel, 'label') }}</span>
        </h4>
        @php
            $fields = data_get($panel, 'fields', []);
        @endphp
        <div
            class="flex flex-col justify-between text-gray-700 border border-gray-200 rounded-lg sm:flex bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
            @foreach ($fields as $fieldIndex => $field)
                <div
                    class="w-full flex flex-col md:flex-row gap-1 md:gap-0 items-center px-4 p-5 md:px-6 @if ($fieldIndex !== count($fields) - 1) border-b-2 border-gray-100 dark:border-gray-700 @endif">
                    <label class="w-full md:w-3/12">
                        <h4 class="text-md text-gray-500 dark:text-gray-400">
                            {{ $field->label }}
                        </h4>
                    </label>
                    <div class="w-full md:w-9/12 text-gray-600 dark:text-gray-300">
                        FIELD
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>
