@extends('supernova::templates.default')
@section('body')
    @livewire('supernova::datatable', [
        'module' => $moduleId,
    ])
@endsection
