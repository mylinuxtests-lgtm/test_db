<?php $session = \Config\Services::session(); ?>
<?php $perfil = $session->get('id_perfil');?>
<div class="mt-2">
  <div class="card">
        <div class="card-body">
        <!--  -->
        <!-- Row para ESTATUS -->
        <h6 class="card-header">Estatus</h6>
            <div class="row mt-1">
                <?php foreach ($dasboard as $item): ?>
                    <?php if ($item->categoria === 'Estatus'): ?>
                        <div class="col-md-4">
                            <div class="card widget-flat">
                                <div class="card-body">
                                    <div class="float-end">
                                      <?php 
                                        $textoColor = 'text-success';
                                        if(strtoupper(esc($item->descripcion)) == 'NO PROCEDENTE'){
                                          $textoColor = 'text-danger';
                                        }elseif(strtoupper(esc($item->descripcion)) == 'EN PROCESO'){
                                          $textoColor = 'text-warning';
                                        }
                                      ?>
                                        <i class="mdi mdi-clipboard-check-outline bg-soft-success <?= $textoColor; ?>  widget-icon rounded-circle"></i>
                                    </div>
                                    <h6 class="text-muted text-uppercase mt-0"> <?= strtoupper(esc($item->descripcion)) ?></h6>
                                    <h2 class="my-2"><?= esc($item->total) ?></h2>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <!-- Row para PRIMICIA -->
            <h6 class="card-header">Clasificación para la Atención (Primicia)</h6>
            <div class="row mt-1">
                <?php foreach ($dasboard as $item): ?>
                    <?php if ($item->categoria === 'Primicia'): ?>
                        <div class="col-md-3">
                            <div class="card widget-flat">
                                <div class="card-body">
                                    <div class="float-end">
                                        <i class="mdi mdi-alert-decagram bg-soft-warning text-warning widget-icon rounded-circle"></i>
                                    </div>
                                    <h6 class="text-muted text-uppercase mt-0"> <?= strtoupper(esc($item->descripcion)) ?></h6>
                                    <h2 class="my-2"><?= esc($item->total) ?></h2>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <!--  -->
            <!-- Tabla boostrap -->
              <div class="row">
                <div class="col-md-4">
                  <div class="input-group " >
                    <label class="input-group-text" for="filtroStatus">Estatus de las solicitudes</label>
                        <select class="form-select" id="filtroStatus" name="filtroStatus" aria-label="Example select with button addon">
                            <option value="">Seleccione</option>
                            <?php foreach ($cat_estatus as $item): ?>
                                <option value="<?php echo $item->id_estatus; ?>"><?php echo $item->dsc_estatus; ?></option>
                            <?php endforeach; ?>
                        </select> 
                    <!-- <button type="button" class="btn btn-primary form-control" id="btnGuardar" title="Filtrar"><i class="mdi mdi-filter-plus" style="font-size: 15px; color: white;"></i></button>      -->
                    <button type="button" class="btn btn-info form-control" id="limpiar_filtro" onclick="sigemed.principal.limpiarFiltro();" title="Limpiar Filtro"> <i class="mdi mdi-filter-remove" style="font-size: 15px; color: white;"></i></button>
                  </div>
                </div>
              </div>
            <table 
                id="table_solicitud"
                data-locale="es-MX"
                data-toolbar="#toolbar"
                data-toggle="table"
                data-search="true"
                data-sortable="true"
                data-show-refresh="true"
                data-header-style="headerStyle"
                 data-unique-id="encode"
                data-show-export="true"
                data-search-highlight="true"
                data-pagination="true"
                data-side-pagination="server"
                data-page-list="[1,10, 25, 50, 100]"
                data-method="post"
                data-query-params="queryParams"
                data-url="<?= base_url("principal/getFolios") ?>"
                data-show-columns="true"
                data-show-columns-toggle-all="true"
                >
                <thead>
                    <tr>
                  <!--   <?php //if($perfil != '2'):?> -->
                      <th data-field="encode"  data-sortable="true" class="text-center" data-formatter="sigemed.principal.formatterAcciones"  >Acciones</th>
                   <!--  <?php //endif; ?> -->
                      <th data-field="dsc_estatus" data-sortable="true" data-formatter="sigemed.principal.formatterEstatus">Estatus</th>
                      <th data-field="dsc_primicia" data-sortable="true" >Primicia</th>
                      <th data-field="fecha_hora_solicitud" data-sortable="true" data-formatter="sigemed.principal.formatterFecha">Fecha de solicitud</th>
                      <th data-field="nombre_paciente" data-sortable="true" data-formatter="sigemed.principal.formatterNombre">Paciente</th>
                      <th data-field="antecedentes_patologicos" data-sortable="true" >Antecedentes patológicos</th>
                      <th data-field="antecedentes_quirurgicos" data-sortable="true" >Antecedentes quirúrgicos</th>
                      <th data-field="antecedentes_gyo" data-sortable="true" >Antecedentes gyo</th>
                      <th data-field="dsc_pais" data-sortable="true" data-formatter="sigemed.principal.formatterUbicacion">Ubicación</th>
                      <th data-field="otro_estado" data-sortable="true" >Otro estado</th>
                      <th data-field="dsc_afiliacion" data-sortable="true" data-formatter="sigemed.principal.formatterAfiliacion">Afiliación</th>
                      <th data-field="diagnosticos" data-sortable="true" >Diagnósticos</th>
                      <th data-field="peticion_concreta" data-sortable="true" >Peticion concreta</th>
                      <th data-field="solicitante" data-sortable="true" >Solicitante</th>
                      <th data-field="dsc_dependencia" data-sortable="true"  data-formatter="sigemed.principal.formatterDependencia">Dependencia</th>
                      <th data-field="dsc_servidor_publico" data-sortable="true" >Servidor público</th>
                      <th data-field="contacto_telefono" data-sortable="true" data-formatter="sigemed.principal.formatterContacto">Contacto</th>
                      <th data-field="padecimiento_actual" data-sortable="true" >Padecimiento actual</th>
                      <th data-field="observaciones" data-sortable="true" >Observaciones</th>
                    </tr>
                </thead>
            </table>
        </div> <!-- end card-body-->
  </div>
</div>
<!-- Standard modal -->

<div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Modal Heading</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
  