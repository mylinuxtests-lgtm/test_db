<?php
namespace App\Models;

use CodeIgniter\Model;

class Comision_model extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'comision';
    protected $primaryKey = 'id_comision';
    protected $allowedFields = [
        'folio',
        'id_empleado_fk',
        'id_estructura_departamento_fk',
        'id_municipio',
        'asunto_corto',
        'fecha_inicio',
        'fecha_fin',
        'asunto',
        'medio_transporte',
        'anticipo_devengo',
        'aplica_alimentos',
        'tarifa',
        'dias',
        'cuota',
        'comision_fuera_estado',
        'partida_3710',
        'partida_3720',
        'partida_total',
        'id_usuario_registro',
        'activo',
        'visible'
    ];

    public function get_comisiones($limit = 10, $offset = 0, $search = '', $sort = 'id_comision', $order = 'asc')
    {
        // Construir la consulta base
        $builder = $this->db->table('comision c');
        $builder->select('
            c.id_comision,
            c.folio as id_oficio,
            CONCAT(e.nombre, " ", e.primer_apellido, " ", COALESCE(e.segundo_apellido, "")) AS id_empleado,
            ce.dsc_estructura as id_estructura_departamento,
            c.asunto_corto AS asunto_corto,
            c.asunto AS asunto,
            c.fecha_inicio,
            c.fecha_fin
        ');
        $builder->join('empleados e', 'c.id_empleado_fk = e.id_empleado', 'left');
        $builder->join('cat_estructura ce', 'c.id_estructura_departamento_fk = ce.id_estructura', 'left');
        $builder->where('c.activo', 1);
        $builder->where('c.visible', 1);

        // Aplicar búsqueda
        if (!empty($search)) {
            $builder->groupStart();
            $builder->like('c.folio', $search);
            $builder->orLike('c.asunto_corto', $search);
            $builder->orLike('c.asunto', $search);
            $builder->orLike('e.nombre', $search);
            $builder->orLike('e.primer_apellido', $search);
            $builder->orLike('ce.dsc_estructura', $search);
            $builder->groupEnd();
        }

        // Obtener el total de registros (sin limit y offset)
        $total = $builder->countAllResults(false);

        // Aplicar ordenamiento
        $builder->orderBy($sort, $order);

        // Aplicar límite y offset
        $builder->limit($limit, $offset);

        // Ejecutar consulta final
        $query = $builder->get();
        $rows = $query->getResultArray();

        return [
            'total' => $total,
            'rows' => $rows
        ];
    }

    public function getEmpleados()
    {
        $builder = $this->db->table('empleados e');
        $builder->select('
            e.id_empleado,
            e.nombre,
            e.primer_apellido,
            e.segundo_apellido,
            p.dsc_puesto,
            p.id_puesto as id_puesto_fk,
            tc.dsc_tipo_contrato,
            ce.dsc_estructura,
            ce.id_estructura as id_estructura_fk
        ');
        $builder->join('cat_puestos p', 'e.id_puesto_fk = p.id_puesto', 'left');
        $builder->join('cat_tipo_contrato tc', 'e.id_tipo_contrato_fk = tc.id_tipo_contrato', 'left');
        $builder->join('cat_estructura ce', 'e.id_estructura_fk = ce.id_estructura', 'left');
        $builder->where('e.activo', 1);

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getEstados()
    {
        $builder = $this->db->table('cat_estado');
        $builder->select('id_estado, nombre_estado, abreviacion');
        $builder->where('id_estado !=', 0);
        $builder->where('id_estado !=', 88);
        $builder->where('id_estado !=', 99);
        $builder->where('visible', 1);
        $builder->orderBy('nombre_estado', 'asc');

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getMunicipios()
    {
        $builder = $this->db->table('cat_municipios m');
        $builder->select('m.id_municipio, m.nombre_municipio, e.nombre_estado');
        $builder->join('cat_estado e', 'm.id_estado = e.id_estado', 'left');
        $builder->where('m.visible', 1);
        $builder->orderBy('m.nombre_municipio', 'asc');

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getUltimoFolio()
    {
        $builder = $this->db->table('comision');
        $builder->select('folio');
        $builder->orderBy('id_comision', 'DESC');
        $builder->limit(1);

        $query = $builder->get();
        $result = $query->getRow();

        return $result ? $result->folio : 0;
    }

    public function guardarComision($data)
    {
        try {
            // Preparar datos para inserción
            $comisionData = [
                'folio' => $data['folio'],
                'id_empleado_fk' => $data['id_empleado_fk'],
                'id_estructura_departamento_fk' => $data['id_estructura_departamento_fk'],
                'id_municipio' => $data['id_municipio'] ?? null,
                'asunto_corto' => $data['asunto_corto'] ?? '',
                'fecha_inicio' => $data['fecha_inicio'],
                'fecha_fin' => $data['fecha_fin'],
                'asunto' => $data['asunto'],
                'medio_transporte' => $data['medio_transporte'] ?? null,
                'anticipo_devengo' => $data['anticipo_devengo'] ?? null,
                'aplica_alimentos' => isset($data['aplica_alimentos']) ? 1 : 0,
                'tarifa' => $data['tarifa'] ?? 0,
                'dias' => $data['dias'] ?? 0,
                'cuota' => $data['cuota'] ?? 0,
                'comision_fuera_estado' => isset($data['comision_fuera_estado']) ? 1 : 0,
                'partida_3710' => $data['partida_3710'] ?? 0,
                'partida_3720' => $data['partida_3720'] ?? 0,
                'partida_total' => $data['partida_total'] ?? 0,
                'id_usuario_registro' => $data['id_usuario_registro'] ?? 1,
                'activo' => 1,
                'visible' => 1
            ];

            $this->db->table('comision')->insert($comisionData);
            return $this->db->insertID();

        } catch (\Exception $e) {
            log_message('error', 'Error en Comision_model::guardarComision: ' . $e->getMessage());
            return false;
        }
    }

    public function getEstructuras()
    {
        $builder = $this->db->table('cat_estructura');
        $builder->select('id_estructura, dsc_estructura');
        $builder->where('visible', 1);

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getPuestos()
    {
        $builder = $this->db->table('cat_puestos');
        $builder->select('id_puesto, dsc_puesto');
        $builder->where('visible', 1);

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getTiposContrato()
    {
        $builder = $this->db->table('cat_tipo_contrato');
        $builder->select('id_tipo_contrato, dsc_tipo_contrato');
        $builder->where('visible', 1);

        $query = $builder->get();
        return $query->getResultArray();
    }
}