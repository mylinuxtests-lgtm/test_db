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

// Obtener el último número de oficio para el incremento automático
$sql = "SELECT folio FROM comision ORDER BY id_comision DESC LIMIT 1";
$result = $conn->query($sql);
$ultimo_folio = "";
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $ultimo_folio = $row["folio"];
}

// Obtener empleados para el dropdown
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
    $id_municipio = $_POST["id_municipio"];
    $partida_total = $_POST["partida_total"] ?: NULL;
    $fecha_registro = date("Y-m-d H:i:s");
    $id_usuario_registro = $_POST["id_usuario_registro"];

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
        // Generar CSV en lugar de PDF
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=oficio_comision.csv');

        $output = fopen('php://output', 'w');

        // Escribir encabezados
        fputcsv($output, array(
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
        ));

        // Escribir datos
        fputcsv($output, array(
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
            $partida_total
        ));

        fclose($output);
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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
    <div class="container">
        <div class="header">
            <div class="user-info">
                <div class="user-avatar">
                    <div class="avatar-placeholder">U</div>
                </div>
                <div class="user-details">
                    <p><strong>Usuario Actual:</strong> Nombre del Usuario</p>
                </div>
            </div>
            <a href="index.php" class="back-button">← Regresar</a>
        </div>

        <h1>Formulario de Comisión</h1>

        <form id="comisionForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <!-- Información del Departamento -->
            <div class="form-section">
                <h2>Información del Departamento</h2>
                <div class="form-group">
                    <label for="direccion_departamento">Dirección del Departamento:</label>
                    <input type="text" id="direccion_departamento" name="direccion_departamento" readonly>
                </div>
                <div class="form-group">
                    <label for="departamento">Departamento:</label>
                    <input type="text" id="departamento" name="departamento" readonly>
                </div>
                <div class="form-group">
                    <label for="folio">Número de Oficio:</label>
                    <input type="text" id="folio" name="folio"
                        value="<?php echo $ultimo_folio ? intval($ultimo_folio) + 1 : '001'; ?>" readonly>
                </div>
            </div>
            <input type="hidden" id="id_estructura_departamento_fk" name="id_estructura_departamento_fk">

           <!-- Información del Empleado -->
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

            <!-- Detalles de la Comisión -->
            <div class="form-section">
                <h2>Detalles de la Comisión</h2>
                <div class="form-group">
                    <label for="destino">Destino:</label>
                    <select id="destino" name="destino" onchange="actualizarAsuntoCorto()" required>
                        <option value="">Seleccione un destino</option>
                        <option value="Ciudad de México">Ciudad de México</option>
                        <option value="Guadalajara">Guadalajara</option>
                        <option value="Monterrey">Monterrey</option>
                        <option value="Puebla">Puebla</option>
                        <option value="Querétaro">Querétaro</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="asunto_corto">Asunto:</label>
                    <input type="text" id="asunto_corto" name="asunto_corto">
                </div>
                <div class="form-group">
                    <label for="fecha_actual">Fecha Actual:</label>
                    <input type="text" id="fecha_actual" name="fecha_actual" value="<?php echo date('d/m/y'); ?>"
                        readonly>
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
                    <textarea id="asunto" name="asunto" rows="4" required></textarea>
                </div>
            </div>

            <!-- Opciones de Transporte y Financiamiento -->
            <div class="form-section">
                <h2>Opciones de Transporte y Financiamiento</h2>
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
                    <input type="checkbox" id="aplica_alimentos" name="aplica_alimentos"
                        onchange="toggleTablaAlimentos()">
                    <label for="aplica_alimentos">Aplica alimentos</label>
                    <div id="tabla_alimentos" class="tabla-adicional" style="display: none;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Tarifa</th>
                                    <th>Días</th>
                                    <th>Cuota Diaria</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="number" id="tarifa" name="tarifa" step="0.01" min="0" placeholder="0">
                                </td>
                                <td><input type="number" id="dias" name="dias" min="0" placeholder="0" disabled></td>
                                <td><input type="number" id="cuota" name="cuota" step="0.01" min="0" placeholder="0.00"
                                disabled></td>
                            </tr>
                        </div>
                        </tbody>
                    </table>
                    </div>
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
                                <th>Partida 3710</th>
                                <th>Partida 3720</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="number" id="partida_3710" name="partida_3710" step="0.01" min="0"
                                        disabled onchange="calcularTotal()"></td>
                                <td><input type="number" id="partida_3720" name="partida_3720" step="0.01" min="0"
                                        disabled onchange="calcularTotal()"></td>
                                <td><input type="number" id="partida_total" name="partida_total" step="0.01" min="0"
                                        disabled placeholder="0.00"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="form-actions">
                <button type="submit" class="btn-submit">Enviar Formulario</button>
                <button type="reset" class="btn-reset" onclick="resetForm()">Restablecer Formulario</button>
                <a href="index.php" class="btn-cancel">Cancelar</a>
            </div>
        </form>
    </div>

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
            } else {
                // Limpiar campos si no hay selección
                document.getElementById('clave_puesto').value = '';
                document.getElementById('denominacion_puesto').value = '';
                document.getElementById('tipo_contratacion').value = '';
                document.getElementById('departamento').value = '';
                document.getElementById('direccion_departamento').value = '';
                document.getElementById('id_estructura_departamento_fk').value = '';
            }
        }

        // Función para determinar la dirección del departamento
        function obtenerDireccionDepartamento(estructura) {
            if (estructura.includes('Direccion General')) {
                return 'Dirección General';
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
            const destino = document.getElementById('destino').value;
            const empleadoSelect = document.getElementById('id_empleado_fk');
            const empleadoNombre = empleadoSelect.options[empleadoSelect.selectedIndex].text;
        }

        // Función para mostrar/ocultar la tabla de alimentos
        function toggleTablaAlimentos() {
            const tabla = document.getElementById('tabla_alimentos');
            const checkbox = document.getElementById('aplica_alimentos');
            const inputs = tabla.querySelectorAll('input');

            if (checkbox.checked) {
                tabla.style.display = 'block';
                inputs.forEach(input => input.disabled = false);
            } else {
                tabla.style.display = 'none';
                inputs.forEach(input => {
                    input.disabled = true;
                    input.value = '';
                });
            }
        }

        // Función para mostrar/ocultar la tabla de comisión fuera del estado
        function toggleTablaFueraEstado() {
            const tabla = document.getElementById('tabla_fuera_estado');
            const checkbox = document.getElementById('comision_fuera_estado');
            const inputs = tabla.querySelectorAll('input');

            if (checkbox.checked) {
                tabla.style.display = 'block';
                inputs.forEach(input => input.disabled = false);
            } else {
                tabla.style.display = 'none';
                inputs.forEach(input => {
                    input.disabled = true;
                    input.value = '';
                });
            }
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
            inputsAlimentos.forEach(input => input.disabled = true);

            const inputsFueraEstado = document.querySelectorAll('#tabla_fuera_estado input');
            inputsFueraEstado.forEach(input => input.disabled = true);

            // Restaurar fecha actual
            document.getElementById('fecha_actual').value = '<?php echo date('d/m'); ?>';
        }

        // Validar que la fecha de fin no sea anterior a la fecha de inicio
        document.getElementById('fecha_inicio').addEventListener('change', function () {
            const fechaInicio = new Date(this.value);
            const fechaFin = new Date(document.getElementById('fecha_fin').value);

            if (fechaFin < fechaInicio) {
                document.getElementById('fecha_fin').value = '';
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
    </script>
</body>

</html>