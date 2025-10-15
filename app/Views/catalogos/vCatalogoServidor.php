<div>
    
    <div class="card">
        <h6 class="card-header">Agregar nuevo servidor público</h6>
        <div class="card-body">
            <form id="form_servidor" name="form_servidor" method="POST">
            <input type="hidden" id="id_servidor_publico" name="id_servidor_publico">
            <input type="hidden" id="usuario_registro" name="usuario_registro" >
            <input type="hidden" id="fecha_registro" name="fecha_registro" >
                <div class="row g-2">
                    <div class="mb-3 col-md-4">
                        <label for="dsc_servidor_publico" class="form-label">Nombre del servidor público</label>
                        <input type="text" class="form-control" id="dsc_servidor_publico" name="dsc_servidor_publico" placeholder="Nombre contratista" required>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="telefono_laboral" class="form-label">Teléfono laboral</label>
                        <input type="tel" class="form-control" name="telefono_laboral" id="telefono_laboral" placeholder="Ingrese su número de teléfono" required>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="direccion_laboral" class="form-label">Dirección laboral</label>
                        <input type="text" class="form-control" name="direccion_laboral" id="direccion_laboral" placeholder="Ingrese su dirección" autocomplete="street-address" required>
                    </div>
                </div>
                <button type="submit" id="btn_guardar" class="btn btn-primary">
                    <span id="btn_text">Agregar</span>
                    <span id="btn_loader" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </form>
        </div> <!-- end card-body-->
    </div>
    <div class="card">
        <div class="card-body">
            <!-- Tabla boostrap -->
            <table 
                id="table_servidor"
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
                data-url="<?= base_url("getServidoresPublicos") ?>"
                >
                <thead>
                    <tr>
                        <th data-field="id_servidor_publico"  data-sortable="true" class="text-center" data-formatter="sigemed.catalogos.formatterAcciones"  data-width="250">Acciones</th>
                        <th data-field="dsc_servidor_publico" data-sortable="true"  >Nombre</th>
                        <th data-field="telefono_laboral" data-sortable="true">Teléfono</th>
                        <th data-field="direccion_laboral" data-sortable="true" class="text-center" >Dirección</th>
                    </tr>
                </thead>
            </table>
        </div> <!-- end card-body-->
    </div>
</div>
