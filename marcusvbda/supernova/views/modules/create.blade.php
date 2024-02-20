@php
    $title = $module->title('create');
@endphp
@extends(config('supernova.modules_template', 'supernova::templates.default'))
@section('title', $title)
@section('content')
    @livewire('supernova::breadcrumb', [
        'entityUrl' => route('supernova.modules.create', ['module' => $module->id()]),
    ])
    <section class="flex flex-col">
        @livewire('supernova::crud', [
            'module' => $module->id(),
            'lazy' => true,
        ])
    </section>
@endsection
