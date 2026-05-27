<?php
// vista/asistente/asi_pagos.php
// Gestión de pagos para asistentes
// Este archivo se renderiza dentro del layout base dashboard.php

$nombre_usuario = $nombre_usuario ?? 'Usuario';
$id_asistente = $id_asistente ?? $_SESSION['usuario'] ?? 0;
?>

<!-- CSS Adicional para esta vista -->
<style>
    .payments-header {
        background: linear-gradient(135deg, #0077b6, #4361ee);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    .payments-header::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -5%;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }
    .payments-header::after {
        content: '';
        position: absolute;
        bottom: -20%;
        left: -5%;
        width: 150px;
        height: 150px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s;
        border: 1px solid #eef2f6;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.1);
    }
    .stat-card .stat-icon {
        position: absolute;
        right: 1rem;
        top: 1rem;
        font-size: 2rem;
        opacity: 0.1;
    }
    .stat-card .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: #0077b6;
        margin-bottom: 0.25rem;
    }
    .stat-card .stat-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 700;
        color: #64748b;
        letter-spacing: 0.5px;
    }
    
    .payment-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #eef2f6;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s;
        margin-bottom: 1rem;
    }
    .payment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    .payment-card .card-header {
        background: white;
        border-bottom: 2px solid #0077b6;
        padding: 1rem 1.5rem;
        border-radius: 16px 16px 0 0;
    }
    .payment-card .card-header h3 {
        font-size: 1rem;
        font-weight: 700;
        margin: 0;
        color: #0f172a;
    }
    
    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    .filter-tab {
        padding: 0.6rem 1.2rem;
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        background: white;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s;
        color: #64748b;
    }
    .filter-tab:hover {
        border-color: #0077b6;
        color: #0077b6;
    }
    .filter-tab.active {
        background: linear-gradient(135deg, #0077b6, #4361ee);
        border-color: #0077b6;
        color: white;
    }
    
    .payment-item {
        padding: 1rem;
        border-bottom: 1px solid #eef2f6;
        transition: all 0.3s;
    }
    .payment-item:last-child {
        border-bottom: none;
    }
    .payment-item:hover {
        background: #f8fafc;
    }
    
    .payment-status {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .payment-status.confirmado {
        background: #d1fae5;
        color: #065f46;
    }
    .payment-status.pendiente {
        background: #fef3c7;
        color: #92400e;
    }
    .payment-status.rechazado {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .payment-type {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 0.8rem;
        background: #f0f7ff;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #0077b6;
    }
    
    .btn-action {
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.3s;
        border: none;
    }
    .btn-confirm {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    .btn-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16,185,129,0.3);
    }
    .btn-reject {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }
    .btn-reject:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239,68,68,0.3);
    }
    .btn-receipt {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }
    .btn-receipt:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245,158,11,0.3);
    }
    .btn-download {
        background: linear-gradient(135deg, #0077b6, #4361ee);
        color: white;
    }
    .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,119,182,0.3);
    }
    
    .form-control, .form-select {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 0.6rem 1rem;
        transition: all 0.3s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0077b6;
        box-shadow: 0 0 0 3px rgba(0,119,182,0.1);
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
        background: #fafbfc;
        border-radius: 12px;
    }
    .empty-state i {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }
    .empty-state p {
        font-size: 0.9rem;
        color: #94a3b8;
        margin-bottom: 0;
    }
    
    .loading-spinner {
        text-align: center;
        padding: 2rem;
    }
    
    .payment-amount {
        font-size: 1.2rem;
        font-weight: 800;
        color: #0077b6;
    }
    
    .badge-comprobante {
        background: #e0e7ff;
        color: #4338ca;
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        border-radius: 20px;
        font-weight: 700;
    }
    
    .modal-content {
        border-radius: 16px;
        border: none;
    }
    .modal-header {
        background: linear-gradient(135deg, #0077b6, #4361ee);
        color: white;
        border-radius: 16px 16px 0 0;
        border: none;
    }
    
    .autocomplete-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .autocomplete-item {
        padding: 0.6rem 1rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .autocomplete-item:hover {
        background: #f0f7ff;
    }
</style>

<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-credit-card"></i> Gestión de Pagos</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <input type="hidden" id="id_asistente" value="<?php echo htmlspecialchars($id_asistente); ?>">
        
        <!-- Payments Header -->
        <div class="payments-header text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-credit-card me-2"></i> 
                        Gestión de Pagos
                    </h2>
                    <p class="mb-0 opacity-75">Administra, confirma y emite comprobantes de pagos de pacientes.</p>
                </div>
                <div class="d-none d-md-block">
                    <i class="fas fa-money-bill-wave fa-3x" style="opacity: 0.3;"></i>
                </div>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 col-12">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-value" id="total_pagos">0</div>
                    <div class="stat-label">Total Pagos</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-value" id="pagos_confirmados">0</div>
                    <div class="stat-label">Confirmados</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-value" id="pagos_pendientes">0</div>
                    <div class="stat-label">Pendientes</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="stat-value" id="monto_total">$0.00</div>
                    <div class="stat-label">Monto Total Confirmado</div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Left Column - Payments List -->
            <div class="col-md-8">
                <div class="payment-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3><i class="fas fa-list me-2"></i> Lista de Pagos</h3>
                            <div class="btn-group">
                                <button class="btn btn-download btn-sm" id="btnDownloadPDF">
                                    <i class="fas fa-file-pdf me-1"></i> PDF
                                </button>
                                <button class="btn btn-download btn-sm" id="btnAddPayment">
                                    <i class="fas fa-plus me-1"></i> Nuevo Pago
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filter Tabs -->
                        <div class="filter-tabs">
                            <button class="filter-tab active" data-filter="todos">
                                <i class="fas fa-th-list me-1"></i> Todos
                            </button>
                            <button class="filter-tab" data-filter="pendiente">
                                <i class="fas fa-clock me-1"></i> Pendientes
                            </button>
                            <button class="filter-tab" data-filter="confirmado">
                                <i class="fas fa-check-circle me-1"></i> Confirmados
                            </button>
                            <button class="filter-tab" data-filter="rechazado">
                                <i class="fas fa-times-circle me-1"></i> Rechazados
                            </button>
                        </div>

                        <!-- Date Filter -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <input type="date" class="form-control" id="fecha_inicio" placeholder="Fecha inicio">
                            </div>
                            <div class="col-md-4">
                                <input type="date" class="form-control" id="fecha_fin" placeholder="Fecha fin">
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-download w-100" id="btnFilter">
                                    <i class="fas fa-filter me-1"></i> Filtrar
                                </button>
                            </div>
                        </div>

                        <!-- Payments List -->
                        <div id="payments-list">
                            <div class="loading-spinner">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Cargando...</span>
                                </div>
                                <p class="mt-2 mb-0 text-muted small">Cargando pagos...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Quick Actions -->
            <div class="col-md-4">
                <!-- Quick Actions Card -->
                <div class="payment-card">
                    <div class="card-header">
                        <h3><i class="fas fa-bolt me-2"></i> Acciones Rápidas</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-download" id="btnAddPaymentQuick">
                                <i class="fas fa-plus me-2"></i> Registrar Pago en Efectivo
                            </button>
                            <button class="btn btn-download" id="btnDownloadDaily">
                                <i class="fas fa-calendar-day me-2"></i> Reporte Diario
                            </button>
                            <button class="btn btn-download" id="btnDownloadWeekly">
                                <i class="fas fa-calendar-week me-2"></i> Reporte Semanal
                            </button>
                            <button class="btn btn-download" id="btnDownloadMonthly">
                                <i class="fas fa-calendar-alt me-2"></i> Reporte Mensual
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="payment-card">
                    <div class="card-header">
                        <h3><i class="fas fa-history me-2"></i> Actividad Reciente</h3>
                    </div>
                    <div class="card-body">
                        <div id="recent-activity">
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p>Sin actividad reciente</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Nuevo Pago -->
<div class="modal fade" id="modalNuevoPago" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Registrar Nuevo Pago</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success" id="pagoCreado" style="display:none;">
                    <i class="fas fa-check-circle"></i> Pago registrado exitosamente
                </div>
                <div class="alert alert-danger" id="pagoError" style="display:none;">
                    <i class="fas fa-exclamation-circle"></i> <span id="errorMensaje"></span>
                </div>
                
                <form id="form-nuevo-pago">
                    <?php echo Security::campoCSRF(); ?>
                    
                    <div class="form-group position-relative">
                        <label for="paciente_busqueda">Paciente *</label>
                        <input type="text" class="form-control" id="paciente_busqueda" 
                               placeholder="Buscar paciente por nombre o cédula" required autocomplete="off">
                        <input type="hidden" id="id_paciente" name="id_paciente">
                        <div class="autocomplete-dropdown" id="paciente-dropdown" style="display:none;"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="tipo_pago">Tipo de Pago *</label>
                        <select class="form-control" id="tipo_pago" name="tipo_pago" required>
                            <option value="">Seleccione tipo de pago...</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                            <option value="transferencia">Transferencia Bancaria</option>
                            <option value="zelle">Zelle</option>
                            <option value="pago_movil">Pago Móvil</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="monto">Monto *</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="number" class="form-control" id="monto" name="monto" 
                                   placeholder="0.00" step="0.01" min="0" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="referencia_pago">Referencia de Pago</label>
                        <input type="text" class="form-control" id="referencia_pago" name="referencia_pago" 
                               placeholder="Número de referencia (opcional)">
                    </div>
                    
                    <div class="form-group">
                        <label for="descripcion_pago">Descripción</label>
                        <textarea class="form-control" id="descripcion_pago" name="descripcion_pago" 
                                  rows="2" placeholder="Descripción adicional del pago (opcional)"></textarea>
                    </div>
                    
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-download">
                            <i class="fas fa-save me-1"></i> Registrar Pago
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Emitir Comprobante -->
<div class="modal fade" id="modalComprobante" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-receipt"></i> Emitir Comprobante</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Al emitir el comprobante, se enviará una notificación al paciente con todos los detalles del pago.
                </div>
                
                <div id="comprobante-details">
                    <!-- Details will be loaded here -->
                </div>
                
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-receipt" id="btnConfirmarComprobante">
                        <i class="fas fa-check me-1"></i> Emitir Comprobante
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal PDF Options -->
<div class="modal fade" id="modalPDF" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-file-pdf"></i> Descargar Reporte PDF</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="pdf_periodo">Periodo del Reporte</label>
                    <select class="form-control" id="pdf_periodo">
                        <option value="dia">Reporte Diario</option>
                        <option value="semana">Reporte Semanal</option>
                        <option value="mes" selected>Reporte Mensual</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="pdf_estado">Estado de Pagos</label>
                    <select class="form-control" id="pdf_estado">
                        <option value="todos">Todos los pagos</option>
                        <option value="confirmado">Solo confirmados</option>
                        <option value="pendiente">Solo pendientes</option>
                    </select>
                </div>
                
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-download" id="btnGenerarPDF">
                        <i class="fas fa-download me-1"></i> Generar PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var id_asistente = $('#id_asistente').val();
    var filtroActual = 'todos';
    var pagoSeleccionado = null;
    
    console.log('=== GESTIÓN DE PAGOS - INICIALIZANDO ===');
    console.log('ID Asistente:', id_asistente);
    
    // ==================== CARGAR ESTADÍSTICAS ====================
    
    function cargarEstadisticas() {
        $.ajax({
            url: APP_URL + '/api/pagos/estadisticas',
            type: 'POST',
            data: {},
            dataType: 'json',
            success: function(response) {
                console.log('Estadísticas:', response);
                
                var data = response;
                if (response.success && response.data) {
                    data = response.data;
                }
                
                $('#total_pagos').text(data.total_pagos || 0);
                $('#pagos_confirmados').text(data.pagos_confirmados || 0);
                $('#pagos_pendientes').text(data.pagos_pendientes || 0);
                $('#monto_total').text('$' + (data.monto_total_confirmado || 0).toFixed(2));
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar estadísticas:', error);
            }
        });
    }
    
    // ==================== CARGAR PAGOS ====================
    
    function cargarPagos(filtro = 'todos', fechaInicio = null, fechaFin = null) {
        var data = {
            estado: filtro === 'todos' ? null : filtro
        };
        
        if (fechaInicio) data.fecha_inicio = fechaInicio;
        if (fechaFin) data.fecha_fin = fechaFin;
        
        $('#payments-list').html(`
            <div class="loading-spinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Cargando...</span>
                </div>
                <p class="mt-2 mb-0 text-muted small">Cargando pagos...</p>
            </div>
        `);
        
        $.ajax({
            url: APP_URL + '/api/pagos/listar',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                console.log('Pagos:', response);
                
                var pagos = [];
                if (response.success && response.data) {
                    pagos = response.data;
                } else if (Array.isArray(response)) {
                    pagos = response;
                }
                
                renderizarPagos(pagos);
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar pagos:', error);
                $('#payments-list').html(`
                    <div class="empty-state">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>Error al cargar pagos</p>
                    </div>
                `);
            }
        });
    }
    
    function renderizarPagos(pagos) {
        if (pagos.length === 0) {
            $('#payments-list').html(`
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>No hay pagos registrados</p>
                </div>
            `);
            return;
        }
        
        var html = '';
        
        for (var i = 0; i < pagos.length; i++) {
            var pago = pagos[i];
            var fecha = new Date(pago.fecha_pago);
            var fechaFormateada = fecha.toLocaleDateString('es-ES', { 
                day: '2-digit', 
                month: '2-digit', 
                year: 'numeric' 
            });
            var horaFormateada = fecha.toLocaleTimeString('es-ES', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
            
            var estadoClass = pago.estado_pago;
            var estadoLabel = pago.estado_pago.charAt(0).toUpperCase() + pago.estado_pago.slice(1);
            
            html += `
                <div class="payment-item" data-id-pago="${pago.id_pago}">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="payment-type me-2">
                                    <i class="fas fa-${getIconoTipoPago(pago.tipo_pago)}"></i>
                                    ${pago.tipo_pago}
                                </div>
                            </div>
                            <div class="mt-1">
                                <strong>${escapeHtml(pago.paciente_nombre)}</strong>
                                <br>
                                <small class="text-muted">${escapeHtml(pago.paciente_cedula)}</small>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="payment-amount">$${parseFloat(pago.monto).toFixed(2)}</div>
                            <small class="text-muted">
                                <i class="far fa-calendar"></i> ${fechaFormateada}
                                <i class="far fa-clock ml-2"></i> ${horaFormateada}
                            </small>
                        </div>
                        <div class="col-md-2 text-center">
                            <span class="payment-status ${estadoClass}">${estadoLabel}</span>
                            ${pago.comprobante_emitido ? '<span class="badge-comprobante ml-1">Comprobante</span>' : ''}
                        </div>
                        <div class="col-md-3 text-right">
                            ${renderizarBotonesAccion(pago)}
                        </div>
                    </div>
                </div>
            `;
        }
        
        $('#payments-list').html(html);
    }
    
    function renderizarBotonesAccion(pago) {
        var botones = '';
        
        if (pago.estado_pago === 'pendiente') {
            botones += `
                <button class="btn-action btn-confirm btn-confirmar-pago" data-id-pago="${pago.id_pago}" title="Confirmar pago">
                    <i class="fas fa-check"></i>
                </button>
                <button class="btn-action btn-reject btn-rechazar-pago" data-id-pago="${pago.id_pago}" title="Rechazar pago">
                    <i class="fas fa-times"></i>
                </button>
            `;
        }
        
        if (pago.estado_pago === 'confirmado' && !pago.comprobante_emitido) {
            botones += `
                <button class="btn-action btn-receipt btn-emitir-comprobante" data-id-pago="${pago.id_pago}" title="Emitir comprobante">
                    <i class="fas fa-receipt"></i>
                </button>
            `;
        }
        
        if (pago.comprobante_emitido) {
            botones += `
                <button class="btn-action btn-download btn-descargar-comprobante" data-id-pago="${pago.id_pago}" title="Descargar comprobante">
                    <i class="fas fa-download"></i>
                </button>
            `;
        }
        
        return botones;
    }
    
    function getIconoTipoPago(tipo) {
        var iconos = {
            'efectivo': 'money-bill',
            'tarjeta': 'credit-card',
            'transferencia': 'university',
            'zelle': 'mobile-alt',
            'pago_movil': 'mobile'
        };
        return iconos[tipo] || 'money-bill';
    }
    
    // ==================== EVENTOS ====================
    
    $('.filter-tab').click(function() {
        $('.filter-tab').removeClass('active');
        $(this).addClass('active');
        filtroActual = $(this).data('filter');
        cargarPagos(filtroActual);
    });
    
    $('#btnFilter').click(function() {
        var fechaInicio = $('#fecha_inicio').val();
        var fechaFin = $('#fecha_fin').val();
        cargarPagos(filtroActual, fechaInicio, fechaFin);
    });
    
    $('#btnAddPayment, #btnAddPaymentQuick').click(function() {
        $('#modalNuevoPago').modal('show');
    });
    
    $('#btnDownloadPDF').click(function() {
        $('#modalPDF').modal('show');
    });
    
    $('#btnDownloadDaily').click(function() {
        generarPDF('dia');
    });
    
    $('#btnDownloadWeekly').click(function() {
        generarPDF('semana');
    });
    
    $('#btnDownloadMonthly').click(function() {
        generarPDF('mes');
    });
    
    $('#btnGenerarPDF').click(function() {
        var periodo = $('#pdf_periodo').val();
        $('#modalPDF').modal('hide');
        generarPDF(periodo);
    });
    
    // ==================== AUTOCOMPLETE PACIENTE ====================
    
    var debounceTimer;
    $('#paciente_busqueda').on('input', function() {
        var termino = $(this).val();
        
        clearTimeout(debounceTimer);
        
        if (termino.length < 2) {
            $('#paciente-dropdown').hide();
            return;
        }
        
        debounceTimer = setTimeout(function() {
            $.ajax({
                url: APP_URL + '/api/pagos/buscar-pacientes',
                type: 'POST',
                data: { termino: termino },
                dataType: 'json',
                success: function(response) {
                    console.log('Pacientes encontrados:', response);
                    
                    var pacientes = [];
                    if (response.success && response.data) {
                        pacientes = response.data;
                    } else if (Array.isArray(response)) {
                        pacientes = response;
                    }
                    
                    if (pacientes.length > 0) {
                        var html = '';
                        for (var i = 0; i < pacientes.length; i++) {
                            var pac = pacientes[i];
                            html += `
                                <div class="autocomplete-item" data-id="${pac.id_paciente}" data-nombre="${pac.nombre_completo}">
                                    <strong>${escapeHtml(pac.nombre_completo)}</strong>
                                    <br>
                                    <small class="text-muted">${escapeHtml(pac.cedula_paciente)}</small>
                                </div>
                            `;
                        }
                        $('#paciente-dropdown').html(html).show();
                    } else {
                        $('#paciente-dropdown').hide();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al buscar pacientes:', error);
                }
            });
        }, 300);
    });
    
    $(document).on('click', '.autocomplete-item', function() {
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');
        
        $('#paciente_busqueda').val(nombre);
        $('#id_paciente').val(id);
        $('#paciente-dropdown').hide();
    });
    
    $(document).click(function(e) {
        if (!$(e.target).closest('.position-relative').length) {
            $('#paciente-dropdown').hide();
        }
    });
    
    // ==================== FORMULARIO NUEVO PAGO ====================
    
    $('#form-nuevo-pago').submit(function(e) {
        e.preventDefault();
        
        var datos = {
            csrf_token: $(this).find('[name="csrf_token"]').val(),
            id_paciente: $('#id_paciente').val(),
            tipo_pago: $('#tipo_pago').val(),
            monto: $('#monto').val(),
            referencia_pago: $('#referencia_pago').val(),
            descripcion_pago: $('#descripcion_pago').val()
        };
        
        if (!datos.id_paciente || !datos.tipo_pago || !datos.monto) {
            $('#pagoError').show().find('#errorMensaje').text('Complete todos los campos requeridos');
            return;
        }
        
        $.ajax({
            url: APP_URL + '/api/pagos/crear',
            type: 'POST',
            data: datos,
            dataType: 'json',
            success: function(response) {
                console.log('Pago creado:', response);
                
                if (response.success) {
                    $('#pagoCreado').show();
                    $('#pagoError').hide();
                    $('#form-nuevo-pajo')[0].reset();
                    $('#id_paciente').val('');
                    
                    setTimeout(function() {
                        $('#modalNuevoPago').modal('hide');
                        $('#pagoCreado').hide();
                        cargarPagos(filtroActual);
                        cargarEstadisticas();
                    }, 2000);
                } else {
                    $('#pagoError').show().find('#errorMensaje').text(response.message || 'Error al crear pago');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al crear pago:', error);
                $('#pagoError').show().find('#errorMensaje').text('Error al crear pago');
            }
        });
    });
    
    // ==================== ACCIONES DE PAGO ====================
    
    $(document).on('click', '.btn-confirmar-pago', function() {
        var idPago = $(this).data('id-pago');
        
        if (!confirm('¿Confirmar este pago?')) return;
        
        $.ajax({
            url: APP_URL + '/api/pagos/confirmar',
            type: 'POST',
            data: {
                csrf_token: $('meta[name="csrf-token"]').attr('content'),
                id_pago: idPago
            },
            dataType: 'json',
            success: function(response) {
                console.log('Pago confirmado:', response);
                
                if (response.success) {
                    mostrarToast('Pago confirmado exitosamente', 'success');
                    cargarPagos(filtroActual);
                    cargarEstadisticas();
                } else {
                    mostrarToast('Error al confirmar pago: ' + (response.message || 'Error desconocido'), 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al confirmar pago:', error);
                mostrarToast('Error al confirmar pago', 'error');
            }
        });
    });
    
    $(document).on('click', '.btn-rechazar-pago', function() {
        var idPago = $(this).data('id-pago');
        
        if (!confirm('¿Rechazar este pago?')) return;
        
        $.ajax({
            url: APP_URL + '/api/pagos/rechazar',
            type: 'POST',
            data: {
                csrf_token: $('meta[name="csrf-token"]').attr('content'),
                id_pago: idPago
            },
            dataType: 'json',
            success: function(response) {
                console.log('Pago rechazado:', response);
                
                if (response.success) {
                    mostrarToast('Pago rechazado', 'warning');
                    cargarPagos(filtroActual);
                    cargarEstadisticas();
                } else {
                    mostrarToast('Error al rechazar pago: ' + (response.message || 'Error desconocido'), 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al rechazar pago:', error);
                mostrarToast('Error al rechazar pago', 'error');
            }
        });
    });
    
    $(document).on('click', '.btn-emitir-comprobante', function() {
        var idPago = $(this).data('id-pago');
        pagoSeleccionado = idPago;
        
        $.ajax({
            url: APP_URL + '/api/pagos/obtener',
            type: 'POST',
            data: { id_pago: idPago },
            dataType: 'json',
            success: function(response) {
                console.log('Pago obtenido:', response);
                
                var pago = response;
                if (response.success && response.data) {
                    pago = response.data;
                }
                
                var html = `
                    <div class="text-center mb-3">
                        <i class="fas fa-receipt fa-3x text-primary mb-2"></i>
                        <h5>Comprobante de Pago</h5>
                    </div>
                    <table class="table table-bordered">
                        <tr>
                            <td><strong>Paciente:</strong></td>
                            <td>${escapeHtml(pago.paciente_nombre)}</td>
                        </tr>
                        <tr>
                            <td><strong>Cédula:</strong></td>
                            <td>${escapeHtml(pago.paciente_cedula)}</td>
                        </tr>
                        <tr>
                            <td><strong>Monto:</strong></td>
                            <td class="payment-amount">$${parseFloat(pago.monto).toFixed(2)}</td>
                        </tr>
                        <tr>
                            <td><strong>Tipo de Pago:</strong></td>
                            <td>${pago.tipo_pago}</td>
                        </tr>
                        <tr>
                            <td><strong>Fecha:</strong></td>
                            <td>${new Date(pago.fecha_pago).toLocaleString('es-ES')}</td>
                        </tr>
                    </table>
                `;
                
                $('#comprobante-details').html(html);
                $('#modalComprobante').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Error al obtener pago:', error);
                mostrarToast('Error al obtener datos del pago', 'error');
            }
        });
    });
    
    $('#btnConfirmarComprobante').click(function() {
        if (!pagoSeleccionado) return;
        
        $.ajax({
            url: APP_URL + '/api/pagos/emitir-comprobante',
            type: 'POST',
            data: {
                csrf_token: $('meta[name="csrf-token"]').attr('content'),
                id_pago: pagoSeleccionado
            },
            dataType: 'json',
            success: function(response) {
                console.log('Comprobante emitido:', response);
                
                if (response.success) {
                    mostrarToast('Comprobante emitido exitosamente', 'success');
                    $('#modalComprobante').modal('hide');
                    cargarPagos(filtroActual);
                } else {
                    mostrarToast('Error al emitir comprobante: ' + (response.message || 'Error desconocido'), 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al emitir comprobante:', error);
                mostrarToast('Error al emitir comprobante', 'error');
            }
        });
    });
    
    // ==================== PDF GENERATION ====================
    
    function generarPDF(periodo) {
        mostrarToast('Generando reporte PDF...', 'info');
        
        // Simulación - en producción esto generaría un PDF real
        setTimeout(function() {
            mostrarToast('Reporte PDF generado para periodo: ' + periodo, 'success');
        }, 1500);
    }
    
    // ==================== FUNCIONES UTILITARIAS ====================
    
    function mostrarToast(mensaje, tipo) {
        var toastHtml = `
            <div class="toast align-items-center text-white bg-${tipo === 'success' ? 'success' : tipo === 'warning' ? 'warning' : 'danger'} border-0 position-fixed" 
                 style="top: 70px; right: 20px; z-index: 9999; min-width: 250px; border-radius: 12px;" 
                 role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas ${tipo === 'success' ? 'fa-check-circle' : tipo === 'warning' ? 'fa-exclamation-triangle' : 'fa-times-circle'} me-2"></i>
                        ${mensaje}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-dismiss="toast"></button>
                </div>
            </div>
        `;
        $('body').append(toastHtml);
        var toast = $('.toast').last();
        setTimeout(function() { toast.fadeOut(300, function() { $(this).remove(); }); }, 3000);
    }
    
    function escapeHtml(str) {
        if (!str) return '';
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }
    
    // ==================== INICIALIZAR ====================
    cargarEstadisticas();
    cargarPagos();
    
    // Actualizar cada 60 segundos
    setInterval(function() {
        cargarEstadisticas();
        cargarPagos(filtroActual);
    }, 60000);
});
</script>
