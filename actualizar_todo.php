<?php
session_start(); // Iniciar la sesión para almacenar mensajes

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost"; 
$usuario = "root";   
$contraseña = "";   
$base_de_datos = "proyectoagua"; 

$conexion = new mysqli($host, $usuario, $contraseña, $base_de_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Actualizar cliente
    if (isset($_POST['numero_cliente'])) {
        $numero_cliente = $_POST['numero_cliente'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $correo_electronico = $_POST['correo'];
        $dui = $_POST['dui'];
        $id_zona = $_POST['id_zona'];
        $telefono = $_POST['telefono'];
        
        $sql_verificar = "SELECT * FROM clientes WHERE numero_cliente='$numero_cliente'";
        $resultado_verificar = $conexion->query($sql_verificar);
        
        if ($resultado_verificar->num_rows > 0) {
            $sql = "UPDATE clientes SET nombre='$nombre', apellido='$apellido', correo_electronico='$correo_electronico', dui='$dui', id_zona='$id_zona', telefono='$telefono' WHERE numero_cliente='$numero_cliente'";
            if ($conexion->query($sql) === TRUE) {
                echo "<script>alert('Cliente actualizado correctamente.'); window.location.href = 'actualizar_todo.html';</script>";
            } else {
                echo "<script>alert('Error al actualizar el cliente: " . $conexion->error . "'); window.location.href = 'actualizar_todo.html';</script>";
            }
        } else {
            echo "<script>alert('Número de cliente no encontrado.'); window.location.href = 'actualizar_todo.html';</script>";
        }
    }
    
    // Actualizar empleado
    if (isset($_POST['numero_empleado'])) {
        $numero_empleado = $_POST['numero_empleado'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $tipo = $_POST['tipo'];
        
        $sql_verificar = "SELECT * FROM empleados WHERE numero_empleado='$numero_empleado'";
        $resultado_verificar = $conexion->query($sql_verificar);
        
        if ($resultado_verificar->num_rows > 0) {
            $sql = "UPDATE empleados SET nombre='$nombre', apellido='$apellido', tipo='$tipo' WHERE numero_empleado='$numero_empleado'";
            if ($conexion->query($sql) === TRUE) {
                echo "<script>alert('Empleado actualizado correctamente.'); window.location.href = 'actualizar_todo.html';</script>";
            } else {
                echo "<script>alert('Error al actualizar el empleado: " . $conexion->error . "'); window.location.href = 'actualizar_todo.html';</script>";
            }
        } else {
            echo "<script>alert('Número de empleado no encontrado.'); window.location.href = 'actualizar_todo.html';</script>";
        }
    }
    
    // Actualizar servicio
    if (isset($_POST['codigo'])) {
        $codigo = $_POST['codigo'];
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        
        $sql_verificar = "SELECT * FROM servicios WHERE codigo='$codigo'";
        $resultado_verificar = $conexion->query($sql_verificar);
        
        if ($resultado_verificar->num_rows > 0) {
            $sql = "UPDATE servicios SET nombre='$nombre', precio='$precio' WHERE codigo='$codigo'";
            if ($conexion->query($sql) === TRUE) {
                echo "<script>alert('Servicio actualizado correctamente.'); window.location.href = 'actualizar_todo.html';</script>";
            } else {
                echo "<script>alert('Error al actualizar el servicio: " . $conexion->error . "'); window.location.href = 'actualizar_todo.html';</script>";
            }
        } else {
            echo "<script>alert('Número de servicio no encontrado.'); window.location.href = 'actualizar_todo.html';</script>";
        }
    }
}

$conexion->close();
?>
