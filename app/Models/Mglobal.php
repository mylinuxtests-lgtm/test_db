<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\Bitacoracontrol;

class Mglobal extends Model
{
    /* protected $table            = 'mglobals';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = []; */

    public $errorConexion = false;
    private $session;

    //protected $table = 'zeus_usuarios';

    function __construct() {
        parent::__construct();        
        $this->db->query("SET lc_time_names = 'es_MX'");
        $this->session = \Config\Services::session();
    }

    /**
     *   getTabla
     *   Busqueda de información por tabla con las propiedades basicas de una query
     *  @method post
     *  @param array:data[
     *       dataBase    string
     *       query       string  (opcional)
     *       tabla       string  (requerido si no existe el arámetro de query)
     *       select      array   (opcional)
     *       join        array[array] (opcional)
     *       where       array   (opcional)
     *       whereIn     array   (opcional)
     *       like        array   (opcional)
     *       orlike      array   (opcional)
     *       order       string  (opcional)
     *       groupBy     array   (opcional)
     *       limit       int     (opcional)
     *   ]
     *  @return object:queryResult
     */
    public function getTabla($data)
    {
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error|Parámetros de entrada';

        /* Busqueda por query directa */
        if (isset($data['query'])){
            try {
                $query = $this->db->query($data['query'])->getResult();
            } catch (\Throwable $th) {
                $response->error = true;
                $response->respuesta = 'Fallo en la consulta a base de datos';
                $response->errorDB = $th;
                return $response;        
            }

            $response->query = $this->db->getLastQuery()->getQuery();
            $response->data = $query;
            $response->error = false;
            $response->respuesta = empty($query) ? 'No se encontraron resultados que coincidan con la busqueda':'Consulta exitosa';
            return $response;
        }

        if (!isset($data['tabla'])) return $response;
        $builder = $this->db->table($data['tabla']);

        if (isset($data['select']))
        $builder->select($data['select']);          //ejemplo: ['field1','field2']

        if(isset($data['join']))                    //ejemplo [['table','conection and rules','type']]
        {
            foreach ($data['join'] as $key ) {
                $builder->join($key[0],$key[1],isset($key[2])?$key[2]:'right');
            }
        }
         
        if (isset($data['where']))
        $builder->where($data['where']);            //ejemplo: ['name' => $name, 'title' => $title, 'status' => $status];
        
        if (isset($data['whereIn'])){               //ejemplo: [[ 'name',aray() ]];
            foreach ($data['whereIn'] as $whereIn) {
                $builder->whereIn($whereIn[0],$whereIn[1]);
            }
        }
        if (isset($data['whereNotIn'])){               //ejemplo: [[ 'name',aray() ]];
            foreach ($data['whereNotIn'] as $whereNotIn) {
                $builder->whereNotIn($whereNotIn[0],$whereNotIn[1]);
            }
        }
        
        if (isset($data['like']))
        $builder->like($data['like']);              //ejemplo: ['name' => $name, 'title' => $title, 'status' => $status];
        
        if (isset($data['orlike']))
        $builder->orLike($data['orlike']);          //ejemplo: ['name' => $name, 'title' => $title, 'status' => $status];

        if (isset($data['order']))
        $builder->orderBy($data['order']);          //ejemplo :'title DESC, name ASC'

        if (isset($data['groupBy']))                // ejemplo : ["title", "date"]
        $builder->groupBy($data['groupBy']);
        
        if (isset($data['limit'])) {                //ejemplo :10 o ['start' => (int), 'length' => (int)] */
            if (isset($data['limit']['length'])&&isset($data['limit']['start']))
                $builder->limit($data['limit']['length'], $data['limit']['start']);
            else
                $builder->limit($data['limit']);
        }
        
        $query = $builder->get()->getResult();
        $response->query = $this->db->getLastQuery()->getQuery();
        $response->data = $query;
        
        if (empty($query) || count($query) == 0)
        {   
            $response->error = false;
            $response->respuesta = 'No se encontraron resultados que coincidan con la busqueda';
            return $response;    
        }
        $response->error = false;
        $response->respuesta = 'Consulta exitosa';
        return $response;
    }

    /**
     *   saveTabla
     *   Función para insertar o editar información de los catálogos
     *   @param array data: información a registrar en la tabla del catálogo (No incluye id)
     *   @param array config: información de configuración para la query de edición o inserción
     *   config = [
     *       dataBase    string
     *       tabla       string
     *       editar      bool
     *       editar_id   array['idNombre'=>id]
     *   ]
     *   @return object result
     *   result->success
     *   result->idInsert
     *   result->message
     */
    public function saveTabla($data,$config,$bitacora)
    {
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error|Parámetros de entrada';
        $error = false;

        if (!isset($config['tabla']) || !isset($config['editar'])) 
        return $response;
        $config['editar'] = json_decode($config['editar']);
        if ($config['editar'] && (!isset($config['idEditar']) || is_null($config['idEditar']))) 
        return $response;

        $Bitacoracontrol = new Bitacoracontrol();

        //verificar registro si es edición
        if ($config['editar'])
        {
            $builder = $this->db->table($config['tabla']);
            $query = $builder->getWhere($config['idEditar'])->getResult();
            if ( !$query || count($query) == 0)
            {
                $response->respuesta = 'Error|No se encontró el registro de edición';
                $response->query = $this->db->getLastQuery()->getQuery();
                return $response;
            }
        }
        
        $this->db->transBegin();
        
        $builder = $this->db->table($config['tabla']);
        if ($config['editar']){
            if(!$builder->update($data, $config['idEditar']))
            {
                $response->respuesta = 'Error|registro no editado';
                $error = true;
                log_message('critical',"DB Query: ".$this->db->getLastQuery()->getQuery());
                log_message('critical',"DB error: ".$this->db->error());
            }
            $response->idRegistro = $config['idEditar'];
            $llave = '';
            foreach ($config['idEditar'] as $key => $value) {
                $campo = $key;
                $llave = $value;
            }
            if(!$response->bitacora=$Bitacoracontrol->RegistraUpdate($data, $bitacora['script'], $this->session->id_usuario, $config['tabla'], $llave))
            {
                $error = true;
                $response->respuesta='Error|Registro de actualizar bitacora';
            }
        }
        else {
            if (!$builder->insert($data))
            {                
                $response->respuesta = 'Error|registro no insertado';
                $error = true;
            }
            $response->idRegistro = $this->db->insertID();
            if(!$response->bitacora=$Bitacoracontrol->RegistraInsert($data, $bitacora['script'], $this->session->id_usuario, $config['tabla'], $response->idRegistro))
            {
                $error = true;
                $response->respuesta='Error|Registro de insertar bitacora';
            }
        }

        if ($this->db->transStatus() === FALSE || $error)
        {
            $this->db->transRollback();
        }
        else
        {
            $this->db->transCommit();
            $response->error = false;
            $response->respuesta = 'Registro guardado correctamente';
            $response->query = $this->db->getLastQuery()->getQuery();
        }
        return $response;
    }

    /**
     *    insertBatch
     *    Función para insertar un arreglo de información en una tabla
     * @param array data: información a registrar en la tabla del catálogo
     * @param array config: información de configuración para la query de edición o inserción
     *    config = [
     *        dataBase    string
     *        tabla       string
     *    ]
     *   @return object result
     *    result->success
     *    result->idInsert
     *    result->message
     */
    public function dataInsertBatch($data,$config)
    {
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error|Parámetros de entrada';

        if (!isset($config['tabla'])) 
        return $response;

        $this->db->transBegin();
        
        $builder = $this->db->table($config['tabla']);
        $builder->insertBatch($data);

        if ($this->db->transStatus() === FALSE)
        {
            log_message('critical','Error: '.json_encode($this->db->error()));
            log_message('critical','Query: '.json_encode($this->db->getLastQuery()->getQuery()));
            $this->db->transRollback();
            $response->errorDB= $this->db->error();
        }
        else
        {
            $this->db->transCommit();
            $response->error = false;
            $response->respuesta = 'Registro guardado correctamente';
            $response->query = $this->db->getLastQuery()->getQuery();
        }
        return $response;
    }

    /**
     * funcion que realiza las acciones necesarias para actualizar una tabla de muchos a muchos:
     * Agerga registros nuevos
     * Realiza borrado lógico de la tabla
     * Respeta los registros que sigan vigentes de una tabla
     * 
     * @param array:dataInsert  arreglo doble de información a insertar en la tabla
     * @param array:dataConfig  arreglo de configuración de tabla para actualizar
     * @param array:dataBitacora  arreglo de información para la bitacora
     * 
     * dataInsert = [[].[]]  Nota: es importante agregar el valor adecuado de borrado lógico inactivo
     * 
     *  dataConfig = [
     *   dataBase     (string)
     *   tabla        (string)
     *   paramIdTabla (string) nombre de la llave primaria de la tabla
     *   paramDelete  (array) ["NombreBorradoLogico"=>value]
     *   whereDelete  (array) ["nombreVariable"=>ValueDelete]
     *   llave        (array) ["llave_1","llave_2"]
     *  ]
     *
     *  dataBitacora = [
     *   script      (string)
     *  ]
     */
    public function updateInsertTabla($dataInsert, $dataConfig, $dataBitacora)
    {
        log_message("critical","------------------------------------------");
        log_message("critical","Proceso de updateInserTabla");
        log_message("critical","dataInsert: ".json_encode($dataInsert));
        log_message("critical","dataConfig: ".json_encode($dataConfig));
        log_message("critical","dataBitacora: ".json_encode($dataBitacora));

        $Bitacoracontrol = new Bitacoracontrol();
        $response = new \stdClass();
        $response->error = true;
        $response->respuesta = 'Error|Parámetros de entrada';
        $errorDB = false;
        $bitacora = [];

        
        if (!isset($dataInsert) || !isset($dataConfig) || !isset($dataBitacora)) 
            return $response;
        if (empty($dataConfig) || empty($dataBitacora)) 
            return $response;
        if (!isset($dataConfig['tabla']) || !isset($dataConfig['paramDelete']) || !isset($dataConfig['llave'])) 
            return $response;
        if (!isset($dataBitacora['script']) || is_null($dataBitacora['script'])) 
            return $response;

        // caso en el que hay que eliminar todos los registros de la tabla de muchos a muchos
        if (empty($dataInsert)){
            //step 1: delete all
            if (!$this->db->table($dataConfig['tabla'])->where($dataConfig['whereDelete'])->update($dataConfig['paramDelete'])){
                $response->respuesta = $this->db->error();
                $response->query = $this->db->getLastQuery()->getQuery();
                log_message("critical", "dataInsert (Vacio): Proceso delete fallido updateInsertTabla");
                log_message("critical", "DBQuery: ".$response->query);
                log_message("critical", "DBError: ".$response->respuesta);
                return $response;
            }
            $response->error = false;
            $response->respuesta = 'Registro guardado correctamente';
            $response->query = $this->db->getLastQuery()->getQuery();
            return $response;
        }

        // Validar las llaves denttro del dataInsert
        foreach ($dataInsert as $item) {
            foreach ($dataConfig['llave'] as $key => $value) {
                if (!isset($item[$value]) || is_null($item[$value]) || empty($item[$value])){
                    $response->respuesta = "La llave primaria {$value} no se encuentra dentro del arreglo de inserción";
                    return $response;
                }
            }
        }

        $this->db->transBegin();
        
        $builder = $this->db->table($dataConfig['tabla']);

        //step 1: delete all
        if (!$this->db->table($dataConfig['tabla'])->where($dataConfig['whereDelete'])->update($dataConfig['paramDelete'])){
            $response->respuesta = $this->db->error();
            $response->query = $this->db->getLastQuery()->getQuery();
            $this->db->transRollback();
            log_message("critical", "Proceso delete fallido updateInsertTabla");
            log_message("critical", "DBQuery: ".$response->query);
            log_message("critical", "DBError: ".$response->respuesta);
            return $response;
        }

        
        //Step 2: activar en insertar los registros vigentes
        $response->idRegistroActivo = [];
        foreach ($dataInsert as $item) {
            $where = [];
            foreach ($dataConfig['llave'] as $key => $value) {
                $where[$value] = $item[$value];
            }

            $query = $this->db->table($dataConfig['tabla'])->where($where)->get()->getResultArray();
            $edit = (empty($query))? false:true;
            if ($edit){
                if (!$this->db->table($dataConfig['tabla'])->where([$dataConfig['paramIdTabla'] => $query[0][$dataConfig['paramIdTabla']]])->update($item)){
                    $response->respuesta = $this->db->error();
                    $response->query = $this->db->getLastQuery()->getQuery();
                    $this->db->transRollback();
                    log_message("critical", "Proceso update fallido updateInsertTabla");
                    log_message("critical", "DBQuery: ".$response->query);
                    log_message("critical", "DBError: ".$response->respuesta);
                    return $response;
                }
                $bitacora[] = [
                    "data"=>$item,
                    "tabla" => $dataConfig["tabla"],
                    "id" => $query[0][$dataConfig['paramIdTabla']],
                ];
                $response->idRegistroActivo[] = $query[0][$dataConfig['paramIdTabla']];
            }
            else{
                if (!$this->db->table($dataConfig['tabla'])->insert($item)){
                    $response->respuesta = $this->db->error();
                    $response->query = $this->db->getLastQuery()->getQuery();
                    $this->db->transRollback();
                    log_message("critical", "Proceso insert fallido updateInsertTabla");
                    log_message("critical", "DBQuery: ".$response->query);
                    log_message("critical", "DBError: ".$response->respuesta);
                    return $response;
                }
                $bitacora[] = [
                    "data"=>$item,
                    "tabla" => $dataConfig["tabla"],
                    "id" => $this->db->insertID(),
                ];
                $response->idRegistroActivo[] = $this->db->insertID();
            }
        }

         //Registro de bitacora
         if(!empty($bitacora)){
            log_message('critical','-- Registro de bitacora');            
            foreach ($bitacora as $item) {
                log_message('critical','Bitacora: '.json_encode($item));
                if(!$Bitacoracontrol->RegistraInsert($item['data'], $dataBitacora['script'], $this->session->id_usuario, $dataConfig['tabla'], $item['id'])){
                    $errorDB = true;
                    log_message('critical',"Error|{$dataBitacora['script']}|".json_encode($item));
                    $this->db->transRollback();
                    $response->respuesta = "Error|Registro de bitacora";
                    return $response;
                }
            }
        }
        

        if ($this->db->transStatus() === FALSE || $errorDB)
        {
            $response->respuesta = $this->db->error();
            $response->query = $this->db->getLastQuery()->getQuery();
            $this->db->transRollback();
            log_message("critical", "Proceso insert fallido updateInsertTabla");
            log_message("critical", "DBQuery: ".$response->query);
            log_message("critical", "DBError: ".$response->respuesta);
        }
        else
        {
            $this->db->transCommit();
            $response->error = false;
            $response->respuesta = 'Registro guardado correctamente';
            $response->query = $this->db->getLastQuery()->getQuery();
        }
        return $response;
    }

     /**
     * Apartado de funcionamiento en local de globals para api
     */
    /**
     * Funcion que realiza el guardado, actualización y manejop de errores en el manejo de tablas
     * 
     * @param object:db                     La instancia de base de datos que estas manejando. [$this->db]
     * @param object:response               Objeto stdClass para manejo de respuesta
     * @param array:dataInsert
     * @param array:dataBitacora
     * @param string:tabla
     * @param array:bitacora
     * @param string:variableReferencia     (opcional) Nombre de la variable que manejará el id insertado, RECOMENDABLE PARA TABLAS PRINCIPALES
     * @param array:editar                  (opcional) Llave primaria para editar ["idCampoTablaName",idTabla]
     * @param array:adicionales             Variable utilizada para el caso en que se requiera cambiar parte de la estructura de la función
     */
    public function localSaveTabla(&$db, &$response, $dataInsert, $dataBitacora, $tabla, &$bitacora, $variableReferencia = false, $editar = false, $adicionales=false)
    {
        try {
            if ($editar){
                (isset($adicionales["usuario_ultima_actualizacion"]))? $dataInsert['usuario_ultima_actualizacion'] = $dataBitacora['id_user'] : $adicionales["usuario_actualiza"] = $dataBitacora['id_user'];
                $db->table($tabla)->update($dataInsert,[$editar[0]=>$editar[1]]);
            }else {
                $dataInsert['usuario_registro'] = $dataBitacora['id_user'];
                $db->table($tabla)->insert($dataInsert);
            }

            $error = $db->error();
            if($error["code"] > 0){
                $db->transRollback();
                $response->errorDB = $error['message'];
                $response->lastQuery = $db->getLastQuery()->getQuery();
                log_message('critical','lastQuery: '.$db->getLastQuery()->getQuery());
                log_message('critical','errorDB: '.json_encode($db->error()));
                return false;
            }    

            $auxID = ($editar)? $editar[1] : $db->insertID();
            if ($variableReferencia)  
            $response->$variableReferencia = $auxID;

        } catch (\Throwable $th) {
            log_message('critical','lastQuery: '.$db->getLastQuery()->getQuery());
            log_message('critical','errorDB: '.json_encode($db->error()));
            log_message('critical','errorTH: '.json_encode($th));
            $db->transRollback();
            $response->respuesta = json_encode($db->error());
            $response->errorTh = json_encode($th);
            return false;
        }

        $bitacora[] = [
            'data'    => $dataInsert,
            'id'      => $auxID,
            'tabla'   => $tabla
        ];
        return true;
    }

    /**
     * Funcion que realiza las acciones necesarias para actualizar una tabla de muchos a muchos:
     * Agrega registros nuevos
     * Realiza borrado lógico de la tabla
     * Respeta los registros que sigan vigentes de una tabla
     * 
     * @param array:dataInsert          arreglo doble de información a insertar en la tabla
     * @param array:dataConfig          arreglo de configuración de tabla para actualizar
     * @param array:dataBitacora        arreglo de información para la bitacora
     * @param string:nombreElemento     nombre de refencia para retornar los elementos activos
     * @param object:db                 instancia de base de datos
     * @param object:response           variable de respuesta
     * @param array:bitacora            Arreglo de cambios de base de datos
     * 
     * dataInsert = [[].[]]  Nota: es importante agregar el valor adecuado de borrado lógico inactivo
     * 
     *  dataConfig = [
     *   tabla        (string) nombre de la tabla a editar
     *   paramIdTabla (string) nombre de la llave primaria de la tabla
     *   paramDelete  (array) ["NombreBorradoLogico"=>value]
     *   whereDelete  (array) ["nombreVariable"=>ValueDelete]
     *   llave        (array) ["llave_1","llave_2"]
     *  ]
     */
    public function localUpdateInsertTabla($dataInsert, $dataConfig, $dataBitacora, $nombreElemento = false, &$db = false, &$response = false, &$bitacora = false )
    {
        /** 
        $dataConfig = [
            "tabla"         => "cima_auxiliares.lab_solicitud_estudios",
            "paramIdTabla"  => "id_lab_solicitud_estudios",
            "paramDelete"   => ["visible"=>0],
            "whereDelete"   => ["id_lab_solicitud"=>$response->idLaboratorio, "visible"=>1],
            "llave"         => ["id_lab_solicitud","id_lab_estudio"],
        ];
        */
        //step 1: delete all
        $elementos = $db->table($dataConfig['tabla'])->where($dataConfig['whereDelete'])->get()->getResult();
        
        if ($elementos){

            try {
                $db->table($dataConfig['tabla'])->where($dataConfig['whereDelete'])->update($dataConfig['paramDelete']);
        
                $error = $db->error();
                if($error["code"] > 0){
                    $db->transRollback();
                    $response->errorDB = $error['message'];
                    $response->lastQuery = $db->getLastQuery()->getQuery();
                    log_message('critical','lastQuery: '.$db->getLastQuery()->getQuery());
                    log_message('critical','errorDB: '.json_encode($db->error()));
                    return false;
                }

                foreach ($elementos as $item) {
                    $bitacora[] = [
                        'data'    => $dataConfig['paramDelete'],
                        'id'      => $item->{$dataConfig['paramIdTabla']},
                        'tabla'   => $dataConfig['tabla']
                    ];
                }
            } catch (\Throwable $th) {
                log_message('critical','lastQuery: '.$db->getLastQuery()->getQuery());
                log_message('critical','errorDB: '.json_encode($db->error()));
                log_message('critical','errorTH1: '.json_encode($th));
                $db->transRollback();
                $response->respuesta = json_encode($db->error());
                $response->errorTh1 = json_encode($th);
                return false;
            }
        }

        if (!$dataInsert) return true;
        
        try {
            // Validar las llaves denttro del dataInsert
            foreach ($dataInsert as $item) {
                foreach ($dataConfig['llave'] as $itemLlave) {
                    if (!isset($item[$itemLlave]) || is_null($item[$itemLlave]) || empty($item[$itemLlave])){
                        $response->respuesta = "La llave primaria {$itemLlave} no se encuentra dentro del arreglo de inserción";
                        return false;
                    }
                }
            }
            
            //Step 2: activar en insertar los registros vigentes
            if($nombreElemento) $response->{$nombreElemento} = [];
            foreach ($dataInsert as $item) {
                $where = [];
                foreach ($dataConfig['llave'] as $itemLlave) {
                    $where[$itemLlave] = $item[$itemLlave];
                }
                $query = $db->table($dataConfig['tabla'])->where($where)->get()->getRow();
                $edit = ($query)? true:false;
                if ($edit){
                    $dataInsert['usuario_actualiza'] = $dataBitacora['id_user'];
                    $db->table($dataConfig['tabla'])->where([$dataConfig['paramIdTabla'] => $query->{$dataConfig['paramIdTabla']}])->update($item);
                }
                else{
                    $dataInsert['usuario_registro'] = $dataBitacora['id_user'];
                    $db->table($dataConfig['tabla'])->insert($item);
                }

                $error = $db->error();
                if($error["code"] > 0){
                    $db->transRollback();
                    $response->errorDB = $error['message'];
                    $response->lastQuery = $db->getLastQuery()->getQuery();
                    log_message('critical','lastQuery: '.$db->getLastQuery()->getQuery());
                    log_message('critical','errorDB: '.json_encode($db->error()));
                    return false;
                }    

                $auxID = ($edit)? $query->{$dataConfig['paramIdTabla']} : $db->insertID();
                if($nombreElemento) $response->{$nombreElemento}[] = $auxID;
                $bitacora[] = [
                    'data'    => $item,
                    'id'      => $auxID,
                    'tabla'   => $dataConfig['tabla']
                ];
            }
        } catch (\Throwable $th) {
            log_message('critical','lastQuery: '.$db->getLastQuery()->getQuery());
            log_message('critical','errorDB: '.json_encode($db->error()));
            log_message('critical','errorTH2: '.json_encode($th));
            $db->transRollback();
            $response->respuesta = json_encode($db->error());
            $response->errorTh2 = json_encode($th);
            return false;
        }
        return true;
    }

    /**
     * función que realiza el guardado de la bitacora de una transacción completa
     * Se reomiuenda usar esta funcion hasta el final de la transacción
     * 
     * @param bitacora:array        Arreglo con información del arreglo de información
     * @param dataBitacora:array    Información de dataBitacora
     * @param response:object       variable de respuesta
     */
    public function localSaveBitacora(&$bitacora, $dataBitacora, &$response)
    {
        $Bitacoracontrol = new Bitacoracontrol();
        $errorDB = false;
        if(!empty($bitacora)){
            log_message('critical','-- Registro de bitacora');            
            foreach ($bitacora as $item) {
                log_message('critical','Bitacora: '.json_encode($item));
                if (!$bitacoraInsert = $Bitacoracontrol->RegistraInsert($item['data'], $dataBitacora['script'], $dataBitacora['id_user'], $item['tabla'], $item['id'])){
                    $errorDB = true;
                    log_message('critical','Error|Registro de bitacora saveConfiguracionHorario|'.json_encode($item));
                    $response->respuesta = json_encode($bitacoraInsert);
                    return $errorDB;
                }
            }
        }

        return $errorDB;
    }
}
