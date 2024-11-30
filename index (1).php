<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido.</title>
    <link rel="stylesheet" href="estilo_index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <nav>
        <ul>
            <li>
                <a href="#inicio"><i class="fas fa-bars"></i> Menú</a>
                <ul class="dropdown">
                    <li><a href="Servicios.html">Servicios</a></li>
                    <li><a href="Sobre_nosotros.html">sobre nosotros</a></li>
                    <li><a href="Contacto.html"></i> Contacto</a></li>
                </ul>
            </li>
            
        </ul>
    </nav>
    
    <div class="container">
                <?php
                $servidor = 'localhost';
                $usuario = 'root';
                $contraseña = '';
                $base_de_datos = 'empresa';

                $conexion = new mysqli($servidor, $usuario, $contraseña, $base_de_datos);

                if ($conexion->connect_error) {
                die("Error de conexión: " . $conexion->connect_error);
                }

                echo "<a href='login.html'>Ir a Login</a>";

             $conexion->close();
              ?>
            </div>
    <section id="Mision">
        <h2>Misión</h2>
        <p>Fortalezer el servicio de agua para el consumo humano en nuestra en forma equitativa, así como la recolección y el 
            tratamiento de aguas residuales manteniendo la armonía y cuidado con el medio ambiente..</p>
    </section>
    <section id="Vision">
        <h2>Visión</h2>
        <p> Brindar el servicio de agua para consumo humano y tratamiento de aguas residuales de manera 
            eficiente, siendo responsables con el medio ambiente y 
            comprometidos con el desarrollo y mejora en la calidad de vida de la comunidad.</p>
    </section>
</body>
</html>



