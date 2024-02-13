@extends(config('supernova.modules_template', 'supernova::templates.default'))
@section('title', 'Dashboard')
@section('content')
    @livewire('supernova::breadcrumb')
@endsection
