<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: Login.php");
    exit();
}
$nombreAdmin  = $_SESSION['nombre'] ?? 'Administrador';
$inicialAdmin = strtoupper(mb_substr($nombreAdmin, 0, 1));
$meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
          'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
$fechaActual  = date('d') . ' de ' . $meses[(int)date('n') - 1] . ' de ' . date('Y');
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel Admin - Taller CHECO</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
    /* ================================================================
       VARIABLES
    ================================================================ */
    :root {
        --sb-bg:        #1e2a3a;
        --sb-hover:     #2d3f53;
        --accent:       #3b82f6;
        --content-bg:   #f0f4f8;
        --sb-text:      #cbd5e1;
        --sb-active:    #ffffff;
        --sb-width:     260px;
        --hdr-h:        60px;
        --card-shadow:  0 2px 8px rgba(0, 0, 0, .08);
    }

    *, *::before, *::after { box-sizing: border-box; }
    body { margin: 0; background: var(--content-bg); }

    /* ================================================================
       LAYOUT WRAPPER
    ================================================================ */
    .wrapper { display: flex; min-height: 100vh; }

    /* ================================================================
       SIDEBAR
    ================================================================ */
    #sidebar {
        width: var(--sb-width);
        flex-shrink: 0;
        background: var(--sb-bg);
        display: flex;
        flex-direction: column;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        z-index: 1050;
        overflow-y: auto;
        overflow-x: hidden;
        transition: transform .3s ease;
        scrollbar-width: thin;
        scrollbar-color: #2d3f53 #1e2a3a;
    }

    /* Brand */
    .sb-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 22px 18px 20px;
        flex-shrink: 0;
    }
    .sb-brand-icon {
        font-size: 2rem;
        color: var(--accent);
        line-height: 1;
        flex-shrink: 0;
    }
    .sb-brand-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #fff;
        letter-spacing: .5px;
        line-height: 1.25;
    }
    .sb-brand-sub {
        font-size: .68rem;
        color: var(--sb-text);
        letter-spacing: .5px;
        text-transform: uppercase;
    }
    .sb-hr { border-color: rgba(255, 255, 255, .07); margin: 0; }

    /* Nav area */
    .sb-nav { padding: 10px 8px; flex-grow: 1; }

    /* Every clickable link in the sidebar */
    .sb-link {
        color: var(--sb-text);
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 13px;
        border-radius: 8px;
        border-left: 3px solid transparent;
        font-size: .875rem;
        text-decoration: none;
        transition: all .2s ease;
        white-space: nowrap;
        cursor: pointer;
        margin-bottom: 2px;
        user-select: none;
    }
    .sb-link .link-icon { font-size: 1rem; flex-shrink: 0; }
    .sb-link .chevron   { margin-left: auto; font-size: .75rem; transition: transform .2s ease; }

    /* Rotate chevron when submenu is open */
    .sb-link[aria-expanded="true"] .chevron { transform: rotate(180deg); }

    .sb-link:hover,
    .sb-link.active {
        background: var(--sb-hover);
        color: var(--sb-active);
        border-left-color: var(--accent);
    }
    .sb-link:focus { outline: none; box-shadow: none; }

    /* Submenus */
    .sb-submenu { padding-left: 14px; }
    .sb-submenu .sb-link {
        font-size: .82rem;
        padding: 8px 13px;
        color: rgba(203, 213, 225, .8);
    }
    .sb-submenu .sb-link:hover,
    .sb-submenu .sb-link.active {
        color: #fff;
        background: var(--sb-hover);
        border-left-color: var(--accent);
    }

    /* Sidebar footer (logout) */
    .sb-footer {
        margin-top: auto;
        padding: 0 8px 16px;
        flex-shrink: 0;
    }
    .sb-logout { color: #f87171; }
    .sb-logout:hover {
        background: rgba(248, 113, 113, .1);
        color: #f87171;
        border-left-color: #f87171;
    }

    /* ================================================================
       MAIN CONTENT AREA
    ================================================================ */
    #main-content {
        margin-left: var(--sb-width);
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        transition: margin-left .3s ease;
    }

    /* ================================================================
       TOP HEADER
    ================================================================ */
    #top-header {
        height: var(--hdr-h);
        background: #fff;
        border-bottom: 1px solid #e2e8f0;
        box-shadow: 0 1px 4px rgba(0, 0, 0, .05);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 24px;
        position: sticky;
        top: 0;
        z-index: 1030;
        flex-shrink: 0;
    }

    .hdr-hamburger {
        display: none;
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #64748b;
        cursor: pointer;
        padding: 3px 7px;
        border-radius: 6px;
        line-height: 1;
        transition: all .2s ease;
        align-items: center;
        justify-content: center;
    }
    .hdr-hamburger:hover { background: #f1f5f9; color: #1e293b; }

    #pageTitle {
        font-size: .975rem;
        font-weight: 600;
        color: #1e293b;
    }

    .hdr-icon-btn {
        background: none;
        border: none;
        font-size: 1.15rem;
        color: #64748b;
        cursor: pointer;
        padding: 6px 9px;
        border-radius: 8px;
        line-height: 1;
        transition: all .2s ease;
    }
    .hdr-icon-btn:hover { background: #f1f5f9; color: #1e293b; }

    .hdr-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: var(--accent);
        color: #fff;
        font-weight: 700;
        font-size: .875rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        user-select: none;
    }
    .hdr-admin-name {
        font-size: .85rem;
        font-weight: 500;
        color: #374151;
    }

    /* ================================================================
       CONTENEDOR (zona AJAX)
    ================================================================ */
    #contenedor {
        padding: 24px;
        flex-grow: 1;
        transition: opacity .4s;
    }

    /* ================================================================
       DASHBOARD — TARJETAS DE MÉTRICAS
    ================================================================ */
    .metric-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: var(--card-shadow);
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: box-shadow .2s ease, transform .2s ease;
    }
    .metric-card:hover {
        box-shadow: 0 6px 20px rgba(0, 0, 0, .12);
        transform: translateY(-2px);
    }
    .metric-num {
        font-size: 2.2rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1;
        margin-bottom: 5px;
    }
    .metric-label {
        font-size: .75rem;
        color: #64748b;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: .4px;
    }
    .metric-icon {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    /* ================================================================
       DASHBOARD — TABLA ÚLTIMAS CITAS
    ================================================================ */
    .dash-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
    }
    .dash-card-header {
        padding: 14px 20px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .dash-card-title {
        font-size: .9rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    /* ================================================================
       OVERLAY MOBILE
    ================================================================ */
    #sidebarOverlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .45);
        z-index: 1040;
        cursor: pointer;
    }
    #sidebarOverlay.show { display: block; }

    /* ================================================================
       RESPONSIVE — MOBILE
    ================================================================ */
    @media (max-width: 767.98px) {
        #sidebar          { transform: translateX(-100%); }
        #sidebar.sb-open  { transform: translateX(0); }
        #main-content     { margin-left: 0; }
        .hdr-hamburger    { display: inline-flex; }
        #contenedor       { padding: 16px; }
    }
    </style>
</head>
<body>
<div class="wrapper">

    <!-- ============================================================
         SIDEBAR
    ============================================================ -->
    <nav id="sidebar">

        <!-- Logo + nombre del sistema -->
        <div class="sb-brand">
            <i class="bi bi-gear-wide-connected sb-brand-icon"></i>
            <div>
                <div class="sb-brand-title">CHECO</div>
                <div class="sb-brand-sub">Panel Administrativo</div>
            </div>
        </div>
        <hr class="sb-hr">

        <!-- Menú de navegación -->
        <div class="sb-nav">

            <!-- Dashboard -->
            <a href="#" class="sb-link active"
               onclick="navegarA(this,'frmBienvenida','Dashboard'); return false;">
                <i class="bi bi-speedometer2 link-icon"></i>
                <span>Dashboard</span>
            </a>

            <!-- Clientes -->
            <a href="#submClientes" data-bs-toggle="collapse"
               class="sb-link" role="button" aria-expanded="false">
                <i class="bi bi-people-fill link-icon"></i>
                <span>Clientes</span>
                <i class="bi bi-chevron-down chevron"></i>
            </a>
            <div class="collapse" id="submClientes">
                <div class="sb-submenu">
                    <a href="#" class="sb-link"
                       onclick="navegarA(this,'frmCliente','Registrar Cliente'); return false;">
                        <i class="bi bi-person-plus link-icon"></i>
                        <span>Registrar Cliente</span>
                    </a>
                    <a href="#" class="sb-link"
                       onclick="navegarA(this,'tablaClientes','Ver Clientes'); return false;">
                        <i class="bi bi-table link-icon"></i>
                        <span>Ver Clientes</span>
                    </a>
                </div>
            </div>

            <!-- Vehículos -->
            <a href="#submVehiculos" data-bs-toggle="collapse"
               class="sb-link" role="button" aria-expanded="false">
                <i class="bi bi-car-front-fill link-icon"></i>
                <span>Vehículos</span>
                <i class="bi bi-chevron-down chevron"></i>
            </a>
            <div class="collapse" id="submVehiculos">
                <div class="sb-submenu">
                    <a href="#" class="sb-link"
                       onclick="navegarA(this,'formVehiculo','Registrar Vehículo'); return false;">
                        <i class="bi bi-plus-circle link-icon"></i>
                        <span>Registrar Vehículo</span>
                    </a>
                    <a href="#" class="sb-link"
                       onclick="navegarA(this,'tablaVehiculos','Ver Vehículos'); return false;">
                        <i class="bi bi-card-list link-icon"></i>
                        <span>Ver Vehículos</span>
                    </a>
                </div>
            </div>

            <!-- Refacciones -->
            <a href="#submRefacciones" data-bs-toggle="collapse"
               class="sb-link" role="button" aria-expanded="false">
                <i class="bi bi-box-seam-fill link-icon"></i>
                <span>Refacciones</span>
                <i class="bi bi-chevron-down chevron"></i>
            </a>
            <div class="collapse" id="submRefacciones">
                <div class="sb-submenu">
                    <a href="#" class="sb-link"
                       onclick="navegarA(this,'frmRefaccion','Registrar Refacción'); return false;">
                        <i class="bi bi-plus-circle link-icon"></i>
                        <span>Registrar Refacción</span>
                    </a>
                    <a href="#" class="sb-link"
                       onclick="navegarA(this,'tablaRefacciones','Ver Inventario'); return false;">
                        <i class="bi bi-archive link-icon"></i>
                        <span>Ver Inventario</span>
                    </a>
                </div>
            </div>

            <!-- Citas -->
            <a href="#submCitas" data-bs-toggle="collapse"
               class="sb-link" role="button" aria-expanded="false">
                <i class="bi bi-calendar-check-fill link-icon"></i>
                <span>Citas</span>
                <i class="bi bi-chevron-down chevron"></i>
            </a>
            <div class="collapse" id="submCitas">
                <div class="sb-submenu">
                    <a href="#" class="sb-link"
                       onclick="navegarA(this,'FormularioAgendarCita','Agendar Cita'); return false;">
                        <i class="bi bi-calendar-plus link-icon"></i>
                        <span>Agendar Cita</span>
                    </a>
                    <a href="#" class="sb-link"
                       onclick="navegarA(this,'tablaCitas','Ver Citas'); return false;">
                        <i class="bi bi-list-check link-icon"></i>
                        <span>Ver Citas</span>
                    </a>
                </div>
            </div>

        </div><!-- /.sb-nav -->

        <!-- Cerrar sesión al fondo -->
        <div class="sb-footer">
            <hr class="sb-hr mb-2">
            <a href="Controlador.php?idopc=CerrarSesion"
               class="sb-link sb-logout"
               onclick="return confirm('¿Deseas cerrar sesión?')">
                <i class="bi bi-box-arrow-left link-icon"></i>
                <span>Cerrar Sesión</span>
            </a>
        </div>

    </nav><!-- /#sidebar -->

    <!-- Overlay para cerrar sidebar en móvil -->
    <div id="sidebarOverlay"></div>

    <!-- ============================================================
         CONTENIDO PRINCIPAL
    ============================================================ -->
    <div id="main-content">

        <!-- Header superior -->
        <header id="top-header">
            <div class="d-flex align-items-center gap-2">
                <button class="hdr-hamburger" id="btnHamburger" aria-label="Abrir menú">
                    <i class="bi bi-list"></i>
                </button>
                <span id="pageTitle">Dashboard</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button class="hdr-icon-btn" title="Notificaciones" aria-label="Notificaciones">
                    <i class="bi bi-bell"></i>
                </button>
                <div class="hdr-avatar" title="<?php echo htmlspecialchars($nombreAdmin); ?>">
                    <?php echo $inicialAdmin; ?>
                </div>
                <span class="hdr-admin-name d-none d-sm-block">
                    <?php echo htmlspecialchars($nombreAdmin); ?>
                </span>
            </div>
        </header>

        <!-- Área de vistas (reemplazada por AJAX) -->
        <div id="contenedor">

            <!-- ===== DASHBOARD INICIAL ===== -->
            <div id="dashboardInicio">

                <h4 class="fw-bold mb-1">
                    Bienvenido, <?php echo htmlspecialchars($nombreAdmin); ?> 👋
                </h4>
                <p class="text-muted mb-4">
                    Resumen del sistema &mdash; <?php echo $fechaActual; ?>
                </p>

                <!-- Tarjetas de métricas -->
                <div class="row g-3 mb-4">

                    <div class="col-sm-6 col-xl-3">
                        <div class="metric-card">
                            <div>
                                <div class="metric-num">--</div>
                                <div class="metric-label">Clientes registrados</div>
                            </div>
                            <div class="metric-icon"
                                 style="background: #3b82f615; color: #3b82f6;">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="metric-card">
                            <div>
                                <div class="metric-num">--</div>
                                <div class="metric-label">Vehículos</div>
                            </div>
                            <div class="metric-icon"
                                 style="background: #10b98115; color: #10b981;">
                                <i class="bi bi-car-front"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="metric-card">
                            <div>
                                <div class="metric-num">--</div>
                                <div class="metric-label">Citas pendientes</div>
                            </div>
                            <div class="metric-icon"
                                 style="background: #f59e0b15; color: #f59e0b;">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="metric-card">
                            <div>
                                <div class="metric-num">--</div>
                                <div class="metric-label">Refacciones en stock</div>
                            </div>
                            <div class="metric-icon"
                                 style="background: #8b5cf615; color: #8b5cf6;">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        </div>
                    </div>

                </div><!-- /.row -->

                <!-- Tabla últimas citas -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h6 class="dash-card-title">
                            <i class="bi bi-clock-history me-2" style="color: var(--accent);"></i>
                            Últimas Citas
                        </h6>
                        <a href="#" class="small" style="color: var(--accent);"
                           onclick="navegarA(null,'tablaCitas','Ver Citas'); return false;">
                            Ver todas
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4 text-muted fw-semibold" style="font-size:.78rem;">#</th>
                                    <th class="text-muted fw-semibold" style="font-size:.78rem;">Cliente</th>
                                    <th class="text-muted fw-semibold" style="font-size:.78rem;">Vehículo</th>
                                    <th class="text-muted fw-semibold" style="font-size:.78rem;">Servicio</th>
                                    <th class="text-muted fw-semibold" style="font-size:.78rem;">Fecha</th>
                                    <th class="text-muted fw-semibold" style="font-size:.78rem;">Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-4 text-muted">001</td>
                                    <td class="fw-medium">Juan Pérez</td>
                                    <td class="text-muted">Toyota Corolla 2020</td>
                                    <td>Cambio de aceite</td>
                                    <td class="text-muted">01/06/2026</td>
                                    <td>
                                        <span class="badge rounded-pill px-3"
                                              style="background:#fef3c7; color:#b45309; font-weight:500;">
                                            Pendiente
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4 text-muted">002</td>
                                    <td class="fw-medium">María García</td>
                                    <td class="text-muted">Honda Civic 2019</td>
                                    <td>Revisión general</td>
                                    <td class="text-muted">01/06/2026</td>
                                    <td>
                                        <span class="badge rounded-pill px-3"
                                              style="background:#dbeafe; color:#1d4ed8; font-weight:500;">
                                            En proceso
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4 text-muted">003</td>
                                    <td class="fw-medium">Carlos López</td>
                                    <td class="text-muted">Nissan Sentra 2021</td>
                                    <td>Balanceo y alineación</td>
                                    <td class="text-muted">02/06/2026</td>
                                    <td>
                                        <span class="badge rounded-pill px-3"
                                              style="background:#dcfce7; color:#15803d; font-weight:500;">
                                            Terminado
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div><!-- /.dash-card -->

            </div><!-- /#dashboardInicio -->

        </div><!-- /#contenedor -->

        <!-- Mensajes del sistema -->
        <div id="contenidoMensaje" class="px-4 pb-3"></div>

    </div><!-- /#main-content -->

</div><!-- /.wrapper -->

<!-- ============================================================
     SCRIPTS
============================================================ -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="funciones.js"></script>

<script>
(function () {
    var sidebar   = document.getElementById('sidebar');
    var overlay   = document.getElementById('sidebarOverlay');
    var btnHamb   = document.getElementById('btnHamburger');
    var pageTitle = document.getElementById('pageTitle');

    /* Abrir/cerrar sidebar en móvil */
    btnHamb.addEventListener('click', function () {
        sidebar.classList.toggle('sb-open');
        overlay.classList.toggle('show');
    });
    overlay.addEventListener('click', function () {
        sidebar.classList.remove('sb-open');
        overlay.classList.remove('show');
    });

    /* Navegación: actualiza título, estado activo y carga la vista */
    window.navegarA = function (linkEl, idopc, titulo) {
        if (pageTitle) pageTitle.textContent = titulo;

        document.querySelectorAll('#sidebar .sb-link').forEach(function (l) {
            l.classList.remove('active');
        });
        if (linkEl) linkEl.classList.add('active');

        sidebar.classList.remove('sb-open');
        overlay.classList.remove('show');

        buscar(idopc, 'contenedor', 'ver', 0, 0);
    };
})();
</script>
</body>
</html>
