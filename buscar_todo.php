<?php
$host = "localhost"; 
$usuario = "root";   
$contraseña = "";   
$base_de_datos = "proyectoagua"; 

$conexion = new mysqli($host, $usuario, $contraseña, $base_de_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Capturar el número ingresado para la búsqueda
$numero_cliente = $_POST['numero_cliente'] ?? ''; // Número de cliente
$numero_empleado = $_POST['numero_empleado'] ?? ''; // Número de empleado
$codigo_servicio = $_POST['codigo'] ?? ''; // Código de servicio

// Inicializar las variables de resultados
$resultado_cliente = null;
$resultado_empleado = null;
$resultado_servicio = null;

// Variable para rastrear qué tipo de búsqueda se realizó
$tipo_busqueda = '';

if (!empty($numero_cliente)) {
    // Consultar en la tabla clientes
    $sql_cliente = "SELECT * FROM clientes WHERE numero_cliente='$numero_cliente'";
    $resultado_cliente = $conexion->query($sql_cliente);
    $tipo_busqueda = 'clientes';
} elseif (!empty($numero_empleado)) {
    // Consultar en la tabla empleados
    $sql_empleado = "SELECT * FROM empleados WHERE numero_empleado='$numero_empleado'";
    $resultado_empleado = $conexion->query($sql_empleado);
    $tipo_busqueda = 'empleados';
} elseif (!empty($codigo_servicio)) {
    // Consultar en la tabla servicios
    $sql_servicio = "SELECT * FROM servicios WHERE codigo='$codigo_servicio'";
    $resultado_servicio = $conexion->query($sql_servicio);
    $tipo_busqueda = 'servicios';
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Resultados</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #ddd;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #FFFFFF;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color:#000000;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

<h1>Resultados de la Búsqueda</h1>

<?php
// Mostrar resultados de clientes
if ($tipo_busqueda === 'clientes' && $resultado_cliente && $resultado_cliente->num_rows > 0) {
    echo "<h2>Clientes</h2>";
    echo "<table>";
    echo "<tr><th>id_cliente</th><th>Numero_cliente</th><th>Nombre</th><th>Apellido</th><th>Correo_electronico</th><th>Dui</th><th>Id_zona</th><th>Telefono</th></tr>";
    while ($fila = $resultado_cliente->fetch_assoc()) {
        echo "<tr>";
        foreach ($fila as $valor) {
            echo "<td>" . htmlspecialchars($valor) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

// Mostrar resultados de empleados
if ($tipo_busqueda === 'empleados' && $resultado_empleado && $resultado_empleado->num_rows > 0) {
    echo "<h2>Empleados</h2>";
    echo "<table>";
    echo "<tr><th>Id_empleado</th><th>Numero_empleado</th><th>Nombre</th><th>Apellido</th><th>Tipo</th></tr>";
    while ($fila = $resultado_empleado->fetch_assoc()) {
        echo "<tr>";
        foreach ($fila as $valor) {
            echo "<td>" . htmlspecialchars($valor) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

// Mostrar resultados de servicios
if ($tipo_busqueda === 'servicios' && $resultado_servicio && $resultado_servicio->num_rows > 0) {
    echo "<h2>Servicios</h2>";
    echo "<table>";
    echo "<tr><th>id_servicio</th><th>codigo</th><th>nombre</th><th>precio</th></tr>";
    while ($fila = $resultado_servicio->fetch_assoc()) {
        echo "<tr>";
        foreach ($fila as $valor) {
            echo "<td>" . htmlspecialchars($valor) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

// Si no hay resultados en la tabla consultada
if ($tipo_busqueda === 'clientes' && (!$resultado_cliente || $resultado_cliente->num_rows == 0)) {
    echo "<script>alert('No se encontraron resultados.'); window.location.href = 'buscar_todo.html';</script>";
} elseif ($tipo_busqueda === 'empleados' && (!$resultado_empleado || $resultado_empleado->num_rows == 0)) {
    echo "<script>alert('No se encontraron resultados.'); window.location.href = 'buscar_todo.html';</script>";
} elseif ($tipo_busqueda === 'servicios' && (!$resultado_servicio || $resultado_servicio->num_rows == 0)) {
    echo "<script>alert('No se encontraron resultados.'); window.location.href = 'buscar_todo.html';</script>";
}

$conexion->close();
?>

</body>
</html>
