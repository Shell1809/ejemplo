<?php

include_once 'Conexion.php';

class Pago {
    var $objetos;
    var $acceso;
    
    public function __construct() {
        $db = new Conexion();
        $this->acceso = $db->pdo;
    }
    
    // ==================== MÉTODOS PRINCIPALES ====================
    
    function crear($datos) {
        try {
            $sql = "INSERT INTO pagos(
                id_paciente, id_asistente, id_receta, tipo_pago, monto, 
                referencia_pago, descripcion_pago
            ) VALUES (
                :id_paciente, :id_asistente, :id_receta, :tipo_pago, :monto,
                :referencia_pago, :descripcion_pago
            )";
            $query = $this->acceso->prepare($sql);
            $resultado = $query->execute(array(
                ':id_paciente' => $datos['id_paciente'],
                ':id_asistente' => $datos['id_asistente'],
                ':id_receta' => $datos['id_receta'] ?? null,
                ':tipo_pago' => $datos['tipo_pago'],
                ':monto' => $datos['monto'],
                ':referencia_pago' => $datos['referencia_pago'] ?? null,
                ':descripcion_pago' => $datos['descripcion_pago'] ?? null
            ));
            
            if ($resultado) {
                $id_pago = $this->acceso->lastInsertId();
                return ['success' => true, 'message' => 'pago_creado', 'id_pago' => $id_pago];
            } else {
                return ['success' => false, 'message' => 'error_bd'];
            }
        } catch(PDOException $e) {
            error_log("Error en crear pago: " . $e->getMessage());
            return ['success' => false, 'message' => 'error_exception'];
        }
    }
    
    function confirmar($id_pago, $id_asistente) {
        try {
            $sql = "UPDATE pagos SET 
                    estado_pago = 'confirmado',
                    fecha_confirmacion = NOW(),
                    confirmado_por = :id_asistente
                    WHERE id_pago = :id_pago";
            $query = $this->acceso->prepare($sql);
            $resultado = $query->execute(array(
                ':id_pago' => $id_pago,
                ':id_asistente' => $id_asistente
            ));
            
            if ($resultado) {
                return ['success' => true, 'message' => 'pago_confirmado'];
            } else {
                return ['success' => false, 'message' => 'error_actualizacion'];
            }
        } catch(PDOException $e) {
            error_log("Error en confirmar pago: " . $e->getMessage());
            return ['success' => false, 'message' => 'error_bd'];
        }
    }
    
    function rechazar($id_pago, $id_asistente) {
        try {
            $sql = "UPDATE pagos SET 
                    estado_pago = 'rechazado',
                    fecha_confirmacion = NOW(),
                    confirmado_por = :id_asistente
                    WHERE id_pago = :id_pago";
            $query = $this->acceso->prepare($sql);
            $resultado = $query->execute(array(
                ':id_pago' => $id_pago,
                ':id_asistente' => $id_asistente
            ));
            
            if ($resultado) {
                return ['success' => true, 'message' => 'pago_rechazado'];
            } else {
                return ['success' => false, 'message' => 'error_actualizacion'];
            }
        } catch(PDOException $e) {
            error_log("Error en rechazar pago: " . $e->getMessage());
            return ['success' => false, 'message' => 'error_bd'];
        }
    }
    
    function marcarComprobanteEmitido($id_pago) {
        try {
            $sql = "UPDATE pagos SET comprobante_emitido = TRUE WHERE id_pago = :id_pago";
            $query = $this->acceso->prepare($sql);
            $resultado = $query->execute(array(':id_pago' => $id_pago));
            
            if ($resultado) {
                return ['success' => true, 'message' => 'comprobante_marcado'];
            } else {
                return ['success' => false, 'message' => 'error_actualizacion'];
            }
        } catch(PDOException $e) {
            error_log("Error en marcar comprobante: " . $e->getMessage());
            return ['success' => false, 'message' => 'error_bd'];
        }
    }
    
    // ==================== MÉTODOS DE CONSULTA ====================
    
    function obtenerTodos($filtros = []) {
        try {
            $where = [];
            $params = [];
            
            if (isset($filtros['estado']) && !empty($filtros['estado'])) {
                $where[] = "p.estado_pago = :estado";
                $params[':estado'] = $filtros['estado'];
            }
            
            if (isset($filtros['fecha_inicio']) && !empty($filtros['fecha_inicio'])) {
                $where[] = "DATE(p.fecha_pago) >= :fecha_inicio";
                $params[':fecha_inicio'] = $filtros['fecha_inicio'];
            }
            
            if (isset($filtros['fecha_fin']) && !empty($filtros['fecha_fin'])) {
                $where[] = "DATE(p.fecha_pago) <= :fecha_fin";
                $params[':fecha_fin'] = $filtros['fecha_fin'];
            }
            
            if (isset($filtros['id_paciente']) && !empty($filtros['id_paciente'])) {
                $where[] = "p.id_paciente = :id_paciente";
                $params[':id_paciente'] = $filtros['id_paciente'];
            }
            
            $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
            
            $sql = "SELECT p.*, 
                           CONCAT(rp.nombre_paciente, ' ', rp.apellido_paciente) as paciente_nombre,
                           rp.cedula_paciente as paciente_cedula,
                           CONCAT(ra.nombre_asistente, ' ', ra.apellido_asistente) as asistente_nombre,
                           r.id_receta, r.fecha_receta
                    FROM pagos p
                    LEFT JOIN registro_paciente rp ON p.id_paciente = rp.id_paciente
                    LEFT JOIN registro_asistente ra ON p.id_asistente = ra.id_asistente
                    LEFT JOIN recetas r ON p.id_receta = r.id_receta
                    $whereClause
                    ORDER BY p.fecha_pago DESC";
            
            $query = $this->acceso->prepare($sql);
            $query->execute($params);
            return $query->fetchAll();
        } catch(PDOException $e) {
            error_log("Error en obtenerTodos: " . $e->getMessage());
            return [];
        }
    }
    
    function obtenerPorId($id_pago) {
        try {
            $sql = "SELECT p.*, 
                           CONCAT(rp.nombre_paciente, ' ', rp.apellido_paciente) as paciente_nombre,
                           rp.cedula_paciente as paciente_cedula,
                           rp.telefono_paciente as paciente_telefono,
                           rp.correo_paciente as paciente_correo,
                           CONCAT(ra.nombre_asistente, ' ', ra.apellido_asistente) as asistente_nombre,
                           r.id_receta, r.fecha_receta, r.diagnostico
                    FROM pagos p
                    LEFT JOIN registro_paciente rp ON p.id_paciente = rp.id_paciente
                    LEFT JOIN registro_asistente ra ON p.id_asistente = ra.id_asistente
                    LEFT JOIN recetas r ON p.id_receta = r.id_receta
                    WHERE p.id_pago = :id_pago";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id_pago' => $id_pago));
            return $query->fetch(PDO::FETCH_OBJ);
        } catch(PDOException $e) {
            error_log("Error en obtenerPorId: " . $e->getMessage());
            return null;
        }
    }
    
    function obtenerPorPaciente($id_paciente, $estado = null) {
        try {
            $sql = "SELECT p.*, 
                           CONCAT(ra.nombre_asistente, ' ', ra.apellido_asistente) as asistente_nombre
                    FROM pagos p
                    LEFT JOIN registro_asistente ra ON p.id_asistente = ra.id_asistente
                    WHERE p.id_paciente = :id_paciente";
            
            if ($estado) {
                $sql .= " AND p.estado_pago = :estado";
            }
            
            $sql .= " ORDER BY p.fecha_pago DESC";
            
            $query = $this->acceso->prepare($sql);
            $params = [':id_paciente' => $id_paciente];
            
            if ($estado) {
                $params[':estado'] = $estado;
            }
            
            $query->execute($params);
            return $query->fetchAll();
        } catch(PDOException $e) {
            error_log("Error en obtenerPorPaciente: " . $e->getMessage());
            return [];
        }
    }
    
    // ==================== MÉTODOS DE ESTADÍSTICAS ====================
    
    function obtenerEstadisticas($filtros = []) {
        try {
            $where = [];
            $params = [];
            
            if (isset($filtros['fecha_inicio']) && !empty($filtros['fecha_inicio'])) {
                $where[] = "DATE(fecha_pago) >= :fecha_inicio";
                $params[':fecha_inicio'] = $filtros['fecha_inicio'];
            }
            
            if (isset($filtros['fecha_fin']) && !empty($filtros['fecha_fin'])) {
                $where[] = "DATE(fecha_pago) <= :fecha_fin";
                $params[':fecha_fin'] = $filtros['fecha_fin'];
            }
            
            $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
            
            $stats = [];
            
            // Total de pagos
            $sql = "SELECT COUNT(*) as total FROM pagos $whereClause";
            $query = $this->acceso->prepare($sql);
            $query->execute($params);
            $stats['total_pagos'] = $query->fetch(PDO::FETCH_OBJ)->total ?? 0;
            
            // Total confirmados
            $sql = "SELECT COUNT(*) as total FROM pagos WHERE estado_pago = 'confirmado'";
            if (!empty($where)) {
                $sql .= ' AND ' . implode(' AND ', $where);
            }
            $query = $this->acceso->prepare($sql);
            $query->execute($params);
            $stats['pagos_confirmados'] = $query->fetch(PDO::FETCH_OBJ)->total ?? 0;
            
            // Total pendientes
            $sql = "SELECT COUNT(*) as total FROM pagos WHERE estado_pago = 'pendiente'";
            if (!empty($where)) {
                $sql .= ' AND ' . implode(' AND ', $where);
            }
            $query = $this->acceso->prepare($sql);
            $query->execute($params);
            $stats['pagos_pendientes'] = $query->fetch(PDO::FETCH_OBJ)->total ?? 0;
            
            // Suma total de montos confirmados
            $sql = "SELECT COALESCE(SUM(monto), 0) as total FROM pagos WHERE estado_pago = 'confirmado'";
            if (!empty($where)) {
                $sql .= ' AND ' . implode(' AND ', $where);
            }
            $query = $this->acceso->prepare($sql);
            $query->execute($params);
            $stats['monto_total_confirmado'] = $query->fetch(PDO::FETCH_OBJ)->total ?? 0;
            
            return $stats;
        } catch(PDOException $e) {
            error_log("Error en obtenerEstadisticas: " . $e->getMessage());
            return [
                'total_pagos' => 0,
                'pagos_confirmados' => 0,
                'pagos_pendientes' => 0,
                'monto_total_confirmado' => 0
            ];
        }
    }
    
    function obtenerResumenPorPeriodo($periodo = 'mes') {
        try {
            $sql = "";
            
            switch($periodo) {
                case 'dia':
                    $sql = "SELECT 
                                DATE(fecha_pago) as fecha,
                                COUNT(*) as total_pagos,
                                SUM(CASE WHEN estado_pago = 'confirmado' THEN monto ELSE 0 END) as monto_confirmado
                            FROM pagos
                            WHERE fecha_pago >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                            GROUP BY DATE(fecha_pago)
                            ORDER BY fecha DESC";
                    break;
                case 'semana':
                    $sql = "SELECT 
                                WEEK(fecha_pago) as semana,
                                YEAR(fecha_pago) as anio,
                                COUNT(*) as total_pagos,
                                SUM(CASE WHEN estado_pago = 'confirmado' THEN monto ELSE 0 END) as monto_confirmado
                            FROM pagos
                            WHERE fecha_pago >= DATE_SUB(CURDATE(), INTERVAL 4 WEEK)
                            GROUP BY WEEK(fecha_pago), YEAR(fecha_pago)
                            ORDER BY anio DESC, semana DESC";
                    break;
                case 'mes':
                default:
                    $sql = "SELECT 
                                DATE_FORMAT(fecha_pago, '%Y-%m') as mes,
                                COUNT(*) as total_pagos,
                                SUM(CASE WHEN estado_pago = 'confirmado' THEN monto ELSE 0 END) as monto_confirmado
                            FROM pagos
                            WHERE fecha_pago >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                            GROUP BY DATE_FORMAT(fecha_pago, '%Y-%m')
                            ORDER BY mes DESC";
                    break;
            }
            
            $query = $this->acceso->prepare($sql);
            $query->execute();
            return $query->fetchAll();
        } catch(PDOException $e) {
            error_log("Error en obtenerResumenPorPeriodo: " . $e->getMessage());
            return [];
        }
    }
}
?>
