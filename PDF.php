<?php
session_start(); // Iniciar sesión para obtener la variable $_SESSION

// Incluir PHPMailer
require 'C:/xampp/htdocs/agua/PHPMailer-master/src/PHPMailer.php';
require 'C:/xampp/htdocs/agua/PHPMailer-master/src/SMTP.php';
require 'C:/xampp/htdocs/agua/PHPMailer-master/src/Exception.php';

// Incluir TCPDF para generar el PDF
require_once('C:/xampp/htdocs/agua/TCPDF-main/tcpdf.php'); // Ajusta el path a donde tengas TCPDF

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Datos de conexión a la base de datos
$servidor = "localhost";
$usuario = "root";  // Cambia estos valores según tu configuración
$contraseña = "";   // Cambia estos valores según tu configuración
$base_datos = "proyectoagua"; // Nombre de tu base de datos

// Crear conexión
$conexion = new mysqli($servidor, $usuario, $contraseña, $base_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// ID del cliente que se desea mostrar (este ID proviene de la sesión)
$id_cliente = $_SESSION['id_cliente']; // Obtener el id del cliente desde la sesión

// Consulta SQL para obtener todas las facturas del cliente
$sql = "SELECT f.id_factura, f.numero_factura, f.id_cliente, f.lectura_anterior, f.lectura_actual, 
               f.consumo, f.id_servicio, f.monto_pendiente, f.monto_actual, f.monto_total, 
               f.cancelado_factura, f.anulada_factura, f.tipo_pago, 
               c.Nombre AS cliente_nombre, c.Apellido AS cliente_apellido, c.correo_electronico, 
               c.Telefono 
        FROM facturas f
        JOIN clientes c ON f.id_cliente = c.Id_cliente 
        WHERE f.id_cliente = '$id_cliente'";

// Ejecutar la consulta
$result = $conexion->query($sql);

// Verificar si se encontraron facturas
if ($result->num_rows > 0) {
    // Crear un array para almacenar todas las facturas
    $facturas = [];
    while ($row = $result->fetch_assoc()) {
        $facturas[] = $row; // Agregar cada factura al array
    }
} else {
    echo "<p>No se encontraron facturas para el cliente con ID: $id_cliente</p>";
}

// Cerrar la conexión
$conexion->close();

// Función para generar el PDF para cada factura
function generarPDF($factura) {
    // Crear una nueva instancia de TCPDF
    $pdf = new TCPDF();

    // Establecer la información del documento
    $pdf->SetCreator('Factura Sistema');
    $pdf->SetTitle('Factura de Servicios de Agua');
    
    // Añadir una página
    $pdf->AddPage();
    
    // Establecer el contenido del PDF (puedes personalizar este HTML)
    $html = "
    <h2>Factura del Cliente: {$factura['cliente_nombre']} {$factura['cliente_apellido']}</h2>
    <p><strong>ID Factura:</strong> {$factura['id_factura']}</p>
    <p><strong>Número de Factura:</strong> {$factura['numero_factura']}</p>
    <p><strong>Lectura Anterior:</strong> {$factura['lectura_anterior']}</p>
    <p><strong>Lectura Actual:</strong> {$factura['lectura_actual']}</p>
    <p><strong>Consumo:</strong> {$factura['consumo']} m³</p>
    <p><strong>Monto Pendiente:</strong> $ {$factura['monto_pendiente']}</p>
    <p><strong>Monto Actual:</strong> $ {$factura['monto_actual']}</p>
    <p><strong>Monto Total:</strong> $ {$factura['monto_total']}</p>
    <p><strong>Cancelado:</strong> " . ($factura['cancelado_factura'] == 'n' ? 'No' : 'Sí') . "</p>
    <p><strong>Anulada:</strong> " . ($factura['anulada_factura'] == 'n' ? 'No' : 'Sí') . "</p>
    <p><strong>Tipo de Pago:</strong> {$factura['tipo_pago']}</p>
    ";
    
    // Escribir el HTML al PDF
    $pdf->writeHTML($html);

    // Guardar el archivo PDF en el servidor (en una carpeta temporal)
    $pdfFilePath = 'C:/xampp/htdocs/agua/facturas/factura_' . $factura['id_factura'] . '.pdf';
    $pdf->Output($pdfFilePath, 'F'); // Guardar en el sistema de archivos

    return $pdfFilePath;
}

// Función para enviar los PDFs por correo
function enviarFacturaPorCorreo($facturas) {
    // Crear una nueva instancia de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configurar el servidor SMTP (cambia estos parámetros con tus credenciales)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Cambia por tu servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'tu_email@gmail.com'; // Tu correo
        $mail->Password = 'tu_contraseña'; // Tu contraseña de correo o la contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Remitente y destinatario
        $mail->setFrom('tu_email@gmail.com', 'Sistema de Facturación'); // Remitente (debe ser tu correo real)
        $mail->addAddress($facturas[0]['correo_electronico'], $facturas[0]['cliente_nombre'] . ' ' . $facturas[0]['cliente_apellido']); // Correo de la base de datos

        // Asunto y contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Facturas de Servicios de Agua';

        // Crear el contenido del correo
        $mail->Body = 'Adjunto encontrará sus facturas de servicios de agua en formato PDF.';

        // Añadir cada factura generada como adjunto
        foreach ($facturas as $factura) {
            $pdfFilePath = generarPDF($factura);
            $mail->addAttachment($pdfFilePath); // Adjuntar el archivo PDF
        }

        // Enviar el correo
        $mail->send();

        echo 'Facturas enviadas exitosamente.';
    } catch (Exception $e) {
        echo "Hubo un error al enviar el correo: {$mail->ErrorInfo}";
    }
}

// Llamar a la función cuando el cliente presiona "Enviar Factura"
if (isset($_POST['enviar_factura'])) {
    enviarFacturaPorCorreo($facturas); // Enviar todas las facturas por correo
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura del Cliente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .factura-container {
            width: 60%;
            margin: 30px auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .factura-title {
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }

        .factura-detail {
            display: flex;
            justify-content: space-between;
            margin: 15px 0;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .factura-detail:last-child {
            border-bottom: none;
        }

        .factura-detail label {
            font-weight: bold;
            color: #555;
            width: 40%;
        }

        .factura-detail span {
            color: #333;
            width: 55%;
            text-align: right;
        }

        .btn {
            display: inline-block;
            background-color: #127fa4;
            color: white;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            margin-top: 30px;
            width: 40%;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #1f3438;
        }

        /* Agregar un pequeño margen al botón */
        .factura-container form {
            text-align: center;
        }
        
/* Ícono de salida en la esquina inferior derecha */
.icono-salida {
    position: absolute; /* Posiciona la imagen de absoluta */
    bottom: 35px; /* Se ubica a 10px desde la parte inferior de la página */
    right: 1265px; /* Se ubica a 10px desde la parte derecha de la página */
    width: 40px; /* Ajusta el ancho del contenedor del ícono */
    height: 40px; /* Ajusta la altura del contenedor del ícono */
}

.icono-salida img {
    width: 100%; /* Asegura que la imagen del ícono ocupe el 100% del ancho del contenedor */
    height: 100%; /* Asegura que la imagen del ícono ocupe el 100% de la altura del contenedor */
}

    </style>
</head>
<body>

    <div class="factura-container">
        <div class="factura-title">
            Detalle de Pago
        </div>

        <?php if (!empty($facturas)): ?>
            <?php foreach ($facturas as $factura): ?>
                <div class="factura-detail">
                    <label>Nombre del Cliente:</label>
                    <span><?php echo $factura['cliente_nombre'] . ' ' . $factura['cliente_apellido']; ?></span>
                </div>
                <div class="factura-detail">
                    <label>ID de la Factura:</label>
                    <span><?php echo $factura['id_factura']; ?></span>
                </div>
                <div class="factura-detail">
                    <label>Correo Electrónico:</label>
                    <span><?php echo $factura['correo_electronico']; ?></span>
                </div>
                <div class="factura-detail">
                    <label>Monto Total:</label>
                    <span>$<?php echo number_format($factura['monto_total'], 2); ?></span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No se encontraron facturas para este cliente.</p>
        <?php endif; ?>

        <form method="POST">
            <button type="submit" name="enviar_factura" class="btn">Enviar Factura</button>
        </form>
    </div>
    <a href="MENU1.html" class="icono-salida">
        <img src="regresar.png" alt="Regresar">
    </a>

</body>
</html>
