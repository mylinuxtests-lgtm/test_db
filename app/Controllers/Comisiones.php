<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Funciones;
use App\Models\Mglobal;
use App\Models\Comision_model;
use stdClass;
use CodeIgniter\API\ResponseTrait;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Comisiones extends BaseController
{
    use ResponseTrait;
    private $globals;
    private $funciones;

    private $defaultData = array(
        'title' => 'Sistema de Administración de Oficios',
        'layout' => 'plantilla/lytDefault',
        'contentView' => 'vUndefined',
        'stylecss' => '',
    );

    public function __construct()
    {
        // Fechas php en español
        setlocale(LC_TIME, 'es_ES.utf8', 'es_MX.UTF-8', 'es_MX', 'esp_esp', 'Spanish');
        date_default_timezone_set('America/Mexico_City');
        $session = \Config\Services::session();
        $this->globals = new Mglobal();
        $this->funciones = new Funciones();
    }

    private function _renderView($data = array())
    {
        $data = array_merge($this->defaultData, $data);
        echo view($data['layout'], $data);
    }

    public function index()
    {
        $session = \Config\Services::session();
        requireLogin();

        $data = array();
        $data['scripts'] = array('principal');
        $data['layout'] = 'plantilla/lytDefault.php';
        $data['contentView'] = 'secciones/vBienvenida';
        $this->_renderView($data);
    }

    public function agregar()
    {
        $session = \Config\Services::session();
        requireLogin();

        $comisionModel = new Comision_model();

        $data = [];
        $data['scripts'] = array('comisiones/agregar');
        $data['layout'] = 'plantilla/lytDefault.php';
        $data['contentView'] = 'secciones/vAgregar';
        $data['title'] = 'Agregar Comisión';
        $data['municipios'] = $comisionModel->getMunicipios();

        $this->_renderView($data);
    }

public function guardar()
{
    try {
        $comisionModel = new Comision_model();
        $session = \Config\Services::session();

        // Recoger todos los datos del formulario
        $data = [
            'folio' => $this->request->getPost('folio'),
            'id_empleado_fk' => $this->request->getPost('id_empleado_fk'),
            'id_estructura_departamento_fk' => $this->request->getPost('id_estructura_departamento_fk'),
            'id_municipio' => $this->request->getPost('id_municipio'),
            'asunto_corto' => $this->request->getPost('asunto_corto'),
            'fecha_inicio' => $this->request->getPost('fecha_inicio'),
            'fecha_fin' => $this->request->getPost('fecha_fin'),
            'asunto' => $this->request->getPost('asunto'),
            'medio_transporte' => $this->request->getPost('medio_transporte'),
            'anticipo_devengo' => $this->request->getPost('anticipo_devengo'),
            'aplica_alimentos' => $this->request->getPost('aplica_alimentos') ? 1 : 0,
            'tarifa' => $this->request->getPost('tarifa') ?? 0,
            'dias' => $this->request->getPost('dias') ?? 0,
            'cuota' => $this->request->getPost('cuota') ?? 0,
            'comision_fuera_estado' => $this->request->getPost('comision_fuera_estado') ? 1 : 0,
            'partida_3710' => $this->request->getPost('partida_3710') ?? 0,
            'partida_3720' => $this->request->getPost('partida_3720') ?? 0,
            'partida_total' => $this->request->getPost('partida_total') ?? 0,
            'id_usuario_registro' => $session->get('id_usuario') ?? 1,
            'activo' => 1,
            'visible' => 1
        ];

        // Validar datos requeridos
        if (empty($data['id_empleado_fk']) || empty($data['fecha_inicio']) || empty($data['fecha_fin']) || empty($data['asunto'])) {
            return redirect()->back()->with('error', 'Faltan campos requeridos');
        }

        // Validar fechas
        if (strtotime($data['fecha_fin']) < strtotime($data['fecha_inicio'])) {
            return redirect()->back()->with('error', 'La fecha de fin no puede ser anterior a la fecha de inicio');
        }

        // Insertar en la base de datos
        if ($comisionModel->insert($data)) {
            $id_comision = $comisionModel->getInsertID();
            
            // Generar y descargar CSV inmediatamente
            return $this->exportar_csv($id_comision);
            
        } else {
            return redirect()->back()->with('error', 'No se pudo guardar la comisión');
        }
    } catch (\Exception $e) {
        log_message('error', 'Error en Comisiones::guardar: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error al guardar la comisión: ' . $e->getMessage());
    }
}

    public function getComisiones()
    {
        try {
            $model = new Comision_model();
            $search = $this->request->getPost('search') ?? '';
            $limit = $this->request->getPost('limit') ?? 10;
            $offset = $this->request->getPost('offset') ?? 0;
            $sort = $this->request->getPost('sort') ?? 'id_comision';
            $order = $this->request->getPost('order') ?? 'asc';

            $result = $model->get_comisiones($limit, $offset, $search, $sort, $order);

            return $this->respond([
                'success' => true,
                'total' => $result['total'],
                'rows' => $result['rows']
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error en Comisiones::getComisiones: ' . $e->getMessage());
            return $this->respond([
                'success' => false,
                'message' => 'Error al obtener los datos',
                'total' => 0,
                'rows' => []
            ], 500);
        }
    }

public function exportar_csv($id_comision = null)
{
    try {
        $comisionModel = new Comision_model();

        if ($id_comision) {
            // Exportar comisión específica
            $comision = $comisionModel->find($id_comision);
            if (!$comision) {
                throw new \Exception('Comisión no encontrada');
            }
            $data = [$comision];
        } else {
            // Exportar todas las comisiones activas
            $data = $comisionModel->where('activo', 1)->findAll();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $headers = [
            'ID',
            'Folio',
            'ID Empleado',
            'ID Departamento',
            'ID Municipio',
            'Asunto Corto',
            'Fecha Inicio',
            'Fecha Fin',
            'Asunto',
            'Medio Transporte',
            'Anticipo/Devengo',
            'Aplica Alimentos',
            'Comisión Fuera Estado',
            'Tarifa',
            'Días',
            'Cuota',
            'Partida 3710',
            'Partida 3720',
            'Partida Total'
        ];
        $sheet->fromArray([$headers], NULL, 'A1');

        // Datos
        $rowData = [];
        foreach ($data as $comision) {
            $rowData[] = [
                $comision['id_comision'],
                $comision['folio'],
                $comision['id_empleado_fk'],
                $comision['id_estructura_departamento_fk'],
                $comision['id_municipio'],
                $comision['asunto_corto'],
                $comision['fecha_inicio'],
                $comision['fecha_fin'],
                $comision['asunto'],
                $comision['medio_transporte'],
                $comision['anticipo_devengo'],
                $comision['aplica_alimentos'] ? 'Sí' : 'No',
                $comision['comision_fuera_estado'] ? 'Sí' : 'No',
                $comision['tarifa'],
                $comision['dias'],
                $comision['cuota'],
                $comision['partida_3710'],
                $comision['partida_3720'],
                $comision['partida_total']
            ];
        }

        if (!empty($rowData)) {
            $sheet->fromArray($rowData, NULL, 'A2');
        }

        // Centrar encabezados
        $sheet->getStyle('A1:S1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Descargar CSV
        $filename = $id_comision ? "comision_{$id_comision}_" . date('Ymd_His') . '.csv' : "comisiones_completas_" . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Csv($spreadsheet);
        $writer->save('php://output');
        exit;

    } catch (\Exception $e) {
        log_message('error', 'Error en Comisiones::exportar_csv: ' . $e->getMessage());
        // Si hay error en la exportación, regresar al formulario con mensaje de error
        return redirect()->to(base_url('Comisiones/agregar'))->with('error', 'Comisión guardada pero error al exportar CSV: ' . $e->getMessage());
    }
}

    /**
     * Generar CSV después de guardar una comisión
     */
    private function generarCSV($data, $id_comision)
    {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Encabezados
            $headers = [
                'Folio',
                'Asunto Corto',
                'Fecha Inicio',
                'Fecha Fin',
                'Asunto',
                'Medio Transporte',
                'Anticipo/Devengo',
                'Aplica Alimentos',
                'Comisión Fuera Estado',
                'Tarifa',
                'Días',
                'Cuota',
                'Partida 3710',
                'Partida 3720',
                'Partida Total'
            ];
            $sheet->fromArray([$headers], NULL, 'A1');

            // Datos
            $sheet->fromArray([
                [
                    $data['folio'],
                    $data['asunto_corto'],
                    $data['fecha_inicio'],
                    $data['fecha_fin'],
                    $data['asunto'],
                    $data['medio_transporte'],
                    $data['anticipo_devengo'],
                    $data['aplica_alimentos'],
                    $data['comision_fuera_estado'],
                    $data['tarifa'],
                    $data['dias'],
                    $data['cuota'],
                    $data['partida_3710'],
                    $data['partida_3720'],
                    $data['partida_total']
                ]
            ], NULL, 'A2');

            // Centrar encabezados
            $sheet->getStyle('A1:O1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Guardar CSV en el sistema de archivos
            $writer = new Csv($spreadsheet);
            $filename = WRITEPATH . 'uploads/comisiones/oficio_comision_' . $id_comision . '_' . date('Ymd_His') . '.csv';

            // Asegurar que el directorio existe
            $dir = dirname($filename);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $writer->save($filename);

            return $filename;
        } catch (\Exception $e) {
            log_message('error', 'Error al generar CSV: ' . $e->getMessage());
            return null;
        }
    }
}