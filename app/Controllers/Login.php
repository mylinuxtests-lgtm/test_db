<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Mglobal;
/* use CodeIgniter\HTTP\ResponseInterface; */
use stdClass;
use CodeIgniter\API\ResponseTrait;

class Login extends BaseController
{
    use ResponseTrait;
    private $defaultData = array(
        'title' => 'Sistema de Administración de Oficios',
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
    }
    private function _renderView($data = array()) {   
       
        $data = array_merge($this->defaultData, $data);
        echo view($data['layout'], $data);               
    }

    public function index()
    {        
        $session = \Config\Services::session();
        $data = array();
        /* if ($session->get('logueado')===1) {
            header('Location:' . base_url() . 'Inicio');
            die();
        } */
        if (isUserLoggedIn()) {
            return redirect()->to(base_url('index.php/Inicio'));
        }
        $data['scripts'] = array('principal');
        $data['layout'] = 'plantilla/lytLogin';
        $data['contentView'] = 'secciones/vLogin';                
        $this->_renderView($data);   
    }
    /* public function validar_usuario(){
        $session = \Config\Services::session();
        $Mglobal = new Mglobal;
        
        $usuario = $this->request->getPost('usuario');
        $contrasenia = $this->request->getPost('contrasenia');

        $data = array();
        $dataDB = array('tabla' => 'sgmd_usuarios', 'where' => 'usuario ="'.$usuario.'" and contrasenia = "'.md5($contrasenia).'" and visible = 1');  
        
        if($usuario && $contrasenia){
            $response = $Mglobal->getTabla($dataDB);
            if(sizeof($response->data) >= 1) {
                $dataInsert= [
                    'usuario' => $usuario,
                    'contrasena' => '',
                    'logokerror' => '1',
                    'hora' => date('Y-m-d H:i:s'),
                    'ip' => $this->ServerVar("REMOTE_ADDR"),
                    'browser' => $this->get_browser_name($this->ServerVar("HTTP_USER_AGENT"))
                ];

                $dataConfig = [
                    "tabla" => "bitacora_accesos",
                    "editar"=> false,
                ];
                $bitacora = $Mglobal->saveTabla($dataInsert,$dataConfig,['script'=>'Login.validarUsuario']);

                $session->set('logueado', 1);
                $session->set('id_usuario',$response->data[0]->id_usuario);
                $session->set('usuario',$response->data[0]->usuario);
                $session->set('id_perfil', $response->data[0]->id_perfil);
                die("correcto");
            }else{
                // Registrar login en bitacora de accesos
                $dataInsert= [
                    'usuario' => $usuario,
                    'contrasena' => $contrasenia,
                    'logokerror' => '0',
                    'hora' => date('Y-m-d H:i:s'),
                    'ip' => $this->ServerVar("REMOTE_ADDR"),
                    'browser' => $this->get_browser_name($this->ServerVar("HTTP_USER_AGENT"))
                ];

                $dataConfig = [
                    "tabla" => "bitacora_accesos",
                    "editar"=> false,
                ];
                $bitacora = $Mglobal->saveTabla($dataInsert,$dataConfig,['script'=>'Login.validarUsuario']);
                die("error");
            }            
        }        
        die("error");

    } */
    public function validar_usuario()
    {
        
        helper('url');
        $session = \Config\Services::session();
        $Mglobal = new Mglobal();
        // Sanitizar entrada
        $usuario = htmlspecialchars(trim($this->request->getPost('usuario')));
        $contrasenia = htmlspecialchars(trim($this->request->getPost('contrasenia')));
        // Validar campos vacíos
        if (empty($usuario) || empty($contrasenia)) {
            $this->registrarBitacora($usuario, $contrasenia, false);
            return $this->response->setJSON([
                'error' => true,
                'message' => 'Campos vacíos o inválidos',
            ]);
        }
        // Consulta de usuario
        $dataDB = [
            'tabla' => 'usuarios',
            'where' => ['usuario'=>$usuario, 'contrasenia'=>md5($contrasenia), 'visible'=>1,'activo'=>1]
        ];
        $response = $Mglobal->getTabla($dataDB);
        if (!empty($response->data) && sizeof($response->data) >= 1) {
            $this->registrarBitacora($usuario, '', true);
    
            $usuarioInfo = $response->data[0];
            $session->set([
                'logueado' => 1,
                'id_usuario' => $usuarioInfo->id_usuario,
                'usuario' => $usuarioInfo->usuario,
                'id_perfil' => $usuarioInfo->id_perfil_fk,
                'id_empleado' => $usuarioInfo->id_empleado_fk,
            ]);
    
            return $this->response->setJSON([
                'error' => false,
                'message' => 'Inicio de sesión exitoso'
            ]);
        }
        // Usuario incorrecto
        $this->registrarBitacora($usuario, $contrasenia, false);
        return $this->response->setJSON([
            'error' => true,
            'message' => 'Usuario o contraseña incorrectos'
        ]);
    }
    private function registrarBitacora(string $usuario, string $contrasenia, bool $exito)
    {
        $Mglobal = new \App\Models\Mglobal();

        $dataInsert = [
            'usuario'     => $usuario,
            'contrasena'  => $exito ? '' : $contrasenia,
            'logokerror'  => $exito ? '1' : '0',
            'hora'        => date('Y-m-d H:i:s'),
            'ip'          => $this->ServerVar("REMOTE_ADDR"),
            'browser'     => $this->get_browser_name($this->ServerVar("HTTP_USER_AGENT")),
        ];

        $dataConfig = [
            "tabla"  => "bitacora_accesos",
            "editar" => false,
        ];

        $Mglobal->saveTabla($dataInsert, $dataConfig, ['script' => 'Login.validarUsuario']);
    }


    
    public function cerrar() {
        $session = \Config\Services::session();  
        /* $session->destroy();
        $session->set('logueado', 0);  */   
        $session->destroy();
        return redirect()->to(base_url('/'));    
       // header('Location:'.base_url());
        //header('Location:' . base_url());
        //die();
    }
    /**
     * Obtiene el nombre del navegador que esta usando el usuario
     * @param type $user_agent La variable del servidor $_SERVER['HTTP_USER_AGENT']
     * @return string El nombre del navegador
     */
    function get_browser_name($user_agent) {
        if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/'))
            return 'Opera';
        elseif (strpos($user_agent, 'Edge'))
            return 'Edge';
        elseif (strpos($user_agent, 'Chrome'))
            return 'Chrome';
        elseif (strpos($user_agent, 'Safari'))
            return 'Safari';
        elseif (strpos($user_agent, 'Firefox'))
            return 'Firefox';
        elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7'))
            return 'Internet Explorer';

        return $user_agent;
    }
    
    function ServerVar($Name) {
        $str = @$_SERVER[$Name];
        if (empty($str)) $str = @$_ENV[$Name];
        return $str;
    }
    
    function miDebug($msg) {
        $filename = ".debug.txt";
        if (!$handle = fopen($filename, 'a'))
                exit;
        if (is_writable($filename)) {
                $separador = "================================================================================";
                fwrite($handle, "" . $msg . "\n" . $separador . "\n\n");
        }
        fclose($handle);
    }
    
}
