// ============================================================
// funciones.js - Taller Mecánico CHECO
// ============================================================

let cargando = false;

// -------------------------------------------------------
//  CORE: buscar() — carga vistas AJAX en un contenedor
// -------------------------------------------------------
function buscar(idopc, iddiv, ops, idr, msg) {
    if (cargando) return;

    let hero = document.getElementById('hero-inicio');
    if (hero) hero.style.display = (idopc === 'frmBienvenida') ? 'block' : 'none';

    let contenedor = seleccionardiv(iddiv);
    if (!contenedor) { console.error('Contenedor no encontrado: ' + iddiv); return; }

    cargando = true;
    contenedor.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-2 text-muted">Cargando...</p></div>';

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'Controlador.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        cargando = false;
        if (xhr.status === 200) {
            contenedor.style.opacity = 0;
            contenedor.innerHTML = xhr.responseText;
            setTimeout(() => {
                contenedor.style.transition = 'opacity 0.4s';
                contenedor.style.opacity = 1;
            }, 30);
        } else {
            contenedor.innerHTML = `<div class="alert alert-danger m-3">Error del servidor (${xhr.status}).</div>`;
        }
    };
    xhr.onerror = function () {
        cargando = false;
        Swal.fire({ icon: 'error', title: 'Error de red', text: 'Verifica que el servidor XAMPP esté activo.' });
    };

    xhr.send(`idopc=${encodeURIComponent(idopc)}&ops=${encodeURIComponent(ops)}&idr=${encodeURIComponent(idr)}&msg=${encodeURIComponent(msg)}`);
}

// Alias global usado en Administrador.php
function cargarVista(idopc, idr = 0) {
    buscar(idopc, 'contenedor', 'ver', idr, 0);
    const ws = document.getElementById('welcomeScreen');
    if (ws) ws.style.display = 'none';
}

// -------------------------------------------------------
//  CORE: seleccionardiv()
// -------------------------------------------------------
function seleccionardiv(iddiv) {
    let el = document.getElementById(iddiv);
    if (el) return el;
    switch (String(iddiv)) {
        case '1': return document.getElementById('contenedor');
        case '2': return document.getElementById('contenedor2');
        case '4': return document.getElementById('contenidoMensaje');
        default:
            console.warn('Mapeo no encontrado para: ' + iddiv);
            return null;
    }
}

// -------------------------------------------------------
//  VALIDACIÓN GENÉRICA
// -------------------------------------------------------
/**
 * Marca en rojo los campos requeridos vacíos.
 * Retorna true si el formulario es válido.
 */
function ValidarFormulario(idForm) {
    const form = document.getElementById(idForm);
    if (!form) return false;

    let valido = true;
    // Limpiar errores previos
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => el.style.display = 'none');

    form.querySelectorAll('[required]').forEach(campo => {
        if (campo.value.trim() === '') {
            campo.classList.add('is-invalid');
            const fb = campo.parentElement.querySelector('.invalid-feedback');
            if (fb) fb.style.display = 'block';
            if (valido) campo.focus();
            valido = false;
        }
    });

    // Validar emails
    form.querySelectorAll('input[type="email"]').forEach(campo => {
        if (campo.value.trim() !== '' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(campo.value.trim())) {
            campo.classList.add('is-invalid');
            valido = false;
        }
    });

    return valido;
}

// -------------------------------------------------------
//  CLIENTES
// -------------------------------------------------------
function GuardarCliente() {
    if (!ValidarFormulario('formCliente')) {
        Swal.fire({ icon: 'warning', title: 'Campos incompletos', text: 'Por favor completa todos los campos obligatorios.' });
        return;
    }
    const formData = new FormData(document.getElementById('formCliente'));
    formData.append('idopc', 'Opsclientes');
    formData.append('ops', 'Insert');

    fetch('Controlador.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ icon: 'success', title: '¡Listo!', text: data.mensaje, timer: 2000, showConfirmButton: false });
                document.getElementById('formCliente').reset();
                setTimeout(() => cargarVista('tablaClientes'), 2100);
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.mensaje });
            }
        })
        .catch(() => Swal.fire({ icon: 'error', title: 'Error de conexión' }));
}

function EditarCliente(id) {
    cargarVista('frmEditarCliente', id);
}

function ActualizarCliente() {
    const formData = new FormData(document.getElementById('frmEditar'));
    formData.append('idopc', 'Opsclientes');
    formData.append('ops', 'Update');

    fetch('Controlador.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ icon: 'success', title: 'Actualizado', text: data.mensaje, timer: 2000, showConfirmButton: false });
                setTimeout(() => cargarVista('tablaClientes'), 2100);
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.mensaje });
            }
        });
}

function EliminarCliente(id) {
    Swal.fire({
        title: '¿Eliminar cliente?',
        text: 'Esta acción también eliminará sus vehículos y citas.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            const fd = new FormData();
            fd.append('idopc', 'Opsclientes');
            fd.append('ops', 'Delete');
            fd.append('idr', id);
            fetch('Controlador.php', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    Swal.fire({ icon: data.success ? 'success' : 'error', title: data.mensaje, timer: 1800, showConfirmButton: false });
                    if (data.success) setTimeout(() => cargarVista('tablaClientes'), 1900);
                });
        }
    });
}

// EnviarDatos legacy (mantenida para compatibilidad)
function EnviarDatos(idopc, iddiv, idform, ops, idr, msg) {
    if (cargando) return;
    const contenedor = seleccionardiv(iddiv);
    const formulario = document.getElementById(idform);
    if (!contenedor || !formulario) return;

    cargando = true;
    const formData = new FormData(formulario);
    formData.append('idopc', idopc);
    formData.append('ops', ops);
    formData.append('idr', idr);
    formData.append('msg', msg);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'Controlador.php', true);
    xhr.onload = function () {
        cargando = false;
        formulario.reset();
        buscar('tablaClientes', 'contenedor', 'ver', 0, 0);
        Swal.fire({ icon: 'success', title: '¡Guardado!', timer: 1500, showConfirmButton: false });
    };
    xhr.onerror = function () { cargando = false; };
    xhr.send(formData);
}

// -------------------------------------------------------
//  VEHÍCULOS
// -------------------------------------------------------
function GuardarVehiculo() {
    if (!ValidarFormulario('frmVehiculo')) {
        Swal.fire({ icon: 'warning', title: 'Campos incompletos', text: 'Completa todos los campos obligatorios.' });
        return;
    }
    const anio = parseInt(document.querySelector('#frmVehiculo [name="anio"]')?.value);
    const anioActual = new Date().getFullYear();
    if (anio < 1980 || anio > anioActual + 1) {
        Swal.fire({ icon: 'warning', title: 'Año inválido', text: `El año debe estar entre 1980 y ${anioActual + 1}.` });
        return;
    }

    const formData = new FormData(document.getElementById('frmVehiculo'));
    formData.append('idopc', 'OpsVehiculos');
    formData.append('ops', 'Insert');

    fetch('Controlador.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ icon: 'success', title: '¡Registrado!', text: data.mensaje, timer: 2000, showConfirmButton: false });
                document.getElementById('frmVehiculo').reset();
                setTimeout(() => cargarVista('tablaVehiculos'), 2100);
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.mensaje });
            }
        });
}

function EditarVehiculo(id) {
    cargarVista('frmEditarVehiculo', id);
}

function ActualizarVehiculo() {
    const formData = new FormData(document.getElementById('frmEditVehiculo'));
    formData.append('idopc', 'OpsVehiculos');
    formData.append('ops', 'Update');

    fetch('Controlador.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ icon: 'success', title: 'Actualizado', text: data.mensaje, timer: 2000, showConfirmButton: false });
                setTimeout(() => cargarVista('tablaVehiculos'), 2100);
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.mensaje });
            }
        });
}

function EliminarVehiculo(id) {
    Swal.fire({
        title: '¿Eliminar vehículo?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            const fd = new FormData();
            fd.append('idopc', 'OpsVehiculos');
            fd.append('ops', 'Delete');
            fd.append('idr', id);
            fetch('Controlador.php', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    Swal.fire({ icon: data.success ? 'success' : 'error', title: data.mensaje, timer: 1800, showConfirmButton: false });
                    if (data.success) setTimeout(() => cargarVista('tablaVehiculos'), 1900);
                });
        }
    });
}

// -------------------------------------------------------
//  REFACCIONES
// -------------------------------------------------------
function GuardarRefaccion() {
    const precio = parseFloat(document.getElementById('ref_precio')?.value);
    const stock  = parseInt(document.getElementById('ref_stock')?.value);

    if (!ValidarFormulario('frmRefaccion')) {
        Swal.fire({ icon: 'warning', title: 'Campos incompletos', text: 'Nombre, precio y stock son obligatorios.' });
        return;
    }
    if (isNaN(precio) || precio <= 0) {
        Swal.fire({ icon: 'warning', title: 'Precio inválido', text: 'El precio debe ser un número positivo.' });
        return;
    }
    if (isNaN(stock) || stock < 0) {
        Swal.fire({ icon: 'warning', title: 'Stock inválido', text: 'El stock no puede ser negativo.' });
        return;
    }

    const formData = new FormData(document.getElementById('frmRefaccion'));
    formData.append('idopc', 'OpsRefacciones');
    formData.append('ops', 'Insert');

    fetch('Controlador.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ icon: 'success', title: '¡Guardado!', text: data.mensaje, timer: 2000, showConfirmButton: false });
                document.getElementById('frmRefaccion').reset();
                setTimeout(() => cargarVista('tablaRefacciones'), 2100);
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.mensaje });
            }
        });
}

function EditarRefaccion(id) {
    cargarVista('frmEditarRefaccion', id);
}

function ActualizarRefaccion() {
    const formData = new FormData(document.getElementById('frmEditRefaccion'));
    formData.append('idopc', 'OpsRefacciones');
    formData.append('ops', 'Update');

    fetch('Controlador.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ icon: 'success', title: 'Actualizado', text: data.mensaje, timer: 2000, showConfirmButton: false });
                setTimeout(() => cargarVista('tablaRefacciones'), 2100);
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.mensaje });
            }
        });
}

function EliminarRefaccion(id) {
    Swal.fire({
        title: '¿Eliminar refacción?',
        text: 'Se eliminará del inventario permanentemente.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            const fd = new FormData();
            fd.append('idopc', 'OpsRefacciones');
            fd.append('ops', 'Delete');
            fd.append('idr', id);
            fetch('Controlador.php', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        // Eliminar fila de la tabla sin recargar
                        const row = document.getElementById('row-ref-' + id);
                        if (row) row.remove();
                        Swal.fire({ icon: 'success', title: 'Eliminado', text: data.mensaje, timer: 1800, showConfirmButton: false });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: data.mensaje });
                    }
                });
        }
    });
}

// -------------------------------------------------------
//  CITAS
// -------------------------------------------------------
function GuardarCita() {
    const cliente  = document.getElementById('cboClienteCita')?.value;
    const vehiculo = document.getElementById('cboVehiculoCita')?.value;
    const fecha    = document.getElementById('cita_fecha')?.value;
    const hora     = document.getElementById('cita_hora')?.value;
    const servicio = document.getElementById('cita_servicio')?.value;

    if (!cliente || !vehiculo || !fecha || !hora || !servicio) {
        Swal.fire({ icon: 'warning', title: 'Faltan datos', text: 'Cliente, vehículo, fecha, hora y servicio son obligatorios.' });
        return;
    }

    // Validar que la fecha no sea pasada
    const hoy = new Date().toISOString().split('T')[0];
    if (fecha < hoy) {
        Swal.fire({ icon: 'warning', title: 'Fecha inválida', text: 'La fecha de la cita no puede ser en el pasado.' });
        return;
    }

    // Validar horario laboral (08:00 - 18:00)
    const horaNum = parseInt(hora.replace(':', ''));
    if (horaNum < 800 || horaNum > 1800) {
        Swal.fire({ icon: 'warning', title: 'Horario inválido', text: 'El horario de atención es de 8:00 AM a 6:00 PM.' });
        return;
    }

    const formData = new FormData(document.getElementById('frmAgendarCita'));
    formData.append('idopc', 'OpsCitas');
    formData.append('ops', 'Insert');

    fetch('Controlador.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ icon: 'success', title: '¡Cita agendada!', text: data.mensaje, timer: 2000, showConfirmButton: false });
                setTimeout(() => cargarVista('tablaCitas'), 2100);
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.mensaje });
            }
        });
}

function CambiarEstatusCita(id, estatusActual) {
    const opciones = {
        'Pendiente':  'Pendiente',
        'En proceso': 'En proceso',
        'Terminado':  'Terminado',
        'Cancelado':  'Cancelado',
    };
    let opcionesHtml = '';
    for (const [val, lbl] of Object.entries(opciones)) {
        const sel = val === estatusActual ? 'selected' : '';
        opcionesHtml += `<option value="${val}" ${sel}>${lbl}</option>`;
    }

    Swal.fire({
        title: 'Cambiar Estatus de Cita',
        html: `<label class="form-label">Nuevo estatus:</label>
               <select id="nuevoEstatus" class="form-select">${opcionesHtml}</select>`,
        showCancelButton: true,
        confirmButtonText: 'Actualizar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#0d6efd',
    }).then(result => {
        if (result.isConfirmed) {
            const nuevoEstatus = document.getElementById('nuevoEstatus').value;
            const fd = new FormData();
            fd.append('idopc', 'OpsCitas');
            fd.append('ops', 'CambiarEstatus');
            fd.append('idr', id);
            fd.append('estatus', nuevoEstatus);

            fetch('Controlador.php', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    Swal.fire({ icon: data.success ? 'success' : 'error', title: data.mensaje, timer: 1800, showConfirmButton: false });
                    if (data.success) setTimeout(() => cargarVista('tablaCitas'), 1900);
                });
        }
    });
}

function FiltrarCitasPorEstatus(estatus) {
    const contenedor = document.getElementById('contenedor');
    if (!contenedor) return;
    cargando = true;
    contenedor.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';

    const fd = new FormData();
    fd.append('idopc', 'tablaCitas');
    fd.append('ops', 'ver');
    fd.append('filtro', estatus);

    fetch('Controlador.php', { method: 'POST', body: fd })
        .then(r => r.text())
        .then(html => {
            cargando = false;
            contenedor.innerHTML = html;
        });
}

function EliminarCita(id) {
    Swal.fire({
        title: '¿Cancelar/Eliminar cita?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'No'
    }).then(result => {
        if (result.isConfirmed) {
            const fd = new FormData();
            fd.append('idopc', 'OpsCitas');
            fd.append('ops', 'Delete');
            fd.append('idr', id);
            fetch('Controlador.php', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    Swal.fire({ icon: data.success ? 'success' : 'error', title: data.mensaje, timer: 1800, showConfirmButton: false });
                    if (data.success) setTimeout(() => cargarVista('tablaCitas'), 1900);
                });
        }
    });
}

// -------------------------------------------------------
//  AJAX: cargar carros de un cliente en el select
// -------------------------------------------------------
function FiltrarCarrosPorCliente(idCliente) {
    const select = document.getElementById('cboVehiculoCita');
    if (!select) return;
    if (!idCliente) {
        select.innerHTML = '<option value="">-- Primero selecciona un cliente --</option>';
        return;
    }
    select.innerHTML = '<option>Cargando...</option>';
    const fd = new FormData();
    fd.append('idopc', 'ObtenerCarrosCliente');
    fd.append('id_cliente', idCliente);

    fetch('Controlador.php', { method: 'POST', body: fd })
        .then(r => r.text())
        .then(html => { select.innerHTML = html; });
}


// -------------------------------------------------------
//  CONSULTA PÚBLICA (index.php)
// -------------------------------------------------------
function consultarEstado() {
    const txtPlaca  = document.getElementById('txtPlaca');
    const contenedor = document.getElementById('resultadoConsulta');
    if (!txtPlaca || !contenedor) return;

    const placa = txtPlaca.value.trim();
    if (!placa) { alert('Ingresa una placa válida.'); return; }

    contenedor.innerHTML = "<div class='text-center'><div class='spinner-border text-primary'></div></div>";
    const xhr = new XMLHttpRequest();
    xhr.open('POST', `Controlador.php?idopc=ConsultaPublica&placa=${encodeURIComponent(placa)}`, true);
    xhr.onload = function () {
        if (xhr.status === 200) contenedor.innerHTML = xhr.responseText;
    };
    xhr.send();
}

// -------------------------------------------------------
//  AUXILIARES
// -------------------------------------------------------
function limpiarPantallaGlobal() {
    ['contenedor','contenidoMensaje','resultadoConsulta'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.innerHTML = '';
    });
}

function cargarServiciosPublicos() {
    const contenedor = document.getElementById('contenedorServicios');
    if (!contenedor) return;
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'Controlador.php?idopc=VerServicios', true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) contenedor.innerHTML = xhr.responseText;
    };
    xhr.send();
}
