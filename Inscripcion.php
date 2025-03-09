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
    public $hastaQueGrado;
    public $porcentajeBeca;
    public $programaEstudio;
    public $horariosDisponibles;
    public $nombrePostula;
    public $telefonoPostula;
    public $correoPostula;
    public $personaPostula; // Nuevo campo agregado

    public function __construct($db) {
        $this->conn = $db;
    }

    private function sanitize($value, $default = "") {
        return isset($value) && !empty(trim($value)) ? htmlspecialchars(strip_tags($value)) : $default;
    }

    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " 
        (nombre, numeroDocumento, direccion, telefono, sisben, edad, hastaQueGrado, 
        porcentajeBeca, programaEstudio, horariosDisponibles, nombrePostula, telefonoPostula, correoPostula, personaPostula) 
        VALUES 
        (:nombre, :numeroDocumento, :direccion, :telefono, :sisben, :edad, :hastaQueGrado, 
        :porcentajeBeca, :programaEstudio, :horariosDisponibles, :nombrePostula, :telefonoPostula, :correoPostula, :personaPostula)";

        $stmt = $this->conn->prepare($query);

        // Sanitizar los datos
        $this->nombre = $this->sanitize($this->nombre);
        $this->numeroDocumento = $this->sanitize($this->numeroDocumento);
        $this->direccion = $this->sanitize($this->direccion);
        $this->telefono = $this->sanitize($this->telefono);
        $this->sisben = $this->sanitize($this->sisben, "No Aplica");
        $this->edad = $this->sanitize($this->edad);
        $this->hastaQueGrado = $this->sanitize($this->hastaQueGrado);
        $this->porcentajeBeca = $this->sanitize($this->porcentajeBeca);
        $this->programaEstudio = $this->sanitize($this->programaEstudio);
        $this->horariosDisponibles = $this->sanitize($this->horariosDisponibles);
        $this->nombrePostula = $this->sanitize($this->nombrePostula);
        $this->telefonoPostula = $this->sanitize($this->telefonoPostula);
        $this->correoPostula = $this->sanitize($this->correoPostula);
        $this->personaPostula = $this->sanitize($this->personaPostula); // Sanitización del nuevo campo

        // Validaciones adicionales
        if (!is_numeric($this->edad) || $this->edad < 1 || $this->edad > 120) {
            error_log("Error: Edad inválida");
            return false;
        }

        if (!filter_var($this->correoPostula, FILTER_VALIDATE_EMAIL)) {
            error_log("Error: Formato de correo inválido");
            return false;
        }

        // Asignar valores a los parámetros
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":numeroDocumento", $this->numeroDocumento);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":sisben", $this->sisben);
        $stmt->bindParam(":edad", $this->edad);
        $stmt->bindParam(":hastaQueGrado", $this->hastaQueGrado);
        $stmt->bindParam(":porcentajeBeca", $this->porcentajeBeca);
        $stmt->bindParam(":programaEstudio", $this->programaEstudio);
        $stmt->bindParam(":horariosDisponibles", $this->horariosDisponibles);
        $stmt->bindParam(":nombrePostula", $this->nombrePostula);
        $stmt->bindParam(":telefonoPostula", $this->telefonoPostula);
        $stmt->bindParam(":correoPostula", $this->correoPostula);
        $stmt->bindParam(":personaPostula", $this->personaPostula); // Nuevo campo añadido a la consulta

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