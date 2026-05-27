
<?php
// vista/paciente/pac_catalogo.php
$nombre_usuario = $nombre_usuario ?? 'Usuario';
$id_paciente = $id_paciente ?? $_SESSION['usuario'] ?? 0;
?>

<style>
    :root { --card-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    
    .welcome-stats {
        background: linear-gradient(135deg, var(--bv-primary), var(--bv-accent));
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
    }
    
    .stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.5rem;
        transition: transform 0.2s, border-color 0.2s;
        margin-bottom: 1rem;
    }
    .stat-card:hover { transform: translateY(-5px); border-color: var(--bv-primary); }
    .stat-value { font-size: 1.8rem; font-weight: 800; color: #1e293b; margin-bottom: 0.25rem; }
    .stat-label { font-size: 0.8rem; color: #64748b; font-weight: 600; text-transform: uppercase; }

    .quick-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.5rem;
        text-decoration: none !important;
        display: block;
        transition: all 0.2s;
        cursor: pointer;
    }
    .quick-card:hover { background: #f8fafc; border-color: var(--bv-primary); }
    .quick-icon { font-size: 1.5rem; margin-bottom: 1rem; color: var(--bv-primary); }
    .quick-card h3 { font-size: 1rem; color: #1e293b; margin-bottom: 0.5rem; }
    .quick-card p { font-size: 0.85rem; color: #64748b; margin: 0; }
    
    .card { border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: none; margin-bottom: 1.5rem; }
    .card-header { background: transparent; border-bottom: 1px solid #f1f5f9; padding: 1.25rem; }
</style>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><i class="fas fa-user-injured"></i> Panel del Paciente</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        
        <div class="welcome-stats">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2>Bienvenido, <?php echo htmlspecialchars($nombre_usuario); ?></h2>
                    <p class="mb-0 opacity-75">Gestiona tus citas, historial y estudios desde un solo lugar.</p>
                </div>
            </div>
        </div>

        <div class="row">
            <?php $stats = ['Recetas' => 'prescription-bottle-alt', 'Médicos' => 'user-md', 'Citas' => 'calendar-check', 'Estudios' => 'file-medical'];
            foreach($stats as $label => $icon): ?>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-value" id="total_<?php echo strtolower($label); ?>">0</div>
                    <div class="stat-label">Mis <?php echo $label; ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="row mt-4">
            <div class="col-md-4"><a href="<?php echo APP_URL; ?>/paciente/recetas" class="quick-card"><div class="quick-icon"><i class="fas fa-file-medical"></i></div><h3>Historial Médico</h3><p>Reportes, recetas e informes.</p></a></div>
            <div class="col-md-4"><a href="javascript:void(0)" class="quick-card" id="btnMisCitas"><div class="quick-icon"><i class="far fa-calendar-check"></i></div><h3>Mis Citas</h3><p>Revisa o programa una consulta.</p></a></div>
            <div class="col-md-4"><a href="<?php echo APP_URL; ?>/perfil" class="quick-card"><div class="quick-icon"><i class="fas fa-user-cog"></i></div><h3>Datos Personales</h3><p>Actualiza tu información.</p></a></div>
        </div>

        <div class="row mt-4" id="contenedor_mis_citas" style="display: none;">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-calendar-alt me-2"></i> Mis Citas Agendadas</h3></div>
                    <div class="card-body p-0">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Especialidad / Médico</th>
                                    <th>Fecha y Hora</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="lista_citas_cuerpo"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // Lógica para desplegar la sección de citas
    $('#btnMisCitas').on('click', function(e) {
        e.preventDefault();
        $('#contenedor_mis_citas').slideToggle(300);
        if ($('#contenedor_mis_citas').is(':visible')) {
            cargarListadoCitas();
        }
    });

    function cargarListadoCitas() {
        $('#lista_citas_cuerpo').html('<tr><td colspan="4" class="text-center">Cargando...</td></tr>');
        $.ajax({
            url: APP_URL + '/api/pacientes/mis-citas',
            type: 'POST',
            data: { id_paciente: <?php echo $id_paciente; ?> },
            dataType: 'json',
            success: function(response) {
                let html = '';
                if (response.data && response.data.length > 0) {
                    response.data.forEach(cita => {
                        html += `<tr>
                            <td><strong>${cita.especialidad}</strong><br><small class="text-muted">${cita.medico}</small></td>
                            <td>${cita.fecha}<br>${cita.hora}</td>
                            <td><span class="badge ${cita.estado === 'confirmada' ? 'bg-success' : 'bg-warning'}">${cita.estado}</span></td>
                            <td><button class="btn btn-sm btn-primary" onclick="descargarComprobante(${cita.id})"><i class="fas fa-file-pdf"></i> Comprobante</button></td>
                        </tr>`;
                    });
                } else {
                    html = '<tr><td colspan="4" class="text-center">No tienes citas.</td></tr>';
                }
                $('#lista_citas_cuerpo').html(html);
            }
        });
    }

    window.descargarComprobante = function(idCita) {
        window.location.href = APP_URL + '/paciente/descargar-comprobante?id=' + idCita;
    };

    // Funciones base existentes
    function cargarEstadisticas() { /* ... */ }
    cargarEstadisticas();
});
</script>