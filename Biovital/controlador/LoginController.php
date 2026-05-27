
<?php
// controlador/LoginController.php

// Asegurar que el modelo esté incluido para poder usar la autenticación
$modelPath = dirname(__DIR__) . '/modelo/LoginPaciente.php';
if (file_exists($modelPath)) {
    include_once $modelPath;
}

class LoginController {
    
    public function index() {
        // Mostrar la página de login
        if (file_exists(VIEW_PATH . '/login/index.php')) {
            require_once VIEW_PATH . '/login/index.php';
        } else {
            // Si no existe la vista, mostrar tu index2.php
            require_once ROOT_PATH . '/index2.php';
        }
    }
    
    public function loginPaciente() {
        // Obligar a que la respuesta del servidor siempre sea interpretada como JSON
        header('Content-Type: application/json');

        // Verificar que la petición sea estrictamente por método POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Método no permitido. El formulario debe enviarse por POST.'
            ]);
            exit();
        }

        // Capturar los valores que vienen del formulario (Cédula y Contraseña)
        // Nota: Asegúrate de que en tu HTML los inputs tengan name="cedula" y name="pass"
        $cedula = isset($_POST['cedula']) ? trim($_POST['cedula']) : '';
        $pass = isset($_POST['pass']) ? $_POST['pass'] : '';

        // Validar que los campos no viajen vacíos desde el cliente
        if (empty($cedula) || empty($pass)) {
            echo json_encode([
                'success' => false,
                'message' => 'Por favor, complete todos los campos requeridos.'
            ]);
            exit();
        }

        // Instanciar el modelo de datos que corregimos en el Paso 1
        $loginModelo = new LoginPaciente();
        $resultado = $loginModelo->Loguearse($cedula, $pass);

        // SOLUCIÓN AL ERROR: Si el array regresa vacío, el usuario no existe o la clave está mal
        if (empty($resultado)) {
            echo json_encode([
                'success' => false,
                'message' => 'Usuario no registrado o datos de acceso incorrectos.'
            ]);
            exit();
        }

        // Si el login fue exitoso, creamos la sesión del paciente
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Guardamos los datos del usuario logueado en la sesión global
        $usuarioLogueado = $resultado[0];
        $_SESSION['usuario'] = $usuarioLogueado->nombre_paciente . ' ' . $usuarioLogueado->apellido_paciente;
        $_SESSION['id_usuario'] = $usuarioLogueado->id_paciente;
        $_SESSION['tipo_usuario'] = $usuarioLogueado->nombre_tipo;

        // Responder con éxito para que JavaScript sepa que puede redirigir
        echo json_encode([
            'success' => true,
            'message' => '¡Inicio de sesión exitoso! Redireccionando...'
        ]);
        exit();
    }
    
    public function loginMedico() {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Procesando login de médico']);
        exit();
    }
    
    public function loginAsistente() {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Procesando login de asistente']);
        exit();
    }
    
    public function loginAdministrador() {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Procesando login de administrador']);
        exit();
    }
    
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: ' . APP_URL);
        exit();
    }
}
?>