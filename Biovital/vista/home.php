<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BioVital - Sistema de Gestión Clínica</title>
    
    <script>
        var APP_URL = '<?php echo rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); ?>';
        console.log('APP_URL:', APP_URL);
    </script>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px 0;
        }
        
        .navbar-brand img {
            height: 50px;
        }
        
        .navbar-brand span {
            font-size: 1.5rem;
            font-weight: 700;
            color: #4e73df;
            margin-left: 10px;
        }
        
        /* Carrusel */
        .carousel {
            margin-top: 76px;
        }
        
        .carousel-item {
            height: 500px;
        }
        
        .carousel-item img {
            object-fit: cover;
            height: 100%;
            width: 100%;
        }
        
        .carousel-caption {
            background: rgba(0,0,0,0.5);
            border-radius: 10px;
            padding: 20px;
        }
        
        .carousel-caption h3 {
            font-size: 2rem;
            font-weight: 600;
        }
        
        /* Tarjetas de acceso */
        .access-section {
            padding: 60px 0;
            background: #f8f9fa;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 15px;
        }
        
        .section-title p {
            color: #666;
            font-size: 1.1rem;
        }
        
        .access-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .access-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        
        .access-card .icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 2.5rem;
            color: white;
        }
        
        .card-paciente .icon { background: #4e73df; }
        .card-medico .icon { background: #1cc88a; }
        .card-asistente .icon { background: #36b9cc; }
        .card-administrador .icon { background: #e74a3b; }
        
        .access-card h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #333;
        }
        
        .access-card p {
            color: #666;
            margin-bottom: 0;
        }
        
        /* Modal de login */
        .modal-content {
            border-radius: 15px;
            border: none;
        }
        
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            border: none;
        }
        
        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }
        
        .login-form .input-group {
            margin-bottom: 20px;
        }
        
        .login-form .input-group-text {
            background: #f0f0f0;
            border: none;
            border-radius: 10px 0 0 10px;
        }
        
        .login-form .form-control {
            border: none;
            border-radius: 0 10px 10px 0;
            background: #f0f0f0;
            padding: 12px 15px;
        }
        
        .login-form .form-control:focus {
            box-shadow: none;
            background: #e8e8e8;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            margin-top: 10px;
        }
        
        .btn-login:hover {
            transform: scale(1.02);
            background: linear-gradient(135deg, #5a67d8 0%, #6b46a0 100%);
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .register-link a {
            color: #4e73df;
            text-decoration: none;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        footer {
            background: #2c3e50;
            color: white;
            padding: 30px 0;
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .carousel-item {
                height: 300px;
            }
            .carousel-caption h3 {
                font-size: 1.2rem;
            }
            .carousel-caption p {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo APP_URL; ?>">
            <img src="<?php echo APP_URL; ?>/img/logo_azul.png" alt="Logo">
            <span>BioVital</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Servicios</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Nosotros</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Contacto</a></li>
            </ul>
        </div>
    </div>
</nav>

<div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="<?php echo APP_URL; ?>/img/Dotora1.jpg" class="d-block w-100" alt="Hospital">
            <div class="carousel-caption d-none d-md-block">
                <h3>Atención Médica de Calidad</h3>
                <p>Comprometidos con tu salud y bienestar</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="<?php echo APP_URL; ?>/img/banner.png" class="d-block w-100" alt="Médicos">
            <div class="carousel-caption d-none d-md-block">
                <h3>Profesionales Especializados</h3>
                <p>Contamos con los mejores especialistas</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="<?php echo APP_URL; ?>/img/imagen_tecnologia.png" class="d-block w-100" alt="Tecnología">
            <div class="carousel-caption d-none d-md-block">
                <h3>Tecnología de Punta</h3>
                <p>Equipos modernos para tu diagnóstico</p>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<section class="access-section">
    <div class="container">
        <div class="section-title">
            <h2>Portal de Acceso</h2>
            <p>Seleccione su perfil para acceder al sistema</p>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="access-card card-paciente" data-rol="paciente">
                    <div class="icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3>Paciente</h3>
                    <p>Acceda a sus recetas y citas médicas</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="access-card card-medico" data-rol="medico">
                    <div class="icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h3>Médico</h3>
                    <p>Gestione sus pacientes y recetas</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="access-card card-asistente" data-rol="asistente">
                    <div class="icon">
                        <i class="fas fa-user-nurse"></i>
                    </div>
                    <h3>Asistente</h3>
                    <p>Administre la agenda y pacientes</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="access-card card-administrador" data-rol="administrador">
                    <div class="icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h3>Administrador</h3>
                    <p>Control total del sistema</p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="loginModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Iniciar Sesión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="loginForm" class="login-form">
                    <input type="hidden" id="rol" name="rol" value="">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                        <input type="text" class="form-control" id="cedula" name="user" placeholder="Cédula">
                    </div>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="pass" placeholder="Contraseña">
                    </div>
                    <button type="submit" class="btn btn-primary btn-login">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </button>
                    <div class="register-link" id="registerLink">
                        <a href="#" id="registerButton">¿No tienes cuenta? Regístrate aquí</a>
                    </div>
                    <div id="loginError" class="alert alert-danger mt-3" style="display:none;"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<footer>
    <div class="container">
        <p>&copy; 2026 BioVital - Sistema de Gestión Clínica. Todos los derechos reservados.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    if (typeof APP_URL === 'undefined') {
        window.APP_URL = '<?php echo rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); ?>';
    }
    
    var modal = null;
    var modalElement = document.getElementById('loginModal');
    if (modalElement) {
        modal = new bootstrap.Modal(modalElement);
    }
    
    function abrirModalConRol(rol) {
        var titulo = '';
        var registerUrl = '';
        
        switch(rol) {
            case 'paciente':
                titulo = 'Acceso Paciente';
                registerUrl = APP_URL + '/registro/paciente';
                break;
            case 'medico':
                titulo = 'Acceso Médico';
                registerUrl = APP_URL + '/registro/medico';
                break;
            case 'asistente':
                titulo = 'Acceso Asistente';
                registerUrl = APP_URL + '/registro/asistente';
                break;
            case 'administrador':
                titulo = 'Acceso Administrador';
                registerUrl = APP_URL + '/registro/administrador';
                break;
            default:
                return false;
        }
        
        $('#modalTitle').text(titulo);
        $('#rol').val(rol);
        $('#registerButton').attr('href', registerUrl);
        
        $('#cedula').val('');
        $('#password').val('');
        $('#loginError').hide().text('');
        
        var $btn = $('#loginForm').find('button[type="submit"]');
        $btn.prop('disabled', false).html('<i class="fas fa-sign-in-alt"></i> Iniciar Sesión');
        
        if (modal) {
            modal.show();
        }
        return true;
    }
    
    $(document).on('click', '.access-card', function() {
        var rol = $(this).data('rol');
        abrirModalConRol(rol);
    });
    
    var urlParams = new URLSearchParams(window.location.search);
    var openLogin = urlParams.get('openLogin');
    if (openLogin) {
        setTimeout(function() {
            abrirModalConRol(openLogin);
            var newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.replaceState({}, document.title, newUrl);
        }, 300);
    }
    
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        
        var rol = $('#rol').val();
        var cedula = $('#cedula').val();
        var password = $('#password').val();
        
        if (cedula) cedula = cedula.trim();
        if (password) password = password.trim();
        
        // INTERCEPCIÓN TOTAL: Si está vacío, frena en seco inmediatamente
        if (!rol || !cedula || !password || cedula === "" || password === "") {
            mostrarError('Por favor, rellene todos los campos antes de ingresar.');
            return false;
        }
        
        var $btn = $(this).find('button[type="submit"]');
        var originalText = $btn.html();
        
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Ingresando...');
        
        $.ajax({
            url: APP_URL + '/login',
            type: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                user: cedula,
                pass: password,
                rol: rol
            },
            dataType: 'json',
            timeout: 15000,
            success: function(response) {
                if (response.success) {
                    window.location.href = APP_URL + '/panel/' + rol;
                } else {
                    mostrarError(response.message || response.error || 'Cédula o contraseña incorrecta');
                    $btn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr, status, error) {
                var errorMsg = 'Error de conexión. ';
                if (xhr.status === 405) {
                    errorMsg += 'Método no permitido.';
                } else {
                    try {
                        var jsonResponse = JSON.parse(xhr.responseText);
                        if (jsonResponse && jsonResponse.message) {
                            errorMsg = jsonResponse.message;
                        }
                    } catch(e) {
                        errorMsg += 'Intente nuevamente.';
                    }
                }
                mostrarError(errorMsg);
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    function mostrarError(mensaje) {
        var $errorDiv = $('#loginError');
        if ($errorDiv.length) {
            $errorDiv.stop(true, true).text(mensaje).fadeIn();
            setTimeout(function() {
                $errorDiv.fadeOut();
            }, 5000);
        } else {
            alert(mensaje);
        }
    }
});
</script>

</body>
</html>