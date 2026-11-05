<?php
// ============================================================
// Vistas.php - Todas las vistas del panel de administración
// ============================================================

// -------------------------------------------------------
//  DASHBOARD
// -------------------------------------------------------
function frmBienvenida($obj) {
    $clientes    = $obj->consultardatos("SELECT COUNT(*) as t FROM usuarios WHERE rol='cliente'");
    $vehiculos   = $obj->consultardatos("SELECT COUNT(*) as t FROM vehiculos");
    $citasPend   = $obj->consultardatos("SELECT COUNT(*) as t FROM citas WHERE estatus='Pendiente'");
    $refacciones = $obj->consultardatos("SELECT COUNT(*) as t FROM refacciones");

    $tc = $clientes[0]['t']    ?? 0;
    $tv = $vehiculos[0]['t']   ?? 0;
    $tp = $citasPend[0]['t']   ?? 0;
    $tr = $refacciones[0]['t'] ?? 0;
?>
<div class="container-fluid py-3">
    <div class="mb-4">
        <h4 class="fw-bold"><i class="bi bi-speedometer2 text-primary me-2"></i>Dashboard General</h4>
        <p class="text-muted small mb-0">Resumen del sistema — Taller Mecánico CHECO</p>
    </div>

    <div class="row g-3 mb-4">
        <!-- Clientes -->
        <div class="col-6 col-md-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#0d6efd,#0a58ca);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 small opacity-75">Clientes</p>
                        <div class="stat-num"><?= $tc ?></div>
                    </div>
                    <i class="bi bi-people-fill stat-icon"></i>
                </div>
                <button class="btn btn-sm btn-light mt-3 w-100 fw-bold"
                        onclick="cargarVista('tablaClientes')">
                    Ver clientes
                </button>
            </div>
        </div>
        <!-- Vehículos -->
        <div class="col-6 col-md-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#198754,#146c43);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 small opacity-75">Vehículos</p>
                        <div class="stat-num"><?= $tv ?></div>
                    </div>
                    <i class="bi bi-car-front-fill stat-icon"></i>
                </div>
                <button class="btn btn-sm btn-light mt-3 w-100 fw-bold"
                        onclick="cargarVista('tablaVehiculos')">
                    Ver vehículos
                </button>
            </div>
        </div>
        <!-- Citas Pendientes -->
        <div class="col-6 col-md-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#ffc107,#e0a800);color:#212529;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 small opacity-75">Citas Pendientes</p>
                        <div class="stat-num"><?= $tp ?></div>
                    </div>
                    <i class="bi bi-calendar-check-fill stat-icon"></i>
                </div>
                <button class="btn btn-sm btn-dark mt-3 w-100 fw-bold"
                        onclick="cargarVista('tablaCitas')">
                    Ver citas
                </button>
            </div>
        </div>
        <!-- Refacciones -->
        <div class="col-6 col-md-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#dc3545,#b02a37);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 small opacity-75">Refacciones</p>
                        <div class="stat-num"><?= $tr ?></div>
                    </div>
                    <i class="bi bi-box-seam-fill stat-icon"></i>
                </div>
                <button class="btn btn-sm btn-light mt-3 w-100 fw-bold"
                        onclick="cargarVista('tablaRefacciones')">
                    Ver inventario
                </button>
            </div>
        </div>
    </div>

    <!-- Citas recientes -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold border-0">
            <i class="bi bi-clock-history text-primary me-2"></i>Citas Recientes
        </div>
        <div class="card-body p-0">
            <?php
            $recientes = $obj->consultardatos(
                "SELECT c.id_cita, c.fecha_cita, c.hora_cita, c.servicio_solicitado, c.estatus,
                        u.nombre AS cliente, v.placa, v.marca, v.modelo
                 FROM citas c
                 JOIN usuarios u ON c.id_cliente  = u.id_usuario
                 JOIN vehiculos v ON c.id_vehiculo = v.id_vehiculo
                 ORDER BY c.fecha_registro DESC LIMIT 5"
            );
            if ($recientes && count($recientes) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Fecha</th><th>Cliente</th><th>Vehículo</th><th>Servicio</th><th>Estatus</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($recientes as $r): ?>
                        <tr>
                            <td><?= $r['fecha_cita'] ?> <?= substr($r['hora_cita'],0,5) ?></td>
                            <td><?= htmlspecialchars($r['cliente']) ?></td>
                            <td><span class="badge bg-secondary"><?= $r['placa'] ?></span> <?= $r['marca'] ?> <?= $r['modelo'] ?></td>
                            <td><?= $r['servicio_solicitado'] ?></td>
                            <td><?= badgeEstatus($r['estatus']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <p class="text-muted text-center py-4">No hay citas registradas aún.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php
}

// -------------------------------------------------------
//  HELPER: badge de estatus
// -------------------------------------------------------
function badgeEstatus($estatus) {
    $map = [
        'Pendiente'  => 'warning text-dark',
        'En proceso' => 'info text-dark',
        'Terminado'  => 'success',
        'Cancelado'  => 'danger',
    ];
    $cls = $map[$estatus] ?? 'secondary';
    return "<span class='badge bg-{$cls}'>{$estatus}</span>";
}

// -------------------------------------------------------
//  CLIENTES
// -------------------------------------------------------
function FormularioNuevoCliente() {
?>
<div class="container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-person-plus-fill text-primary me-2"></i>Registrar Nuevo Cliente</h5>
                </div>
                <div class="card-body bg-light p-4">
                    <form id="formCliente" novalidate>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nombre Completo <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person text-primary"></i></span>
                                    <input type="text" class="form-control" name="nombre"
                                           id="cli_nombre" placeholder="Ej. Juan Pérez García" required>
                                    <div class="invalid-feedback">El nombre es obligatorio.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Correo Electrónico <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope text-primary"></i></span>
                                    <input type="email" class="form-control" name="email"
                                           id="cli_email" placeholder="correo@ejemplo.com" required>
                                    <div class="invalid-feedback">Ingresa un correo válido.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Teléfono <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone text-primary"></i></span>
                                    <input type="text" class="form-control" name="telefono"
                                           id="cli_tel" placeholder="483 123 4567" required>
                                    <div class="invalid-feedback">El teléfono es obligatorio.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Dirección</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt text-primary"></i></span>
                                    <input type="text" class="form-control" name="direccion"
                                           placeholder="Calle, Colonia, Ciudad">
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <button type="button" class="btn btn-primary w-100 py-2 fw-bold"
                                        onclick="GuardarCliente()">
                                    <i class="bi bi-save me-2"></i>Guardar Cliente
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}

function VerTablaClientes($obj) {
    $datos = $obj->consultardatos("SELECT * FROM usuarios WHERE rol='cliente' ORDER BY nombre");
?>
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0"><i class="bi bi-people-fill text-primary me-2"></i>Clientes Registrados</h5>
        <button class="btn btn-primary btn-sm" onclick="cargarVista('frmCliente')">
            <i class="bi bi-person-plus me-1"></i>Nuevo Cliente
        </button>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tblClientes">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th><th>Nombre</th><th>Email</th>
                            <th>Teléfono</th><th>Dirección</th><th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($datos && count($datos) > 0):
                          $i = 1;
                          foreach ($datos as $row): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($row['nombre']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['telefono']) ?></td>
                            <td><?= htmlspecialchars($row['direccion']) ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm me-1"
                                        onclick="EditarCliente(<?= $row['id_usuario'] ?>)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-danger btn-sm"
                                        onclick="EliminarCliente(<?= $row['id_usuario'] ?>)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach;
                    else: ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">No hay clientes registrados.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        if ($.fn.DataTable.isDataTable('#tblClientes')) {
            $('#tblClientes').DataTable().destroy();
        }
        $('#tblClientes').DataTable({
            language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-MX.json' },
            pageLength: 10,
            columnDefs: [{ orderable: false, targets: 5 }]
        });
    });
</script>
<?php
}

function frmEditarCliente($obj, $id) {
    $datos = $obj->consultardatos("SELECT * FROM usuarios WHERE id_usuario = $id");
    if (!$datos) { echo "<div class='alert alert-danger m-3'>Cliente no encontrado.</div>"; return; }
    $c = $datos[0];
?>
<div class="container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-person-gear text-primary me-2"></i>Editar Cliente</h5>
                </div>
                <div class="card-body bg-light p-4">
                    <form id="frmEditar" novalidate>
                        <input type="hidden" name="idr" value="<?= $c['id_usuario'] ?>">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nombre Completo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nombre"
                                       value="<?= htmlspecialchars($c['nombre']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Correo Electrónico <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email"
                                       value="<?= htmlspecialchars($c['email']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Teléfono</label>
                                <input type="text" class="form-control" name="telefono"
                                       value="<?= htmlspecialchars($c['telefono']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Dirección</label>
                                <input type="text" class="form-control" name="direccion"
                                       value="<?= htmlspecialchars($c['direccion']) ?>">
                            </div>
                            <div class="col-12 d-flex gap-2 mt-2">
                                <button type="button" class="btn btn-outline-secondary"
                                        onclick="cargarVista('tablaClientes')">
                                    <i class="bi bi-arrow-left me-1"></i>Regresar
                                </button>
                                <button type="button" class="btn btn-primary flex-grow-1"
                                        onclick="ActualizarCliente()">
                                    <i class="bi bi-save me-1"></i>Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}

// -------------------------------------------------------
//  VEHÍCULOS
// -------------------------------------------------------
function FormularioNuevoVehiculo($obj) {
    $clientes = $obj->consultardatos(
        "SELECT id_usuario, nombre FROM usuarios WHERE rol='cliente' ORDER BY nombre"
    );
?>
<div class="container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-car-front-fill text-primary me-2"></i>Registrar Vehículo</h5>
                </div>
                <div class="card-body bg-light p-4">
                    <form id="frmVehiculo" novalidate>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Propietario <span class="text-danger">*</span></label>
                                <select name="id_cliente" class="form-select" id="veh_cliente" required>
                                    <option value="">-- Selecciona un cliente --</option>
                                    <?php foreach ($clientes as $cli): ?>
                                        <option value="<?= $cli['id_usuario'] ?>"><?= htmlspecialchars($cli['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Número de Placa <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase" name="placa"
                                       placeholder="Ej. ABC-123-A" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Marca <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="marca"
                                       placeholder="Nissan, Ford, VW..." required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Modelo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="modelo"
                                       placeholder="Sentra, Lobo..." required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Año <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="anio"
                                       placeholder="<?= date('Y') ?>" min="1980" max="<?= (int)date('Y') + 1 ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Color</label>
                                <input type="text" class="form-control" name="color" placeholder="Blanco, Negro...">
                            </div>
                            <div class="col-12 mt-2">
                                <button type="button" class="btn btn-primary w-100 py-2 fw-bold"
                                        onclick="GuardarVehiculo()">
                                    <i class="bi bi-plus-circle me-2"></i>Registrar Vehículo
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}

function tablaVehiculos($obj) {
    $datos = $obj->consultardatos(
        "SELECT v.*, u.nombre AS dueno FROM vehiculos v
         JOIN usuarios u ON v.id_cliente = u.id_usuario ORDER BY u.nombre"
    );
?>
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0"><i class="bi bi-car-front-fill text-primary me-2"></i>Vehículos Registrados</h5>
        <button class="btn btn-primary btn-sm" onclick="cargarVista('formVehiculo')">
            <i class="bi bi-plus-circle me-1"></i>Nuevo Vehículo
        </button>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tblVehiculos">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th><th>Propietario</th><th>Placa</th>
                            <th>Marca</th><th>Modelo</th><th>Año</th><th>Color</th><th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($datos && count($datos) > 0):
                          $i = 1;
                          foreach ($datos as $row): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($row['dueno']) ?></td>
                            <td><span class="badge bg-dark"><?= $row['placa'] ?></span></td>
                            <td><?= $row['marca'] ?></td>
                            <td><?= $row['modelo'] ?></td>
                            <td><?= $row['anio'] ?></td>
                            <td><?= $row['color'] ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm me-1"
                                        onclick="EditarVehiculo(<?= $row['id_vehiculo'] ?>)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-danger btn-sm"
                                        onclick="EliminarVehiculo(<?= $row['id_vehiculo'] ?>)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach;
                    else: ?>
                        <tr><td colspan="8" class="text-center py-4 text-muted">No hay vehículos registrados.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        if ($.fn.DataTable.isDataTable('#tblVehiculos')) $('#tblVehiculos').DataTable().destroy();
        $('#tblVehiculos').DataTable({
            language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-MX.json' },
            pageLength: 10,
            columnDefs: [{ orderable: false, targets: 7 }]
        });
    });
</script>
<?php
}

function frmEditarVehiculo($obj, $id) {
    $datos    = $obj->consultardatos("SELECT * FROM vehiculos WHERE id_vehiculo = $id");
    $clientes = $obj->consultardatos("SELECT id_usuario, nombre FROM usuarios WHERE rol='cliente' ORDER BY nombre");
    if (!$datos) { echo "<div class='alert alert-danger m-3'>Vehículo no encontrado.</div>"; return; }
    $v = $datos[0];
?>
<div class="container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-pencil-square text-primary me-2"></i>Editar Vehículo</h5>
                </div>
                <div class="card-body bg-light p-4">
                    <form id="frmEditVehiculo" novalidate>
                        <input type="hidden" name="idr" value="<?= $v['id_vehiculo'] ?>">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Propietario</label>
                                <select name="id_cliente" class="form-select">
                                    <?php foreach ($clientes as $cli): ?>
                                        <option value="<?= $cli['id_usuario'] ?>"
                                            <?= $cli['id_usuario'] == $v['id_cliente'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cli['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Placa</label>
                                <input type="text" class="form-control text-uppercase" name="placa"
                                       value="<?= $v['placa'] ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Marca</label>
                                <input type="text" class="form-control" name="marca" value="<?= $v['marca'] ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Modelo</label>
                                <input type="text" class="form-control" name="modelo" value="<?= $v['modelo'] ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Año</label>
                                <input type="number" class="form-control" name="anio"
                                       value="<?= $v['anio'] ?>" min="1980" max="<?= (int)date('Y') + 1 ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Color</label>
                                <input type="text" class="form-control" name="color" value="<?= $v['color'] ?>">
                            </div>
                            <div class="col-12 d-flex gap-2 mt-2">
                                <button type="button" class="btn btn-outline-secondary"
                                        onclick="cargarVista('tablaVehiculos')">
                                    <i class="bi bi-arrow-left me-1"></i>Regresar
                                </button>
                                <button type="button" class="btn btn-primary flex-grow-1"
                                        onclick="ActualizarVehiculo()">
                                    <i class="bi bi-save me-1"></i>Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}

// -------------------------------------------------------
//  REFACCIONES
// -------------------------------------------------------
function FormularioNuevoRefaccion() {
?>
<div class="container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-box-seam-fill text-primary me-2"></i>Agregar Refacción</h5>
                </div>
                <div class="card-body bg-light p-4">
                    <form id="frmRefaccion" novalidate>
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nombre" id="ref_nombre"
                                       placeholder="Ej. Filtro de aceite, Bujías NGK..." required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Categoría</label>
                                <select class="form-select" name="categoria">
                                    <option value="">-- Sin categoría --</option>
                                    <option>Filtros</option>
                                    <option>Encendido</option>
                                    <option>Frenos</option>
                                    <option>Lubricantes</option>
                                    <option>Motor</option>
                                    <option>Suspensión</option>
                                    <option>Eléctrico</option>
                                    <option>Carrocería</option>
                                    <option>Otro</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Descripción</label>
                                <textarea class="form-control" name="descripcion" rows="2"
                                          placeholder="Descripción breve de la refacción..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Precio (MXN) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="precio" id="ref_precio"
                                           placeholder="0.00" min="0.01" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Stock <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="stock" id="ref_stock"
                                       placeholder="0" min="0" required>
                            </div>
                            <div class="col-12 mt-2">
                                <button type="button" class="btn btn-primary w-100 py-2 fw-bold"
                                        onclick="GuardarRefaccion()">
                                    <i class="bi bi-save me-2"></i>Guardar Refacción
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}

function tablaRefacciones($obj) {
    $datos = $obj->consultardatos(
        "SELECT * FROM refacciones ORDER BY categoria, nombre"
    );
?>
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0"><i class="bi bi-archive text-primary me-2"></i>Inventario de Refacciones</h5>
        <button class="btn btn-primary btn-sm" onclick="cargarVista('frmRefaccion')">
            <i class="bi bi-plus-circle me-1"></i>Nueva Refacción
        </button>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tblRefacciones">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th><th>Nombre</th><th>Categoría</th>
                            <th>Descripción</th><th>Precio</th><th>Stock</th><th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($datos && count($datos) > 0):
                          $i = 1;
                          foreach ($datos as $r): ?>
                        <tr id="row-ref-<?= $r['id_refaccion'] ?>">
                            <td><?= $i++ ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($r['nombre']) ?></td>
                            <td><span class="badge bg-secondary"><?= $r['categoria'] ?: 'General' ?></span></td>
                            <td class="small text-muted"><?= htmlspecialchars($r['descripcion']) ?></td>
                            <td class="fw-bold text-success">
                                $<?= number_format($r['precio'], 2) ?>
                            </td>
                            <td>
                                <?php
                                $stockBadge = ($r['stock'] < 5) ? 'danger' : (($r['stock'] < 10) ? 'warning text-dark' : 'success');
                                ?>
                                <span class="badge bg-<?= $stockBadge ?> fs-6"><?= $r['stock'] ?></span>
                                <?php if ($r['stock'] < 5): ?>
                                    <span class="badge bg-danger ms-1 small">Stock bajo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm me-1"
                                        onclick="EditarRefaccion(<?= $r['id_refaccion'] ?>)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-danger btn-sm"
                                        onclick="EliminarRefaccion(<?= $r['id_refaccion'] ?>)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach;
                    else: ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">No hay refacciones registradas.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        if ($.fn.DataTable.isDataTable('#tblRefacciones')) $('#tblRefacciones').DataTable().destroy();
        $('#tblRefacciones').DataTable({
            language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-MX.json' },
            pageLength: 10,
            columnDefs: [{ orderable: false, targets: 6 }]
        });
    });
</script>
<?php
}

function frmEditarRefaccion($obj, $id) {
    $datos = $obj->consultardatos("SELECT * FROM refacciones WHERE id_refaccion = $id");
    if (!$datos) { echo "<div class='alert alert-danger m-3'>Refacción no encontrada.</div>"; return; }
    $r = $datos[0];
?>
<div class="container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-pencil-square text-primary me-2"></i>Editar Refacción</h5>
                </div>
                <div class="card-body bg-light p-4">
                    <form id="frmEditRefaccion" novalidate>
                        <input type="hidden" name="idr" value="<?= $r['id_refaccion'] ?>">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Nombre</label>
                                <input type="text" class="form-control" name="nombre"
                                       value="<?= htmlspecialchars($r['nombre']) ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Categoría</label>
                                <input type="text" class="form-control" name="categoria"
                                       value="<?= htmlspecialchars($r['categoria']) ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Descripción</label>
                                <textarea class="form-control" name="descripcion" rows="2"><?= htmlspecialchars($r['descripcion']) ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Precio</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="precio"
                                           value="<?= $r['precio'] ?>" min="0.01" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Stock</label>
                                <input type="number" class="form-control" name="stock"
                                       value="<?= $r['stock'] ?>" min="0">
                            </div>
                            <div class="col-12 d-flex gap-2 mt-2">
                                <button type="button" class="btn btn-outline-secondary"
                                        onclick="cargarVista('tablaRefacciones')">
                                    <i class="bi bi-arrow-left me-1"></i>Regresar
                                </button>
                                <button type="button" class="btn btn-primary flex-grow-1"
                                        onclick="ActualizarRefaccion()">
                                    <i class="bi bi-save me-1"></i>Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}

// -------------------------------------------------------
//  CITAS
// -------------------------------------------------------
function FormularioAgendarCita($clientes) {
?>
<div class="container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-9">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-calendar-plus text-primary me-2"></i>Agendar Nueva Cita</h5>
                </div>
                <div class="card-body bg-light p-4">
                    <form id="frmAgendarCita" novalidate>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Cliente <span class="text-danger">*</span></label>
                                <select name="id_cliente" class="form-select" id="cboClienteCita"
                                        onchange="FiltrarCarrosPorCliente(this.value)" required>
                                    <option value="">-- Selecciona un cliente --</option>
                                    <?php foreach ($clientes as $c): ?>
                                        <option value="<?= $c['id_usuario'] ?>">
                                            <?= htmlspecialchars($c['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Vehículo <span class="text-danger">*</span></label>
                                <select name="id_vehiculo" class="form-select" id="cboVehiculoCita" required>
                                    <option value="">-- Primero selecciona un cliente --</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Fecha <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="fecha_cita"
                                       id="cita_fecha" min="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Hora <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="hora_cita"
                                       id="cita_hora" min="08:00" max="18:00" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Servicio <span class="text-danger">*</span></label>
                                <select name="servicio_solicitado" class="form-select" id="cita_servicio" required>
                                    <option value="">-- Tipo de servicio --</option>
                                    <option>Afinación</option>
                                    <option>Frenos</option>
                                    <option>Scanner / Diagnóstico</option>
                                    <option>Suspensión</option>
                                    <option>Sistema Eléctrico</option>
                                    <option>Cambio de aceite</option>
                                    <option>Otro</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Notas adicionales</label>
                                <textarea class="form-control" name="notas" rows="2"
                                          placeholder="Descripción del problema o indicaciones especiales..."></textarea>
                            </div>
                            <div class="col-12 mt-2">
                                <button type="button" class="btn btn-primary w-100 py-2 fw-bold"
                                        onclick="GuardarCita()">
                                    <i class="bi bi-calendar-check me-2"></i>Confirmar Cita
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}

function tablaCitas($obj, $filtro = '') {
    $where = '';
    if ($filtro && in_array($filtro, ['Pendiente','En proceso','Terminado','Cancelado'])) {
        $where = "WHERE c.estatus = '" . mysqli_real_escape_string($obj->conexion(), $filtro) . "'";
    }
    $datos = $obj->consultardatos(
        "SELECT c.*, u.nombre AS cliente, u.telefono,
                v.placa, v.marca, v.modelo
         FROM citas c
         JOIN usuarios u  ON c.id_cliente  = u.id_usuario
         JOIN vehiculos v ON c.id_vehiculo = v.id_vehiculo
         $where
         ORDER BY c.fecha_cita DESC, c.hora_cita DESC"
    );
    $estatuses = ['Pendiente','En proceso','Terminado','Cancelado'];
?>
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="fw-bold mb-0"><i class="bi bi-calendar-week text-primary me-2"></i>Gestión de Citas</h5>
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-outline-secondary btn-sm <?= !$filtro ? 'active' : '' ?>"
                    onclick="FiltrarCitasPorEstatus('')">Todas</button>
            <?php foreach ($estatuses as $e): ?>
            <button class="btn btn-sm btn-outline-<?= badgeColor($e) ?> <?= $filtro === $e ? 'active' : '' ?>"
                    onclick="FiltrarCitasPorEstatus('<?= $e ?>')">
                <?= $e ?>
            </button>
            <?php endforeach; ?>
            <button class="btn btn-primary btn-sm" onclick="cargarVista('FormularioAgendarCita')">
                <i class="bi bi-plus-circle me-1"></i>Nueva Cita
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tblCitas">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th><th>Fecha / Hora</th><th>Cliente</th>
                            <th>Vehículo</th><th>Servicio</th><th>Estatus</th><th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($datos && count($datos) > 0):
                          $i = 1;
                          foreach ($datos as $cita): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td>
                                <span class="fw-semibold"><?= $cita['fecha_cita'] ?></span><br>
                                <small class="text-muted"><?= substr($cita['hora_cita'],0,5) ?></small>
                            </td>
                            <td><?= htmlspecialchars($cita['cliente']) ?></td>
                            <td>
                                <span class="badge bg-dark"><?= $cita['placa'] ?></span>
                                <?= $cita['marca'] ?> <?= $cita['modelo'] ?>
                            </td>
                            <td><?= $cita['servicio_solicitado'] ?></td>
                            <td><?= badgeEstatus($cita['estatus']) ?></td>
                            <td>
                                <button class="btn btn-info btn-sm me-1"
                                        onclick="CambiarEstatusCita(<?= $cita['id_cita'] ?>, '<?= $cita['estatus'] ?>')">
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                                <button class="btn btn-danger btn-sm"
                                        onclick="EliminarCita(<?= $cita['id_cita'] ?>)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach;
                    else: ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">No hay citas <?= $filtro ? "con estatus \"$filtro\"" : 'registradas' ?>.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        if ($.fn.DataTable.isDataTable('#tblCitas')) $('#tblCitas').DataTable().destroy();
        $('#tblCitas').DataTable({
            language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-MX.json' },
            pageLength: 10,
            columnDefs: [{ orderable: false, targets: 6 }],
            order: [[1, 'desc']]
        });
    });
</script>
<?php
}

function badgeColor($estatus) {
    $map = ['Pendiente' => 'warning','En proceso' => 'info','Terminado' => 'success','Cancelado' => 'danger'];
    return $map[$estatus] ?? 'secondary';
}

// -------------------------------------------------------
//  VISTAS HEREDADAS (sin cambios funcionales)
// -------------------------------------------------------
function MostrarSomos() { ?>
<div class="container mt-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-5">
            <h1 class="display-5 fw-bold text-primary text-center">Sobre Nosotros</h1>
            <p class="lead text-muted text-center">Taller Mecánico "Checo Hernández"</p>
            <div class="row align-items-center mt-4">
                <div class="col-md-6">
                    <h3>Nuestra Historia</h3>
                    <p>Taller familiar con años de experiencia en Tamazunchale, dedicados a mantener tu vehículo en óptimas condiciones.</p>
                    <h3 class="mt-4">Misión</h3>
                    <p>Brindar soluciones mecánicas confiables con tecnología de punta y refacciones de calidad.</p>
                </div>
                <div class="col-md-6 text-center">
                    <img src="Taller01.jpg" class="img-fluid rounded shadow w-75" alt="Taller">
                </div>
            </div>
        </div>
    </div>
</div>
<?php }

function Confianza() { ?>
<div class="container mt-4">
    <div class="text-center mb-4">
        <h3 class="fw-bold">Tu seguridad es nuestra prioridad</h3>
        <p class="text-muted">En Taller "Checo Hernández" cada reparación cuenta con garantía por escrito.</p>
    </div>
    <div class="row text-center">
        <div class="col-md-4"><i class="bi bi-patch-check text-primary fs-1"></i><h5 class="mt-2">Refacciones Originales</h5></div>
        <div class="col-md-4"><i class="bi bi-eye text-primary fs-1"></i><h5 class="mt-2">Transparencia Total</h5></div>
        <div class="col-md-4"><i class="bi bi-hand-thumbs-up text-primary fs-1"></i><h5 class="mt-2">Sin Sorpresas</h5></div>
    </div>
</div>
<?php }

function PantallaVision() { ?>
<div class="container mt-5">
    <div class="card border-0 shadow-lg rounded-4">
        <div class="p-5 text-center">
            <h2 class="fw-bold">Nuestra Visión</h2>
            <p class="lead mt-3">Convertirnos en el centro de servicio automotriz líder en Tamazunchale para el año 2030.</p>
        </div>
    </div>
</div>
<?php }

function MostrarServicios($contenedor = 1) { ?>
<div class="container-fluid mt-3">
    <div class="bg-dark text-white p-3 rounded shadow-sm mb-4">
        <h4 class="mb-0"><i class="bi bi-list-check text-primary"></i> Catálogo de Servicios</h4>
    </div>
    <?php
    $servicios = [
        ['DetalleAfinacion','bg-primary','bi-gear-wide-connected','Afinación General','Mantenimiento preventivo y limpieza de inyectores.'],
        ['DetalleFrenos','bg-danger','bi-disc-fill','Sistema de Frenos','Rectificado de discos y seguridad activa.'],
        ['DetalleScanner','bg-warning text-dark','bi-cpu','Diagnóstico Scanner','Borrado de códigos y diagnóstico de sensores.'],
        ['DetalleSuspension','bg-success','bi-tools','Suspensión / Dirección','Amortiguadores, rótulas y terminales.'],
        ['DetalleElectrico','bg-info','bi-lightning-charge-fill','Sistema Eléctrico','Marchas, alternadores y diagnóstico de batería.'],
    ];
    foreach ($servicios as $s): ?>
    <div class="d-flex align-items-center p-3 rounded border mb-2 bg-white"
         style="cursor:pointer;transition:.2s" onmouseover="this.style.borderColor='#0d6efd'"
         onmouseout="this.style.borderColor='#dee2e6'"
         onclick="cargarVista('<?= $s[0] ?>')">
        <div class="<?= $s[1] ?> text-white rounded me-3 d-flex align-items-center justify-content-center"
             style="width:42px;height:42px;font-size:1.2rem;flex-shrink:0">
            <i class="bi <?= $s[2] ?>"></i>
        </div>
        <div class="flex-grow-1">
            <h6 class="mb-0 fw-bold"><?= $s[3] ?></h6>
            <small class="text-muted"><?= $s[4] ?></small>
        </div>
        <i class="bi bi-chevron-right text-muted"></i>
    </div>
    <?php endforeach; ?>
</div>
<?php }

function MostrarDetalleFrenos() { ?>
<div class="container py-4">
    <button class="btn btn-sm btn-secondary mb-3" onclick="cargarVista('VerServicios')">
        <i class="bi bi-arrow-left"></i> Volver
    </button>
    <span class="badge bg-danger mb-2">Seguridad Crítica</span>
    <h1 class="fw-bold">Sistema de Frenos</h1>
    <ul class="list-group list-group-flush mt-3">
        <li class="list-group-item">Rectificado de discos y tambores.</li>
        <li class="list-group-item">Cambio de balatas de cerámica.</li>
        <li class="list-group-item">Purgado y cambio de líquido de frenos DOT 4.</li>
        <li class="list-group-item">Diagnóstico de sensores ABS.</li>
    </ul>
</div>
<?php }

function MostrarDetalleScanner() { ?>
<div class="container py-4">
    <button class="btn btn-sm btn-secondary mb-3" onclick="cargarVista('VerServicios')">
        <i class="bi bi-arrow-left"></i> Volver
    </button>
    <span class="badge bg-warning text-dark mb-2">Tecnología Avanzada</span>
    <h1 class="fw-bold">Diagnóstico por Scanner</h1>
    <ul class="list-group list-group-flush mt-3">
        <li class="list-group-item">Lectura de códigos de falla en motor y transmisión.</li>
        <li class="list-group-item">Monitoreo de sensores de oxígeno en vivo.</li>
        <li class="list-group-item">Reseteo de intervalos de servicio.</li>
        <li class="list-group-item">Reporte digital detallado.</li>
    </ul>
</div>
<?php }

function MostrarDetalleSuspension() { ?>
<div class="container py-4">
    <button class="btn btn-sm btn-secondary mb-3" onclick="cargarVista('VerServicios')">
        <i class="bi bi-arrow-left"></i> Volver
    </button>
    <span class="badge bg-success mb-2">Confort al Manejar</span>
    <h1 class="fw-bold">Suspensión y Dirección</h1>
    <ul class="list-group list-group-flush mt-3">
        <li class="list-group-item">Reemplazo de amortiguadores y bases.</li>
        <li class="list-group-item">Cambio de rótulas, terminales y bujes.</li>
        <li class="list-group-item">Revisión de cremallera y bombas.</li>
        <li class="list-group-item">Alineación computarizada.</li>
    </ul>
</div>
<?php }

function MostrarUbicacion() { ?>
<div class="container mt-4">
    <div class="card shadow border-0">
        <div class="card-header bg-danger text-white text-center">
            <h5 class="mb-0"><i class="bi bi-geo-alt-fill"></i> Nuestra Ubicación</h5>
        </div>
        <div class="card-body p-0">
            <div class="ratio ratio-21x9">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14902.123456789!2d-98.7885!3d21.2585!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85d6d936173a14e9%3A0x892e8633364f89d0!2sTamazunchale%2C%20S.L.P.!5e0!3m2!1ses-419!2smx" style="border:0;" allowfullscreen loading="lazy"></iframe>
            </div>
            <div class="p-4 text-center">
                <h4 class="fw-bold">Taller Mecánico "Checo"</h4>
                <p class="text-muted">Tamazunchale, San Luis Potosí, México</p>
            </div>
        </div>
    </div>
</div>
<?php }

function consultarEstadoPublico($placa) {
    if (empty($placa)) {
        echo '<div class="alert alert-warning">Ingresa una placa válida.</div>';
        return;
    }
    $obj2  = new OperacionesBd();
    $con2  = $obj2->conexion();
    $obj   = $obj2;
    $placa = mysqli_real_escape_string($con2, strtoupper($placa));
    $res = $obj2->consultardatos(
        "SELECT v.*, u.nombre AS dueno, c.servicio_solicitado, c.estatus
         FROM vehiculos v
         JOIN usuarios u ON v.id_cliente = u.id_usuario
         LEFT JOIN citas c ON v.id_vehiculo = c.id_vehiculo
           AND c.id_cita = (SELECT MAX(id_cita) FROM citas WHERE id_vehiculo = v.id_vehiculo)
         WHERE v.placa = '$placa' LIMIT 1"
    );
    if ($res && count($res) > 0) {
        $r = $res[0];
        echo "<div class='alert alert-success'><strong>Vehículo encontrado:</strong> {$r['marca']} {$r['modelo']} — Propietario: {$r['dueno']} — Estatus: " . ($r['estatus'] ?? 'Sin cita activa') . "</div>";
    } else {
        echo "<div class='alert alert-danger'>No se encontró ningún vehículo con la placa <strong>$placa</strong>.</div>";
    }
}
