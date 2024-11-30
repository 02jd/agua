<?php
// login.php

// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root"; // Cambia esto según tu configuración
$password = ""; // Cambia esto según tu configuración
$dbname = "proyectoagua"; // Nombre de tu base de datos

// Conexión con la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Comprobar si se recibieron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Numero_cuenta = $_POST['Numero_cuenta'];
    $contraseña = $_POST['contraseña'];

    // Prevenir inyección SQL utilizando declaraciones preparadas
    $stmt = $conn->prepare("SELECT * FROM banco WHERE Numero_cuenta = ?");
    $stmt->bind_param("s", $Numero_cuenta); // 's' indica que el parámetro es una cadena

    // Ejecutar la consulta
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si la cuenta existe
    if ($result->num_rows > 0) {
        // Si existe la cuenta, obtener los datos
        $row = $result->fetch_assoc();
        
        // Verificar la contraseña
        if ($contraseña === $row['contraseña']) {
            // Contraseña correcta, redirigir a otra página
            header("Location: pago.html"); // Reemplaza con la página a la que quieres redirigir
            exit();
        } else {
            // Contraseña incorrecta
            // echo "Contraseña incorrecta.";
            echo "<script>alert('Credenciales correcta'); window.location.href='PDF.PHP';</script>";
        }
    } else {
        // Cuenta no existe
        echo "Número de cuenta no encontrado.";
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
}
?>
