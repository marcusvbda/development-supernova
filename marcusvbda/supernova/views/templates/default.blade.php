@php
    use App\Http\Supernova\Application;
    $novaApp = app()->make(config('supernova.application', Application::class));
@endphp

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('supernova::templates.scripts.tailwind')
    <title>@yield('title') - {{ $novaApp->title() }}</title>
    <link rel="icon" type="image/x-icon" href="{{ $novaApp->icon() }}" />
    <style>
        {!! $novaApp->styles() !!}
    </style>
    @yield('head')
    @livewireStyles
    @include('supernova::templates.scripts.vue')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    @livewire('supernova::navbar')
    @yield('body')
    @yield('content')
    @yield('footer')
    @livewireScripts
</body>

</html>
