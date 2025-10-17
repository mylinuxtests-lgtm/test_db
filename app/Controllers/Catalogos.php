<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\Funciones;
use App\Models\Mglobal;
use App\Models\Comision_model;
use stdClass;
use CodeIgniter\API\ResponseTrait;

class Catalogos extends BaseController
{

    use ResponseTrait;
    private $globals;
    private $funciones;
    private $comision_model;

    private $defaultData = array(
        'title' => 'Sistema de Gestiones Médicas del Despacho',
        'layout' => 'plantilla/lytDefault',
        'contentView' => 'vUndefined',
        'stylecss' => '',
    );
    public function __construct()
    {
        setlocale(LC_TIME, 'es_ES.utf8', 'es_MX.UTF-8', 'es_MX', 'esp_esp', 'Spanish'); // usar solo LC_TIME para evitar que los decimales los separe con coma en lugar de punto y fallen los inserts de peso y talla
        date_default_timezone_set('America/Mexico_City');  
        $session = \Config\Services::session();
        $this->funciones = new Funciones();
        $this->globals = new Mglobal();

        $this->comision_model = new Comision_model();
    }
    private function _renderView($data = array()) {   
       
        $data = array_merge($this->defaultData, $data);
        echo view($data['layout'], $data);               
    }

    public function getComisiones()
    {
        try {
            // Obtener parámetros desde POST (igual que en tu clase original)
            $limit = $this->request->getPost('limit') ?? 10;
            $offset = $this->request->getPost('offset') ?? 0;
            $search = $this->request->getPost('search') ?? '';
            $sort = $this->request->getPost('sort') ?? 'id_comision';
            $order = $this->request->getPost('order') ?? 'asc';

            $limit = (int) $limit;
            $offset = (int) $offset;

            // Obtener los datos desde el modelo
            $result = $this->comision_model->get_comisiones($limit, $offset, $search, $sort, $order);

            // Devolver respuesta JSON
            return $this->respond([
                'total' => $result['total'],
                'rows' => $result['rows']
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error en getComisiones: ' . $e->getMessage());
            return $this->respond([
                'success' => false,
                'message' => 'Error al obtener las comisiones',
                'total' => 0,
                'rows' => []
            ], 500);
        }
    }
    public function index($params = false)
    {
        requireLogin(); 
        $session = \Config\Services::session();
        //if ( !$params ){}

        
        $data = array();

      /*   
        if ($params) {
            $params = $this->funciones->decrypt( $params );
            $data['solicitud'] = $this->globals->getTabla([
                "tabla" => "sgmd_solicitudes",
                "select" => "*",
                "where" => ["id_solicitud" => $params->idSolicitud]
            ])->data;
            
        }

        if ( isset($data['solicitud'][0]) && $data['solicitud'][0]->id_pais == ID_PAIS_MEXICO ){
            $data['estado'] = $this->globals->getTabla(["tabla"=>"cat_estado","select" =>"id_estado, dsc_estado", "where"=>[ "visible"=>1]])->data; 
            $data['municipios'] = $this->globals->getTabla(["tabla"=>"cat_municipios","select" =>"id_municipio, nombre_municipio", "where"=>["visible"=>1, 'id_estado'=>$data['solicitud'][0]->id_estado ]])->data; 
        }
      
        $estatus = $this->globals->getTabla(["tabla"=>"sgmd_cat_estatus","select" =>"id_estatus, dsc_estatus", "where"=>["visible"=>1]]); 
        $primicia = $this->globals->getTabla(["tabla"=>"sgmd_cat_primicia","select" =>"id_primicia, dsc_primicia", "where"=>["visible"=>1]]); 
        $afiliacion = $this->globals->getTabla(["tabla"=>"cat_afiliacion","select" =>"id_afiliacion, dsc_afiliacion", "where"=>["visible"=>1]]); 
        $pais = $this->globals->getTabla(["tabla"=>"cat_pais","select" =>"id_pais, dsc_pais", "where"=>["visible"=>1]]); 
        $dependencia = $this->globals->getTabla(["tabla"=>"cat_dependencia","select" =>"id_dependencia, dsc_dependencia", "where"=>["visible"=>1]]); 
        $servidorPublico = $this->globals->getTabla(["tabla"=>"sgmd_servidor_publico","select" =>"id_servidor_publico, dsc_servidor_publico", "where"=>["visible"=>1]]); 
        
        
        $data['estatus'] = !empty($estatus->data) ? $estatus->data : [];
        $data['primicia'] = !empty($primicia->data) ? $primicia->data : [];
        $data['pais'] = !empty($pais->data) ? $pais->data : [];
        $data['afiliacion'] = !empty($afiliacion->data) ? $afiliacion->data : [];
        $data['dependencia'] = !empty($dependencia->data) ? $dependencia->data : [];
        $data['servidorPublico'] = !empty($servidorPublico->data) ? $servidorPublico->data : [];
         */
       /*  var_dump($session->get());
        die(); */

        $data['scripts'] = array('catalogos');
        $data['contentView'] = 'catalogos/vCatalogoServidor';                
        $this->_renderView($data);  
    }
    public function dependencia($params = false)
    {
        requireLogin(); 
        $session = \Config\Services::session();
        //if ( !$params ){}
        $data = array();


        $data['scripts'] = array('catalogos');
        $data['contentView'] = 'catalogos/vCatalogoDependencia';                
        $this->_renderView($data);  
    }


    public function getServidoresPublicos()
    {      
        $global = new Mglobal();

        $data = $this->request->getBody();
        $data = json_decode($data);
        $response = new \stdClass();
        $dataConfig = [
            'dataBase' => 'sigemed',
            'tabla' => 'sgmd_servidor_publico',
            'where' => 'visible = 1',
            'order' => 'id_servidor_publico DESC',
        ];
        $dataConfig['limit'] = ['start' => $data->offset, 'length' => $data->limit];
        
        $where = "visible = 1 ";
       /*  if($data->estatus ){
            $where .= " AND id_estatus IN ('".$data->estatus."')  ";
        } */
        if ($data->search != "") {
            if (is_numeric($data->search)) {
                $where .= " AND ( id_servidor_publico = {$data->search} ";
            } else {
                $where .= " AND ( id_servidor_publico = '0' "; 
            }
            /* $where .= " AND ( id_turno = {$data->search} "; */
            /* $where .= " OR nombre LIKE '%{$data->search}%'"; */
           /*  $where .= " OR resumen LIKE '%{$data->search}%'"; */
            $where .= " OR dsc_servidor_publico LIKE '%{$data->search}%' )";
        }
        $dataConfig['where'] = $where;
        $request = $this->globals->getTabla($dataConfig);
        if (isset($dataConfig['limit'])) {
            unset($dataConfig['limit']);
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
    public function guardaServidor()
    {        
        $global = new Mglobal();
        $response = new \stdClass();
        $data = $this->request->getPost();
        $session = \Config\Services::session();
        /* Validacion */
        helper(['form']); 

        // validacion de datos
        $rules = [
            'dsc_servidor_publico' => 'required|string|max_length[100]',
            'telefono_laboral' => 'required|numeric|max_length[15]',
            'direccion_laboral' => 'permit_empty|string|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            //  Regresa errores si falla
            return $this->response->setJSON([
                'error' => true,
                'messages' => $this->validator->getErrors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }
        // sanitizar la data
        $data = $this->funciones->sanitizeArray($data);

        $currentDateTime = new \DateTime();
        $formattedDate = $currentDateTime->format('Y-m-d H:i:s');
       // $fecha_inicio = $currentDateTime::createFromFormat('d-m-Y', $data['fecha_inicio']);
               

        /* $fecha_registro = date("Y-m-d H:i:s");  */
        $fecha_registro = (isset($data['id_servidor_publico']) && !empty( $data['id_servidor_publico'] ) ) ? $data['fecha_registro'] : date("Y-m-d H:i:s"); 
        $dataInsert = [           
            'dsc_servidor_publico'              =>  (isset($data['dsc_servidor_publico']) || !empty($data['dsc_servidor_publico']))?$data['dsc_servidor_publico']:null,         
            'telefono_laboral'                  =>  $data['telefono_laboral'],         
            'direccion_laboral'                 =>  $data['direccion_laboral'],         
            'fecha_registro'                    =>  $fecha_registro, 
            'usuario_registro'                  => (isset($data['id_servidor_publico']) && !empty( $data['id_servidor_publico'] ) ) ? $data['usuario_registro'] : $session->get('id_usuario'),
            'usuario_actualiza'          => (isset($data['id_servidor_publico']) && !empty( $data['id_servidor_publico'] ) ) ? $session->get('id_usuario'): '' ,
        ];
       
        $dataBitacora = ['id_user' =>  1, 'script' => 'Catalogos.php/guardaServidor'];
        
        $dataConfig = [
            'dataBase' =>'sigemed',
            'tabla' => 'sgmd_servidor_publico',
            "editar"=>(isset($data['id_servidor_publico']) && !empty( $data['id_servidor_publico'] ) ) ? true : false ,
            'idEditar'=>(isset($data['id_servidor_publico']) && !empty( $data['id_servidor_publico'] ) ) ? ['id_servidor_publico' => $data['id_servidor_publico']] : '',
        ];
        try {
            $respuesta = $global->saveTabla($dataInsert,$dataConfig ,$dataBitacora);
            $response->respuesta = $respuesta;
            return $this->respond($response);
        } catch (\Exception $e) {
            //$this->handleException($e);
            $response->error = $e->getMessage();
            return $this->respond($response);
        }
    }
    public function eliminarServidor()
    {
        $response = new \stdClass();
        $Mglobal = new Mglobal();
        $response->error = true;
        $data = $this->request->getPost();
        
        if (!isset($data['id_servidor_publico']) || empty($data['id_servidor_publico'])){
            $response->respuesta = "No se ha proporcionado un identificador válido";
            return $this->respond($response);
        }

        $dataConfig = [
            "tabla"=>"sgmd_servidor_publico",
            "editar"=>true,
            "idEditar"=>['id_servidor_publico'=>$data['id_servidor_publico']]
        ];
       
        $response = $Mglobal->saveTabla(["visible"=>0],$dataConfig,["script"=>"Catalogos.deleteServidor"]);
        return $this->respond($response);
    }
    public function getDependencias()
    {      
        $global = new Mglobal();

        $data = $this->request->getBody();
        $data = json_decode($data);
        $response = new \stdClass();
        $dataConfig = [
            'dataBase' => 'sigemed',
            'tabla' => 'cat_dependencia',
            'where' => 'visible = 1',
            'order' => 'id_dependencia DESC',
        ];
        $dataConfig['limit'] = ['start' => $data->offset, 'length' => $data->limit];
        
        $where = "visible = 1 ";
       /*  if($data->estatus ){
            $where .= " AND id_estatus IN ('".$data->estatus."')  ";
        } */
        if ($data->search != "") {
            if (is_numeric($data->search)) {
                $where .= " AND ( id_dependencia = {$data->search} ";
            } else {
                $where .= " AND ( id_dependencia = '0' "; 
            }
            /* $where .= " AND ( id_turno = {$data->search} "; */
            /* $where .= " OR nombre LIKE '%{$data->search}%'"; */
           /*  $where .= " OR resumen LIKE '%{$data->search}%'"; */
            $where .= " OR dsc_dependencia LIKE '%{$data->search}%' )";
        }
        $dataConfig['where'] = $where;
        $request = $this->globals->getTabla($dataConfig);
        if (isset($dataConfig['limit'])) {
            unset($dataConfig['limit']);
        }
        $dataConfig['select'] = 'count(*) AS total_registros';
        $requestTotal = $this->globals->getTabla($dataConfig);
        $response->rows = $request->data;
        $response->total = $requestTotal->data[0]->total_registros;
        $response->totalNotFiltered = $requestTotal->data[0]->total_registros;

        return $this->respond($response);
    }
    public function eliminarDependencia()
    {
        $response = new \stdClass();
        $Mglobal = new Mglobal();
        $response->error = true;
        $data = $this->request->getPost();
        
        if (!isset($data['id_dependencia']) || empty($data['id_dependencia'])){
            $response->respuesta = "No se ha proporcionado un identificador válido";
            return $this->respond($response);
        }

        $dataConfig = [
            "tabla"=>"cat_dependencia",
            "editar"=>true,
            "idEditar"=>['id_dependencia'=>$data['id_dependencia']]
        ];
       
        $response = $Mglobal->saveTabla(["visible"=>0],$dataConfig,["script"=>"Catalogos.deleteDependecia"]);
        return $this->respond($response);
    }
    public function guardaDependencia()
    {        
        $global = new Mglobal();
        $response = new \stdClass();
        $data = $this->request->getPost();
        /* Validacion */
        helper(['form']); 

        // validacion de datos
        $rules = [
            'dsc_dependencia' => 'required|string|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            //  Regresa errores si falla
            return $this->response->setJSON([
                'error' => true,
                'messages' => $this->validator->getErrors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }
        // sanitizar la data
        $data = $this->funciones->sanitizeArray($data);

        $currentDateTime = new \DateTime();
        $formattedDate = $currentDateTime->format('Y-m-d H:i:s');
       // $fecha_inicio = $currentDateTime::createFromFormat('d-m-Y', $data['fecha_inicio']);
               

        $fecha_registro = date("Y-m-d H:i:s"); 
        $dataInsert = [           
            'dsc_dependencia'              =>  (isset($data['dsc_dependencia']) || !empty($data['dsc_dependencia']))?$data['dsc_dependencia']:null,                
            'fecha_registro'                    =>  $fecha_registro, 
            'usuario_registro'                  =>  1, 
        ];
       
        $dataBitacora = ['id_user' =>  1, 'script' => 'Catalogos.php/guardaDependencia'];
        
        $dataConfig = [
            'dataBase' =>'sigemed',
            'tabla' => 'cat_dependencia',
            "editar"=>(isset($data['id_dependencia']) && !empty( $data['id_dependencia'] ) ) ? true : false ,
            'idEditar'=>(isset($data['id_dependencia']) && !empty( $data['id_dependencia'] ) ) ? ['id_dependencia' => $data['id_dependencia']] : '',
        ];
        try {
            $respuesta = $global->saveTabla($dataInsert,$dataConfig ,$dataBitacora);
            $response->respuesta = $respuesta;
            return $this->respond($response);
        } catch (\Exception $e) {
            //$this->handleException($e);
            $response->error = $e->getMessage();
            return $this->respond($response);
        }
    }
    public function listado_estructura(){
        requireLogin(); 
        $session = \Config\Services::session();
        //if ( !$params ){}

        
        $data = array();
        $data['scripts'] = array('catalogos');
        $data['contentView'] = 'catalogos/vCatalogoEstructura';                
        $this->_renderView($data);  
    }
    public function getEstructura()
    {      
        $global = new Mglobal();

        $data = $this->request->getBody();
        $data = json_decode($data);
        $response = new \stdClass();
        $dataConfig = [
            'dataBase' => 'administracion_oficios',
            'tabla' => 'cat_estructura',
            'where' => 'visible = 1',
            'order' => 'id_estructura',
        ];
        $dataConfig['limit'] = ['start' => $data->offset, 'length' => $data->limit];
        
        $where = "visible = 1 ";
       /*  if($data->estatus ){
            $where .= " AND id_estatus IN ('".$data->estatus."')  ";
        } */
        if ($data->search != "") {           
            /* $where .= " AND ( id_turno = {$data->search} "; */
            /* $where .= " OR nombre LIKE '%{$data->search}%'"; */
           /*  $where .= " OR resumen LIKE '%{$data->search}%'"; */
            $where .= " and (dsc_estructura LIKE '%{$data->search}%' )";
        }
        $dataConfig['where'] = $where;
        $request = $this->globals->getTabla($dataConfig);
        if (isset($dataConfig['limit'])) {
            unset($dataConfig['limit']);
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
