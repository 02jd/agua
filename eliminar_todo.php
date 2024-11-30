<?php
session_start(); // Iniciar la sesión
$host = "localhost"; 
$usuario = "root";   
$contraseña = "";   
$base_de_datos = "proyectoagua"; 

// Crear conexión
$conexion = new mysqli($host, $usuario, $contraseña, $base_de_datos);

// Verificar conexión
if ($conexion->connect_error) {
    echo "<script>alert('Error de conexión: " . $conexion->connect_error . "'); window.location.href = 'eliminar_todo.html';</script>";
    exit();
}

// Capturar los datos de eliminación desde el formulario
$numero_cliente = $_POST['numero_cliente'] ?? '';
$numero_empleado = $_POST['numero_empleado'] ?? '';
$codigo = $_POST['codigo'] ?? '';

if (!empty($numero_cliente)) {
    // Verificar si el cliente existe
    $verificar_sql = "SELECT * FROM clientes WHERE numero_cliente='$numero_cliente'";
    $verificacion = $conexion->query($verificar_sql);

    if ($verificacion && $verificacion->num_rows > 0) {
        // Eliminar cliente
        $sql = "DELETE FROM clientes WHERE numero_cliente='$numero_cliente'";
        if ($conexion->query($sql) === TRUE) {
            echo "<script>alert('Cliente eliminado correctamente.'); window.location.href = 'eliminar_todo.html';</script>";
        } else {
            echo "<script>alert('Error al eliminar el cliente: " . $conexion->error . "'); window.location.href = 'eliminar_todo.html';</script>";
        }
    } else {
        echo "<script>alert('El cliente no existe.'); window.location.href = 'eliminar_todo.html';</script>";
    }
} elseif (!empty($numero_empleado)) {
    // Verificar si el empleado existe
    $verificar_sql = "SELECT * FROM empleados WHERE numero_empleado='$numero_empleado'";
    $verificacion = $conexion->query($verificar_sql);

    if ($verificacion && $verificacion->num_rows > 0) {
        // Eliminar empleado
        $sql = "DELETE FROM empleados WHERE numero_empleado='$numero_empleado'";
        if ($conexion->query($sql) === TRUE) {
            echo "<script>alert('Empleado eliminado correctamente.'); window.location.href = 'eliminar_todo.html';</script>";
        } else {
            echo "<script>alert('Error al eliminar el empleado: " . $conexion->error . "'); window.location.href = 'eliminar_todo.html';</script>";
        }
    } else {
        echo "<script>alert('El empleado no existe.'); window.location.href = 'eliminar_todo.html';</script>";
    }
} elseif (!empty($codigo)) {
    // Verificar si el servicio existe
    $verificar_sql = "SELECT * FROM servicios WHERE codigo='$codigo'";
    $verificacion = $conexion->query($verificar_sql);

    if ($verificacion && $verificacion->num_rows > 0) {
        // Eliminar servicio
        $sql = "DELETE FROM servicios WHERE codigo='$codigo'";
        if ($conexion->query($sql) === TRUE) {
            echo "<script>alert('Servicio eliminado correctamente.'); window.location.href = 'eliminar_todo.html';</script>";
        } else {
            echo "<script>alert('Error al eliminar el servicio: " . $conexion->error . "'); window.location.href = 'eliminar_todo.html';</script>";
        }
    } else {
        echo "<script>alert('El servicio no existe.'); window.location.href = 'eliminar_todo.html';</script>";
    }
} else {
    echo "<script>alert('No se proporcionó un dato válido para eliminar.'); window.location.href = 'eliminar_todo.html';</script>";
}

// Cerrar conexión
$conexion->close();
exit();
?>
