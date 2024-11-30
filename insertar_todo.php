<?php
session_start(); // Iniciar la sesión para almacenar mensajes

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$usuario = "root";
$contraseña = "";
$base_de_datos = "proyectoagua";

// Crear la conexión
$conexion = new mysqli($host, $usuario, $contraseña, $base_de_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Insertar cliente
if (isset($_POST['numero_cliente'])) {
    $numero_cliente = $_POST['numero_cliente'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $dui = $_POST['dui'];
    $id_zona = $_POST['id_zona'];
    $telefono = $_POST['telefono'];

    $sql_verificar = "SELECT * FROM clientes WHERE numero_cliente='$numero_cliente'";
    $resultado_verificar = $conexion->query($sql_verificar);

    if ($resultado_verificar->num_rows > 0) {
        echo "<script>alert('El número de cliente ya existe. Por favor, elige otro.'); window.location.href = 'insertar_todo.html';</script>";
    } else {
        $sql = "INSERT INTO clientes (numero_cliente, nombre, apellido, correo_electronico, dui, id_zona, telefono) VALUES ('$numero_cliente', '$nombre', '$apellido', '$correo', '$dui', '$id_zona', '$telefono')";
        if ($conexion->query($sql) === TRUE) {
            echo "<script>alert('Nuevo cliente insertado correctamente'); window.location.href = 'insertar_todo.html';</script>";
        } else {
            echo "<script>alert('Error al insertar el cliente: " . $conexion->error . "'); window.location.href = 'insertar_todo.html';</script>";
        }
    }
}

// Insertar empleado
if (isset($_POST['numero_empleado'])) {
    $numero_empleado = $_POST['numero_empleado'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $tipo = $_POST['tipo'];

    $sql_verificar = "SELECT * FROM empleados WHERE numero_empleado='$numero_empleado'";
    $resultado_verificar = $conexion->query($sql_verificar);

    if ($resultado_verificar->num_rows > 0) {
        echo "<script>alert('El número de empleado ya existe. Por favor, elige otro.'); window.location.href = 'insertar_todo.html';</script>";
    } else {
        $sql = "INSERT INTO empleados (numero_empleado, nombre, apellido, tipo) VALUES ('$numero_empleado', '$nombre', '$apellido', '$tipo')";
        if ($conexion->query($sql) === TRUE) {
            echo "<script>alert('Nuevo empleado insertado correctamente'); window.location.href = 'insertar_todo.html';</script>";
        } else {
            echo "<script>alert('Error al insertar el empleado: " . $conexion->error . "'); window.location.href = 'insertar_todo.html';</script>";
        }
    }
}

// Insertar servicio
if (isset($_POST['codigo'])) {
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];

    $sql_verificar = "SELECT * FROM servicios WHERE codigo='$codigo'";
    $resultado_verificar = $conexion->query($sql_verificar);

    if ($resultado_verificar->num_rows > 0) {
        echo "<script>alert('El número de servicio ya existe. Por favor, elige otro.'); window.location.href = 'insertar_todo.html';</script>";
    } else {
        $sql = "INSERT INTO servicios (codigo, nombre, precio) VALUES ('$codigo', '$nombre', '$precio')";
        if ($conexion->query($sql) === TRUE) {
            echo "<script>alert('Nuevo servicio insertado correctamente'); window.location.href = 'insertar_todo.html';</script>";
        } else {
            echo "<script>alert('Error al insertar el servicio: " . $conexion->error . "'); window.location.href = 'insertar_todo.html';</script>";
        }
    }
}


//insertar usuario
if (isset($_POST['codigo'])) {
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];

    $sql_verificar = "SELECT * FROM usuarios WHERE codigo='$codigo'";
    $resultado_verificar = $conexion->query($sql_verificar);

    if ($resultado_verificar->num_rows > 0) {
        echo "<script>alert('El número de servicio ya existe. Por favor, elige otro.'); window.location.href = 'insertar_todo.html';</script>";
    } else {
        $sql = "INSERT INTO usuarios (codigo, nombre, precio) VALUES ('$codigo', '$nombre', '$precio')";
        if ($conexion->query($sql) === TRUE) {
            echo "<script>alert('Nuevo servicio insertado correctamente'); window.location.href = 'insertar_todo.html';</script>";
        } else {
            echo "<script>alert('Error al insertar el servicio: " . $conexion->error . "'); window.location.href = 'insertar_todo.html';</script>";
        }
    }
}
$conexion->close();
?>
