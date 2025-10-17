<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Funciones;
use App\Models\Mglobal;

use stdClass;
/* use CodeIgniter\HTTP\ResponseInterface; */
use CodeIgniter\API\ResponseTrait;

class Principal extends BaseController
{
    use ResponseTrait;
    private $globals;
    private $funciones;

    private $defaultData = array(
        'title' => 'Sistema de Gestiones Médicas del Despacho',
        'layout' => 'plantilla/lytDefault',
        'contentView' => 'vUndefined',
        'stylecss' => '',
    );
    public function __construct()
    {
        //fechas php en espanol
        setlocale(LC_TIME, 'es_ES.utf8', 'es_MX.UTF-8', 'es_MX', 'esp_esp', 'Spanish'); // usar solo LC_TIME para evitar que los decimales los separe con coma en lugar de punto y fallen los inserts de peso y talla
        date_default_timezone_set('America/Mexico_City');  
        $session = \Config\Services::session();
        $this->globals = new Mglobal();
        $this->funciones = new Funciones();
    }
    private function _renderView($data = array()) {   
       
        $data = array_merge($this->defaultData, $data);
        echo view($data['layout'], $data);               
    }

    public function index()
    {        
        $session = \Config\Services::session();
        $data = array();
        requireLogin(); 
        $cat_estatus = $this->globals->getTabla(["tabla"=>"sgmd_cat_estatus","select" =>"id_estatus, dsc_estatus", "where"=>["visible"=>1]]); 
        $dasboard = $this->globals->getTabla(["tabla"=>"vw_resumen_solicitudes","select" =>"categoria, descripcion, total"]); 
        $data['cat_estatus'] = !empty($cat_estatus->data) ? $cat_estatus->data : [];
        $data['dasboard'] = !empty($dasboard->data) ? $dasboard->data : [];
        $data['scripts'] = array('principal');
        $data['layout'] = 'plantilla/lytDefault.php';
        $data['contentView'] = 'secciones/vInicio';                
        $this->_renderView($data);   
        
    }
    public function getFolios()
    {
        $global = new Mglobal();
        $data = $this->request->getBody();
        $data = json_decode($data);
        $response = new \stdClass();
        $dataConfig = [
            'dataBase' => 'sigemed',
            'tabla' => 'vw_solicitudes',
            'where' => 'visible = 1',
            'order' => 'id_solicitud DESC',
        ];
        $dataConfig['limit'] = ['start' => $data->offset, 'length' => $data->limit];
        
        $where = "visible = 1 ";
        if($data->estatus ){
            $where .= " AND id_estatus IN ('".$data->estatus."')  ";
        }
        if ($data->search != "") {
            if (is_numeric($data->search)) {
                $where .= " AND ( id_solicitud = {$data->search} ";
            } else {
                $where .= " AND ( id_solicitud = '0' "; 
            }
            /* $where .= " AND ( id_turno = {$data->search} "; */
            /* $where .= " OR nombre LIKE '%{$data->search}%'"; */
           /*  $where .= " OR resumen LIKE '%{$data->search}%'"; */
            $where .= " OR nombre_paciente LIKE '%{$data->search}%' )";
        }
        $dataConfig['where'] = $where;
        $request = $this->globals->getTabla($dataConfig);
        if (isset($dataConfig['limit'])) {
            unset($dataConfig['limit']);
        }

        foreach ($request->data as $item) {
            $item->encode = $this->funciones->encrypt( [ 'idSolicitud'=>$item->id_solicitud ] );
            unset($item->id_solicitud);
        }

        $dataConfig['select'] = 'count(*) AS total_registros';
        $requestTotal = $this->globals->getTabla($dataConfig);
        $response->rows = $request->data;
        $response->total = $requestTotal->data[0]->total_registros;
        $response->totalNotFiltered = $requestTotal->data[0]->total_registros;

        return $this->respond($response);
       /*  $obras = $global->getTabla(["tabla"=>"vw_obras", 'where' => [ 'visible' => 1 ]]); */
        /* return $this->respond($obras->data); */ 
    
    }
    
   
}
