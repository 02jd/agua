<?php
require_once './dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tipo_reporte'])) {
    $tipo_reporte = htmlspecialchars(trim($_POST['tipo_reporte']));
    $filtro = isset($_POST['filtro']) ? htmlspecialchars(trim($_POST['filtro'])) : '';

    $host = 'localhost';  
    $dbname = 'proyectoagua';  
    $username = 'root';   
    $password = '';       

    try {
        // Conectar a la base de datos
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Cargar la imagen y convertirla a Base64
        $rutaImagen = 'C:/xampp/htdocs/agua/ACOAPEN.jpg'; // Ruta de tu imagen
        $imagenBase64 = base64_encode(file_get_contents($rutaImagen));
        $tipoImagen = mime_content_type($rutaImagen); // Obtener el tipo de imagen (e.g., image/png)

        // Generar el HTML para el PDF según el tipo de reporte
        $html = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                th, td { padding: 10px; border: 1px solid black; text-align: left; }
                th { background-color: #f2f2f2; }
                .header-img {
                    position: absolute;
                    top: 10px;
                    right: 10px;
                    width: 100px; /* Ajusta el tamaño según sea necesario */
                }
            </style>
        </head>
        <body>
        <img src="data:' . $tipoImagen . ';base64,' . $imagenBase64 . '" class="header-img">
        <br>';

        switch ($tipo_reporte) {
            case 'clientes':
                $sql = "SELECT id_cliente, numero_cliente, nombre, apellido, correo_electronico, dui, id_zona, telefono FROM clientes WHERE numero_cliente LIKE :filtro";
                $titulo = 'Reporte de Clientes';
                break;
            case 'empleados':
                $sql = "SELECT id_empleado, numero_empleado, nombre, apellido, tipo FROM empleados WHERE numero_empleado LIKE :filtro";
                $titulo = 'Reporte de Empleados';
                break;
            case 'servicios':
                $sql = "SELECT id_servicio, codigo, nombre, precio FROM servicios WHERE codigo LIKE :filtro";
                $titulo = 'Reporte de Servicios';
                break;
            case 'facturas':
                $sql = "SELECT id_factura, numero_factura, id_cliente, lectura_anterior, lectura_actual, consumo, id_servicio, monto_pendiente, monto_actual, monto_total, cancelado_factura, anulada_factura, tipo_pago FROM facturas WHERE numero_factura LIKE :filtro";
                $titulo = 'Reporte de Facturas';
                break;
            case 'usuarios':
                $sql = "SELECT id, nombre, correo_electronico, contrasena FROM usuarios WHERE nombre LIKE :filtro";
                $titulo = 'Reporte de Usuarios';
                break;
            default:
                throw new Exception('Tipo de reporte no válido.');
        }

        // Preparar la consulta SQL
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['filtro' => "%$filtro%"]);

        $html .= '<center><h2>' . $titulo . '</h2></center><br><table>';

        // Encabezados según el tipo de reporte
        switch ($tipo_reporte) {
            case 'clientes':
                $html .= '
                <tr>
                    <th>ID</th>
                    <th>Número Cliente</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Correo Electrónico</th>
                    <th>DUI</th>
                    <th>ID Zona</th>
                    <th>Teléfono</th>
                </tr>';
                break;
            case 'empleados':
                $html .= '
                <tr>
                    <th>ID</th>
                    <th>Número Empleado</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Tipo</th>
                </tr>';
                break;
            case 'servicios':
                $html .= '
                <tr>
                    <th>ID Servicio</th>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                </tr>';
                break;
            case 'facturas':
                $html .= '
                <tr>
                    <th>ID Factura</th>
                    <th>Número Factura</th>
                    <th>ID Cliente</th>
                    <th>Lectura Anterior</th>
                    <th>Lectura Actual</th>
                    <th>Consumo</th>
                    <th>ID Servicio</th>
                    <th>Monto Pendiente</th>
                    <th>Monto Actual</th>
                    <th>Monto Total</th>
                    <th>Cancelado</th>
                    <th>Anulada</th>
                    <th>Tipo Pago</th>
                </tr>';
                break;
            case 'usuarios':
                $html .= '
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo Electrónico</th>
                    <th>Contraseña</th>
                </tr>';
                break;
        }

        // Rellenar con los datos de la base de datos
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $html .= '<tr>';
                foreach ($row as $column => $value) {
                    $html .= '<td>' . htmlspecialchars($value) . '</td>';
                }
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr><td colspan="8">No se encontraron resultados para "' . htmlspecialchars($filtro) . '"</td></tr>';
        }

        // Cierre de la tabla
        $html .= '</table></body></html>';

        // Configurar DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true); // Habilitar el análisis HTML5
        $options->set('isPhpEnabled', true); // Habilitar PHP si se necesita
        $options->set('isRemoteEnabled', true); // Habilitar imágenes remotas
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape'); // Tamaño de papel y orientación
        $dompdf->render();

        // Enviar el PDF al navegador
        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=" . $titulo . ".pdf");
        echo $dompdf->output();

    } catch (PDOException $e) {
        die("Error al conectar a la base de datos: " . $e->getMessage());
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    echo "Por favor, utiliza el formulario para seleccionar un reporte.";
}
?>
