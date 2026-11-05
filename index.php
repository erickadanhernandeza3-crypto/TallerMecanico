<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taller Mecánico CHECO - Sistema Profesional</title>

    <!-- Frameworks y Estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f4f7f6;
        scroll-behavior: smooth;
    }

    /* NAVBAR cristalina */
    .navbar {
        background-color: rgba(33, 37, 41, 0.95) !important;
        backdrop-filter: blur(12px);
        border-bottom: 2px solid #0d6efd;
    }

    /* HERO: Ajustado para ser el fondo perfecto */
   .hero-section, #hero-inicio {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.8)), url('Taller01.jpg') !important;
        background-size: cover !important;
        background-position: center !important;
        background-attachment: fixed !important;
        min-height: 500px !important; 
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
    }

    .hero-section h1 {
        font-size: 3.5rem;
        font-weight: 900;
        text-shadow: 3px 3px 10px rgba(0,0,0,0.8);
    }

    /* ESCENARIO: Sin márgenes locos, solo flujo natural */
 #escenario-principal {
    padding-top: 20px; /* Controla qué tan cerca está del Hero arriba */
    padding-bottom: 80px; /* Crea un colchón de espacio antes del footer negro */
    background-color: #f4f7f6; /* Fondo gris claro para que la tarjeta blanca resalte */
}
    /* LA CAJA MÁGICA: Esto es lo que va a contener tus vistas */
    .contenedor-vista {
    background: white;
    border-radius: 25px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    padding: 50px 30px;
    margin-top: -40px; /* Ajusta esto para subir o bajar la tarjeta respecto al Hero */
    margin-bottom: 20px; /* Separa la tarjeta del final del escenario */
}   

    footer {
        background: #111;
        padding: 40px 0;
        border-top: 4px solid #0d6efd;
    }
</style>
</head>
<body onload="cargarInicio()">

<nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="bi bi-gear-wide-connected text-primary"></i> CHECO <span class="text-primary">Mecánico</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="navegar('cli_inicio')">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="navegar('cli_servicios')">Servicios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="navegar('cli_somos')">Sobre Nosotros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="navegar('cli_mision')">Nuestra Filosofía</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="navegar('cli_consulta')">
                        <i class="bi bi-search small"></i> Consultar Placa
                    </a>
                </li>
                <li class="nav-item ms-lg-3">
                    <a class="btn btn-primary rounded-pill px-4" href="Login.php">
                        <i class="bi bi-person-circle"></i> Acceso General
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- SECCIÓN HERO (Banner Principal) -->
<header id="escenario-principa" class="hero-section animate__animated animate__fadeIn">
    <div class="container">
        <h1 class="display-2 fw-bold">Taller Mecánico <span class="text-primary">"CHECO"</span></h1>
        <p class="lead mb-5">Calidad, confianza y tecnología para tu vehículo en la Huasteca</p>
        
        <div class="d-flex justify-content-center gap-3">
            <button class="btn btn-primary btn-hero shadow" onclick="navegar('cli_servicios')">
                <i class="bi bi-tools me-2"></i> Nuestros Servicios
            </button>
            <button class="btn btn-outline-light btn-hero" onclick="navegar('cli_consulta')">
                <i class="bi bi-search me-2"></i> Rastrear Auto
            </button>
        </div>
    </div>
</header>

<!-- En tu index.php busca el main -->
<main id="escenario-principal" class="container">
    <!-- Este es el que falta o tiene otro nombre -->
    <div id="contenedor"> 
        <!-- Aquí se cargará todo al dar clic -->
    </div>
</main>
<footer class="py-5 mt-auto">
    <div class="container text-center">
        <div class="mb-4">
            <i class="bi bi-gear-wide-connected fs-1 text-primary"></i>
        </div>
        <p class="mb-1 text-white">© 2026 Taller Mecánico Sergio Hernández "CHECO"</p>
        <p class="small">Tamazunchale, S.L.P. | Servicio Profesional Multimarca</p>
        <div class="mt-3">
            <a href="Login.php" class="text-decoration-none text-muted small">Panel Administrativo</a>
        </div>
    </div>
</footer>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="funciones.js"></script>

<script>
    // 1. Carga la bienvenida por defecto al abrir la página por primera vez
    function cargarInicio() {
        // Aseguramos que el encabezado con la imagen del taller esté visible al entrar
        $('#hero-inicio').show(); 
        // Llamamos al caso 'cli_inicio' que definimos en el Controlador
        buscar('cli_inicio', 'escenario-principal', 'ver', 0, 0);
    }

    // 2. Función maestra para navegar por el sitio sin recargar la página
    function navegar(opcion) {
        let hero = $('#hero-inicio');
        
        // LÓGICA DE VISIBILIDAD DEL HEADER
        if (opcion === 'cli_inicio') {
            // Si el usuario regresa al inicio, deslizamos el banner hacia abajo
            hero.slideDown(500); 
        } else {
            // Si elige 'Servicios', 'Sobre Nosotros', etc., ocultamos el banner
            // para que el contenido suba y se vea de inmediato.
            hero.slideUp(500); 
        }

        // PETICIÓN AJAX
        // 'opcion' es el caso del switch en Controlador.php
        // 'escenario-principal' es el ID del <main> en tu index.php
        buscar(opcion, 'escenario-principal', 'ver', 0, 0);
        
        // UX: Cierra automáticamente el menú desplegable en celulares después de dar clic
        if ($('.navbar-collapse').hasClass('show')) {
            $('.navbar-collapse').collapse('hide');
        }
    }
</script>

</body>
</html>