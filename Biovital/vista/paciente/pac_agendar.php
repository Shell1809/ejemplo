<?php
// vista/paciente/pac_agendar.php
// Vista para agendar nueva cita
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-calendar-plus me-2"></i> Agendar Nueva Cita</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Selecciona los detalles de tu consulta</h3>
                    </div>
                    <div class="card-body">
                        <form id="formAgendarCita">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Especialidad</label>
                                    <select class="form-select" id="especialidad" required>
                                        <option value="">Seleccione...</option>
                                        </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Médico</label>
                                    <select class="form-select" id="medico" required>
                                        <option value="">Primero seleccione especialidad...</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Fecha</label>
                                    <input type="date" class="form-control" id="fecha" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Horario</label>
                                    <select class="form-select" id="hora" required>
                                        <option value="">Seleccione hora...</option>
                                    </select>
                                </div>
                            </div>

                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-2"></i> Confirmar Cita
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-info card-outline">
                    <div class="card-body">
                        <h5 class="fw-bold"><i class="fas fa-info-circle me-2"></i> Instrucciones</h5>
                        <p class="small text-muted mt-3">
                            1. Selecciona la especialidad que necesitas.<br>
                            2. Elige a tu médico de preferencia.<br>
                            3. Escoge una fecha disponible.<br>
                            4. Verifica los datos antes de confirmar.
                        </p>
                        <div class="alert alert-warning small mt-3">
                            <i class="fas fa-exclamation-triangle"></i> Recuerda llegar 15 minutos antes de tu cita.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // Aquí iría la lógica para cargar especialidades y médicos vía AJAX
    console.log('Vista de agendado inicializada');
});
</script>