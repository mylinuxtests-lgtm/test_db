<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpleadoModel extends Model
{
    protected $table = 'empleados';
    protected $primaryKey = 'id_empleado';
    protected $allowedFields = ['nombre', 'clave_puesto', 'denominacion_puesto', 'tipo_contratacion', 'activo'];
    protected $useTimestamps = false;

    public function getEmpleadoCompleto($id)
    {
        return $this->select('empleados.*, cat_estructura.nombre as nombre_estructura, 
                             cat_estructura.direccion as direccion_estructura')
                    ->join('cat_estructura', 'cat_estructura.id_estructura = empleados.id_estructura_fk', 'left')
                    ->where('empleados.id_empleado', $id)
                    ->first();
    }
}