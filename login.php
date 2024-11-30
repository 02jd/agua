<?php
session_start(); // Iniciar sesión

// Datos de conexión a la base de datos
$servidor = 'localhost';
$usuario = 'root';
$contraseña = '';
$base_de_datos = 'proyectoagua';

$conexion = new mysqli($servidor, $usuario, $contraseña, $base_de_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$correo = $_POST['correo'];
$contraseña = $_POST['contrasena'];

// Consulta para verificar las credenciales del usuario
$query = "SELECT * FROM usuarios WHERE correo_electronico = ? AND contrasena = SHA2(?, 256)";
$stmt = $conexion->prepare($query);
$stmt->bind_param('ss', $correo, $contraseña);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    // Si el usuario existe, obtener los datos del usuario
    $usuario = $resultado->fetch_assoc();
    
    // Verificar si el usuario es un cliente o un administrador
    $admin_users = ['smim053022@gmail.com', 'smim122622@gmail.com', 'smim052722@gmail.com', 'smbd058019@gmail.com'];

    if (in_array($correo, $admin_users)) {
        // Si es administrador, redirigir a la página del administrador
        $_SESSION['id_usuario'] = $usuario['id']; // Guardar id del usuario administrador en la sesión
        echo "<script>alert('Credenciales correctas. Bienvenido Administrador.'); window.location.href='menu_admin.html';</script>";
    } else {
        // Si es cliente, obtener el id_cliente desde la tabla clientes
        $query_cliente = "SELECT id_cliente FROM clientes WHERE correo_electronico = ?";
        $stmt_cliente = $conexion->prepare($query_cliente);
        $stmt_cliente->bind_param('s', $correo);
        $stmt_cliente->execute();
        $resultado_cliente = $stmt_cliente->get_result();

        if ($resultado_cliente->num_rows > 0) {
            // Si existe el cliente, almacenar el id_cliente en la sesión
            $cliente = $resultado_cliente->fetch_assoc();
            $_SESSION['id_cliente'] = $cliente['id_cliente']; // Guardar id_cliente en la sesión
            echo "<script>alert('Credenciales correctas. Bienvenido Cliente.'); window.location.href='factura.php';</script>";
        } else {
            echo "<script>alert('Usuario no encontrado en la base de datos.'); window.location.href='login.html';</script>";
        }

        $stmt_cliente->close();
    }
} else {
    echo "<script>alert('Credenciales incorrectas.'); window.location.href='login.html';</script>";
}

$stmt->close();
$conexion->close();
?>
