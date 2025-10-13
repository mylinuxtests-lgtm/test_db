<?php

namespace App\Models;

use CodeIgniter\Model;

class ComisionModel extends Model
{
    protected $table = 'comision';
    protected $primaryKey = 'id_comision';
    protected $allowedFields = [
        'id_estructura_departamento_fk', 'id_empleado_fk', 'folio', 'asunto_corto',
        'fecha_inicio', 'fecha_fin', 'asunto', 'medio_transporte', 'anticipo_devengo',
        'aplica_alimentos', 'comision_fuera_estado', 'tarifa', 'dias', 'cuota',
        'partida_3710', 'partida_3720', 'id_municipio', 'partida_total',
        'fecha_registro', 'id_usuario_registro', 'fecha_actualiza', 'id_usuario_actualiza',
        'activo', 'visible'
    ];

    protected $useTimestamps = false;

    public function getUltimoFolio()
    {
        $result = $this->select('folio')
                      ->where('visible', 1)
                      ->orderBy('id_comision', 'DESC')
                      ->first();
        
        return $result ? $result['folio'] : null;
    }

    public function getComisionCompleta($id)
    {
        return $this->select('comision.*, empleados.nombre as nombre_empleado, empleados.clave_puesto, 
                             empleados.denominacion_puesto, empleados.tipo_contratacion,
                             cat_estructura.nombre as nombre_estructura,
                             municipios.nombre as nombre_municipio')
                    ->join('empleados', 'empleados.id_empleado = comision.id_empleado_fk')
                    ->join('cat_estructura', 'cat_estructura.id_estructura = comision.id_estructura_departamento_fk')
                    ->join('municipios', 'municipios.id_municipio = comision.id_municipio', 'left')
                    ->where('comision.id_comision', $id)
                    ->first();
    }
}