<?php
class Inscripcion {
    private $conn;
    private $table_name = "inscripciones";

    public $nombre;
    public $numeroDocumento;
    public $direccion;
    public $telefono;
    public $sisben;
    public $edad;
    public $victima;
    public $hastaQueGrado;
    public $programaEstudio;
    public $personaPostula;
    public $nombrePostula;
    public $telefonoPostula;
    public $correoPostula;

    public function __construct($db) {
        $this->conn = $db;
    }

    private function sanitize($value) {
        return isset($value) && !empty(trim($value)) ? htmlspecialchars(strip_tags($value)) : null;
    }

    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " 
        (nombre, numeroDocumento, direccion, telefono, sisben, edad, victima, hastaQueGrado, 
        programaEstudio, personaPostula, nombrePostula, telefonoPostula, correoPostula) 
        VALUES 
        (:nombre, :numeroDocumento, :direccion, :telefono, :sisben, :edad, :victima, :hastaQueGrado, 
        :programaEstudio, :personaPostula, :nombrePostula, :telefonoPostula, :correoPostula)";

        $stmt = $this->conn->prepare($query);

        // Sanitizar los datos
        $this->nombre = $this->sanitize($this->nombre);
        $this->numeroDocumento = $this->sanitize($this->numeroDocumento);
        $this->direccion = $this->sanitize($this->direccion);
        $this->telefono = $this->sanitize($this->telefono);
        $this->sisben = $this->sanitize($this->sisben);
        $this->edad = $this->sanitize($this->edad);
        $this->victima = $this->sanitize($this->victima);
        $this->hastaQueGrado = $this->sanitize($this->hastaQueGrado);
        $this->programaEstudio = $this->sanitize($this->programaEstudio);
        $this->personaPostula = $this->sanitize($this->personaPostula);
        $this->nombrePostula = $this->sanitize($this->nombrePostula);
        $this->telefonoPostula = $this->sanitize($this->telefonoPostula);
        $this->correoPostula = $this->sanitize($this->correoPostula);

        // Validaciones adicionales
        if (!is_numeric($this->edad) || $this->edad < 1 || $this->edad > 120) {
            return false; // Edad no válida
        }

        if (!filter_var($this->correoPostula, FILTER_VALIDATE_EMAIL)) {
            return false; // Correo no válido
        }

        // Asignar valores a los parámetros
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":numeroDocumento", $this->numeroDocumento);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":sisben", $this->sisben);
        $stmt->bindParam(":edad", $this->edad);
        $stmt->bindParam(":victima", $this->victima);
        $stmt->bindParam(":hastaQueGrado", $this->hastaQueGrado);
        $stmt->bindParam(":programaEstudio", $this->programaEstudio);
        $stmt->bindParam(":personaPostula", $this->personaPostula);
        $stmt->bindParam(":nombrePostula", $this->nombrePostula);
        $stmt->bindParam(":telefonoPostula", $this->telefonoPostula);
        $stmt->bindParam(":correoPostula", $this->correoPostula);

        // Ejecutar consulta
        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Error en la consulta: " . print_r($stmt->errorInfo(), true));
            return false;
        }
    }
}
?>