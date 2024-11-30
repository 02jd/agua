<?php
$servidor = 'localhost';
$usuario = 'root';
$contraseña = '';
$base_de_datos = 'proyectoagua';

$conexion = new mysqli($servidor, $usuario, $contraseña, $base_de_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

// Verificar si el correo ya existe
$query_verificar = "SELECT * FROM usuarios WHERE correo_electronico = ?";
$stmt_verificar = $conexion->prepare($query_verificar);
$stmt_verificar->bind_param('s', $correo);
$stmt_verificar->execute();
$resultado = $stmt_verificar->get_result();

$response = [];

if ($resultado->num_rows > 0) {
    $response['mensaje'] = "El correo electrónico ya está registrado.";
    $response['exito'] = false;
} else {
    // Insertar nuevo usuario
    $query_insertar = "INSERT INTO usuarios (nombre, correo_electronico, contrasena) VALUES (?, ?, SHA2(?, 256))";
    $stmt_insertar = $conexion->prepare($query_insertar);
    $stmt_insertar->bind_param('sss', $nombre, $correo, $contrasena);

    if ($stmt_insertar->execute()) {
        $response['mensaje'] = "Usuario registrado exitosamente.";
        $response['exito'] = true;
    } else {
        $response['mensaje'] = "Error al registrar el usuario: " . $stmt_insertar->error;
        $response['exito'] = false;
    }

    $stmt_insertar->close();
}

$stmt_verificar->close();
$conexion->close();

echo json_encode($response);
?>
