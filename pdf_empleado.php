<?php
require_once './dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['numero_empleado'])) {
    $numero_empleado = htmlspecialchars(trim($_POST['numero_empleado']));

    $host = 'localhost';  
    $base_de_datos = 'proyectoagua';  
    $username = 'root';   
    $password = '';       

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$base_de_datos;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        //Consulta para buscar al empleado
        $sql = "SELECT id_empleado, numero_empleado, nombre, apellido, tipo FROM empleados WHERE numero_empleado LIKE :numero_empleado";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['numero_empleado' => "%$numero_empleado%"]);

        // Cargar la imagen y convertir a Base64
        $rutaImagen = 'C:\xampp\htdocs\agua\ACOAPEN.jpg'; // Ruta de tu imagen
        $imagenBase64 = base64_encode(file_get_contents($rutaImagen));
        $tipoImagen = mime_content_type($rutaImagen); // Obtener el tipo de imagen (e.g., image/png)

        //Generar el HTML para el PDF
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
        <br>
        <center><h2>EMPLEADOS</h2> <br></center>
        <table>
            <tr>
                <th>ID</th>
                <th>Número Empleado</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Tipo</th>
            </tr>';

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $html .= '
                <tr>
                    <td>' . htmlspecialchars($row['id_empleado']) . '</td>
                    <td>' . htmlspecialchars($row['numero_empleado']) . '</td>
                    <td>' . htmlspecialchars($row['nombre']) . '</td>
                    <td>' . htmlspecialchars($row['apellido']) . '</td>
                    <td>' . htmlspecialchars($row['tipo']) . '</td>
                </tr>';
            }
        } else {
            $html .= '<tr><td colspan="5">No se encontraron resultados para "' . htmlspecialchars($numero_empleado) . '"</td></tr>';
        }

        $html .= '
        </table>
        </body>
        </html>';
    
        // Configurar DOMPDF
    $options = new Options();
    $options->set('isRemoteEnabled', true); // Habilitar imágenes remotas
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

        // Enviar PDF al navegador
        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=documento.pdf");
        echo $dompdf->output();

    } catch (PDOException $e) {
        die("Error al conectar a la base de datos: " . $e->getMessage());
    }
} else {
    echo "Por favor, utiliza el formulario para buscar un empleado.";
}
?>
