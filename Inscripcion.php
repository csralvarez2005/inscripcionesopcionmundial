<?php
class Inscripcion {
    private $conn;
    private $table_name = "inscripciones";

    public $tipoDocumento;
    public $numeroDocumento;
    public $nombre;
    public $barrio;
    public $direccion;
    public $telefono;
    public $correo;
    public $fechaNacimiento;
    public $sisben;
    public $puntajeSisben;
    public $victima;
    public $discapacidad;
    public $tipoDiscapacidad;
    public $identidad;
    public $escolaridad;
    public $hastaQueGrado;
    public $nombreAcudiente;
    public $telefonoAcudiente;
    public $correoAcudiente;
    public $barrioAcudiente;
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
        (tipoDocumento, numeroDocumento, nombre, barrio, direccion, telefono, correo, fechaNacimiento, 
        sisben, puntajeSisben, victima, discapacidad, tipoDiscapacidad, identidad, escolaridad, hastaQueGrado, 
        nombreAcudiente, telefonoAcudiente, correoAcudiente, barrioAcudiente, programaEstudio, personaPostula, 
        nombrePostula, telefonoPostula, correoPostula) 
        VALUES 
        (:tipoDocumento, :numeroDocumento, :nombre, :barrio, :direccion, :telefono, :correo, :fechaNacimiento, 
        :sisben, :puntajeSisben, :victima, :discapacidad, :tipoDiscapacidad, :identidad, :escolaridad, :hastaQueGrado, 
        :nombreAcudiente, :telefonoAcudiente, :correoAcudiente, :barrioAcudiente, :programaEstudio, :personaPostula, 
        :nombrePostula, :telefonoPostula, :correoPostula)";

        $stmt = $this->conn->prepare($query);

        // Aplicar sanitización
        $this->tipoDocumento = $this->sanitize($this->tipoDocumento);
        $this->numeroDocumento = $this->sanitize($this->numeroDocumento);
        $this->nombre = $this->sanitize($this->nombre);
        $this->barrio = $this->sanitize($this->barrio);
        $this->direccion = $this->sanitize($this->direccion);
        $this->telefono = $this->sanitize($this->telefono);
        $this->correo = $this->sanitize($this->correo);
        $this->fechaNacimiento = $this->sanitize($this->fechaNacimiento);
        $this->sisben = $this->sanitize($this->sisben);
        $this->puntajeSisben = $this->sanitize($this->puntajeSisben);
        $this->victima = $this->sanitize($this->victima);
        $this->discapacidad = $this->sanitize($this->discapacidad);
        $this->tipoDiscapacidad = $this->sanitize($this->tipoDiscapacidad);
        $this->identidad = $this->sanitize($this->identidad);
        $this->escolaridad = $this->sanitize($this->escolaridad);
        $this->hastaQueGrado = $this->sanitize($this->hastaQueGrado);
        $this->nombreAcudiente = $this->sanitize($this->nombreAcudiente);
        $this->telefonoAcudiente = $this->sanitize($this->telefonoAcudiente);
        $this->correoAcudiente = $this->sanitize($this->correoAcudiente);
        $this->barrioAcudiente = $this->sanitize($this->barrioAcudiente);
        $this->programaEstudio = $this->sanitize($this->programaEstudio);
        $this->personaPostula = $this->sanitize($this->personaPostula);

        // Validar y asignar valores predeterminados si no se proporciona nombrePostula
        $this->nombrePostula = !empty($this->sanitize($this->nombrePostula)) ? $this->sanitize($this->nombrePostula) : "No especificado";
        $this->telefonoPostula = !empty($this->sanitize($this->telefonoPostula)) ? $this->sanitize($this->telefonoPostula) : "No especificado";
        $this->correoPostula = !empty($this->sanitize($this->correoPostula)) ? $this->sanitize($this->correoPostula) : "No especificado";

        // Asignar valores a los parámetros
        $stmt->bindParam(":tipoDocumento", $this->tipoDocumento);
        $stmt->bindParam(":numeroDocumento", $this->numeroDocumento);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":barrio", $this->barrio);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":correo", $this->correo);
        $stmt->bindParam(":fechaNacimiento", $this->fechaNacimiento);
        $stmt->bindParam(":sisben", $this->sisben);
        $stmt->bindParam(":puntajeSisben", $this->puntajeSisben);
        $stmt->bindParam(":victima", $this->victima);
        $stmt->bindParam(":discapacidad", $this->discapacidad);
        $stmt->bindParam(":tipoDiscapacidad", $this->tipoDiscapacidad);
        $stmt->bindParam(":identidad", $this->identidad);
        $stmt->bindParam(":escolaridad", $this->escolaridad);
        $stmt->bindParam(":hastaQueGrado", $this->hastaQueGrado);
        $stmt->bindParam(":nombreAcudiente", $this->nombreAcudiente);
        $stmt->bindParam(":telefonoAcudiente", $this->telefonoAcudiente);
        $stmt->bindParam(":correoAcudiente", $this->correoAcudiente);
        $stmt->bindParam(":barrioAcudiente", $this->barrioAcudiente);
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