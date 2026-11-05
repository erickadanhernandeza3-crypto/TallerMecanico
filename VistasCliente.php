<?php






// 1. Pantalla de Bienvenida (La que sale al inicio o al dar clic en Inicio)

// VistasClientes.php - Aquí SOLO van las funciones, sin switch ni variables $_GET.

function MostrarBienvenidaCliente() {
?>
    <div class="container animate__animated animate__fadeIn">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="bg-white shadow-lg rounded-4 p-5 text-center" style="margin-top: 80px; position: relative; z-index: 20;">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-primary" style="font-size: 3.5rem;"></i>
                    </div>
                    <h1 class="fw-bold text-dark mb-3">¡Expertos en cada detalle!</h1>
                    <p class="lead text-muted mb-4">
                        En Taller CHECO combinamos años de experiencia con diagnósticos computarizados 
                        de última generación en la Huasteca.
                    </p>
                    <div class="d-flex justify-content-center gap-2 mb-2">
                        <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">Confianza</span>
                        <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">Transparencia</span>
                        <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">Tecnología</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5 py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="display-5 fw-bold text-dark mb-4">TALLER MECÁNICO <span class="text-primary">AUTOMOTRIZ CHECHO HDZ </span></h2>
                <p class="fs-5 text-muted">
                    Somos especialistas en reparación, mantenimiento y mecánica En carros. 
                    Atención personalizada a precios competitivos en toda la región.
                </p>
                <div class="mt-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-arrow-right-circle-fill text-primary fs-4 me-3"></i>
                        <span class="fw-bold">Atención vial y reparación en el camino 24-7</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-arrow-right-circle-fill text-primary fs-4 me-3"></i>
                        <span class="fw-bold">Personal altamente capacitado y certificado</span>
                    </div>
                </div>
                <a href="https://wa.me/4831078918" class="btn btn-primary btn-lg mt-4 px-5 rounded-pill shadow">¡Llámanos Ahora!</a>
            </div>
            
            <div class="col-lg-6 text-center">
                <div class="position-relative">
                    <img src="Taller01.jpg" class="img-fluid rounded-5 shadow-lg border border-5 border-white" alt="Taller">
                    <div class="position-absolute top-0 start-0 m-3 badge bg-danger p-2 shadow">Rescate 24/7</div>
                </div>
            </div>
        </div>

     

        <div class="row g-4 mt-5">
             <div class="col-md-4">
                 <div class="p-4 bg-white rounded-4 shadow-sm border-bottom border-primary border-4 h-100">
                     <img src="img/motoRepatado.jpg" class="img-fluid rounded mb-3" alt="Motores">
                     <h5 class="fw-bold">Motores y Diferenciales</h5>
                     <p class="small text-muted">Reparacion  de  armado y mantenimiento correcto de motores.</p>
                
                 </div>
             </div>
             <div class="col-md-4">
                 <div class="p-4 bg-white rounded-4 shadow-sm border-bottom border-primary border-4 h-100">
                     <img src="img/Sistemadefreno.jpg" class="img-fluid rounded mb-3" alt="Suspensión">
                     <h5 class="fw-bold">Frenos y Suspensión</h5>
                     <p class="small text-muted">Mnatenimiento de sistema de freno y suspencio y alineacion de llantas.</p>
                 </div>
             </div>
             <div class="col-md-4">
                 <div class="p-4 bg-white rounded-4 shadow-sm border-bottom border-primary border-4 h-100">
                     <img src="img/scanner.jpg" class="img-fluid rounded mb-3" alt="Escaner">
                     <h5 class="fw-bold">Detección de Fallos</h5>
                     <p class="small text-muted">Diagnóstico preciso por medio de computadora y escaner especializado.</p>
                 </div>
             </div>
        </div>
    </div>
<?php
}






// 2. El Catálogo de Servicios
function MostrarServiciosCliente() {
?>
<div class="container py-5 animate__animated animate__fadeIn">
    <div class="text-center mb-5">
        <h6 class="text-primary fw-bold text-uppercase tracking-widest">Soluciones de Ingeniería</h6>
        <h2 class="display-5 fw-bold text-dark">Catálogo de Servicios Profesionales</h2>
        <div class="mx-auto bg-primary rounded-pill" style="height: 4px; width: 80px;"></div>
        <p class="text-muted mt-3 fs-5">Mantenimiento preventivo y correctivo con estándares de alta calidad.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm service-card overflow-hidden" 
                 onclick="buscar('DetalleAfinacion', 'escenario-principal', 'ver', 0, 0)">
                <div class="position-relative">
                    <img src="img/Afinacion.jpg" class="card-img-top" alt="Afinación" style="height: 200px; object-fit: cover;">
                   
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box-small bg-primary-subtle text-primary me-2"><i class="bi bi-gear-wide-connected"></i></div>
                        <h5 class="card-title fw-bold mb-0">Afinación General</h5>
                    </div>
                    <p class="card-text text-muted small">Limpieza de inyectores, cambio de bujías y filtros para un motor optimizado al 100%.</p>
                    <div class="text-primary fw-bold small mt-3">Ver detalles <i class="bi bi-arrow-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm service-card overflow-hidden" 
                 onclick="buscar('DetalleFrenos', 'escenario-principal', 'ver', 0, 0)">
                <div class="position-relative">
                    <img src="img/Sistemadefreno.jpg" class="card-img-top" alt="Frenos" style="height: 200px; object-fit: cover;">
                   
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box-small bg-danger-subtle text-danger me-2"><i class="bi bi-disc-fill"></i></div>
                        <h5 class="card-title fw-bold mb-0">Frenos y Seguridad</h5>
                    </div>
                    <p class="card-text text-muted small">Rectificado de discos, cambio de balatas y revisión del sistema ABS por expertos.</p>
                    <div class="text-danger fw-bold small mt-3">Ver detalles <i class="bi bi-arrow-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm service-card overflow-hidden" 
                 onclick="buscar('DetalleScanner', 'escenario-principal', 'ver', 0, 0)">
                <div class="position-relative">
                    <img src="img/scanner.jpg" class="card-img-top" alt="Scanner" style="height: 200px; object-fit: cover;">
                 
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box-small bg-warning-subtle text-warning me-2"><i class="bi bi-cpu"></i></div>
                        <h5 class="card-title fw-bold mb-0">Diagnóstico por Scanner</h5>
                    </div>
                    <p class="card-text text-muted small">Detección de códigos de error y fallas electrónicas con equipos de última generación.</p>
                    <div class="text-warning fw-bold small mt-3">Ver detalles <i class="bi bi-arrow-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm service-card overflow-hidden" 
                 onclick="buscar('DetalleSuspension', 'escenario-principal', 'ver', 0, 0)">
                <div class="position-relative">
                    <img src="img/suspension.jpg" class="card-img-top" alt="Suspensión" style="height: 200px; object-fit: cover;">
                    
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box-small bg-success-subtle text-success me-2"><i class="bi bi-tools"></i></div>
                        <h5 class="card-title fw-bold mb-0">Suspensión y Dirección</h5>
                    </div>
                    <p class="card-text text-muted small">Cambio de amortiguadores, rótulas y terminales para una estabilidad perfecta.</p>
                    <div class="text-success fw-bold small mt-3">Ver detalles <i class="bi bi-arrow-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm service-card overflow-hidden" 
                 onclick="buscar('DetalleElectrico', 'escenario-principal', 'ver', 0, 0)">
                <div class="position-relative">
                    <img src="img/electrico.jpg" class="card-img-top" alt="Eléctrico" style="height: 200px; object-fit: cover;">
                    
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box-small bg-info-subtle text-info me-2"><i class="bi bi-lightning-charge"></i></div>
                        <h5 class="card-title fw-bold mb-0">Sistema Eléctrico</h5>
                    </div>
                    <p class="card-text text-muted small">Reparación de marchas, alternadores y diagnóstico de fallas en el cableado general.</p>
                    <div class="text-info fw-bold small mt-3">Ver detalles <i class="bi bi-arrow-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm service-card overflow-hidden" 
                 onclick="buscar('DetalleDiesel', 'escenario-principal', 'ver', 0, 0)">
                <div class="position-relative">
                    <img src="img/disel.jpg" class="card-img-top" alt="Diesel" style="height: 200px; object-fit: cover;">
                    <div class="service-badge bg-dark">Mecánica Pesada</div>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box-small bg-dark-subtle text-dark me-2"><i class="bi bi-truck"></i></div>
                        <h5 class="card-title fw-bold mb-0">Mecánica Diésel</h5>
                    </div>
                    <p class="card-text text-muted small">Especialistas en motores de alta potencia y tractocamiones nacionales e importados.</p>
                    <div class="text-dark fw-bold small mt-3">Ver detalles <i class="bi bi-arrow-right"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .service-card { transition: all 0.3s ease; border-radius: 20px; cursor: pointer; }
    .service-card:hover { transform: translateY(-10px); shadow: 0 1rem 3rem rgba(0,0,0,.175) !important; }
    .icon-box-small { width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 10px; font-size: 1rem; }
    .service-badge { position: absolute; top: 15px; right: 15px; padding: 5px 15px; border-radius: 50px; font-size: 0.7rem; font-weight: bold; text-transform: uppercase; color: white; }
    .tracking-widest { letter-spacing: 0.1em; }
</style>
<?php
}





// 3. Formulario de Consulta de Placas
function ConsultaPublicaForm() {
?>
<div class="container py-5 animate__animated animate__fadeIn">
    
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 p-4 rounded-4 bg-white border-start border-primary border-5">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary-subtle p-3 rounded-circle me-3">
                        <i class="bi bi-search fs-2 text-primary"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold text-dark mb-0">Rastrea tu Reparación</h2>
                        <p class="text-muted mb-0">Consulta el avance de tu unidad con tu número de placa.</p>
                    </div>
                </div>
                <div class="input-group mb-2">
                    <input type="text" id="txtPlaca" class="form-control form-control-lg text-uppercase" placeholder="EJ: ABC-123-A" style="letter-spacing: 2px; font-weight: bold;">
                    <button class="btn btn-primary px-5 fw-bold" onclick="consultarEstado()">
                        <i class="bi bi-search me-2"></i>BUSCAR ESTATUS
                    </button>
                </div>
                <div id="resultadoConsulta" class="mt-3"></div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mb-5">
    <div class="col-lg-10">
        <div class="card shadow-lg border-0 p-5 rounded-4 text-white" 
             style="background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%); border-left: 8px solid #007bff !important;">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="fw-bold mb-3">
                        <i class="bi bi-chat-right-dots-fill text-info me-2"></i>¿Dudas técnicas? Habla con el Encargado
                    </h3>
                    <p class="opacity-75 mb-4 fs-5">Envía tus comentarios o dudas sobre tu presupuesto y te responderemos a la brevedad.</p>
                    
                    <form id="frmMensajeEncargado">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating text-dark">
                                    <input type="text" name="nombre_cli" class="form-control border-0 shadow-sm" id="floatNom" placeholder="Tu Nombre" style="background: rgba(255,255,255,0.9);">
                                    <label for="floatNom">Tu Nombre</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating text-dark">
                                    <input type="text" name="tel_cli" class="form-control border-0 shadow-sm" id="floatTel" placeholder="WhatsApp" style="background: rgba(255,255,255,0.9);">
                                    <label for="floatTel">WhatsApp / Teléfono</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating text-dark">
                                    <textarea name="mensaje_cli" class="form-control border-0 shadow-sm" id="floatMsg" placeholder="Duda" style="background: rgba(255,255,255,0.9); height: 100px;"></textarea>
                                    <label for="floatMsg">Escribe tu duda técnica aquí...</label>
                                </div>
                            </div>
                            <div class="col-12 text-end">
                                <button type="button" class="btn btn-info btn-lg px-5 fw-bold shadow-sm text-white rounded-pill" onclick="EnviarMensajeEncargado()">
                                    <i class="bi bi-send-check-fill me-2"></i>ENVIAR AL ENCARGADO
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 text-center d-none d-md-block">
                    <div class="p-4 rounded-circle bg-white bg-opacity-10 d-inline-block">
                        <i class="bi bi-headset text-info" style="font-size: 7rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-6">
                        <div class="h-100" style="min-height: 350px;">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3725.123456789!2d-98.789!3d21.234!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjHCsDE0JzAyLjQiTiA5OMKwNDcnMjAuNCJX!5e0!3m2!1ses!2smx!4v123456789" 
                                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                    <div class="col-md-6 bg-light p-5">
                        <h4 class="fw-bold text-dark mb-3"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Nuestra Ubicación</h4>
                        <p class="text-muted">Visítanos en nuestras instalaciones en <b>Tamazunchale, S.L.P.</b> Contamos con entrada para equipo pesado y seguridad 24/7.</p>
                        
                        <h6 class="fw-bold mt-4 mb-3 text-uppercase small text-muted">Galería de Instalaciones</h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <img src="img/taller1.jpg" class="img-fluid rounded-3 shadow-sm hover-img" alt="Fachada">
                            </div>
                            <div class="col-6">
                                <img src="img/taller2.jpg" class="img-fluid rounded-3 shadow-sm hover-img" alt="Interior">
                            </div>
                        </div>
                        <div class="mt-4 d-flex justify-content-between align-items-center">
                            <span class="badge bg-success">Abierto ahora</span>
                            <div class="text-warning">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-secondary { background-color: #3d444b !important; }
    .hover-img { transition: 0.3s; cursor: pointer; }
    .hover-img:hover { transform: scale(1.05); }
</style>
<?php
}

// 4. Quiénes Somos
function MostrarQuienesSomosCliente() {
?>
<div class="container py-5 animate__animated animate__fadeIn">
    <div class="row align-items-center mb-5">
        <div class="col-lg-7 mb-4 mb-lg-0">
            <h1 class="display-4 fw-bold text-dark mb-3">Tu auto en manos de <br><span class="text-primary">Ingenieros</span></h1>
            <p class="text-muted fs-5 mb-4">En Taller CHECO no solo reparamos, diagnosticamos con precisión quirúrgica basándonos en datos y tecnología de vanguardia en la Huasteca.</p>
            
            <div class="row g-4 mt-2">
                <div class="col-md-6">
                    <h2 class="fw-bold h4 mb-3"><i class="bi bi-clock-history text-primary"></i> Nuestra Historia</h2>
                    <p class="text-muted small">
                        Nacimos con el objetivo de elevar el estándar mecánico regional. De un pequeño taller familiar a un centro especializado en vehículos particulares y flotas Diésel.
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="p-3 bg-primary-subtle rounded-4 border-start border-primary border-4">
                        <h4 class="fw-bold text-primary h6 mb-2"><i class="bi bi-eye-fill"></i> Visión 2026</h4>
                        <p class="mb-0 small">Liderar la ingeniería automotriz en San Luis Potosí con diagnósticos remotos e IA.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card bg-dark text-white p-4 shadow-lg border-0" style="border-radius: 30px;">
                <div class="card-body text-center py-4">
                    <i class="bi bi-whatsapp fs-1 text-success mb-3"></i>
                    <h3 class="fw-bold mb-2">¿Falla en carretera?</h3>
                    <p class="small opacity-75 mb-4">Rescate vial en Tamazunchale y alrededores 24/7. No importa la hora, nosotros vamos.</p>
                    <a href="https://wa.me/4831078918" target="_blank" class="btn btn-success btn-lg w-100 rounded-pill py-3 fw-bold shadow">
                        Contactar a Soporte
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 py-4 border-top">
        <div class="col-md-6">
            <div class="p-4 bg-light rounded-4 border-start border-dark border-4 h-100">
                <h4 class="fw-bold text-dark"><i class="bi bi-award-fill"></i> Nuestro Compromiso</h4>
                <p class="mb-0 text-muted">Transparencia absoluta. El cliente recibe evidencia fotográfica y en video de cada fallo detectado antes de realizar cualquier gasto. Sin sorpresas en tu cuenta final.</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="d-flex gap-4 h-100 align-items-center justify-content-center">
                <div class="bg-white p-3 shadow-sm rounded-3 border-start border-primary border-4 text-center" style="min-width: 140px;">
                    <h2 class="fw-bold mb-0 text-primary">+150</h2>
                    <p class="small text-muted mb-0">Servicios/Mes</p>
                </div>
                <div class="bg-white p-3 shadow-sm rounded-3 border-start border-success border-4 text-center" style="min-width: 140px;">
                    <h2 class="fw-bold mb-0 text-success">100%</h2>
                    <p class="small text-muted mb-0">Garantía</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 py-5 text-center bg-light rounded-5 px-3 mt-2">
        <div class="col-md-4">
            <div class="p-4 h-100">
                <div class="icon-circle bg-primary text-white mx-auto shadow mb-3" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="bi bi-shield-fill-check fs-2"></i>
                </div>
                <h5 class="fw-bold">Seguridad Certificada</h5>
                <p class="small text-muted">Protocolos de verificación industrial en cada ajuste realizado.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 h-100">
                <div class="icon-circle bg-danger text-white mx-auto shadow mb-3" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="bi bi-fuel-pump-fill fs-2"></i>
                </div>
                <h5 class="fw-bold">Ahorro de Gasolina</h5>
                <p class="small text-muted">Ajustes precisos para maximizar el rendimiento por litro.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 h-100">
                <div class="icon-circle bg-warning text-dark mx-auto shadow mb-3" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="bi bi-camera-video-fill fs-2"></i>
                </div>
                <h5 class="fw-bold">Reportes Digitales</h5>
                <p class="small text-muted">Fotos y videos de tu reparación directo a tu WhatsApp.</p>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <div class="position-relative rounded-5 overflow-hidden shadow-lg" style="height: 250px;">
                <img src="img/taller-interior.jpg" class="w-100 h-100" style="object-fit: cover;" alt="Nuestro Taller">
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.4);">
                    <div class="text-center text-white p-3">
                        <h2 class="fw-bold h3">Equipamiento de Grado Industrial</h2>
                        <p class="mb-0">Rampas, escáners y precisión quirúrgica en cada reto.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}



// 5. Misión y Visión
function MostrarMisionVisionCliente() {
?>
<div class="container mt-5 animate__animated animate__fadeIn">
    <div class="row mb-5">
        <div class="col-12">
            <div class="rounded-5 shadow-lg overflow-hidden position-relative" style="height: 250px;">
                <img src="img/mision-banner.jpg" class="w-100 h-100" style="object-fit: cover;" alt="Filosofía Checo">
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(to right, rgba(13, 110, 253, 0.8), rgba(0,0,0,0.6));">
                    <div class="text-center text-white p-3">
                        <h2 class="display-5 fw-bold">Nuestra Filosofía</h2>
                        <p class="fs-5">Comprometidos con la excelencia automotriz en cada kilómetro.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden border-start border-primary border-5">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary-subtle p-3 rounded-circle me-3">
                            <i class="bi bi-bullseye fs-1 text-primary"></i>
                        </div>
                        <h3 class="fw-bold mb-0 text-dark">Nuestra Misión</h3>
                    </div>
                    <p class="text-muted fs-5">
                        Proveer un servicio automotriz de alta ingeniería, garantizando la seguridad y movilidad 
                        de nuestros clientes mediante diagnósticos precisos, tecnología de vanguardia y un 
                        acompañamiento honesto en cada reparación.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden border-start border-dark border-5">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-dark-subtle p-3 rounded-circle me-3">
                            <i class="bi bi-eye fs-1 text-dark"></i>
                        </div>
                        <h3 class="fw-bold mb-0 text-dark">Nuestra Visión</h3>
                    </div>
                    <p class="text-muted fs-5">
                        Consolidarnos para el 2028 como el centro de servicio diésel y gasolina más innovador 
                        de la Huasteca, siendo referentes en honestidad, rapidez y uso de herramientas 
                        computarizadas de grado industrial.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-light rounded-5 p-5 shadow-sm text-center">
        <h3 class="fw-bold text-dark mb-5">Valores que nos definen</h3>
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <i class="bi bi-shield-lock-fill text-primary display-5 mb-3"></i>
                <h6 class="fw-bold">Honestidad</h6>
                <p class="small text-muted">Presupuestos reales sin costos ocultos.</p>
            </div>
            <div class="col-md-3 col-6">
                <i class="bi bi-lightning-charge-fill text-warning display-5 mb-3"></i>
                <h6 class="fw-bold">Puntualidad</h6>
                <p class="small text-muted">Entregamos tu unidad en el tiempo pactado.</p>
            </div>
            <div class="col-md-3 col-6">
                <i class="bi bi-award-fill text-success display-5 mb-3"></i>
                <h6 class="fw-bold">Calidad</h6>
                <p class="small text-muted">Piezas certificadas y mano de obra experta.</p>
            </div>
            <div class="col-md-3 col-6">
                <i class="bi bi-people-fill text-info display-5 mb-3"></i>
                <h6 class="fw-bold">Servicio</h6>
                <p class="small text-muted">Atención personalizada y acompañamiento vial.</p>
            </div>
        </div>
    </div>
</div>
<?php
}
// Función que une TODO para la pantalla principal


?>
