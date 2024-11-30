<?php
session_start(); // Iniciar sesión

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_cliente'])) {
    header('Location: login.html'); // Si no está logueado, redirigir al login
    exit();
}

$id_cliente = $_SESSION['id_cliente']; // Obtener el id_cliente desde la sesión

// Datos de conexión a la base de datos
$servidor = "localhost"; // o la IP del servidor
$usuario = "root";       // usuario de la base de datos
$contraseña = "";        // contraseña de la base de datos
$base_datos = "proyectoagua"; // nombre de la base de datos

// Crear conexión
$conexion = new mysqli($servidor, $usuario, $contraseña, $base_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Consulta SQL para obtener los datos de la factura y del cliente
$sql = "
    SELECT f.id_factura, f.numero_factura, f.id_cliente, f.lectura_anterior, f.lectura_actual, 
           f.consumo, f.id_servicio, f.monto_pendiente, f.monto_actual, f.monto_total, 
           f.cancelado_factura, f.anulada_factura, f.tipo_pago, 
           c.Nombre AS cliente_nombre, c.Apellido AS cliente_apellido, c.correo_electronico, 
           c.Telefono 
    FROM facturas f
    JOIN clientes c ON f.id_cliente = c.Id_cliente 
    WHERE f.id_cliente = ?";

// Usamos consultas preparadas para evitar inyección SQL
$stmt = $conexion->prepare($sql);
$stmt->bind_param('i', $id_cliente); // 'i' indica que el parámetro es un entero
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encontró la factura
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    $row = null;
    echo "<p>No se encontró la factura con el ID del cliente: $id_cliente</p>";
}

// Cerrar la conexión
$stmt->close();
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Factura.css">
    <title>Detalles de la Factura</title>
</head>
<body>
    <div class="container">
        <h2>Detalles de la Factura</h2>

        <?php if ($row): ?>
        <table>
            <tr>
                <th>ID Factura</th>
                <td><?php echo $row['id_factura']; ?></td>
            </tr>
            <tr>
                <th>Número de Factura</th>
                <td><?php echo $row['numero_factura']; ?></td>
            </tr>
            <tr>
                <th>Cliente</th>
                <td><?php echo $row['cliente_nombre'] . " " . $row['cliente_apellido']; ?></td>
            </tr>
            <tr>
                <th>Correo Electrónico</th>
                <td><?php echo $row['correo_electronico']; ?></td>
            </tr>
            <tr>
                <th>Teléfono</th>
                <td><?php echo $row['Telefono']; ?></td>
            </tr>
            <tr>
                <th>Lectura Anterior</th>
                <td><?php echo $row['lectura_anterior']; ?></td>
            </tr>
            <tr>
                <th>Lectura Actual</th>
                <td><?php echo $row['lectura_actual']; ?></td>
            </tr>
            <tr>
                <th>Consumo (m³)</th>
                <td><?php echo $row['consumo']; ?></td>
            </tr>
            <tr>
                <th>Monto Pendiente</th>
                <td>$<?php echo $row['monto_pendiente']; ?></td>
            </tr>
            <tr>
                <th>Monto Actual</th>
                <td>$<?php echo $row['monto_actual']; ?></td>
            </tr>
            <tr>
                <th>Monto Total</th>
                <td>$<?php echo $row['monto_total']; ?></td>
            </tr>
            <tr>
                <th>Cancelado</th>
                <td><?php echo $row['cancelado_factura'] == 'n' ? 'No' : 'Sí'; ?></td>
            </tr>
            <tr>
                <th>Anulada</th>
                <td><?php echo $row['anulada_factura'] == 'n' ? 'No' : 'Sí'; ?></td>
            </tr>
            <tr>
                <th>Tipo de Pago</th>
                <td><?php echo $row['tipo_pago']; ?></td>
            </tr>
        </table>
        <?php else: ?>
            <p>No se encontraron facturas para este cliente.</p>
        <?php endif; ?>

        
    </div>
    <!-- Botones Centrados -->
    <div class="button-container">
            <a href="menu.html" class="btn">Volver al Menú</a>
            <a href="pago.html" class="btn">Realizar Pago</a>
        </div>
</body>
</html>
