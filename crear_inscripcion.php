<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
require_once 'database.php';
require_once 'Inscripcion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredFields = [
        'nombre', 'numeroDocumento', 'direccion', 'telefono', 'sisben', 
        'edad', 'hastaQueGrado', 'porcentajeBeca', 'programaEstudio', 
        'horariosDisponibles', 'nombrePostula', 'telefonoPostula', 'correoPostula', 'personaPostula' // Agregado nuevo campo
    ];

    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Falta el campo: $field'
                    });
                  </script>";
            exit;
        }
    }

    $database = new Database();
    $db = $database->getConnection();
    $inscripcion = new Inscripcion($db);

    $inscripcion->nombre = htmlspecialchars(strip_tags($_POST['nombre']));
    $inscripcion->numeroDocumento = filter_var($_POST['numeroDocumento'], FILTER_SANITIZE_NUMBER_INT);
    $inscripcion->direccion = htmlspecialchars(strip_tags($_POST['direccion']));
    $inscripcion->telefono = filter_var($_POST['telefono'], FILTER_SANITIZE_NUMBER_INT);
    $inscripcion->sisben = isset($_POST['sisben']) ? htmlspecialchars(strip_tags($_POST['sisben'])) : null;
    $inscripcion->edad = filter_var($_POST['edad'], FILTER_SANITIZE_NUMBER_INT);
    $inscripcion->hastaQueGrado = htmlspecialchars(strip_tags($_POST['hastaQueGrado']));
    $inscripcion->porcentajeBeca = htmlspecialchars(strip_tags($_POST['porcentajeBeca']));
    $inscripcion->programaEstudio = htmlspecialchars(strip_tags($_POST['programaEstudio']));
    $inscripcion->horariosDisponibles = htmlspecialchars(strip_tags($_POST['horariosDisponibles']));
    $inscripcion->nombrePostula = htmlspecialchars(strip_tags($_POST['nombrePostula']));
    $inscripcion->telefonoPostula = filter_var($_POST['telefonoPostula'], FILTER_SANITIZE_NUMBER_INT);
    $inscripcion->correoPostula = filter_var($_POST['correoPostula'], FILTER_SANITIZE_EMAIL);
    $inscripcion->personaPostula = htmlspecialchars(strip_tags($_POST['personaPostula'])); // Nuevo campo agregado

    // Validaciones específicas
    if (!filter_var($inscripcion->correoPostula, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Formato de correo no válido'
                });
              </script>";
        exit;
    }

    if (!is_numeric($inscripcion->edad) || $inscripcion->edad < 1 || $inscripcion->edad > 120) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ingrese una edad válida entre 1 y 120 años'
                });
              </script>";
        exit;
    }

    if ($inscripcion->crear()) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Inscripción creada exitosamente',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'listar.php'; 
                });
              </script>";
    } else {
        error_log("Error al registrar la inscripción en la base de datos.");
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al crear la inscripción'
                });
              </script>";
    }
} else {
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Método no permitido'
            });
          </script>";
}
?>