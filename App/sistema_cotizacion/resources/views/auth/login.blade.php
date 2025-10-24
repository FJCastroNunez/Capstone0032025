<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Estilos personalizados --}}
    <style>
        body {
            margin: 0;
            height: 100vh;
            background: url("{{ asset('images/fondo-login.jpg') }}") no-repeat center center fixed;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            /* Mueve el contenido a la derecha */
            font-family: 'Poppins', sans-serif;
        }

        .login-card {
            width: 340px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            margin-right: 8%;
            border-radius: 15px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.3);
        }

        .login-card h3 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: 600;
            color: #333;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-primary {
            width: 100%;
            border-radius: 10px;
            background-color: #007bff;
            border: none;
            font-weight: 500;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .text-muted {
            text-align: center;
            font-size: 0.9em;
        }

        /* Eliminar barra superior de Laravel Breeze/Jetstream */
        nav.navbar,
        header {
            display: none !important;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <h3>Iniciar Sesión</h3>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Correo --}}
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input id="email" type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>

            {{-- Contraseña --}}
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input id="password" type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    name="password" required>
                @error('password')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>

            {{-- Botón --}}
            <button type="submit" class="btn btn-primary">
                Ingresar
            </button>


        </form>
    </div>

</body>

</html>