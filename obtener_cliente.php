<?php
$host = "localhost"; 
$usuario = "root";   
$contraseña = "";   
$base_de_datos = "proyectoagua"; 
$id_cliente = $_GET['id_cliente'];
// Realiza la conexión a la base de datos
$conn = new mysqli($host, $usuario, $contraseña, $base_de_datos);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT * FROM clientes WHERE id_cliente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$result = $stmt->get_result();

$clientes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row;
    }
}

echo json_encode($clientes);

$stmt->close();
$conn->close();
?>
