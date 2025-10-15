<div>
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
                data-query-params="queryParamsComision"               
                data-url="<?php echo base_url('Catalogos/getComisiones') ?>"
                >
                <thead>
                    <tr>
                        <th data-field="id_estructura"  data-sortable="true" class="text-center" data-formatter="sigemed.catalogos.formatterAccionesEstructura"  data-width="250">Acciones</th>
                        <th data-field="id_oficio" data-sortable="true"  >No. Oficio</th>
                        <th data-field="id_empleado" data-sortable="true"  >Nombre Empleado</th>  
                        <th data-field="id_estructura_departamento" data-sortable="true"  >Departamento</th>       
                        <th data-field="asunto_corto" data-sortable="true"  >Asunto</th>
                    </tr>
                </thead>
            </table>
        </div> <!-- end card-body-->
    </div>
</div>

