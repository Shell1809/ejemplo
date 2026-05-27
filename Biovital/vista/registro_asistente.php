<?php
// vista/registro_asistente.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Asistente - BioVital</title>
    
    <script>
        // Definición global de la URL base del proyecto de manera dinámica
        var APP_URL = '<?php echo defined("APP_URL") ? APP_URL : "/biovital"; ?>';
    </script>
    
    <style>
        body {
            background: #764ba2;
            font-family: Arial, sans-serif;
            padding: 20px;
            color: #333;
        }
        .registro-container {
            max-width: 600px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .registro-header {
            background: #28a745;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-control {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }
        .col-md-6 {
            flex: 1;
        }
        .btn-registro {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }
        .btn-registro:hover {
            background: #218838;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        .login-link a {
            color: #764ba2;
            text-decoration: none;
            font-weight: bold;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="registro-container">
        <div class="registro-header">
            <h2>Registro de Asistente</h2>
            <p>Complete todos los campos para registrarse</p>
        </div>

        <form id="form-registro">
            <?php 
            $securityPath = dirname(__DIR__) . '/modelo/Security.php';
            if (file_exists($securityPath)) {
                include_once $securityPath;
                if (class_exists('Security')) {
                    echo Security::campoCSRF(); 
                }
            }
            ?>

            <input type="hidden" name="estado" value="Lara">
            <input type="hidden" name="ciudad" value="Barquisimeto">
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nombre *</label>
                        <input type="text" class="form-control" name="nombre" value="MICHELLE" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Apellido *</label>
                        <input type="text" class="form-control" name="apellidos" value="DELGADO" required>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Fecha de Nacimiento *</label>
                        <input type="date" class="form-control" name="fecha_nacimiento" value="2003-09-18" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Cédula *</label>
                        <input type="text" class="form-control" name="cedula" value="30178901" required>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Teléfono *</label>
                        <input type="tel" class="form-control" name="telefono" value="04245088336" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Sexo *</label>
                        <select class="form-control" name="sexo" required>
                            <option value="Femenino" selected>Femenino</option>
                            <option value="Masculino">Masculino</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <h3>Ubicación</h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Municipio</label>
                        <select class="form-control" name="municipio">
                            <option value="Iribarren" selected>Iribarren</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Parroquia</label>
                        <select class="form-control" name="parroquia">
                            <option value="Juan de Villegas" selected>Juan de Villegas</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Dirección Detallada *</label>
                <input type="text" class="form-control" name="direccion" value="Lomas de Leon, calle aragua" required>
            </div>

            <h3>Credenciales de Acceso</h3>
            <div class="form-group">
                <label>Correo Electrónico *</label>
                <input type="email" class="form-control" name="correo" value="michelle_prod_test@gmail.com" required>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Contraseña *</label>
                        <input type="password" class="form-control" name="pass" id="password" value="123456" minlength="6" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Confirmar Contraseña *</label>
                        <input type="password" class="form-control" name="confirm_pass" id="confirm_pass" value="123456" minlength="6" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Información Adicional</label>
                <textarea class="form-control" name="info_adicional" rows="2"></textarea>
            </div>

            <button type="submit" class="btn-registro">Crear Cuenta / Registrar</button>
            
            <div class="login-link">
                <p>¿Ya tienes cuenta? <a href="<?php echo defined('APP_URL') ? APP_URL : '/biovital'; ?>/"><i class="fas fa-sign-in-alt"></i> Inicia sesión aquí</a></p>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('form-registro').addEventListener('submit', function(event) {
            event.preventDefault(); 
            
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_pass').value;

            if (password !== confirmPassword) {
                alert("Las contraseñas ingresadas no coinciden.");
                return; 
            }

            var formData = new FormData(this);
            
            // Construcción dinámica del endpoint de la API
            fetch(APP_URL + '/api/registro/asistente', {
                method: 'POST',
                body: formData
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    alert(data.message);
                    
                    // CORRECCIÓN: Redirección directa a la raíz del proyecto para evitar errores 405
                    window.location.href = APP_URL + '/'; 
                } else {
                    alert("Aviso del sistema: " + data.message);
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                alert("Ocurrió un inconveniente al procesar el formulario.");
            });
        });
    </script>
</body>
</html>

