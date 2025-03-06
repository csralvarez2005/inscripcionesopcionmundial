<?php
require_once 'database.php';

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
    public $hastaQueGrado; // Nuevo campo agregado
    public $fechaRegistro;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crear() {
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                      (tipo_documento, numero_documento, nombre, barrio, direccion, telefono, correo, 
                       fecha_nacimiento, sisben, puntaje_sisben, victima_conflicto, discapacidad, tipo_discapacidad, 
                       identidad, escolaridad, hasta_que_grado, fecha_registro) 
                      VALUES 
                      (:tipoDocumento, :numeroDocumento, :nombre, :barrio, :direccion, :telefono, :correo, 
                       :fechaNacimiento, :sisben, :puntajeSisben, :victima, :discapacidad, :tipoDiscapacidad, 
                       :identidad, :escolaridad, :hastaQueGrado, NOW())"; 

            $stmt = $this->conn->prepare($query);

            // Sanitización y validación de datos
            $this->tipoDocumento = htmlspecialchars(strip_tags($this->tipoDocumento));
            $this->numeroDocumento = filter_var($this->numeroDocumento, FILTER_SANITIZE_NUMBER_INT);
            $this->nombre = htmlspecialchars(strip_tags($this->nombre));
            $this->barrio = htmlspecialchars(strip_tags($this->barrio));
            $this->direccion = htmlspecialchars(strip_tags($this->direccion));
            $this->telefono = filter_var($this->telefono, FILTER_SANITIZE_NUMBER_INT);
            $this->correo = filter_var($this->correo, FILTER_SANITIZE_EMAIL);
            $this->fechaNacimiento = htmlspecialchars(strip_tags($this->fechaNacimiento));
            $this->sisben = htmlspecialchars(strip_tags($this->sisben));
            $this->puntajeSisben = filter_var($this->puntajeSisben, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $this->victima = htmlspecialchars(strip_tags($this->victima));
            $this->discapacidad = htmlspecialchars(strip_tags($this->discapacidad));
            $this->tipoDiscapacidad = htmlspecialchars(strip_tags($this->tipoDiscapacidad)); 
            $this->identidad = htmlspecialchars(strip_tags($this->identidad));
            $this->escolaridad = htmlspecialchars(strip_tags($this->escolaridad));
            $this->hastaQueGrado = htmlspecialchars(strip_tags($this->hastaQueGrado));

            // Validación de campos obligatorios
            if (empty($this->tipoDocumento) || empty($this->numeroDocumento) || empty($this->nombre) || 
                empty($this->barrio) || empty($this->direccion) || empty($this->telefono) || 
                empty($this->correo) || empty($this->fechaNacimiento) || empty($this->sisben) || 
                empty($this->victima) || empty($this->discapacidad) || empty($this->identidad) || 
                empty($this->escolaridad)) {
                throw new Exception("Todos los campos obligatorios deben estar llenos.");
            }

            // Validación de hastaQueGrado (solo si escolaridad es Primaria o Bachiller)
            if (in_array($this->escolaridad, ['Primaria', 'Bachiller']) && empty($this->hastaQueGrado)) {
                throw new Exception("Debe especificar hasta qué grado llegó en la escolaridad.");
            }

            // Validación de correo electrónico
            if (!filter_var($this->correo, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Formato de correo electrónico no válido.");
            }

            // Validación de puntaje de Sisbén
            if (!empty($this->puntajeSisben) && !is_numeric($this->puntajeSisben)) {
                throw new Exception("El puntaje del Sisbén debe ser un número válido.");
            }

            // Validación de tipo de discapacidad si se indica que tiene discapacidad
            if ($this->discapacidad === "Sí" && empty($this->tipoDiscapacidad)) {
                throw new Exception("Debe especificar el tipo de discapacidad si selecciona 'Sí'.");
            }

            // Si no tiene discapacidad, asegurarse de que el campo se almacene como vacío
            if ($this->discapacidad === "No") {
                $this->tipoDiscapacidad = "";
            }

            // Si escolaridad no es Primaria o Bachiller, hastaQueGrado debe ser NULL
            if (!in_array($this->escolaridad, ['Primaria', 'Bachiller'])) {
                $this->hastaQueGrado = NULL;
            }

            // Vinculación de parámetros
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

            // Intentar ejecutar la consulta
            if ($stmt->execute()) {
                return true;
            } else {
                throw new Exception("Error al registrar la inscripción.");
            }
        } catch (Exception $e) {
            error_log("Error en la inscripción: " . $e->getMessage());
            return false;
        }
    }
}
?>