<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Archivos locales -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>

<body>
    <div id="app" class="d-flex">

        {{-- SIDEBAR IZQUIERDA --}}
        @auth
        <div class="d-flex flex-column justify-content-between border-end bg-dark text-white"
            id="sidebar-wrapper" style="min-height: 100vh; width: 230px;">

            {{-- Encabezado --}}
            <div>
                <div class="sidebar-heading text-center py-4 fs-5 border-bottom bg-primary">
                    <strong>Sistema Cotización</strong>
                </div>
                @if (Auth::user()->rol == 1)
                {{-- Opciones principales --}}
                <div class="list-group list-group-flush my-3">
                    <a href="{{ route('usuarios.index') }}" class="list-group-item list-group-item-action bg-dark text-white border-0 py-2">
                        <i class="bi bi-people"></i> Usuarios
                    </a>
                    <a href="{{ route('clientes.index') }}" class="list-group-item list-group-item-action bg-dark text-white border-0 py-2">
                        <i class="bi bi-person"></i> Clientes
                    </a>
                    <a href="{{ route('productos.index') }}" class="list-group-item list-group-item-action bg-dark text-white border-0 py-2">
                        <i class="bi bi-box"></i> Productos
                    </a>
                </div>
                @elseif (Auth::user()->rol == 2)
                {{-- Opciones principales --}}
                <div class="list-group list-group-flush my-3">
                    <a href="{{ route('cotizaciones.index') }}" class="list-group-item list-group-item-action bg-dark text-white border-0 py-2">
                        <i class="bi bi-people"></i> Cotizaciones
                    </a>
                </div>
                @endif
            </div>

            {{-- Cerrar sesión (anclado al fondo) --}}
            <div class="p-3 border-top">
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="list-group-item list-group-item-action bg-dark text-white border-0 py-2">
                    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
        @endauth

        {{-- CONTENIDO PRINCIPAL --}}
        <div id="page-content-wrapper" class="flex-grow-1">
            <nav class="navbar navbar-expand-md navbar-light shadow-sm navbar-custom">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto"></ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ms-auto">
                            <!-- Authentication Links -->
                            @guest
                            @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Iniciar Sesión') }}</a>
                            </li>
                            @endif

                            @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Registrate') }}</a>
                            </li>
                            @endif
                            @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle text-dark fw-semibold"
                                    href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="bi bi-person-circle"></i>
                                    {{ Auth::user()->nombre ?? Auth::user()->correo }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('perfil.edit') }}">
                                        <i class="bi bi-person-badge"></i> Editar mi perfil
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="py-4 px-4">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>