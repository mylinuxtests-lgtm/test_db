<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Comision_model;
use CodeIgniter\API\ResponseTrait;

class Getcomision extends BaseController
{
    use ResponseTrait;

    private $comision_model;

    public function __construct()
    {
        setlocale(LC_TIME, 'es_ES.utf8', 'es_MX.UTF-8', 'es_MX', 'esp_esp', 'Spanish');
        date_default_timezone_set('America/Mexico_City');

        $this->comision_model = new Comision_model();
    }

    public function index()
    {
        try {
            $limit = $this->request->getPost('limit') ?? 10;
            $offset = $this->request->getPost('offset') ?? 0;
            $search = $this->request->getPost('search') ?? '';
            $sort = $this->request->getPost('sort') ?? 'id_comision';
            $order = $this->request->getPost('order') ?? 'asc';

            $result = $this->comision_model->get_comisiones($limit, $offset, $search, $sort, $order);

            return $this->respond([
                'success' => true,
                'total' => $result['total'],
                'rows' => $result['rows']
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error en Getcomision: ' . $e->getMessage());
            return $this->respond([
                'success' => false,
                'message' => 'Error al obtener los datos',
                'total' => 0,
                'rows' => []
            ], 500);
        }
    }

    public function getComisiones()
    {
        return $this->index();
    }
}