@php
    use App\Http\Supernova\Application;
    $novaApp = app()->make(config('supernova.application', Application::class));
@endphp

<html class="{{ $novaApp->darkMode() ? 'dark' : 'ligth' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('supernova::templates.scripts.tailwind')
    <title>{{ $novaApp->title() }} | @yield('title')</title>
    <link rel="icon" type="image/x-icon" href="{{ $novaApp->icon() }}" />
    @include('supernova::templates.styles.styles')
    <style>
        {!! $novaApp->styles() !!}
    </style>
    @yield('head')
    @livewireStyles
    @include('supernova::templates.scripts.vue')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-100 dark:bg-gray-700">
    @livewire('supernova::navbar')
    @yield('body')
    @if (session('quick.alerts'))
        @foreach (session('quick.alerts') as $alert)
            @php
                $class = match (data_get($alert, 'type')) {
                    'success' => 'bg-green-100 border-green-500 text-green-700',
                    'error' => 'bg-red-100 border-red-500 text-red-700',
                    'warning' => 'bg-orange-100 border-orange-500 text-orange-700',
                    'info' => 'bg-blue-100 border-blue-500 text-blue-700',
                    default => 'bg-teal-100 border-teal-500 text-teal-700',
                };
            @endphp
            <div class="{{ $class }} px-4 py-3 shadow-md mb-2 alert-message" role="alert">
                <div class="flex">
                    <p class="text-sm">{{ data_get($alert, 'message') }}</p>
                </div>
            </div>
            </div>
            @php
                session()->forget('quick.alerts');
            @endphp
        @endforeach
    @endif
    <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8 py-4">
        @yield('content')
    </div>
    @yield('footer')
    <script>
        if (document.querySelector('.alert-message')) {
            setTimeout(() => {
                document.querySelector('.alert-message').remove();
            }, 5000);
        }
    </script>
    @livewireScripts
</body>

</html>
