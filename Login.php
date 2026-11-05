<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso - Taller Adán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('img/fondo_taller.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            color: white;
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
        }
        .form-control {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            border-radius: 10px;
        }
        .form-control:focus {
            background: rgba(255,255,255,0.2);
            color: white;
            box-shadow: none;
            border-color: #0d6efd;
        }
        .btn-login {
            border-radius: 10px;
            padding: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .logo-icon {
            font-size: 3rem;
            color: #0d6efd;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="login-card text-center">
    <div class="logo-icon">
        <i class="bi bi-gear-wide-connected"></i>
    </div>
    <h2 class="fw-bold mb-4">Taller Mecanico</h2>
    
    <form action="Controlador.php?idopc=AccesoLogin" method="POST">
        <div class="mb-3 text-start">
            <label class="form-label small text-uppercase opacity-75">Correo Electrónico</label>
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0 text-white"><i class="bi bi-person"></i></span>
                <input type="email" name="usuario" class="form-control border-start-0" placeholder="admin@taller.com" required>
            </div>
        </div>
        
        <div class="mb-4 text-start">
            <label class="form-label small text-uppercase opacity-75">Contraseña</label>
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0 text-white"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control border-start-0" placeholder="••••••••" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 btn-login mb-3 shadow">Entrar al Sistema</button>
        
        <div class="mt-3">
            <a href="index.php" class="text-white-50 text-decoration-none small">
                <i class="bi bi-arrow-left"></i> Volver a la página pública
            </a>
        </div>
    </form>
</div>

</body>
</html>