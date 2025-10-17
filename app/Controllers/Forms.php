<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\Funciones;
use App\Models\Mglobal;
use stdClass;
use CodeIgniter\API\ResponseTrait;
class Forms extends BaseController
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
        setlocale(LC_TIME, 'es_ES.utf8', 'es_MX.UTF-8', 'es_MX', 'esp_esp', 'Spanish'); // usar solo LC_TIME para evitar que los decimales los separe con coma en lugar de punto y fallen los inserts de peso y talla
        date_default_timezone_set('America/Mexico_City');  
        $session = \Config\Services::session();
        $this->funciones = new Funciones();
        $this->globals = new Mglobal();
    }
    private function _renderView($data = array()) {   
       
        $data = array_merge($this->defaultData, $data);
        echo view($data['layout'], $data);               
    }
    public function index($params = false)
    {
        requireLogin(); 
        $session = \Config\Services::session();
        //if ( !$params ){}

        
        $data = array();

        
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
        
       /*  var_dump($session->get());
        die(); */

        $data['scripts'] = array('forms');
        $data['contentView'] = 'formularios/vFormSolicitud';                
        $this->_renderView($data);  
    }
    public function getEstadosByPais()
    { 
        $idPais = $this->request->getPost('id_pais');

        if(!$idPais || $idPais != 142) {
            return $this->response->setJSON(['data' => []]);
        }
        $estados = $this->globals->getTabla([
            "tabla" => "cat_estado",
            "select" =>"id_estado, dsc_estado",
            "where" => ["visible" => 1]
        ]);
        return $this->response->setJSON($estados->data);
    }
    public function getMunicipiosByEstado()
    {
        $idEstado = $this->request->getPost('id_estado');
        $municipios = $this->globals->getTabla([
            "tabla" => "cat_municipios",
            "select" => "id_municipio, nombre_municipio",
            "where" => ["visible" => 1, "id_estado" => $idEstado]
        ]);
        return $this->response->setJSON($municipios->data);
    }
    public function guardarFolio(){
        $global = new Mglobal();
        $response = new \stdClass();
        $session = \Config\Services::session();
        $data = $this->request->getPost();
        $response->error = true;

        helper(['form']); 

        // validacion de datos
        $rules = [
            'nombre_paciente' => 'required|string|max_length[100]',
            'primer_apellido_paciente' => 'required|string|max_length[100]',
            'segundo_apellido_paciente' => 'permit_empty|string|max_length[100]',
            'contacto_correo' => 'required|valid_email|max_length[100]',
            'contacto_telefono' => 'required|numeric|max_length[15]',
            'fecha_hora_solicitud' => 'required|valid_date[Y-m-d\TH:i]',
            'id_pais' => 'required|numeric',
            'id_estatus' => 'required|numeric',
            'id_primicia' => 'required|numeric',
            /* 'solicitante' => 'required|string|max_length[100]', */
            /* 'id_dependencia' => 'required|numeric', */
            'padecimiento_actual' => 'permit_empty|string',
            'diagnosticos' => 'permit_empty|string',
            'peticion_concreta' => 'permit_empty|string',
            
        ];

        if (!$this->validate($rules)) {
            //  Regresa errores si falla
            return $this->response->setJSON([
                'error' => true,
                'messages' => $this->validator->getErrors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }
    
       /*  $validacion = $this->funciones->validateData($data, $response, $rules); */
        /* if (count($validacion)) return $validacion; */
        
        // sanitizar la data
        $data = $this->funciones->sanitizeArray($data);
        $currentDateTime = new \DateTime();
        $formattedDate = $currentDateTime->format('Y-m-d H:i:s');
       // $fecha_inicio = $currentDateTime::createFromFormat('d-m-Y', $data['fecha_inicio']);
        
        $estado = (!empty($data['id_estado'])) ? $data['id_estado'] : null;
        $id_servidor_publico = (!empty($data['id_servidor_publico'])) ? $data['id_servidor_publico'] : 401;
        $fecha_registro = (isset($data['id_solicitud']) && !empty( $data['id_solicitud'] ) ) ? $data['fecha_registro'] : date("Y-m-d H:i:s"); 
        $dataInsert = [           
            'id_estatus'                 => $data['id_estatus'],
            'id_primicia'                => $data['id_primicia'],
            'fecha_hora_solicitud'       => $data['fecha_hora_solicitud'],
            'nombre_paciente'            => $data['nombre_paciente'],
            'primer_apellido_paciente'   => $data['primer_apellido_paciente'],
            'segundo_apellido_paciente'  => $data['segundo_apellido_paciente'],
            'fecha_nacimiento_paciente'  => $data['fecha_nacimiento_paciente'],
            'antecedentes_patologicos'   => $data['antecedentes_patologicos'],
            'antecedentes_quirurgicos'   => $data['antecedentes_quirurgicos'],
            'antecedentes_gyo'           => $data['antecedentes_gyo'],
            'id_pais'                    => $data['id_pais'],
            'id_estado'                  => $estado,
            'id_municipio'               => $data['id_municipio'] ?? null,
            'domicilio_paciente'         => $data['domicilio_paciente'],
            'otro_estado'                => $data['otro_estado'] ?? null,
            'id_afiliacion'              => $data['id_afiliacion'],
            'folio_afiliacion'           => $data['folio_afiliacion'],
            'diagnosticos'               => $data['diagnosticos'],
            'peticion_concreta'          => $data['peticion_concreta'],
            'solicitante'                => $data['solicitante'],
            'id_dependencia'             => $data['id_dependencia'],
            'id_servidor_publico'        => $id_servidor_publico,
            'contacto_telefono'          => $data['contacto_telefono'],
            'contacto_correo'            => $data['contacto_correo'],
            'padecimiento_actual'        => $data['padecimiento_actual'],
            'observaciones'              => $data['observaciones'],
            'usuario_registro'           => (isset($data['id_solicitud']) && !empty( $data['id_solicitud'] ) ) ? $data['usuario_registro'] : $session->get('id_usuario'),
            'fecha_registro'             => $fecha_registro,
            'usuario_actualiza'          => (isset($data['id_solicitud']) && !empty( $data['id_solicitud'] ) ) ? $session->get('id_usuario'): '' ,
            /* 'fecha_actualiza'            => $data['fecha_actualiza'], */
        ];
       
        $dataBitacora = ['id_user' =>  1, 'script' => 'Forms.php/guardarFolio'];
        
        $dataConfig = [
            'dataBase' =>'sigemed',
            'tabla' => 'sgmd_solicitudes',
            "editar"=>(isset($data['id_solicitud']) && !empty( $data['id_solicitud'] ) ) ? true : false ,
            'idEditar'=>(isset($data['id_solicitud']) && !empty( $data['id_solicitud'] ) ) ? ['id_solicitud' => $data['id_solicitud']] : '',
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
    public function eliminarSolicitud()
    {
        $response = new \stdClass();
        $Mglobal = new Mglobal();
        $response->error = true;
        $data = $this->request->getPost();
        
        $id_solicitud = $this->funciones->decrypt($data['id_solicitud']);
        /* var_dump($id_solicitud->idSolicitud);
        die(); */
        if (!isset($data['id_solicitud']) || empty($data['id_solicitud'])){
            $response->respuesta = "No se ha proporcionado un identificador válido";
            return $this->respond($response);
        }
        

        $dataConfig = [
            "tabla"=>"sgmd_solicitudes",
            "editar"=>true,
            "idEditar"=>['id_solicitud'=> $id_solicitud->idSolicitud]
        ];
       
        $response = $Mglobal->saveTabla(["visible"=>0],$dataConfig,["script"=>"Forms.deleteSolicitud"]);
        if (isset($response->query)){
            unset($response->query);
        }
        return $this->respond($response);
    }
    public function getSolicitud()
    {
        $id = $this->request->getPost('id_solicitud');

        if ($id) {
            $solicitud = $this->globals->getTabla([
                "tabla" => "sgmd_solicitudes",
                "select" => "*",
                "where" => ["id_solicitud" => $id]
            ]);

            if (!empty($solicitud->data)) {
                return $this->response->setJSON($solicitud->data[0]);
            }
        }
        return $this->response->setJSON([]);
    }
}
