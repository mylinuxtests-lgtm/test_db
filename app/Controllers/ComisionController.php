<?php

namespace App\Controllers;

use App\Models\ComisionModel;
use App\Models\EmpleadoModel;
use App\Models\EstructuraModel;
use App\Models\MunicipioModel;
use App\Models\UsuarioModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class ComisionController extends BaseController
{
    protected $comisionModel;
    protected $empleadoModel;
    protected $estructuraModel;
    protected $municipioModel;
    protected $usuarioModel;

    public function __construct()
    {
        $this->comisionModel = new ComisionModel();
        $this->empleadoModel = new EmpleadoModel();
        $this->estructuraModel = new EstructuraModel();
        $this->municipioModel = new MunicipioModel();
        $this->usuarioModel = new UsuarioModel();
    }

    // Vista para crear nueva comisión
    public function crear()
    {
        $data = [
            'empleados' => $this->empleadoModel->where('activo', 1)->findAll(),
            'estructuras' => $this->estructuraModel->where('activo', 1)->findAll(),
            'municipios' => $this->municipioModel->where('activo', 1)->findAll(),
            'ultimo_folio' => $this->comisionModel->getUltimoFolio(),
            'usuario_actual' => session()->get('usuario_nombre')
        ];

        return view('comision/crear', $data);
    }

    // Obtener datos del empleado por AJAX
    public function getEmpleadoData($id)
    {
        $empleado = $this->empleadoModel->getEmpleadoCompleto($id);
        return $this->response->setJSON($empleado);
    }

    // Obtener datos del municipio por AJAX
    public function getMunicipioData($id)
    {
        $municipio = $this->municipioModel->find($id);
        return $this->response->setJSON($municipio);
    }

    // Guardar nueva comisión
    public function guardar()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'id_empleado_fk' => 'required',
            'id_estructura_departamento_fk' => 'required',
            'id_municipio' => 'required',
            'fecha_inicio' => 'required',
            'fecha_fin' => 'required',
            'asunto' => 'required',
            'medio_transporte' => 'required',
            'anticipo_devengo' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Generar folio único
        $folio = $this->generarFolio();

        $data = [
            'id_estructura_departamento_fk' => $this->request->getPost('id_estructura_departamento_fk'),
            'id_empleado_fk' => $this->request->getPost('id_empleado_fk'),
            'folio' => $folio,
            'asunto_corto' => $this->request->getPost('asunto_corto'),
            'fecha_inicio' => $this->request->getPost('fecha_inicio'),
            'fecha_fin' => $this->request->getPost('fecha_fin'),
            'asunto' => $this->request->getPost('asunto'),
            'medio_transporte' => $this->request->getPost('medio_transporte'),
            'anticipo_devengo' => $this->request->getPost('anticipo_devengo'),
            'aplica_alimentos' => $this->request->getPost('aplica_alimentos') ? 1 : 0,
            'comision_fuera_estado' => $this->request->getPost('comision_fuera_estado') ? 1 : 0,
            'tarifa' => $this->request->getPost('tarifa'),
            'dias' => $this->request->getPost('dias'),
            'cuota' => $this->request->getPost('cuota'),
            'partida_3710' => $this->request->getPost('partida_3710'),
            'partida_3720' => $this->request->getPost('partida_3720'),
            'id_municipio' => $this->request->getPost('id_municipio'),
            'partida_total' => $this->request->getPost('partida_total'),
            'fecha_registro' => date('Y-m-d H:i:s'),
            'id_usuario_registro' => session()->get('usuario_id')
        ];

        if ($this->comisionModel->insert($data)) {
            // Generar y descargar CSV
            $this->generarCSV($this->comisionModel->getInsertID());
            
            return redirect()->to('/comision/crear')->with('success', 'Comisión creada exitosamente y CSV descargado');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al crear la comisión');
        }
    }

    // Generar folio único
    private function generarFolio()
    {
        $ultimoFolio = $this->comisionModel->getUltimoFolio();
        
        if ($ultimoFolio && preg_match('/\d+/', $ultimoFolio, $matches)) {
            $ultimoNumero = intval($matches[0]);
            $nuevoNumero = str_pad($ultimoNumero + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nuevoNumero = '0001';
        }
        
        return $nuevoNumero;
    }

    // Generar CSV de la comisión
    private function generarCSV($comisionId)
    {
        $comision = $this->comisionModel->getComisionCompleta($comisionId);
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Encabezados
        $sheet->setCellValue('A1', 'OFICIO DE COMISIÓN');
        $sheet->setCellValue('A2', 'Folio: ' . $comision['folio']);
        $sheet->setCellValue('A3', 'Fecha: ' . date('d/m/Y'));
        
        // Datos del empleado
        $sheet->setCellValue('A5', 'Nombre: ' . $comision['nombre_empleado']);
        $sheet->setCellValue('A6', 'Clave de Puesto: ' . $comision['clave_puesto']);
        $sheet->setCellValue('A7', 'Denominación del Puesto: ' . $comision['denominacion_puesto']);
        $sheet->setCellValue('A8', 'Tipo de Contratación: ' . $comision['tipo_contratacion']);
        
        // Datos de la comisión
        $sheet->setCellValue('A10', 'Destino: ' . $comision['nombre_municipio']);
        $sheet->setCellValue('A11', 'Asunto Corto: ' . $comision['asunto_corto']);
        $sheet->setCellValue('A12', 'Fecha Inicio: ' . $comision['fecha_inicio']);
        $sheet->setCellValue('A13', 'Fecha Fin: ' . $comision['fecha_fin']);
        $sheet->setCellValue('A14', 'Asunto: ' . $comision['asunto']);
        $sheet->setCellValue('A15', 'Medio de Transporte: ' . ($comision['medio_transporte'] == 1 ? 'Oficial' : 'Particular'));
        
        $writer = new Csv($spreadsheet);
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="comision_' . $comision['folio'] . '.csv"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
}