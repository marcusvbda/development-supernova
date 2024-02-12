<html>

<head>
    @yield('head')
    @vite('resources/scss/app.scss')
    @livewireStyles
</head>

<body>
    @livewire('supernova::navbar')
    @yield('body')
    @yield('content')
    @yield('footer')
    @livewireScripts
</body>

</html>
