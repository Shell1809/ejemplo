<?php

class PagoController {
    
    public function index() {
        if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'asistente') {
            header('Location: ' . APP_URL . '/login/asistente');
            exit();
        }
        
        $id_asistente = $_SESSION['usuario'];
        $nombre_usuario = $_SESSION['nombre'] ?? 'Asistente';
        
        $data = [
            'nombre_usuario' => $nombre_usuario,
            'id_asistente' => $id_asistente
        ];
        
        ViewHelper::renderView('asistente/asi_pagos', $data);
    }
    
    public function listar() {
        if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'asistente') {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_UNAUTHORIZED, 'No autorizado');
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
            }
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_METHOD_NOT_ALLOWED, 'Método no permitido');
            } else {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            }
            exit();
        }
        
        $filtros = [
            'estado' => $_POST['estado'] ?? null,
            'fecha_inicio' => $_POST['fecha_inicio'] ?? null,
            'fecha_fin' => $_POST['fecha_fin'] ?? null,
            'id_paciente' => $_POST['id_paciente'] ?? null
        ];
        
        $pago = new Pago();
        $pagos = $pago->obtenerTodos($filtros);
        
        if (class_exists('ApiResponse')) {
            ApiResponse::send(true, ApiResponse::CODE_SUCCESS, 'Pagos obtenidos', $pagos);
        } else {
            echo json_encode(['success' => true, 'data' => $pagos]);
        }
    }
    
    public function obtener() {
        if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'asistente') {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_UNAUTHORIZED, 'No autorizado');
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
            }
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_METHOD_NOT_ALLOWED, 'Método no permitido');
            } else {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            }
            exit();
        }
        
        $id_pago = $_POST['id_pago'] ?? null;
        
        if (!$id_pago) {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_BAD_REQUEST, 'ID de pago requerido');
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'ID de pago requerido']);
            }
            exit();
        }
        
        $pago = new Pago();
        $pagoData = $pago->obtenerPorId($id_pago);
        
        if ($pagoData) {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(true, ApiResponse::CODE_SUCCESS, 'Pago obtenido', $pagoData);
            } else {
                echo json_encode(['success' => true, 'data' => $pagoData]);
            }
        } else {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_NOT_FOUND, 'Pago no encontrado');
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Pago no encontrado']);
            }
        }
    }
    
    public function crear() {
        if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'asistente') {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_UNAUTHORIZED, 'No autorizado');
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
            }
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_METHOD_NOT_ALLOWED, 'Método no permitido');
            } else {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            }
            exit();
        }
        
        // Validar CSRF
        if (!Security::validarCSRF($_POST['csrf_token'] ?? '')) {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_FORBIDDEN, 'Error de validación CSRF');
            } else {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Error de validación CSRF']);
            }
            exit();
        }
        
        $datos = [
            'id_paciente' => $_POST['id_paciente'] ?? null,
            'id_asistente' => $_SESSION['usuario'],
            'id_receta' => $_POST['id_receta'] ?? null,
            'tipo_pago' => $_POST['tipo_pago'] ?? null,
            'monto' => $_POST['monto'] ?? null,
            'referencia_pago' => $_POST['referencia_pago'] ?? null,
            'descripcion_pago' => $_POST['descripcion_pago'] ?? null
        ];
        
        // Validaciones
        if (!$datos['id_paciente'] || !$datos['tipo_pago'] || !$datos['monto']) {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_BAD_REQUEST, 'Datos incompletos');
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            }
            exit();
        }
        
        $pago = new Pago();
        $resultado = $pago->crear($datos);
        
        if ($resultado['success']) {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(true, ApiResponse::CODE_CREATED, 'Pago creado exitosamente', $resultado);
            } else {
                echo json_encode(['success' => true, 'message' => 'Pago creado exitosamente', 'data' => $resultado]);
            }
        } else {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_INTERNAL_ERROR, $resultado['message']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => $resultado['message']]);
            }
        }
    }
    
    public function confirmar() {
        if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'asistente') {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_UNAUTHORIZED, 'No autorizado');
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
            }
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_METHOD_NOT_ALLOWED, 'Método no permitido');
            } else {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            }
            exit();
        }
        
        // Validar CSRF
        if (!Security::validarCSRF($_POST['csrf_token'] ?? '')) {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_FORBIDDEN, 'Error de validación CSRF');
            } else {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Error de validación CSRF']);
            }
            exit();
        }
        
        $id_pago = $_POST['id_pago'] ?? null;
        $id_asistente = $_SESSION['usuario'];
        
        if (!$id_pago) {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_BAD_REQUEST, 'ID de pago requerido');
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'ID de pago requerido']);
            }
            exit();
        }
        
        $pago = new Pago();
        $resultado = $pago->confirmar($id_pago, $id_asistente);
        
        if ($resultado['success']) {
            // Obtener datos del pago para notificar al paciente
            $pagoData = $pago->obtenerPorId($id_pago);
            
            if ($pagoData) {
                // Crear notificación al paciente
                $notificacion = new Notificacion();
                $notificacion->crearNotificacionPagoConfirmado($pagoData->id_paciente, [
                    'monto' => $pagoData->monto,
                    'tipo_pago' => $pagoData->tipo_pago,
                    'fecha_pago' => $pagoData->fecha_pago,
                    'id_pago' => $pagoData->id_pago
                ]);
            }
            
            if (class_exists('ApiResponse')) {
                ApiResponse::send(true, ApiResponse::CODE_SUCCESS, 'Pago confirmado exitosamente');
            } else {
                echo json_encode(['success' => true, 'message' => 'Pago confirmado exitosamente']);
            }
        } else {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_INTERNAL_ERROR, $resultado['message']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => $resultado['message']]);
            }
        }
    }
    
    public function rechazar() {
        if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'asistente') {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_UNAUTHORIZED, 'No autorizado');
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
            }
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_METHOD_NOT_ALLOWED, 'Método no permitido');
            } else {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            }
            exit();
        }
        
        // Validar CSRF
        if (!Security::validarCSRF($_POST['csrf_token'] ?? '')) {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_FORBIDDEN, 'Error de validación CSRF');
            } else {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Error de validación CSRF']);
            }
            exit();
        }
        
        $id_pago = $_POST['id_pago'] ?? null;
        $id_asistente = $_SESSION['usuario'];
        
        if (!$id_pago) {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_BAD_REQUEST, 'ID de pago requerido');
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'ID de pago requerido']);
            }
            exit();
        }
        
        $pago = new Pago();
        $resultado = $pago->rechazar($id_pago, $id_asistente);
        
        if ($resultado['success']) {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(true, ApiResponse::CODE_SUCCESS, 'Pago rechazado');
            } else {
                echo json_encode(['success' => true, 'message' => 'Pago rechazado']);
            }
        } else {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_INTERNAL_ERROR, $resultado['message']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => $resultado['message']]);
            }
        }
    }
    
    public function emitirComprobante() {
        if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'asistente') {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_UNAUTHORIZED, 'No autorizado');
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
            }
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_METHOD_NOT_ALLOWED, 'Método no permitido');
            } else {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            }
            exit();
        }
        
        // Validar CSRF
        if (!Security::validarCSRF($_POST['csrf_token'] ?? '')) {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_FORBIDDEN, 'Error de validación CSRF');
            } else {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Error de validación CSRF']);
            }
            exit();
        }
        
        $id_pago = $_POST['id_pago'] ?? null;
        
        if (!$id_pago) {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_BAD_REQUEST, 'ID de pago requerido');
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'ID de pago requerido']);
            }
            exit();
        }
        
        $pago = new Pago();
        $pagoData = $pago->obtenerPorId($id_pago);
        
        if (!$pagoData) {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_NOT_FOUND, 'Pago no encontrado');
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Pago no encontrado']);
            }
            exit();
        }
        
        if ($pagoData->estado_pago !== 'confirmado') {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_BAD_REQUEST, 'El pago debe estar confirmado para emitir comprobante');
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'El pago debe estar confirmado para emitir comprobante']);
            }
            exit();
        }
        
        // Generar número de comprobante
        $numero_comprobante = 'COMP-' . date('Ymd') . '-' . str_pad($id_pago, 6, '0', STR_PAD_LEFT);
        
        // Marcar comprobante como emitido
        $resultado = $pago->marcarComprobanteEmitido($id_pago);
        
        if ($resultado['success']) {
            // Crear notificación al paciente
            $notificacion = new Notificacion();
            $notificacion->crearNotificacionComprobanteEmitido($pagoData->id_paciente, [
                'monto' => $pagoData->monto,
                'numero_comprobante' => $numero_comprobante,
                'fecha_emision' => date('Y-m-d H:i:s'),
                'id_pago' => $id_pago,
                'datos_cita' => [
                    'fecha' => $pagoData->fecha_receta ?? null,
                    'diagnostico' => $pagoData->diagnostico ?? null
                ]
            ]);
            
            if (class_exists('ApiResponse')) {
                ApiResponse::send(true, ApiResponse::CODE_SUCCESS, 'Comprobante emitido exitosamente', [
                    'numero_comprobante' => $numero_comprobante,
                    'fecha_emision' => date('Y-m-d H:i:s')
                ]);
            } else {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Comprobante emitido exitosamente',
                    'data' => [
                        'numero_comprobante' => $numero_comprobante,
                        'fecha_emision' => date('Y-m-d H:i:s')
                    ]
                ]);
            }
        } else {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_INTERNAL_ERROR, $resultado['message']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => $resultado['message']]);
            }
        }
    }
    
    public function estadisticas() {
        if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'asistente') {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_UNAUTHORIZED, 'No autorizado');
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
            }
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_METHOD_NOT_ALLOWED, 'Método no permitido');
            } else {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            }
            exit();
        }
        
        $filtros = [
            'fecha_inicio' => $_POST['fecha_inicio'] ?? null,
            'fecha_fin' => $_POST['fecha_fin'] ?? null
        ];
        
        $pago = new Pago();
        $estadisticas = $pago->obtenerEstadisticas($filtros);
        
        if (class_exists('ApiResponse')) {
            ApiResponse::send(true, ApiResponse::CODE_SUCCESS, 'Estadísticas obtenidas', $estadisticas);
        } else {
            echo json_encode(['success' => true, 'data' => $estadisticas]);
        }
    }
    
    public function resumenPeriodo() {
        if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'asistente') {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_UNAUTHORIZED, 'No autorizado');
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
            }
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_METHOD_NOT_ALLOWED, 'Método no permitido');
            } else {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            }
            exit();
        }
        
        $periodo = $_POST['periodo'] ?? 'mes';
        
        $pago = new Pago();
        $resumen = $pago->obtenerResumenPorPeriodo($periodo);
        
        if (class_exists('ApiResponse')) {
            ApiResponse::send(true, ApiResponse::CODE_SUCCESS, 'Resumen obtenido', $resumen);
        } else {
            echo json_encode(['success' => true, 'data' => $resumen]);
        }
    }
    
    public function buscarPacientes() {
        if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'asistente') {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_UNAUTHORIZED, 'No autorizado');
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'No autorizado']);
            }
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if (class_exists('ApiResponse')) {
                ApiResponse::send(false, ApiResponse::CODE_METHOD_NOT_ALLOWED, 'Método no permitido');
            } else {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            }
            exit();
        }
        
        $termino = $_POST['termino'] ?? '';
        $limit = $_POST['limit'] ?? 10;
        
        $paciente = new Paciente();
        $resultados = $paciente->buscarAutocompletar($termino, $limit);
        
        if (class_exists('ApiResponse')) {
            ApiResponse::send(true, ApiResponse::CODE_SUCCESS, 'Pacientes encontrados', $resultados);
        } else {
            echo json_encode(['success' => true, 'data' => $resultados]);
        }
    }
}
?>
