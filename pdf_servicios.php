<?php
require_once './dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['codigo'])) {
    $codigo_servicio = htmlspecialchars(trim($_POST['codigo']));

    $host = 'localhost';  
    $dbname = 'proyectoagua';  
    $username = 'root';   
    $password = '';       

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   
        //Consulta para buscar servicios
        $sql = "SELECT id_servicio, codigo, nombre, precio FROM servicios WHERE codigo LIKE :codigo";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['codigo' => "%$codigo_servicio%"]);

        // Cargar la imagen y convertir a Base64
        $rutaImagen = 'C:\xampp\htdocs\agua\ACOAPEN.jpg'; // Ruta de tu imagen
        $imagenBase64 = base64_encode(file_get_contents($rutaImagen));
        $tipoImagen = mime_content_type($rutaImagen); // Obtener el tipo de imagen (e.g., image/png)
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
        <br><center><h2>SERVICIOS</h2></center></br>
        <table>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Precio</th>
            </tr>';

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $html .= '
                <tr>
                    <td>' . htmlspecialchars($row['id_servicio']) . '</td>
                    <td>' . htmlspecialchars($row['codigo']) . '</td>
                    <td>' . htmlspecialchars($row['nombre']) . '</td>
                    <td>' . htmlspecialchars($row['precio']) . '</td>
                </tr>';
            }
        } else {
            $html .= '<tr><td colspan="4">No se encontraron resultados para "' . htmlspecialchars($codigo_servicio) . '"</td></tr>';
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
        header("Content-Disposition: inline; filename=servicios.pdf");
        echo $dompdf->output();

    } catch (PDOException $e) {
        die("Error al conectar a la base de datos: " . $e->getMessage());
    }
} else {
    echo "Por favor, utiliza el formulario para buscar un servicio.";
}
?>
