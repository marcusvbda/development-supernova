@php
    $title = $module->title('details');
@endphp
@extends(config('supernova.modules_template', 'supernova::templates.default'))
@section('title', $title)
@section('content')
    @livewire('supernova::breadcrumb', [
        'entityUrl' => route('supernova.modules.details', ['module' => $module->id(), 'id' => $entity->id]),
        'entityId' => $entity->id,
    ])
    <section class="flex flex-col">
        @livewire('supernova::details', [
            'module' => $module->id(),
            'entity' => $entity,
            'lazy' => true,
        ])
    </section>
@endsection
