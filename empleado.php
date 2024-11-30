<?php
class Servicio {
  
    private $conexion;

    public function __construct($host = "localhost", $usuario = "root", $contraseña = "", $bd = "proyectoagua") {
        $this->conexion = new mysqli($host, $usuario, $contraseña, $bd);
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    public function insertarServicio($codigo, $nombre, $precio) {
        $sql ="INSERT INTO servicios (codigo, nombre, precio) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssddd",$codigo, $nombre, $precio);
        return $stmt->execute();
    }

    public function obtenerServicio() {
        $sql = "SELECT * FROM servicios";
        $resultado = $this->conexion->query($sql);
        $servicio = [];
        while ($fila = $resultado->fetch_assoc()) {
            $servicio[] = $fila;
        }
        return $servicio;
    }

    public function obtenerServicioPorCodigo($codigo) {
        $sql = "SELECT * FROM servicios WHERE codigo = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $codigo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function actualizarServicio($codigo, $nombre, $precio) {
        $sql = "UPDATE servicios SET codigo = ?, nombre = ?, precio = ?  WHERE codigo = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssdddi", $codigo, $nombre, $precio);
        return $stmt->execute();
    }

    public function eliminarServicio($codigo) {
        $sql = "DELETE FROM servicios WHERE codigo = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $codigo);
        return $stmt->execute();
    }

    public function __destruct() {
        $this->conexion->close();
    }
}
?>
