<div>
    <div class="card">
        <h6 class="card-header">Agregar nuevo puesto</h6>
        <div class="card-body">
            <form id="form_puesto" name="form_puesto" method="POST">
                <input type="hidden" id="id_puesto" name="id_puesto">
                <input type="hidden" id="usuario_registro" name="usuario_registro">
                <input type="hidden" id="fecha_registro" name="fecha_registro">
                <div class="row g-2">
                    <div class="mb-3 col-md-12">
                        <label for="dsc_puesto" class="form-label">Descripción del puesto</label>
                        <input type="text" class="form-control" id="dsc_puesto" name="dsc_puesto" placeholder="Descripción del puesto" required>
                    </div>
                </div>
                <button type="submit" id="btn_guardar_puesto" class="btn btn-primary">
                    <span id="btn_text_puesto">Agregar</span>
                    <span id="btn_loader_puesto" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </form>
        </div> <!-- end card-body-->
    </div>
    <div class="card">
        <div class="card-body">
            <!-- Tabla bootstrap -->
            <table 
                id="table_puestos"
                data-locale="es-MX"
                data-toolbar="#toolbar"
                data-toggle="table"
                data-search="true"
                data-sortable="true"
                data-show-refresh="true"
                data-header-style="headerStyle"
                data-show-export="true"
                data-search-highlight="true"
                data-pagination="true"
                data-side-pagination="server"
                data-page-list="[1,10, 25, 50, 100]"
                data-method="post"
                data-query-params="queryParamsPuestos"               
                data-url="<?php echo base_url()."index.php/Catalogos/getPuestos" ?>"
                >
                <thead>
                    <tr>
                        <th data-field="id_puesto" data-sortable="true" class="text-center" data-formatter="sigemed.catalogos.formatterAccionesPuestos" data-width="250">Acciones</th>
                        <th data-field="dsc_puesto" data-sortable="true">Descripción</th>  
                    </tr>
                </thead>
            </table>
        </div> <!-- end card-body-->
    </div>
</div>