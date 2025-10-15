<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "0000";
$dbname = "administracion_oficios";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Style\Alignment;


// Obtener el último número de oficio
$sql = "SELECT folio FROM comision ORDER BY id_comision DESC LIMIT 1";
$result = $conn->query($sql);
$ultimo_folio = "";
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $ultimo_folio = $row["folio"];
}

// Obtener empleados
$sql_empleados = "SELECT e.id_empleado, e.nombre, e.primer_apellido, e.segundo_apellido,
                p.dsc_puesto, e.id_puesto_fk, e.id_estructura_fk,
                t.dsc_tipo_contrato, es.dsc_estructura
                FROM empleados e
                LEFT JOIN cat_puestos p ON e.id_puesto_fk = p.id_puesto
                LEFT JOIN cat_tipo_contrato t ON e.id_tipo_contrato_fk = t.id_tipo_contrato
                LEFT JOIN cat_estructura es ON e.id_estructura_fk = es.id_estructura
                WHERE e.activo = 1";

$result_empleados = $conn->query($sql_empleados);
$empleados = array();

while ($row = $result_empleados->fetch_assoc()) {
    $empleados[] = $row;
}

// Obtener estructuras organizacionales
$sql_estructuras = "SELECT id_estructura, dsc_estructura FROM cat_estructura WHERE visible = 1";
$result_estructuras = $conn->query($sql_estructuras);
$estructuras = array();

while ($row = $result_estructuras->fetch_assoc()) {
    $estructuras[] = $row;
}

// Obtener puestos
$sql_puestos = "SELECT id_puesto, dsc_puesto FROM cat_puestos WHERE visible = 1";
$result_puestos = $conn->query($sql_puestos);
$puestos = array();

while ($row = $result_puestos->fetch_assoc()) {
    $puestos[] = $row;
}

// Obtener estados
$sql_estados = "SELECT id_estado, nombre_estado, abreviacion FROM cat_estado WHERE visible = 1 ORDER BY nombre_estado";
$result_estados = $conn->query($sql_estados);
$estados = array();

while ($row = $result_estados->fetch_assoc()) {
    $estados[] = $row;
}

// Obtener tipo de contrato
$sql_tipo_contrato = "SELECT id_tipo_contrato, dsc_tipo_contrato FROM cat_tipo_contrato WHERE visible = 1";
$result_tipo_contrato = $conn->query($sql_tipo_contrato);
$tipos_contrato = array();

while ($row = $result_tipo_contrato->fetch_assoc()) {
    $tipos_contrato[] = $row;
}



// Procesar el formulario cuando se envíe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger y validar datos del formulario
    $id_estructura_departamento_fk = $_POST["id_estructura_departamento_fk"];
    $id_empleado_fk = $_POST["id_empleado_fk"];
    $folio = $_POST["folio"];
    $asunto_corto = $_POST["asunto_corto"];
    $fecha_inicio = $_POST["fecha_inicio"];
    $fecha_fin = $_POST["fecha_fin"];
    $asunto = $_POST["asunto"];
    $medio_transporte = $_POST["medio_transporte"];
    $anticipo_devengo = $_POST["anticipo_devengo"];
    $aplica_alimentos = isset($_POST["aplica_alimentos"]) ? 1 : 0;
    $comision_fuera_estado = isset($_POST["comision_fuera_estado"]) ? 1 : 0;
    $tarifa = $_POST["tarifa"] ?: NULL;
    $dias = $_POST["dias"] ?: NULL;
    $cuota = $_POST["cuota"] ?: NULL;
    $partida_3710 = $_POST["partida_3710"] ?: NULL;
    $partida_3720 = $_POST["partida_3720"] ?: NULL;
    $id_municipio = $_POST["id_municipio"] ?: NULL;
    $partida_total = $_POST["partida_total"] ?: NULL;
    $fecha_registro = date("Y-m-d H:i:s");
    $id_usuario_registro = 1;

    // Insertar datos en la base de datos
    $sql = "INSERT INTO comision (
        id_estructura_departamento_fk, id_empleado_fk, folio, asunto_corto, 
        fecha_inicio, fecha_fin, asunto, medio_transporte, anticipo_devengo, 
        aplica_alimentos, comision_fuera_estado, tarifa, dias, cuota, 
        partida_3710, partida_3720, id_municipio, partida_total, 
        fecha_registro, id_usuario_registro
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "iisssssiiiiidiididsi",
        $id_estructura_departamento_fk,
        $id_empleado_fk,
        $folio,
        $asunto_corto,
        $fecha_inicio,
        $fecha_fin,
        $asunto,
        $medio_transporte,
        $anticipo_devengo,
        $aplica_alimentos,
        $comision_fuera_estado,
        $tarifa,
        $dias,
        $cuota,
        $partida_3710,
        $partida_3720,
        $id_municipio,
        $partida_total,
        $fecha_registro,
        $id_usuario_registro
    );


    if ($stmt->execute()) {
        // Generar CSV
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $headers = [
            'Folio', 'Asunto Corto', 'Fecha Inicio', 'Fecha Fin', 'Asunto',
            'Medio Transporte', 'Anticipo/Devengo', 'Aplica Alimentos', 'Comisión Fuera Estado',
            'Tarifa', 'Días', 'Cuota', 'Partida 3710', 'Partida 3720', 'Partida Total'
        ];
        $sheet->fromArray([$headers], NULL, 'A1');

        // Datos
        $sheet->fromArray([[
            $folio, $asunto_corto, $fecha_inicio, $fecha_fin, $asunto,
            $medio_transporte, $anticipo_devengo, $aplica_alimentos, $comision_fuera_estado,
            $tarifa, $dias, $cuota, $partida_3710, $partida_3720, $partida_total
        ]], NULL, 'A2');

        // Centrar encabezados
        $sheet->getStyle('A1:O1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Descargar CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="oficio_comision_' . date('Ymd_His') . '.csv"');
        $writer = new Csv($spreadsheet);
        $writer->save('php://output');
        exit;
    } else {
        echo "<div style='color:red;'>Error al insertar en la base de datos: " . $stmt->error . "</div>";
    }

    $stmt->close();
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Comisión</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eaeaea;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-avatar {
            margin-right: 15px;
        }

        .avatar-placeholder {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #3498db;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 20px;
        }

        .user-details p {
            margin: 0;
        }

        .back-button {
            text-decoration: none;
            color: #3498db;
            font-weight: bold;
            padding: 8px 15px;
            border: 1px solid #3498db;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .back-button:hover {
            background-color: #3498db;
            color: white;
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-section {
            margin-bottom: 25px;
            padding: 15px;
            border: 1px solid #eaeaea;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .form-section h2 {
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eaeaea;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-group input:read-only,
        .form-group textarea:read-only {
            background-color: #f0f0f0;
            color: #777;
        }

        .radio-group {
            display: flex;
            gap: 20px;
        }

        .radio-group input[type="radio"] {
            width: auto;
            margin-right: 5px;
        }

        .radio-group label {
            display: inline;
            font-weight: normal;
        }

        .checkbox-group {
            margin-bottom: 10px;
        }

        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin-right: 8px;
        }

        .checkbox-group label {
            display: inline;
            font-weight: normal;
        }

        .tabla-adicional {
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .tabla-adicional table {
            width: 100%;
            border-collapse: collapse;
        }

        .tabla-adicional th,
        .tabla-adicional td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .tabla-adicional th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .tabla-adicional input {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
        }

        .btn-submit,
        .btn-reset,
        .btn-cancel {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .btn-submit {
            background-color: #2ecc71;
            color: white;
        }

        .btn-submit:hover {
            background-color: #27ae60;
        }

        .btn-reset {
            background-color: #f39c12;
            color: white;
        }

        .btn-reset:hover {
            background-color: #d35400;
        }

        .btn-cancel {
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            text-align: center;
            line-height: normal;
        }

        .btn-cancel:hover {
            background-color: #c0392b;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
                margin: 10px;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .back-button {
                margin-top: 10px;
            }

            .form-actions {
                flex-direction: column;
                gap: 10px;
            }

            .btn-submit,
            .btn-reset,
            .btn-cancel {
                width: 100%;
            }
        }
    </style>
    <div class="container">
        <h1>Comisión</h1>
        <form id="comisionForm" method="POST" action="<?php echo base_url(); ?>">
            <div class="form-section">
                <h2>Información del Empleado</h2>
                <div class="form-group">
                    <label for="id_empleado_fk">Nombre:</label>
                    <select id="id_empleado_fk" name="id_empleado_fk" onchange="cargarDatosEmpleado()" required>
                        <option value="">Seleccione un empleado</option>
                        <?php foreach ($empleados as $empleado): ?>
                            <?php
                            $nombre_completo = $empleado['nombre'] . ' ' . $empleado['primer_apellido'];
                            if (!empty($empleado['segundo_apellido'])) {
                                $nombre_completo .= ' ' . $empleado['segundo_apellido'];
                            }
                            ?>
                            <option value="<?php echo $empleado['id_empleado']; ?>"
                                data-puesto="<?php echo $empleado['dsc_puesto']; ?>"
                                data-puesto-id="<?php echo $empleado['id_puesto_fk']; ?>"
                                data-contrato="<?php echo $empleado['dsc_tipo_contrato']; ?>"
                                data-estructura="<?php echo $empleado['dsc_estructura']; ?>"
                                data-estructura-id="<?php echo $empleado['id_estructura_fk']; ?>">
                                <?php echo $nombre_completo; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="folio">Número de Oficio:</label>
                    <input type="text" id="folio" name="folio"
                        value="<?php echo $ultimo_folio ? intval($ultimo_folio) + 1 : '001'; ?>" readonly>
                </div>
                <input type="hidden" id="id_estructura_departamento_fk" name="id_estructura_departamento_fk">
                <div class="form-group">
                    <label for="direccion_departamento">Dirección del Departamento:</label>
                    <input type="text" id="direccion_departamento" name="direccion_departamento" readonly>
                </div>
                <div class="form-group">
                    <label for="departamento">Departamento:</label>
                    <input type="text" id="departamento" name="departamento" readonly>
                </div>
                <div class="form-group">
                    <label for="clave_puesto">Clave de Puesto:</label>
                    <input type="text" id="clave_puesto" name="clave_puesto" readonly>
                </div>
                <div class="form-group">
                    <label for="denominacion_puesto">Denominación del Puesto:</label>
                    <input type="text" id="denominacion_puesto" name="denominacion_puesto" readonly>
                </div>
                <div class="form-group">
                    <label for="tipo_contratacion">Tipo de Contratación:</label>
                    <input type="text" id="tipo_contratacion" name="tipo_contratacion" readonly>
                </div>
            </div>
    </div>


    <!-- Detalles de la Comisión -->
    <div class="container">
        <div class="form-section">
            <h2>Detalles</h2>
            <div class="form-group">
                <label for="id_estado_destino">Estado Destino:</label>
                <select id="id_estado_destino" name="id_estado_destino" onchange="actualizarAsuntoCorto()" required>
                    <option value="">Seleccione un estado</option>
                    <?php foreach ($estados as $estado): ?>
                        <?php if ($estado['id_estado'] != 0 && $estado['id_estado'] != 88 && $estado['id_estado'] != 99): ?>
                            <option value="<?php echo $estado['id_estado']; ?>">
                                <?php echo $estado['nombre_estado']; ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="municipio_destino">Municipio Destino:</label>
                <input type="text" id="municipio_destino" name="municipio_destino"
                    placeholder="Ingrese el municipio de destino">
            </div>
            <div class="form-group">
                <label for="asunto_corto">Asunto Corto:</label>
                <input type="text" id="asunto_corto" name="asunto_corto">
            </div>
            <div class="form-group">
                <label for="fecha_actual">Fecha Actual:</label>
                <input type="text" id="fecha_actual" name="fecha_actual" value="<?php echo date('d/m/Y'); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="fecha_inicio">Fecha de Inicio:</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" required>
            </div>
            <div class="form-group">
                <label for="fecha_fin">Fecha de Fin:</label>
                <input type="date" id="fecha_fin" name="fecha_fin" required>
            </div>
            <div class="form-group">
                <label for="asunto">Con objeto de:</label>
                <textarea id="asunto" name="asunto" rows="4" required
                    placeholder="Describa el objetivo de la comisión..."></textarea>
            </div>
        </div>

        <!-- Opciones de Transporte y Financiamiento -->
        <div class="form-section">
            <div class="form-group">
                <label>Medio de Transporte:</label>
                <div class="radio-group">
                    <input type="radio" id="transporte_oficial" name="medio_transporte" value="1" required>
                    <label for="transporte_oficial">Oficial</label>
                    <input type="radio" id="transporte_particular" name="medio_transporte" value="2">
                    <label for="transporte_particular">Particular</label>
                </div>
            </div>
            <div class="form-group">
                <label>Se le proporcionará:</label>
                <div class="radio-group">
                    <input type="radio" id="anticipo" name="anticipo_devengo" value="1" required>
                    <label for="anticipo">Anticipo</label>
                    <input type="radio" id="devengo" name="anticipo_devengo" value="2">
                    <label for="devengo">Devengo</label>
                </div>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="aplica_alimentos" name="aplica_alimentos" onchange="toggleTablaAlimentos()">
                <label for="aplica_alimentos">Aplica alimentos</label>
            </div>

            <div id="tabla_alimentos" class="tabla-adicional" style="display: none;">
                <table>
                    <thead>
                        <tr>
                            <th>Tarifa</th>
                            <th>Días</th>
                            <th>Cuota Diaria</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="number" id="tarifa" name="tarifa" step="0.01" min="0" placeholder="0.00"
                                    disabled onchange="calcularSubtotalAlimentos()"></td>
                            <td><input type="number" id="dias" name="dias" min="0" placeholder="0" disabled
                                    onchange="calcularSubtotalAlimentos()"></td>
                            <td><input type="number" id="cuota" name="cuota" step="0.01" min="0" placeholder="0.00"
                                    disabled onchange="calcularSubtotalAlimentos()"></td>
                            <td><input type="number" id="subtotal_alimentos" name="subtotal_alimentos" step="0.01"
                                    min="0" placeholder="0.00" readonly></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="comision_fuera_estado" name="comision_fuera_estado"
                    onchange="toggleTablaFueraEstado()">
                <label for="comision_fuera_estado">Comisión fuera del estado</label>
            </div>

            <div id="tabla_fuera_estado" class="tabla-adicional" style="display: none;">
                <table>
                    <thead>
                        <tr>
                            <th>Partida 3710 (Pasajes Aéreos)</th>
                            <th>Partida 3720 (Pasajes Terrestres)</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="number" id="partida_3710" name="partida_3710" step="0.01" min="0"
                                    placeholder="0.00" disabled onchange="calcularTotal()"></td>
                            <td><input type="number" id="partida_3720" name="partida_3720" step="0.01" min="0"
                                    placeholder="0.00" disabled onchange="calcularTotal()"></td>
                            <td><input type="number" id="partida_total" name="partida_total" step="0.01" min="0"
                                    placeholder="0.00" readonly></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <input type="hidden" id="id_municipio" name="id_municipio" value="">
        <input type="hidden" id="id_usuario_registro" name="id_usuario_registro" value="1">

        <!-- Botones de Acción -->
        <div class="form-actions">
            <button type="submit" class="btn-submit">Enviar Formulario</button>
            <button type="reset" class="btn-reset" onclick="resetForm()">Restablecer Formulario</button>
            <a href="<?php echo base_url(); ?>" class="btn-cancel">Cancelar</a>
        </div>
        </form>



        <script>
            // Función para cargar datos del empleado seleccionado
            function cargarDatosEmpleado() {
                const select = document.getElementById('id_empleado_fk');
                const selectedOption = select.options[select.selectedIndex];

                if (selectedOption.value) {
                    document.getElementById('denominacion_puesto').value = selectedOption.getAttribute('data-puesto');
                    document.getElementById('clave_puesto').value = 'CP' + selectedOption.getAttribute('data-puesto-id').toString().padStart(3, '0');
                    document.getElementById('tipo_contratacion').value = selectedOption.getAttribute('data-contrato');
                    document.getElementById('departamento').value = selectedOption.getAttribute('data-estructura');
                    document.getElementById('direccion_departamento').value = obtenerDireccionDepartamento(selectedOption.getAttribute('data-estructura'));
                    document.getElementById('id_estructura_departamento_fk').value = selectedOption.getAttribute('data-estructura-id');
                    actualizarAsuntoCorto();
                } else {
                    // Limpiar campos si no hay selección
                    document.getElementById('clave_puesto').value = '';
                    document.getElementById('denominacion_puesto').value = '';
                    document.getElementById('tipo_contratacion').value = '';
                    document.getElementById('departamento').value = '';
                    document.getElementById('direccion_departamento').value = '';
                    document.getElementById('id_estructura_departamento_fk').value = '';
                    document.getElementById('asunto_corto').value = '';
                }
            }

            // Función para determinar la dirección del departamento
            function obtenerDireccionDepartamento(estructura) {
                if (estructura.includes('Direccion General')) {
                    return 'Dirección General de Planeación';
                } else if (estructura.includes('Direccion de Tecnologias')) {
                    return 'Dirección de Tecnologías de la Información y Comunicaciones';
                } else if (estructura.includes('Soporte Tecnico')) {
                    return 'Departamento de Soporte Técnico y Comunicaciones';
                } else if (estructura.includes('Desarrollo de Sistemas')) {
                    return 'Departamento de Desarrollo de Sistemas Web';
                }
                return estructura;
            }

            // Función para actualizar el asunto corto basado en el destino
            function actualizarAsuntoCorto() {
                const estadoSelect = document.getElementById('id_estado_destino');
                const municipioInput = document.getElementById('municipio_destino');
                const empleadoSelect = document.getElementById('id_empleado_fk');
                const empleadoNombre = empleadoSelect.options[empleadoSelect.selectedIndex].text;
                const estadoNombre = estadoSelect.options[estadoSelect.selectedIndex].text;
                const municipio = municipioInput.value;
            }


            // Función para mostrar/ocultar la tabla de alimentos
            function toggleTablaAlimentos() {
                const tabla = document.getElementById('tabla_alimentos');
                const checkbox = document.getElementById('aplica_alimentos');
                const inputs = tabla.querySelectorAll('input');

                if (checkbox.checked) {
                    tabla.style.display = 'block';
                    inputs.forEach(input => {
                        if (input.id !== 'subtotal_alimentos') {
                            input.disabled = false;
                        }
                    });
                } else {
                    tabla.style.display = 'none';
                    inputs.forEach(input => {
                        if (input.id !== 'subtotal_alimentos') {
                            input.disabled = true;
                            input.value = '';
                        }
                    });
                    document.getElementById('subtotal_alimentos').value = '';
                }
            }

            // Función para mostrar/ocultar la tabla de comisión fuera del estado
            function toggleTablaFueraEstado() {
                const tabla = document.getElementById('tabla_fuera_estado');
                const checkbox = document.getElementById('comision_fuera_estado');
                const inputs = tabla.querySelectorAll('input');

                if (checkbox.checked) {
                    tabla.style.display = 'block';
                    inputs.forEach(input => {
                        if (input.id !== 'partida_total') {
                            input.disabled = false;
                        }
                    });
                } else {
                    tabla.style.display = 'none';
                    inputs.forEach(input => {
                        if (input.id !== 'partida_total') {
                            input.disabled = true;
                            input.value = '';
                        }
                    });
                    document.getElementById('partida_total').value = '';
                }
            }

            // Función para calcular el subtotal de alimentos
            function calcularSubtotalAlimentos() {
                const tarifa = parseFloat(document.getElementById('tarifa').value) || 0;
                const dias = parseFloat(document.getElementById('dias').value) || 0;
                const cuota = parseFloat(document.getElementById('cuota').value) || 0;
                const subtotal = tarifa * dias * cuota;
                document.getElementById('subtotal_alimentos').value = subtotal.toFixed(2);
            }

            // Función para calcular el total de partidas
            function calcularTotal() {
                const partida3710 = parseFloat(document.getElementById('partida_3710').value) || 0;
                const partida3720 = parseFloat(document.getElementById('partida_3720').value) || 0;
                const total = partida3710 + partida3720;
                document.getElementById('partida_total').value = total.toFixed(2);
            }

            // Función para resetear completamente el formulario
            function resetForm() {
                document.getElementById('comisionForm').reset();
                document.getElementById('tabla_alimentos').style.display = 'none';
                document.getElementById('tabla_fuera_estado').style.display = 'none';

                // Deshabilitar inputs de tablas
                const inputsAlimentos = document.querySelectorAll('#tabla_alimentos input');
                inputsAlimentos.forEach(input => {
                    if (input.id !== 'subtotal_alimentos') {
                        input.disabled = true;
                    }
                });

                const inputsFueraEstado = document.querySelectorAll('#tabla_fuera_estado input');
                inputsFueraEstado.forEach(input => {
                    if (input.id !== 'partida_total') {
                        input.disabled = true;
                    }
                });

                // Restaurar fecha actual
                document.getElementById('fecha_actual').value = '<?php echo date('d/m/Y'); ?>';

                // Limpiar asunto corto
                document.getElementById('asunto_corto').value = '';
            }

            // Validar que la fecha de fin no sea anterior a la fecha de inicio
            document.getElementById('fecha_inicio').addEventListener('change', function () {
                const fechaInicio = new Date(this.value);
                const fechaFinInput = document.getElementById('fecha_fin');
                const fechaFin = new Date(fechaFinInput.value);

                if (fechaFinInput.value && fechaFin < fechaInicio) {
                    fechaFinInput.value = '';
                    alert('La fecha de fin no puede ser anterior a la fecha de inicio');
                }
            });

            document.getElementById('fecha_fin').addEventListener('change', function () {
                const fechaInicio = new Date(document.getElementById('fecha_inicio').value);
                const fechaFin = new Date(this.value);

                if (fechaFin < fechaInicio) {
                    this.value = '';
                    alert('La fecha de fin no puede ser anterior a la fecha de inicio');
                }
            });

            // Inicializar el formulario
            document.addEventListener('DOMContentLoaded', function () {
                resetForm();
            });
        </script>
</body>

</html>