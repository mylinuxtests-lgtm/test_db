<div>
    <div class="card">
        <h6 class="card-header">Agregar nuevo tipo de contrato</h6>
        <div class="card-body">
            <form id="form_tipo_contrato" name="form_tipo_contrato" method="POST">
                <input type="hidden" id="id_tipo_contrato" name="id_tipo_contrato">
                <input type="hidden" id="usuario_registro" name="usuario_registro">
                <input type="hidden" id="fecha_registro" name="fecha_registro">
                <div class="row g-2">
                    <div class="mb-3 col-md-12">
                        <label for="dsc_tipo_contrato" class="form-label">Descripción del tipo de contrato</label>
                        <input type="text" class="form-control" id="dsc_tipo_contrato" name="dsc_tipo_contrato"
                            placeholder="Descripción del tipo de contrato" required>
                    </div>
                </div>
                <button type="submit" id="btn_guardar_tipo_contrato" class="btn btn-primary">
                    <span id="btn_text_tipo_contrato">Agregar</span>
                    <span id="btn_loader_tipo_contrato" class="spinner-border spinner-border-sm d-none" role="status"
                        aria-hidden="true"></span>
                </button>
            </form>
        </div> <!-- end card-body-->
    </div>
    <div class="card">
        <div class="card-body">
            <!-- Tabla bootstrap -->
            <table id="table_tipo_contrato" data-locale="es-MX" data-toolbar="#toolbar" data-toggle="table"
                data-search="true" data-sortable="true" data-show-refresh="true" data-header-style="headerStyle"
                data-show-export="true" data-search-highlight="true" data-pagination="true"
                data-side-pagination="server" data-page-list="[1,10, 25, 50, 100]" data-method="post"
                data-query-params="queryParamsTipoContrato"
                data-url="<?php echo base_url() . "index.php/Catalogos/getTipoContrato" ?>">
                <thead>
                    <tr>
                        <th data-field="id_tipo_contrato" data-sortable="true" class="text-center"
                            data-formatter="sigemed.catalogos.formatterAccionesTipoContrato" data-width="250">Acciones
                        </th>
                        <th data-field="dsc_tipo_contrato" data-sortable="true">Descripción</th>
                    </tr>
                </thead>
            </table>
        </div> <!-- end card-body-->
    </div>
</div>