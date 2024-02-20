@extends(config('supernova.modules_template', 'supernova::templates.default'))
@section('title', $module->title('index'))
@section('content')
    @livewire('supernova::breadcrumb')
    @php
        $metricCards = $module->metrics();
    @endphp
    @if (count($metricCards))
        <div class="grid lg:grid-cols-3 md:grid-cols-3 gap-3 mt-5 mb-3">
            {!! Blade::render(implode(' ', $metricCards)) !!}
        </div>
    @endif
    <section class="flex flex-col">
        <h4 class="text-3xl text-neutral-800 font-bold dark:text-neutral-200 mt-3 mb-2 flex items-center gap-3 mt-6">
            {{ $module->title('index') }}
        </h4>
        @livewire('supernova::datatable', [
            'module' => $module->id(),
            'sort' => $module->defaultSort(),
            'lazy' => true,
        ])
    </section>
@endsection
