<?php
require 'Empleado.php'; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];

    $servicio = new Servicio(); 
    $resultado = $servicio->insertarServicio($codigo, $nombre, $precio);

    if ($resultado) {
        echo "Servicios agregado exitosamente.";
    } else {
        echo "Error al agregar el servicios.";
    }
}
?>