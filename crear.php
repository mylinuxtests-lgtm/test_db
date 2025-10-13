<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Comisión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .user-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .section-header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 20px;
        }
        .form-section {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            background: #f8f9fa;
        }
        .table-disabled {
            background-color: #e9ecef;
            opacity: 0.6;
        }
        .btn-custom {
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Información del Usuario -->
        <div class="row">
            <div class="col-12">
                <div class="user-info d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><i class="bi bi-person-circle me-2"></i><?= $usuario_actual ?></h4>
                        <small>Sistema de Gestión de Comisiones</small>
                    </div>
                    <a href="<?= base_url('comision') ?>" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Regresar
                    </a>
                </div>
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= base_url('comision/guardar') ?>" id="formComision">
            <!-- Sección 1: Información General -->
            <div class="form-section">
                <div class="section-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Información General</h5>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="direccion_departamento" class="form-label">Dirección del Departamento</label>
                        <input type="text" class="form-control" id="direccion_departamento" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="id_estructura_departamento_fk" class="form-label">Departamento</label>
                        <input type="text" class="form-control" id="id_estructura_departamento_fk" readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="folio" class="form-label">Número de Oficio</label>
                        <input type="text" class="form-control" id="folio" name="folio" value="<?= $ultimo_folio ? str_pad(intval($ultimo_folio) + 1, 4, '0', STR_PAD_LEFT) : '0001' ?>" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="fecha_actual" class="form-label">Fecha Actual</label>
                        <input type="text" class="form-control" id="fecha_actual" value="<?= date('d/m') ?>" readonly>
                    </div>
                </div>
            </div>

            <!-- Sección 2: Datos del Empleado -->
            <div class="form-section">
                <div class="section-header">
                    <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Datos del Empleado</h5>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="id_empleado_fk" class="form-label">Nombre del Empleado</label>
                        <select class="form-select" id="id_empleado_fk" name="id_empleado_fk" required>
                            <option value="">Seleccionar empleado...</option>
                            <?php foreach ($empleados as $empleado): ?>
                                <option value="<?= $empleado['id_empleado'] ?>"
                                        data-clave="<?= $empleado['clave_puesto'] ?>"
                                        data-puesto="<?= $empleado['denominacion_puesto'] ?>"
                                        data-contrato="<?= $empleado['tipo_contratacion'] ?>"
                                        data-estructura="<?= $empleado['id_estructura_fk'] ?>"
                                        data-direccion="<?= $empleado['direccion_estructura'] ?? '' ?>">
                                    <?= $empleado['nombre'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="clave_puesto" class="form-label">Clave de Puesto</label>
                        <input type="text" class="form-control" id="clave_puesto" readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="denominacion_puesto" class="form-label">Denominación del Puesto</label>
                        <input type="text" class="form-control" id="denominacion_puesto" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tipo_contratacion" class="form-label">Tipo de Contratación</label>
                        <input type="text" class="form-control" id="tipo_contratacion" readonly>
                    </div>
                </div>
            </div>

            <!-- Sección 3: Detalles de la Comisión -->
            <div class="form-section">
                <div class="section-header">
                    <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Detalles de la Comisión</h5>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="id_municipio" class="form-label">Destino</label>
                        <select class="form-select" id="id_municipio" name="id_municipio" required>
                            <option value="">Seleccionar destino...</option>
                            <?php foreach ($municipios as $municipio): ?>
                                <option value="<?= $municipio['id_municipio'] ?>"><?= $municipio['nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="asunto_corto" class="form-label">Asunto Corto</label>
                        <input type="text" class="form-control" id="asunto_corto" name="asunto_corto" readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="asunto" class="form-label">Asunto</label>
                    <textarea class="form-control" id="asunto" name="asunto" rows="4" placeholder="Describa el asunto de la comisión..." required></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Medio de Transporte</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="medio_transporte" id="oficial" value="1" required>
                                <label class="form-check-label" for="oficial">Oficial</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="medio_transporte" id="particular" value="2">
                                <label class="form-check-label" for="particular">Particular</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Se le proporcionará</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="anticipo_devengo" id="anticipo" value="1" required>
                                <label class="form-check-label" for="anticipo">Anticipo</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="anticipo_devengo" id="devengo" value="2">
                                <label class="form-check-label" for="devengo">Devengo</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 4: Conforme a lo siguiente -->
            <div class="form-section">
                <div class="section-header">
                    <h5 class="mb-0"><i class="bi bi-check-square me-2"></i>Conforme a lo siguiente</h5>
                </div>
                
                <!-- Aplica Alimentos -->
                <div class="mb-4">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="aplica_alimentos" name="aplica_alimentos" value="1">
                        <label class="form-check-label fw-bold" for="aplica_alimentos">Aplica Alimentos</label>
                    </div>
                    
                    <div class="table-responsive table-disabled" id="tablaAlimentos">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Tarifa</th>
                                    <th>Días</th>
                                    <th>Cuota Diaria</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="number" class="form-control" id="tarifa" name="tarifa" step="0.01" placeholder="0.00" disabled>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" id="dias" name="dias" placeholder="0" disabled>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" id="cuota" name="cuota" step="0.01" placeholder="0.00" disabled>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Comisión Fuera del Estado -->
                <div class="mb-4">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="comision_fuera_estado" name="comision_fuera_estado" value="1">
                        <label class="form-check-label fw-bold" for="comision_fuera_estado">Comisión Fuera del Estado</label>
                    </div>
                    
                    <div class="table-responsive table-disabled" id="tablaFueraEstado">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Partida 3710 (Pasajes Aéreos)</th>
                                    <th>Partida 3720 (Pasajes Terrestres)</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="number" class="form-control" id="partida_3710" name="partida_3710" step="0.01" placeholder="0.00" disabled>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" id="partida_3720" name="partida_3720" step="0.01" placeholder="0.00" disabled>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" id="partida_total" name="partida_total" step="0.01" placeholder="0.00" readonly>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="row mt-4">
                <div class="col-12 d-flex justify-content-between">
                    <div>
                        <button type="submit" class="btn btn-success btn-custom me-2">
                            <i class="bi bi-check-circle me-1"></i> Enviar y Descargar CSV
                        </button>
                        <button type="reset" class="btn btn-warning btn-custom me-2">
                            <i class="bi bi-arrow-clockwise me-1"></i> Restablecer
                        </button>
                    </div>
                    <a href="<?= base_url('comision') ?>" class="btn btn-danger btn-custom">
                        <i class="bi bi-x-circle me-1"></i> Cancelar
                    </a>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Actualizar datos del empleado al seleccionar
            $('#id_empleado_fk').change(function() {
                var selectedOption = $(this).find('option:selected');
                $('#clave_puesto').val(selectedOption.data('clave'));
                $('#denominacion_puesto').val(selectedOption.data('puesto'));
                $('#tipo_contratacion').val(selectedOption.data('contrato'));
                $('#id_estructura_departamento_fk').val(selectedOption.data('estructura'));
                $('#direccion_departamento').val(selectedOption.data('direccion'));
            });

            // Actualizar asunto corto al seleccionar destino
            $('#id_municipio').change(function() {
                var destino = $(this).find('option:selected').text();
                $('#asunto_corto').val('Comisión a ' + destino);
            });

            // Habilitar/deshabilitar tabla de alimentos
            $('#aplica_alimentos').change(function() {
                var tablaAlimentos = $('#tablaAlimentos');
                var inputs = tablaAlimentos.find('input');
                
                if ($(this).is(':checked')) {
                    tablaAlimentos.removeClass('table-disabled');
                    inputs.prop('disabled', false);
                } else {
                    tablaAlimentos.addClass('table-disabled');
                    inputs.prop('disabled', true).val('');
                }
            });

            // Habilitar/deshabilitar tabla de comisión fuera del estado
            $('#comision_fuera_estado').change(function() {
                var tablaFueraEstado = $('#tablaFueraEstado');
                var inputs = tablaFueraEstado.find('input');
                
                if ($(this).is(':checked')) {
                    tablaFueraEstado.removeClass('table-disabled');
                    inputs.not('#partida_total').prop('disabled', false);
                } else {
                    tablaFueraEstado.addClass('table-disabled');
                    inputs.prop('disabled', true).val('');
                    $('#partida_total').val('');
                }
            });

            // Calcular total automáticamente para comisión fuera del estado
            $('#partida_3710, #partida_3720').on('input', function() {
                var partida3710 = parseFloat($('#partida_3710').val()) || 0;
                var partida3720 = parseFloat($('#partida_3720').val()) || 0;
                var total = partida3710 + partida3720;
                $('#partida_total').val(total.toFixed(2));
            });

            // Validación de fechas
            $('#fecha_inicio, #fecha_fin').change(function() {
                var inicio = new Date($('#fecha_inicio').val());
                var fin = new Date($('#fecha_fin').val());
                
                if (inicio && fin && inicio > fin) {
                    alert('La fecha de inicio no puede ser posterior a la fecha de fin');
                    $('#fecha_fin').val('');
                }
            });

            // Validación de números y decimales
            $('input[type="number"]').on('input', function() {
                var value = $(this).val();
                if (value && !/^\d*\.?\d*$/.test(value)) {
                    $(this).val(value.slice(0, -1));
                }
            });

            // Validación de asunto (solo letras y números)
            $('#asunto').on('input', function() {
                var value = $(this).val();
                if (value && !/^[a-zA-Z0-9\sáéíóúÁÉÍÓÚñÑ.,;:()\-]*$/.test(value)) {
                    $(this).val(value.slice(0, -1));
                    alert('El asunto solo puede contener letras, números y signos de puntuación básicos.');
                }
            });
        });
    </script>
</body>
</html>