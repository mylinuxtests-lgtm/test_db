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

<script src="<?php echo base_url('assets/js/comision.js'); ?>"></script>