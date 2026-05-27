<?php

include_once 'Conexion.php';

class Notificacion {
    var $objetos;
    var $acceso;
    
    public function __construct() {
        $db = new Conexion();
        $this->acceso = $db->pdo;
    }
    
    // ==================== MÉTODOS PRINCIPALES ====================
    
    function crear($datos) {
        try {
            $sql = "INSERT INTO notificaciones(
                id_usuario, tipo_usuario, tipo_notificacion, titulo, mensaje,
                datos_adicionales, enlace
            ) VALUES (
                :id_usuario, :tipo_usuario, :tipo_notificacion, :titulo, :mensaje,
                :datos_adicionales, :enlace
            )";
            $query = $this->acceso->prepare($sql);
            $resultado = $query->execute(array(
                ':id_usuario' => $datos['id_usuario'],
                ':tipo_usuario' => $datos['tipo_usuario'],
                ':tipo_notificacion' => $datos['tipo_notificacion'],
                ':titulo' => $datos['titulo'],
                ':mensaje' => $datos['mensaje'],
                ':datos_adicionales' => isset($datos['datos_adicionales']) ? json_encode($datos['datos_adicionales']) : null,
                ':enlace' => $datos['enlace'] ?? null
            ));
            
            if ($resultado) {
                $id_notificacion = $this->acceso->lastInsertId();
                return ['success' => true, 'message' => 'notificacion_creada', 'id_notificacion' => $id_notificacion];
            } else {
                return ['success' => false, 'message' => 'error_bd'];
            }
        } catch(PDOException $e) {
            error_log("Error en crear notificacion: " . $e->getMessage());
            return ['success' => false, 'message' => 'error_exception'];
        }
    }
    
    function marcarLeida($id_notificacion) {
        try {
            $sql = "UPDATE notificaciones SET 
                    leida = TRUE,
                    fecha_lectura = NOW()
                    WHERE id_notificacion = :id_notificacion";
            $query = $this->acceso->prepare($sql);
            $resultado = $query->execute(array(':id_notificacion' => $id_notificacion));
            
            if ($resultado) {
                return ['success' => true, 'message' => 'notificacion_leida'];
            } else {
                return ['success' => false, 'message' => 'error_actualizacion'];
            }
        } catch(PDOException $e) {
            error_log("Error en marcarLeida: " . $e->getMessage());
            return ['success' => false, 'message' => 'error_bd'];
        }
    }
    
    function marcarTodasLeidas($id_usuario, $tipo_usuario) {
        try {
            $sql = "UPDATE notificaciones SET 
                    leida = TRUE,
                    fecha_lectura = NOW()
                    WHERE id_usuario = :id_usuario 
                    AND tipo_usuario = :tipo_usuario
                    AND leida = FALSE";
            $query = $this->acceso->prepare($sql);
            $resultado = $query->execute(array(
                ':id_usuario' => $id_usuario,
                ':tipo_usuario' => $tipo_usuario
            ));
            
            if ($resultado) {
                return ['success' => true, 'message' => 'notificaciones_leidas'];
            } else {
                return ['success' => false, 'message' => 'error_actualizacion'];
            }
        } catch(PDOException $e) {
            error_log("Error en marcarTodasLeidas: " . $e->getMessage());
            return ['success' => false, 'message' => 'error_bd'];
        }
    }
    
    // ==================== MÉTODOS DE CONSULTA ====================
    
    function obtenerPorUsuario($id_usuario, $tipo_usuario, $limit = 20) {
        try {
            $sql = "SELECT n.*,
                           CASE 
                               WHEN n.datos_adicionales IS NOT NULL THEN JSON_UNQUOTE(JSON_EXTRACT(n.datos_adicionales, '$.monto'))
                               ELSE NULL
                           END as monto_pago
                    FROM notificaciones n
                    WHERE n.id_usuario = :id_usuario 
                    AND n.tipo_usuario = :tipo_usuario
                    ORDER BY n.fecha_creacion DESC
                    LIMIT :limit";
            $query = $this->acceso->prepare($sql);
            $query->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $query->bindValue(':tipo_usuario', $tipo_usuario, PDO::PARAM_STR);
            $query->bindValue(':limit', $limit, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll();
        } catch(PDOException $e) {
            error_log("Error en obtenerPorUsuario: " . $e->getMessage());
            return [];
        }
    }
    
    function obtenerNoLeidas($id_usuario, $tipo_usuario) {
        try {
            $sql = "SELECT COUNT(*) as total 
                    FROM notificaciones 
                    WHERE id_usuario = :id_usuario 
                    AND tipo_usuario = :tipo_usuario
                    AND leida = FALSE";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(
                ':id_usuario' => $id_usuario,
                ':tipo_usuario' => $tipo_usuario
            ));
            return $query->fetch(PDO::FETCH_OBJ)->total ?? 0;
        } catch(PDOException $e) {
            error_log("Error en obtenerNoLeidas: " . $e->getMessage());
            return 0;
        }
    }
    
    function obtenerPorId($id_notificacion) {
        try {
            $sql = "SELECT n.*,
                           CASE 
                               WHEN n.datos_adicionales IS NOT NULL THEN n.datos_adicionales
                               ELSE NULL
                           END as datos_json
                    FROM notificaciones n
                    WHERE n.id_notificacion = :id_notificacion";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id_notificacion' => $id_notificacion));
            $resultado = $query->fetch(PDO::FETCH_OBJ);
            
            if ($resultado && $resultado->datos_json) {
                $resultado->datos_adicionales = json_decode($resultado->datos_json, true);
            }
            
            return $resultado;
        } catch(PDOException $e) {
            error_log("Error en obtenerPorId: " . $e->getMessage());
            return null;
        }
    }
    
    // ==================== MÉTODOS ESPECIALIZADOS PARA PAGOS ====================
    
    function crearNotificacionPagoConfirmado($id_paciente, $datos_pago) {
        try {
            $datos = [
                'id_usuario' => $id_paciente,
                'tipo_usuario' => 'paciente',
                'tipo_notificacion' => 'pago_confirmado',
                'titulo' => 'Pago Confirmado',
                'mensaje' => "Su pago de {$datos_pago['monto']} ha sido confirmado exitosamente.",
                'datos_adicionales' => [
                    'monto' => $datos_pago['monto'],
                    'tipo_pago' => $datos_pago['tipo_pago'],
                    'fecha_pago' => $datos_pago['fecha_pago'],
                    'id_pago' => $datos_pago['id_pago']
                ],
                'enlace' => '/paciente/recetas'
            ];
            
            return $this->crear($datos);
        } catch(Exception $e) {
            error_log("Error en crearNotificacionPagoConfirmado: " . $e->getMessage());
            return ['success' => false, 'message' => 'error_exception'];
        }
    }
    
    function crearNotificacionComprobanteEmitido($id_paciente, $datos_comprobante) {
        try {
            $datos = [
                'id_usuario' => $id_paciente,
                'tipo_usuario' => 'paciente',
                'tipo_notificacion' => 'comprobante_emitido',
                'titulo' => 'Comprobante de Pago Emitido',
                'mensaje' => "Se ha emitido su comprobante de pago por {$datos_comprobante['monto']}.",
                'datos_adicionales' => [
                    'monto' => $datos_comprobante['monto'],
                    'numero_comprobante' => $datos_comprobante['numero_comprobante'],
                    'fecha_emision' => $datos_comprobante['fecha_emision'],
                    'id_pago' => $datos_comprobante['id_pago'],
                    'datos_cita' => $datos_comprobante['datos_cita'] ?? null
                ],
                'enlace' => '/paciente/recetas'
            ];
            
            return $this->crear($datos);
        } catch(Exception $e) {
            error_log("Error en crearNotificacionComprobanteEmitido: " . $e->getMessage());
            return ['success' => false, 'message' => 'error_exception'];
        }
    }
}
?>
