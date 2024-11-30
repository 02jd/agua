<?php
require_once './vendor/autoload.php'; // Asegúrate de que la ruta al autoload sea correcta
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['numero_cliente'])) {
    $numero_cliente = htmlspecialchars(trim($_POST['numero_cliente']));

    $host = 'localhost';  
    $dbname = 'proyectoagua';  
    $username = 'root';   
    $password = '';       

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta para buscar al cliente
        $sql = "SELECT id_cliente, numero_cliente, nombre, apellido, correo_electronico, dui, id_zona, telefono FROM clientes WHERE numero_cliente LIKE :numero_cliente";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['numero_cliente' => "%$numero_cliente%"]);

        // Crear una nueva instancia de Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Agregar los encabezados de la tabla
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Número Cliente');
        $sheet->setCellValue('C1', 'Nombre');
        $sheet->setCellValue('D1', 'Apellido');
        $sheet->setCellValue('E1', 'Correo Electrónico');
        $sheet->setCellValue('F1', 'DUI');
        $sheet->setCellValue('G1', 'ID Zona');
        $sheet->setCellValue('H1', 'Teléfono');

        $rowNumber = 2; // Inicia en la fila 2, debajo de los encabezados

        // Agregar los datos de los clientes
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $sheet->setCellValue('A' . $rowNumber, htmlspecialchars($row['id_cliente']));
                $sheet->setCellValue('B' . $rowNumber, htmlspecialchars($row['numero_cliente']));
                $sheet->setCellValue('C' . $rowNumber, htmlspecialchars($row['nombre']));
                $sheet->setCellValue('D' . $rowNumber, htmlspecialchars($row['apellido']));
                $sheet->setCellValue('E' . $rowNumber, htmlspecialchars($row['correo_electronico']));
                $sheet->setCellValue('F' . $rowNumber, htmlspecialchars($row['dui']));
                $sheet->setCellValue('G' . $rowNumber, htmlspecialchars($row['id_zona']));
                $sheet->setCellValue('H' . $rowNumber, htmlspecialchars($row['telefono']));
                $rowNumber++;
            }
        } else {
            $sheet->setCellValue('A2', 'No se encontraron resultados para "' . htmlspecialchars($numero_cliente) . '"');
        }

        // Crear el escritor y guardar el archivo Excel
        $writer = new Xlsx($spreadsheet);

        // Enviar el archivo Excel al navegador
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="clientes.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output'); // Esto enviará el archivo al navegador

    } catch (PDOException $e) {
        die("Error al conectar a la base de datos: " . $e->getMessage());
    }
} else {
    echo "Por favor, utiliza el formulario para buscar un cliente.";
}
?>
