@php
    $icon = $module->icon();
@endphp
@extends(config('supernova.modules_template', 'supernova::templates.default'))
@section('title', $module->title('index'))
@section('content')
    @livewire('supernova::breadcrumb')
    <section class="flex flex-col">
        <h4 class="text-3xl text-neutral-800 font-bold dark:text-neutral-200 mt-5 mb-8 flex items-center gap-3">
            @if ($icon)
                <div class="w-[30px] h-[30px] dark:stroke-white stroke-neutral-800">
                    {!! $icon !!}
                </div>
            @endif
            {{ $module->title('index') }}
        </h4>

        @livewire('supernova::datatable', [
            'module' => $module->id(),
            'sort' => $module->defaultSort(),
        ])
    </section>
@endsection
