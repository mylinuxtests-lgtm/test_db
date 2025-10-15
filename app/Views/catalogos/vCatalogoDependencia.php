<div>
    
    <div class="card">
        <h6 class="card-header">Agregar nueva Dependencia</h6>
        <div class="card-body">
            <form id="form_dependencia" name="form_servidor" method="POST">
            <input type="hidden" id="id_dependencia" name="id_dependencia">
                <div class="row g-2">
                    <div class="mb-3 col-md-4">
                        <label for="dsc_dependencia" class="form-label">Nombre de la dependencia</label>
                        <input type="text" class="form-control" id="dsc_dependencia" name="dsc_dependencia" placeholder="Nombre Dependencia" required>
                    </div>
                </div>
                <button type="submit" id="btn_guardar_dependencia" class="btn btn-primary">
                    <span id="btn_text_dependencia">Agregar</span>
                    <span id="btn_loader_dependencia" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </form>
        </div> <!-- end card-body-->
    </div>
    <div class="card">
        <div class="card-body">
            <!-- Tabla boostrap -->
            <table 
                id="table_dependencia"
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
                data-query-params="queryParams"
                data-url="<?= base_url("getDependencias") ?>"
                >
                <thead>
                    <tr>
                        <th data-field="id_dependencia"  data-sortable="true" class="text-center" data-formatter="sigemed.catalogos.formatterAccionesDependencias"  data-width="250">Acciones</th>
                        <th data-field="dsc_dependencia" data-sortable="true"  >Dependencias</th>
                    </tr>
                </thead>
            </table>
        </div> <!-- end card-body-->
    </div>
</div>
