<html>

<head>
    @yield('head')
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
