-- ============================================
-- SISTEMA DE PAGOS Y NOTIFICACIONES BIOVITAL
-- ============================================

-- Tabla de pagos
CREATE TABLE IF NOT EXISTS pagos (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    id_paciente INT NOT NULL,
    id_asistente INT NOT NULL,
    id_receta INT NULL,
    tipo_pago ENUM('efectivo', 'tarjeta', 'transferencia', 'zelle', 'pago_movil') NOT NULL,
    monto DECIMAL(10, 2) NOT NULL,
    fecha_pago DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado_pago ENUM('pendiente', 'confirmado', 'rechazado') NOT NULL DEFAULT 'pendiente',
    referencia_pago VARCHAR(100) NULL,
    descripcion_pago TEXT NULL,
    comprobante_emitido BOOLEAN NOT NULL DEFAULT FALSE,
    fecha_confirmacion DATETIME NULL,
    confirmado_por INT NULL,
    creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_paciente) REFERENCES registro_paciente(id_paciente) ON DELETE CASCADE,
    FOREIGN KEY (id_asistente) REFERENCES registro_asistente(id_asistente) ON DELETE CASCADE,
    FOREIGN KEY (id_receta) REFERENCES recetas(id_receta) ON DELETE SET NULL,
    INDEX idx_paciente (id_paciente),
    INDEX idx_asistente (id_asistente),
    INDEX idx_estado (estado_pago),
    INDEX idx_fecha (fecha_pago)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de notificaciones
CREATE TABLE IF NOT EXISTS notificaciones (
    id_notificacion INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    tipo_usuario ENUM('paciente', 'medico', 'asistente', 'administrador') NOT NULL,
    tipo_notificacion ENUM('pago_confirmado', 'comprobante_emitido', 'cita_programada', 'receta_emitida', 'general') NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    mensaje TEXT NOT NULL,
    datos_adicionales JSON NULL,
    leida BOOLEAN NOT NULL DEFAULT FALSE,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_lectura DATETIME NULL,
    enlace VARCHAR(255) NULL,
    FOREIGN KEY (id_usuario) REFERENCES registro_paciente(id_paciente) ON DELETE CASCADE,
    INDEX idx_usuario (id_usuario),
    INDEX idx_tipo_usuario (tipo_usuario),
    INDEX idx_leida (leida),
    INDEX idx_fecha (fecha_creacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de comprobantes
CREATE TABLE IF NOT EXISTS comprobantes (
    id_comprobante INT AUTO_INCREMENT PRIMARY KEY,
    id_pago INT NOT NULL,
    numero_comprobante VARCHAR(50) NOT NULL UNIQUE,
    fecha_emision DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datos_cita JSON NULL,
    datos_pago JSON NOT NULL,
    datos_paciente JSON NOT NULL,
    total_pagado DECIMAL(10, 2) NOT NULL,
    emitido_por INT NOT NULL,
    ruta_pdf VARCHAR(255) NULL,
    creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pago) REFERENCES pagos(id_pago) ON DELETE CASCADE,
    INDEX idx_pago (id_pago),
    INDEX idx_numero (numero_comprobante),
    INDEX idx_fecha (fecha_emision)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
