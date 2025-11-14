<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vidriería Verónica | Sistema de Cotizaciones</title>

    {{-- Bootstrap y Animate --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f8fb;
            color: #333;
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)),
            url('{{ asset("images/fondo-login.jpg") }}') center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
        }

        .hero p {
            font-size: 1.3rem;
            margin-top: 15px;
        }

        .btn-custom {
            background-color: #007bff;
            color: white;
            padding: 12px 25px;
            border-radius: 30px;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-custom:hover {
            background-color: #0056b3;
        }

        /* Secciones */
        section {
            padding: 80px 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-weight: 700;
            color: #007bff;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-10px);
        }

        footer {
            background-color: #0a0f1f;
            color: white;
            padding: 40px 0;
        }

        footer a {
            color: #66b3ff;
            text-decoration: none;
        }

        footer a:hover {
            color: white;
        }
    </style>
</head>

<body>

    {{-- HERO SECTION --}}
    <div class="hero animate__animated animate__fadeIn">
        <div>
            <h1 class="animate__animated animate__fadeInDown">Vidriería Verónica</h1>
            <p class="animate__animated animate__fadeInUp">
                Soluciones en vidrio y aluminio con precisión, calidad y confianza.
            </p>
            <a href="{{ route('login') }}" class="btn-custom mt-4">Acceder al Sistema</a>
            <a href="{{ route('cotizaciones.visita') }}" class="btn-custom mt-4">Cotiza con nosotros</a>
        </div>
    </div>

    {{-- NOSOTROS --}}
    <section id="nosotros">
        <div class="container">
            <div class="section-title">
                <h2>Nosotros</h2>
                <p>Más de 15 años de experiencia ofreciendo productos de primera calidad en vidrios y estructuras de aluminio.</p>
            </div>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="{{ asset('images/vidrieria1.jpg') }}" class="img-fluid rounded shadow">
                </div>
                <div class="col-md-6">
                    <p>
                        En <strong>Vidriería Verónica</strong> nos especializamos en cortes a medida, instalación de ventanales, mamparas y espejos decorativos.
                        Nuestro compromiso es la satisfacción total de nuestros clientes, trabajando con materiales de excelencia y atención personalizada.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- SERVICIOS --}}
    <section id="servicios" style="background-color:#e9f2fa;">
        <div class="container">
            <div class="section-title">
                <h2>Servicios</h2>
            </div>
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="card p-4">
                        <i class="bi bi-window fs-1 text-primary"></i>
                        <h4 class="mt-3">Ventanas y Mamparas</h4>
                        <p>Fabricación e instalación con medidas personalizadas y materiales de alta durabilidad.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card p-4">
                        <i class="bi bi-droplet-half fs-1 text-primary"></i>
                        <h4 class="mt-3">Vidrios Templados</h4>
                        <p>Ideal para baños, oficinas y fachadas modernas, con estilo y resistencia superior.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card p-4">
                        <i class="bi bi-brush fs-1 text-primary"></i>
                        <h4 class="mt-3">Espejos Decorativos</h4>
                        <p>Diseños modernos y elegantes que aportan amplitud y estética a cualquier espacio.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTACTO --}}
    <section id="contacto">
        <div class="container text-center">
            <div class="section-title">
                <h2>Contáctanos</h2>
                <p>Solicita tu cotización o visítanos en nuestro local.</p>
            </div>
            <p><i class="bi bi-geo-alt"></i> Quilicura, Santiago, Chile</p>
            <p><i class="bi bi-envelope"></i> contacto@vidrieriaveronica.cl</p>
            <p><i class="bi bi-telephone"></i> +56 9 1234 5678</p>
            <a href="{{ route('login') }}" class="btn btn-primary mt-3">Acceder al Sistema</a>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="text-center">
        <div class="container">
            <p>© {{ date('Y') }} Vidriería Verónica — Todos los derechos reservados.</p>
            <p><a href="#nosotros">Nosotros</a> | <a href="#servicios">Servicios</a> | <a href="#contacto">Contacto</a></p>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>