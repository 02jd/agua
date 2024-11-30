<?php
$servidor = 'localhost';
$usuario = 'root';
$contraseña = '';
$base_de_datos = 'empresa';

$conexion = new mysqli($servidor, $usuario, $contraseña, $base_de_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

echo "Pantalla de Bienvenida ";
echo"<br>";
echo"<br>";
echo"<br>";
echo"<br>";
echo"<br>";
echo"<br>";



 echo "<a href='login.html'>Ir a Login</a>"; 


$conexion->close();
?>
