@extends(config('supernova.modules_template', 'supernova::templates.default'))
@section('title', $module->title('index'))
@section('content')
    @livewire('supernova::breadcrumb')
    @livewire('supernova::datatable', [
        'module' => $module->id(),
    ])
@endsection
