<?php
class database {
    private $host = "localhost"; // Servidor de la base de datos
    private $db_name = "fundacion"; // Nombre de la base de datos
    private $username = "root"; // Usuario de la base de datos
    private $password = ""; // Contraseña de la base de datos
    public $conn;

    // Método para conectar a la base de datos
    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>