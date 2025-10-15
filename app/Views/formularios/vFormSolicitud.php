
    <div class="card shadow-sm mt-2">
    
        <div class="card-body">
            <form id="form_solicitud" name="form_solicitud" method="POST">
                <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?= isset($solicitud) ? esc($solicitud[0]->id_solicitud) : '' ?>">
                <input type="hidden" id="usuario_registro" name="usuario_registro" value="<?= isset($solicitud) ? esc($solicitud[0]->usuario_registro) : '' ?>">
                <input type="hidden" id="fecha_registro" name="fecha_registro" value="<?= isset($solicitud) ? esc($solicitud[0]->fecha_registro) : '' ?>">
                    <div class="row g-2 ">
                        <div class="col-md-12">
                            <h5 class="mb-3 font-weight-bold text-primary">📝 Solicitud</h5>
                        </div>
                       
                        <div class="mb-3 col-md-4 ">
                            <label for="id_estatus" class="form-label">Estatus <span class="text-danger">*</span></label>
                            <select class="form-control select2-no-search" id="id_estatus" name="id_estatus" required>
                                <?php if (!empty($estatus)) : ?>
                                    <option value="">Seleccione un estatus</option>
                                    <?php foreach ($estatus as $item): ?>
                                    <?php 
                                    $selected = false;
                                    if (isset($solicitud)) {
                                        $selected = ($solicitud[0]->id_estatus == $item->id_estatus);
                                    } else {
                                        $selected = ($item->id_estatus == 1); 
                                    }
                                    ?>
                                    <option value="<?= $item->id_estatus ?>" <?= $selected ? 'selected' : '' ?>>
                                        <?= esc($item->dsc_estatus) ?>
                                    </option>
                                <?php endforeach; ?>
                                <?php else : ?>
                                    <option value="">No hay datos para mostrar</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label for="id_primicia" class="form-label">Clasificación para la Atención (Primicia) <span class="text-danger">*</span></label>
                            <select class="form-control select2-no-search" id="id_primicia" name="id_primicia" required>
                                <?php if (!empty($primicia)) : ?>
                                    <option value="">Seleccione una clasificación</option>
                                    <?php foreach ($primicia as $item): ?>
                                            <!-- <option value="<?php echo $item->id_primicia; ?>"><?php echo $item->dsc_primicia; ?></option> -->
                                            <option value="<?= $item->id_primicia ?>"
                                            <?= (isset($solicitud) && $solicitud[0]->id_primicia == $item->id_primicia) ? 'selected' : '' ?>>
                                            <?= esc($item->dsc_primicia) ?>
                                            </option>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <option value="">No hay datos para mostrar</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-3 col-md-4 mb-2">
                            <label for="fecha_hora_solicitud" class="form-label">Fecha de Solicitud <span class="text-danger">*</span></label>
                            <!-- <input type="datetime-local" class="form-control" id="fecha_hora_solicitud" name="fecha_hora_solicitud" value="<?= isset($solicitud) ? esc($solicitud[0]->fecha_hora_solicitud) : '' ?>"> -->
                            <input type="datetime-local" class="form-control" id="fecha_hora_solicitud" name="fecha_hora_solicitud" value="<?= isset($solicitud) ? date('Y-m-d\TH:i', strtotime($solicitud[0]->fecha_hora_solicitud)) : date('Y-m-d\TH:i') ?>" max="<?= date('Y-m-d\TH:i') ?>" required>
                        </div>
                    </div>
                   
                    <div class="row mb-3">
                        <div class=" col-12">
                        <hr>
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="3"> <?= isset($solicitud) ? esc($solicitud[0]->observaciones) : '' ?></textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3 font-weight-bold text-primary">🧾 Datos del paciente</h5>
                        </div>
                        <div class=" col-12 col-md-3">
                            <label for="nombre_paciente">Nombre del Paciente <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre_paciente" name="nombre_paciente" value="<?= isset($solicitud) ? esc($solicitud[0]->nombre_paciente) : '' ?>">
                        </div>

                        <div class=" col-12 col-md-3">
                            <label for="primer_apellido_paciente">Primer Apellido <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="primer_apellido_paciente" name="primer_apellido_paciente" value="<?= isset($solicitud) ? esc($solicitud[0]->primer_apellido_paciente) : '' ?>">
                        </div>

                        <div class=" col-12 col-md-3">
                            <label for="segundo_apellido_paciente">Segundo Apellido <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="segundo_apellido_paciente" name="segundo_apellido_paciente" value="<?= isset($solicitud) ? esc($solicitud[0]->segundo_apellido_paciente) : '' ?>">
                        </div>
                        
                        <div class=" col-12 col-md-3">
                            <label for="fecha_nacimiento_paciente">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="fecha_nacimiento_paciente" name="fecha_nacimiento_paciente" value="<?= isset($solicitud) ? esc($solicitud[0]->fecha_nacimiento_paciente) : '' ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class=" col-12 col-md-6">
                            <label for="id_afiliacion">Afiliación</label>
                            <select class="form-control select2" id="id_afiliacion" name="id_afiliacion">
                                <?php if (!empty($afiliacion)) : ?>
                                    <option value="">Seleccione una afiliación</option>
                                    <?php foreach ($afiliacion as $item): ?>
                                            <!-- <option value="<?php echo $item->id_afiliacion; ?>"><?php echo $item->dsc_afiliacion; ?></option> -->
                                            <option value="<?= $item->id_afiliacion ?>"
                                            <?= (isset($solicitud) && $solicitud[0]->id_afiliacion == $item->id_afiliacion) ? 'selected' : '' ?>>
                                            <?= esc($item->dsc_afiliacion) ?>
                                            </option>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <option value="">No hay datos para mostrar</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class=" col-12 col-md-6">
                            <label for="folio_afiliacion">Folio de afiliación (si aplica)</label>
                            <input type="text" class="form-control" id="folio_afiliacion" name="folio_afiliacion" value="<?= isset($solicitud) ? esc($solicitud[0]->folio_afiliacion) : '' ?>">
                        </div>
                    </div>
                    <hr>    
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3 font-weight-bold text-primary">📋 Antecedentes</h5>
                        </div>
                        <!-- Textareas -->
                        <div class=" col-12 col-md-12">
                            <label for="antecedentes_patologicos">Antecedentes Patológicos</label>
                            <textarea class="form-control" id="antecedentes_patologicos" name="antecedentes_patologicos" rows="3" ><?= isset($solicitud) ? esc($solicitud[0]->antecedentes_patologicos) : '' ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class=" col-12 col-md-12">
                            <label for="antecedentes_quirurgicos">Antecedentes Quirúrgicos</label>
                            <textarea class="form-control" id="antecedentes_quirurgicos" name="antecedentes_quirurgicos" rows="3" ><?= isset($solicitud) ? esc($solicitud[0]->antecedentes_quirurgicos) : '' ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class=" col-12 col-md-12">
                            <label for="antecedentes_gyo">Antecedentes Gineco-Obstétricos</label>
                            <textarea class="form-control" id="antecedentes_gyo" name="antecedentes_gyo" rows="3" ><?= isset($solicitud) ? esc($solicitud[0]->antecedentes_gyo) : '' ?></textarea>
                        </div>
                    </div>
                    <hr>    
                    <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3 font-weight-bold text-primary">📍 Ubicación y Contacto</h5>
                    </div>
                        <!-- Más selects -->
                        <div class=" col-12 col-md-4">
                            <label for="id_pais">País <span class="text-danger">*</span></label>
                            <select class="form-control select2" id="id_pais" name="id_pais">
                                    <?php if (!empty($pais)) : ?>
                                    <option value="">Seleccione un estatus</option>
                                    <?php foreach ($pais as $item): ?>
                                    <?php 
                                    $selected = false;
                                    if (isset($solicitud)) {
                                        $selected = ($solicitud[0]->id_pais == $item->id_pais);
                                    } else {
                                        $selected = ($item->id_pais == 142); 
                                    }
                                    ?>
                                    <option value="<?= $item->id_pais ?>" <?= $selected ? 'selected' : '' ?>>
                                        <?= esc($item->dsc_pais) ?>
                                    </option>
                                <?php endforeach; ?>
                                <?php else : ?>
                                    <option value="">No hay datos para mostrar</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class=" col-12 col-md-4">
                            <label for="id_estado">Estado</label>
                            <select class="form-control select2" id="id_estado" name="id_estado">
                                <option value="">Seleccione</option>
                                <?php if(isset($estado)): ?>
                                    <?php foreach($estado as $item): ?>
                                        <option value="<?= $item->id_estado?>" <?= ( isset( $solicitud[0] ) && $solicitud[0]->id_estado == $item->id_estado )? "selected":"" ?> ><?= $item->dsc_estado?></option>
                                    <?php endforeach?>
                                <?php endif?>
                            </select>
                        </div>

                        <div class=" col-12 col-md-4">
                            <label for="id_municipio">Municipio</label>
                            <select class="form-control select2" id="id_municipio" name="id_municipio">
                            <option value="">Seleccione</option>
                            <?php if(isset($municipios)): ?>
                                    <?php foreach($municipios as $item): ?>
                                        <option value="<?= $item->id_municipio?>" <?= ( isset( $solicitud[0] ) && $solicitud[0]->id_municipio == $item->id_municipio )? "selected":"" ?> ><?= $item->nombre_municipio?></option>
                                    <?php endforeach?>
                                <?php endif?>
                            </select>
                        </div>
                    </div>
                       
                    <div class="row">
                        <div class=" col-12 col-md-6" >
                            <label for="otro_estado">Otro Estado</label>
                            <input type="text" class="form-control" id="otro_estado" name="otro_estado" value="<?= isset($solicitud) ? esc($solicitud[0]->otro_estado) : '' ?>">
                        </div>
                        <!-- Más inputs -->
                        <div class=" col-12 col-md-6">
                            <label for="domicilio_paciente">Domicilio</label>
                            <input type="text" class="form-control" id="domicilio_paciente" name="domicilio_paciente" value="<?= isset($solicitud) ? esc($solicitud[0]->domicilio_paciente) : '' ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class=" col-12 col-md-6">
                            <label for="solicitante">Solicitante</label>
                            <input type="text" class="form-control" id="solicitante" name="solicitante" value="<?= isset($solicitud) ? esc($solicitud[0]->solicitante) : '' ?>">
                        </div>
                        <div class=" col-12 col-md-6">
                            <label for="id_servidor_publico">Servidor Público</label>
                            <select class="form-control select2" id="id_servidor_publico" name="id_servidor_publico">
                                <?php if (!empty($servidorPublico)) : ?>
                                    <option value="">Seleccione un servidor public</option>
                                    <?php foreach ($servidorPublico as $item): ?>
                                            <!-- <option value="<?php echo $item->id_servidor_publico; ?>"><?php echo $item->dsc_servidor_publico; ?></option> -->
                                            <option value="<?= $item->id_servidor_publico ?>"
                                            <?= (isset($solicitud) && $solicitud[0]->id_servidor_publico == $item->id_servidor_publico) ? 'selected' : '' ?>>
                                            <?= esc($item->dsc_servidor_publico) ?>
                                            </option>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <option value="">No hay datos para mostrar</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class=" col-12 col-md-6">
                            <label for="id_dependencia">Dependencia</label>
                            <select class="form-control select2" id="id_dependencia" name="id_dependencia">
                                <?php if (!empty($dependencia)) : ?>
                                    <option value="">Seleccione una dependencia</option>
                                    <?php foreach ($dependencia as $item): ?>
                                            <!-- <option value="<?php echo $item->id_dependencia; ?>"><?php echo $item->dsc_dependencia; ?></option> -->
                                            <option value="<?= $item->id_dependencia ?>"
                                            <?= (isset($solicitud) && $solicitud[0]->id_dependencia == $item->id_dependencia) ? 'selected' : '' ?>>
                                            <?= esc($item->dsc_dependencia) ?>
                                            </option>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <option value="">No hay datos para mostrar</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class=" col-12 col-md-6">
                            <label for="contacto_telefono">Teléfono <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="contacto_telefono" name="contacto_telefono" value="<?= isset($solicitud) ? esc($solicitud[0]->contacto_telefono) : '' ?>">
                        </div>

                        <div class=" col-12 col-md-6">
                            <label for="contacto_correo">Correo <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="contacto_correo" name="contacto_correo" value="<?= isset($solicitud) ? esc($solicitud[0]->contacto_correo) : '' ?>">
                        </div>
                    </div>
                    <hr>       
                    <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3 font-weight-bold text-primary">📄 Petición</h5>
                    </div>
                        <!-- Más textareas -->
                        <div class=" col-12 col-md-12">
                            <label for="diagnosticos">Diagnósticos <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="diagnosticos" name="diagnosticos" rows="3" ><?= isset($solicitud) ? esc($solicitud[0]->diagnosticos) : '' ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class=" col-12 col-md-12">
                            <label for="peticion_concreta">Petición Concreta <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="peticion_concreta" name="peticion_concreta" rows="3" ><?= isset($solicitud) ? esc($solicitud[0]->peticion_concreta) : '' ?></textarea>
                        </div>
                    </div>
                    <hr> 
                    <div class="row mb-2">
                        <div class=" col-12">
                            <label for="padecimiento_actual">Padecimiento Actual <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="padecimiento_actual" name="padecimiento_actual" value="<?= isset($solicitud) ? esc($solicitud[0]->padecimiento_actual) : '' ?>">
                        </div>
                    </div>    
                    <div class="text-right">
                    <!-- <button type="submit" class="btn btn-primary">Guardar</button> -->
                        <button type="submit" id="btn_guardar" class="btn <?= isset($solicitud) ? "btn-success" : "btn-primary" ?>">
                            <span id="btn_text"><?= isset($solicitud) ? "Actualizar" : "Agregar" ?></span>
                            <span id="btn_loader" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
            </form>
        </div>
    </div>
