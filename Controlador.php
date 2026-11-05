<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'Conexion.php';
include_once 'Vistas.php';
include_once 'VistasCliente.php';

$obj   = new OperacionesBd();
$con   = $obj->conexion();

$idopc = isset($_REQUEST['idopc']) ? $_REQUEST['idopc'] : '';
$ops   = isset($_REQUEST['ops'])   ? $_REQUEST['ops']   : '';
$idr   = isset($_REQUEST['idr'])   ? (int)$_REQUEST['idr'] : 0;

// Helper: respuesta JSON
function jsonResp($success, $mensaje) {
    header('Content-Type: application/json');
    echo json_encode(['success' => $success, 'mensaje' => $mensaje]);
    exit();
}

// Helper: sanitizar
function esc($con, $val) {
    return mysqli_real_escape_string($con, trim($val));
}

switch ($idopc) {

    // ============================================================
    //  SESIÓN
    // ============================================================
    case 'AccesoLogin':
        $user = esc($con, $_POST['usuario'] ?? '');
        $pass = esc($con, $_POST['password'] ?? '');

        $sql = "SELECT * FROM usuarios WHERE email = '$user' AND password = '$pass' LIMIT 1";
        $res = $obj->consultardatos($sql);

        if ($res && count($res) > 0) {
            $row = $res[0];
            $_SESSION['id_usuario'] = $row['id_usuario'];
            $_SESSION['nombre']     = $row['nombre'];
            $_SESSION['rol']        = $row['rol'];

            if ($row['rol'] === 'admin') {
                header("Location: Administrador.php");
            } else {
                header("Location: index.php");
            }
        } else {
            echo "<script>
                    Swal.fire({icon:'error',title:'Acceso denegado',
                    text:'Correo o contraseña incorrectos.'})
                    .then(()=>{ window.location='Login.php'; });
                  </script>";
        }
        break;

    case 'CerrarSesion':
        session_destroy();
        header("Location: Login.php");
        exit();

    // ============================================================
    //  VISTAS - PANEL ADMIN
    // ============================================================
    case 'frmBienvenida':
        frmBienvenida($obj);
        break;

    case 'frmCliente':
        FormularioNuevoCliente();
        break;

    case 'tablaClientes':
        VerTablaClientes($obj);
        break;

    case 'frmEditarCliente':
        frmEditarCliente($obj, $idr);
        break;

    case 'formVehiculo':
        FormularioNuevoVehiculo($obj);
        break;

    case 'tablaVehiculos':
        tablaVehiculos($obj);
        break;

    case 'frmEditarVehiculo':
        frmEditarVehiculo($obj, $idr);
        break;

    case 'frmRefaccion':
        FormularioNuevoRefaccion();
        break;

    case 'frmEditarRefaccion':
        frmEditarRefaccion($obj, $idr);
        break;

    case 'tablaRefacciones':
        tablaRefacciones($obj);
        break;

    case 'FormularioAgendarCita':
        $sqlCli  = "SELECT id_usuario, nombre FROM usuarios WHERE rol = 'cliente' ORDER BY nombre ASC";
        $clientes = $obj->consultardatos($sqlCli);
        FormularioAgendarCita($clientes);
        break;

    case 'tablaCitas':
        $filtro = isset($_REQUEST['filtro']) ? esc($con, $_REQUEST['filtro']) : '';
        tablaCitas($obj, $filtro);
        break;

    // Vistas heredadas / públicas
    case 'fmrSomos':        MostrarSomos();         break;
    case 'frmConfianza':    Confianza();             break;
    case 'frmvision':       PantallaVision();        break;
    case 'Ubicacion':       MostrarUbicacion();      break;
    case 'VerServicios':    MostrarServicios(1);     break;
    case 'DetalleAfinacion':   DetalleAfinacion();  break;
    case 'DetalleFrenos':      MostrarDetalleFrenos();    break;
    case 'DetalleScanner':     MostrarDetalleScanner();   break;
    case 'DetalleSuspension':  MostrarDetalleSuspension();break;
    case 'DetalleElectrico':   MostrarDetalleElectrico(); break;

    // ============================================================
    //  CRUD CLIENTES
    // ============================================================
    case 'Opsclientes':
        if ($ops === 'Insert') {
            $nombre   = esc($con, $_POST['nombre']    ?? '');
            $email    = esc($con, $_POST['email']     ?? '');
            $telefono = esc($con, $_POST['telefono']  ?? '');
            $direccion= esc($con, $_POST['direccion'] ?? '');

            if (empty($nombre) || empty($email)) {
                jsonResp(false, 'Nombre y correo son obligatorios.');
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                jsonResp(false, 'El formato del correo no es válido.');
            }
            // Verificar email duplicado
            $chk = $obj->consultardatos("SELECT id_usuario FROM usuarios WHERE email='$email'");
            if ($chk && count($chk) > 0) {
                jsonResp(false, 'Ya existe un cliente con ese correo.');
            }
            $sql = "INSERT INTO usuarios (nombre, email, password, rol, telefono, direccion)
                    VALUES ('$nombre','$email','123456','cliente','$telefono','$direccion')";
            $r = $obj->guardardatos($sql);
            if (strpos($r, 'Error') !== false) jsonResp(false, $r);
            jsonResp(true, 'Cliente registrado correctamente.');
        }
        elseif ($ops === 'Update') {
            $id       = (int)($_POST['idr']       ?? 0);
            $nombre   = esc($con, $_POST['nombre']    ?? '');
            $email    = esc($con, $_POST['email']     ?? '');
            $telefono = esc($con, $_POST['telefono']  ?? '');
            $direccion= esc($con, $_POST['direccion'] ?? '');

            if (empty($nombre) || empty($email)) jsonResp(false, 'Nombre y correo son obligatorios.');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) jsonResp(false, 'Correo inválido.');
            // Verificar que el email no esté en uso por OTRO cliente
            $chk = $obj->consultardatos("SELECT id_usuario FROM usuarios WHERE email='$email' AND id_usuario != $id");
            if ($chk && count($chk) > 0) jsonResp(false, 'Ese correo ya está registrado con otro cliente.');

            $sql = "UPDATE usuarios SET nombre='$nombre', email='$email',
                    telefono='$telefono', direccion='$direccion'
                    WHERE id_usuario = $id";
            $obj->actualizadatos($sql);
            jsonResp(true, 'Cliente actualizado correctamente.');
        }
        elseif ($ops === 'Delete') {
            $id  = (int)($_POST['idr'] ?? $idr);
            $sql = "DELETE FROM usuarios WHERE id_usuario = $id";
            $obj->eliminardatos($sql);
            jsonResp(true, 'Cliente eliminado.');
        }
        break;

    // ============================================================
    //  CRUD VEHÍCULOS
    // ============================================================
    case 'OpsVehiculos':
        if ($ops === 'Insert') {
            $id_cliente = (int)($_POST['id_cliente'] ?? 0);
            $placa      = strtoupper(esc($con, $_POST['placa']   ?? ''));
            $marca      = esc($con, $_POST['marca']   ?? '');
            $modelo     = esc($con, $_POST['modelo']  ?? '');
            $anio       = (int)($_POST['anio']  ?? 0);
            $color      = esc($con, $_POST['color']   ?? '');

            if (!$id_cliente || empty($placa) || empty($marca) || empty($modelo) || !$anio) {
                jsonResp(false, 'Todos los campos obligatorios son requeridos.');
            }
            if ($anio < 1980 || $anio > (int)date('Y') + 1) {
                jsonResp(false, 'El año debe estar entre 1980 y ' . ((int)date('Y') + 1) . '.');
            }
            $chk = $obj->consultardatos("SELECT id_vehiculo FROM vehiculos WHERE placa='$placa'");
            if ($chk && count($chk) > 0) jsonResp(false, 'Ya existe un vehículo con esa placa.');

            $sql = "INSERT INTO vehiculos (id_cliente, placa, marca, modelo, anio, color)
                    VALUES ($id_cliente,'$placa','$marca','$modelo',$anio,'$color')";
            $r = $obj->guardardatos($sql);
            if (strpos($r, 'Error') !== false) jsonResp(false, $r);
            jsonResp(true, 'Vehículo registrado correctamente.');
        }
        elseif ($ops === 'Update') {
            $id         = (int)($_POST['idr']        ?? $idr);
            $id_cliente = (int)($_POST['id_cliente'] ?? 0);
            $placa      = strtoupper(esc($con, $_POST['placa']   ?? ''));
            $marca      = esc($con, $_POST['marca']   ?? '');
            $modelo     = esc($con, $_POST['modelo']  ?? '');
            $anio       = (int)($_POST['anio']  ?? 0);
            $color      = esc($con, $_POST['color']   ?? '');

            $chk = $obj->consultardatos("SELECT id_vehiculo FROM vehiculos WHERE placa='$placa' AND id_vehiculo != $id");
            if ($chk && count($chk) > 0) jsonResp(false, 'Esa placa ya está registrada en otro vehículo.');

            $sql = "UPDATE vehiculos SET id_cliente=$id_cliente, placa='$placa',
                    marca='$marca', modelo='$modelo', anio=$anio, color='$color'
                    WHERE id_vehiculo = $id";
            $obj->actualizadatos($sql);
            jsonResp(true, 'Vehículo actualizado.');
        }
        elseif ($ops === 'Delete') {
            $id = (int)($_POST['idr'] ?? $idr);
            $obj->eliminardatos("DELETE FROM vehiculos WHERE id_vehiculo = $id");
            jsonResp(true, 'Vehículo eliminado.');
        }
        break;

    // ============================================================
    //  CRUD REFACCIONES
    // ============================================================
    case 'OpsRefacciones':
        if ($ops === 'Insert') {
            $nombre      = esc($con, $_POST['nombre']      ?? '');
            $descripcion = esc($con, $_POST['descripcion'] ?? '');
            $precio      = (float)($_POST['precio'] ?? 0);
            $stock       = (int)($_POST['stock']   ?? 0);
            $categoria   = esc($con, $_POST['categoria']   ?? '');

            if (empty($nombre) || $precio <= 0 || $stock < 0) {
                jsonResp(false, 'Nombre, precio y stock son requeridos y deben ser positivos.');
            }
            $sql = "INSERT INTO refacciones (nombre, descripcion, precio, stock, categoria)
                    VALUES ('$nombre','$descripcion',$precio,$stock,'$categoria')";
            $r = $obj->guardardatos($sql);
            if (strpos($r, 'Error') !== false) jsonResp(false, $r);
            jsonResp(true, 'Refacción agregada al inventario.');
        }
        elseif ($ops === 'Update') {
            $id          = (int)($_POST['idr']         ?? $idr);
            $nombre      = esc($con, $_POST['nombre']      ?? '');
            $descripcion = esc($con, $_POST['descripcion'] ?? '');
            $precio      = (float)($_POST['precio'] ?? 0);
            $stock       = (int)($_POST['stock']   ?? 0);
            $categoria   = esc($con, $_POST['categoria']   ?? '');

            $sql = "UPDATE refacciones SET nombre='$nombre', descripcion='$descripcion',
                    precio=$precio, stock=$stock, categoria='$categoria'
                    WHERE id_refaccion = $id";
            $obj->actualizadatos($sql);
            jsonResp(true, 'Refacción actualizada.');
        }
        elseif ($ops === 'Delete') {
            $id = (int)($_POST['idr'] ?? $idr);
            $obj->eliminardatos("DELETE FROM refacciones WHERE id_refaccion = $id");
            jsonResp(true, 'Refacción eliminada del inventario.');
        }
        break;

    // ============================================================
    //  CRUD CITAS
    // ============================================================
    case 'OpsCitas':
        if ($ops === 'Insert') {
            $id_cliente  = (int)($_POST['id_cliente']  ?? 0);
            $id_vehiculo = (int)($_POST['id_vehiculo'] ?? 0);
            $fecha       = esc($con, $_POST['fecha_cita']           ?? '');
            $hora        = esc($con, $_POST['hora_cita']            ?? '');
            $servicio    = esc($con, $_POST['servicio_solicitado']  ?? '');
            $notas       = esc($con, $_POST['notas']                ?? '');

            if (!$id_cliente || !$id_vehiculo || empty($fecha) || empty($hora) || empty($servicio)) {
                jsonResp(false, 'Cliente, vehículo, fecha, hora y servicio son obligatorios.');
            }
            if ($fecha < date('Y-m-d')) {
                jsonResp(false, 'La fecha de la cita no puede ser en el pasado.');
            }
            $sql = "INSERT INTO citas (id_cliente, id_vehiculo, fecha_cita, hora_cita, servicio_solicitado, notas)
                    VALUES ($id_cliente, $id_vehiculo, '$fecha', '$hora', '$servicio', '$notas')";
            $r = $obj->guardardatos($sql);
            if (strpos($r, 'Error') !== false) jsonResp(false, $r);
            jsonResp(true, 'Cita agendada correctamente.');
        }
        elseif ($ops === 'CambiarEstatus') {
            $id      = (int)($_POST['idr']    ?? $idr);
            $estatus = esc($con, $_POST['estatus'] ?? '');
            $validos = ['Pendiente','En proceso','Terminado','Cancelado'];
            if (!in_array($estatus, $validos)) jsonResp(false, 'Estatus inválido.');
            $obj->actualizadatos("UPDATE citas SET estatus='$estatus' WHERE id_cita = $id");
            jsonResp(true, 'Estatus actualizado a: ' . $estatus);
        }
        elseif ($ops === 'Delete') {
            $id = (int)($_POST['idr'] ?? $idr);
            $obj->eliminardatos("DELETE FROM citas WHERE id_cita = $id");
            jsonResp(true, 'Cita eliminada.');
        }
        break;

    // ============================================================
    //  AJAX: Carros por cliente
    // ============================================================
    case 'ObtenerCarrosCliente':
        $id_cli = (int)($_POST['id_cliente'] ?? 0);
        $carros = $obj->consultardatos(
            "SELECT id_vehiculo, marca, modelo, placa FROM vehiculos WHERE id_cliente = $id_cli ORDER BY marca"
        );
        echo '<option value="">-- Selecciona el vehículo --</option>';
        if ($carros) {
            foreach ($carros as $c) {
                echo "<option value='{$c['id_vehiculo']}'>{$c['marca']} {$c['modelo']} ({$c['placa']})</option>";
            }
        } else {
            echo '<option value="">Este cliente no tiene vehículos registrados.</option>';
        }
        break;

    // ============================================================
    //  AJAX: Datos para el dashboard
    // ============================================================
    case 'DatosDashboard':
        header('Content-Type: application/json');
        $clientes   = $obj->consultardatos("SELECT COUNT(*) as total FROM usuarios WHERE rol='cliente'");
        $vehiculos  = $obj->consultardatos("SELECT COUNT(*) as total FROM vehiculos");
        $citasPend  = $obj->consultardatos("SELECT COUNT(*) as total FROM citas WHERE estatus='Pendiente'");
        $refacciones= $obj->consultardatos("SELECT COUNT(*) as total FROM refacciones");
        echo json_encode([
            'clientes'    => $clientes[0]['total']    ?? 0,
            'vehiculos'   => $vehiculos[0]['total']   ?? 0,
            'citas_pend'  => $citasPend[0]['total']   ?? 0,
            'refacciones' => $refacciones[0]['total'] ?? 0,
        ]);
        break;

    // ============================================================
    //  CONSULTA PÚBLICA
    // ============================================================
    case 'ConsultaPublica':
        $placa = esc($con, $_REQUEST['placa'] ?? '');
        consultarEstadoPublico($placa);
        break;

    // Secciones de cliente (VistasCliente.php)
    case 'cli_inicio':    MostrarBienvenidaCliente();      break;
    case 'cli_servicios': MostrarServiciosCliente();       break;
    case 'cli_somos':     MostrarQuienesSomosCliente();    break;
    case 'cli_mision':    MostrarMisionVisionCliente();    break;
    case 'cli_consulta':  ConsultaPublicaForm();           break;

    default:
        if ($idopc !== '') {
            echo "<div class='alert alert-warning m-3'>Opción no reconocida: <b>" . htmlspecialchars($idopc) . "</b></div>";
        }
        break;
}

// ============================================================
//  Funciones auxiliares del controlador
// ============================================================
function DetalleAfinacion() {
?>
<div class="container py-4">
    <button class="btn btn-sm btn-secondary mb-3" onclick="cargarVista('VerServicios')">
        <i class="bi bi-arrow-left"></i> Volver a servicios
    </button>
    <div class="row align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold">Afinación Completa</h2>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><i class="bi bi-check2 text-primary me-2"></i>Cambio de aceite y filtro</li>
                <li class="list-group-item"><i class="bi bi-check2 text-primary me-2"></i>Limpieza de inyectores</li>
                <li class="list-group-item"><i class="bi bi-check2 text-primary me-2"></i>Cambio de bujías</li>
                <li class="list-group-item"><i class="bi bi-check2 text-primary me-2"></i>Revisión de filtros de aire/gasolina</li>
            </ul>
            <h4 class="text-primary fw-bold mt-3">Desde $1,200 MXN</h4>
        </div>
    </div>
</div>
<?php
}

function MostrarDetalleElectrico() {
?>
<div class="container py-4">
    <button class="btn btn-sm btn-secondary mb-3" onclick="cargarVista('VerServicios')">
        <i class="bi bi-arrow-left"></i> Volver
    </button>
    <div class="badge bg-info mb-2">Sistema Eléctrico</div>
    <h1 class="fw-bold text-dark mb-3">Sistema Eléctrico</h1>
    <ul class="list-group list-group-flush">
        <li class="list-group-item"><i class="bi bi-lightning-charge text-info me-2"></i>Diagnóstico de marchas y alternadores</li>
        <li class="list-group-item"><i class="bi bi-lightning-charge text-info me-2"></i>Revisión y carga de batería</li>
        <li class="list-group-item"><i class="bi bi-lightning-charge text-info me-2"></i>Reparación de arneses</li>
    </ul>
</div>
<?php
}
?>
