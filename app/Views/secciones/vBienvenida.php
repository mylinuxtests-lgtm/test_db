<div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Listado de Comisiones</h4>

            <!-- Barra de herramientas -->
            <div id="toolbar" class="mb-3">
                <div class="row">
                    <div class="col-md-6 text-right">
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control" placeholder="Buscar...">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="searchButton">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla Bootstrap Table -->
            <table id="table_servidor" data-locale="es-MX" data-toolbar="#toolbar" data-toggle="table"
                data-search="true" data-sortable="true" data-show-refresh="true" data-header-style="headerStyle"
                data-show-export="true" data-search-highlight="true" data-pagination="true"
                data-side-pagination="server" data-page-list="[10, 25, 50, 100, All]" data-page-size="10"
                data-method="post" data-content-type="application/x-www-form-urlencoded"
                data-query-params="queryParamsComision" data-url="<?php echo base_url('getComisiones') ?>"
                data-response-handler="responseHandler">
                <thead>
                    <tr>
                        <th data-field="id_comision" data-sortable="true" class="text-center"
                            data-formatter="accionesFormatter" data-width="120">Acciones</th>
                        <th data-field="id_oficio" data-sortable="true" data-width="100">No. Oficio</th>
                        <th data-field="id_empleado" data-sortable="true">Nombre Empleado</th>
                        <th data-field="id_estructura_departamento" data-sortable="true">Departamento</th>
                        <th data-field="asunto_corto" data-sortable="true">Asunto Corto</th>
                        <th data-field="fecha_inicio" data-sortable="true" data-formatter="fechaFormatter"
                            data-width="120">Fecha Inicio</th>
                        <th data-field="fecha_fin" data-sortable="true" data-formatter="fechaFormatter"
                            data-width="120">Fecha Fin</th>
                        <th data-field="asunto" data-visible="false">Asunto Completo</th>
                    </tr>
                </thead>
            </table>
        </div> <!-- end card-body-->
    </div>
</div>

<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }

    .card-title {
        color: #2c3e50;
        font-weight: 600;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #212529;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    .fixed-table-toolbar .search input {
        border-radius: 4px;
        padding: 6px 12px;
    }
</style>

<script>
    // Función para los parámetros de consulta
    function queryParamsComision(params) {
        return {
            limit: params.limit,
            offset: params.offset,
            search: params.search,
            sort: params.sort,
            order: params.order
        };
    }

    // Función para manejar la respuesta
    function responseHandler(res) {
        if (res.success) {
            return {
                total: res.total,
                rows: res.rows
            };
        } else {
            // Mostrar mensaje de error si hay alguno
            if (res.message) {
                alert('Error: ' + res.message);
            }
            return {
                total: 0,
                rows: []
            };
        }
    }

    // Función para formatear las acciones
    function accionesFormatter(value, row) {
        return `
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-primary btn-sm" onclick="verComision(${value})" title="Ver">
                    <i class="fas fa-eye"></i>
                </button>
                <button type="button" class="btn btn-warning btn-sm" onclick="editarComision(${value})" title="Editar">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-danger btn-sm" onclick="eliminarComision(${value})" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
                <button type="button" class="btn btn-success btn-sm" onclick="exportarComision(${value})" title="Exportar CSV">
                    <i class="fas fa-download"></i>
                </button>
            </div>
        `;
    }

    // Función para formatear fechas
    function fechaFormatter(value) {
        if (!value) return '';
        const date = new Date(value);
        const day = date.getDate().toString().padStart(2, '0');
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }

    // Función para ver una comisión
    function verComision(id) {
        alert('Ver comisión ID: ' + id);
        // window.location.href = '<?php echo base_url('Comisiones/ver/') ?>' + id;
    }

    // Función para editar una comisión
    function editarComision(id) {
        alert('Editar comisión ID: ' + id);
        // window.location.href = '<?php echo base_url('Comisiones/editar/') ?>' + id;
    }

    // Función para eliminar una comisión
    function eliminarComision(id) {
        if (confirm('¿Estás seguro de que deseas eliminar esta comisión?')) {
            // Llamada AJAX para eliminar
            fetch('<?php echo base_url('Comisiones/eliminar/') ?>' + id, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Comisión eliminada correctamente');
                        // Recargar la tabla
                        $('#table_servidor').bootstrapTable('refresh');
                    } else {
                        alert('Error al eliminar: ' + (data.message || 'Error desconocido'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar la comisión');
                });
        }
    }

    // Función para exportar una comisión a CSV
    function exportarComision(id) {
        window.location.href = '<?php echo base_url('Comisiones/exportar_csv/') ?>' + id;
    }

    // Función para el estilo del encabezado
    function headerStyle(column) {
        return {
            css: {
                'font-weight': 'bold',
                'background-color': '#f8f9fa',
                'border-color': '#dee2e6'
            }
        };
    }

    // Inicialización cuando el documento está listo
    $(document).ready(function () {
        // Configurar búsqueda en tiempo real
        $('#searchInput').on('keyup', function () {
            const searchValue = $(this).val();
            $('#table_servidor').bootstrapTable('refresh', {
                query: {
                    search: searchValue
                }
            });
        });

        $('#searchButton').on('click', function () {
            const searchValue = $('#searchInput').val();
            $('#table_servidor').bootstrapTable('refresh', {
                query: {
                    search: searchValue
                }
            });
        });

        // Mostrar mensajes de éxito/error de PHP
        <?php if (session()->has('success')): ?>
            alert('<?= session('success') ?>');
        <?php endif; ?>

        <?php if (session()->has('error')): ?>
            alert('Error: <?= session('error') ?>');
        <?php endif; ?>
    });
</script>