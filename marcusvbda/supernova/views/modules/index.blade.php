@extends(config('supernova.modules_template', 'supernova::templates.default'))
@section('content')
    @livewire('supernova::datatable', [
        'module' => $moduleId,
    ])
@endsection
