<?php
class OperacionesBd {
    // En Render se usan variables de entorno; en local, los valores de XAMPP.
    private function env($nombre, $defecto) {
        $valor = getenv($nombre);
        return $valor === false ? $defecto : $valor;
    }

    public function conexion() {
        $conexion = mysqli_connect(
            $this->env("DB_HOST", "localhost"),
            $this->env("DB_USER", "root"),
            $this->env("DB_PASS", ""),
            $this->env("DB_NAME", "taller_mecanico_checo"),
            (int)$this->env("DB_PORT", "3307") // Puerto de tu MariaDB
        );
        if (!$conexion) {
             die("Error de conexión: " . mysqli_connect_error());
        }
        return $conexion;
    }

    public function guardardatos($sql) {
        $conexion = $this->conexion(); 
        $resultado = mysqli_query($conexion, $sql);
        if (!$resultado) {
            return "Error: " . mysqli_error($conexion);
        }
        return "¡Éxito!"; 
    }

    public function eliminardatos($sql) {
        $conexion = $this->conexion();
        return mysqli_query($conexion, $sql);
    }

    public function actualizadatos($sql) {
        $conexion = $this->conexion();
        return mysqli_query($conexion, $sql);
    }

    public function consultardatos($sql) {
        $conexion = $this->conexion();
        $resultado = mysqli_query($conexion, $sql);
        if ($resultado) {
            return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
        } else {
            return false;
        }
    }

    public function vistas() {
        include('vistas.php');
    }

    public function operaciones_bd() {
        include('Operaciones.php');
    }
} // <--- Esta llave cierra TODA la clase. Es la más importante.

?>