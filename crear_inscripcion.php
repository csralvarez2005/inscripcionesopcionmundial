<?php
require_once 'database.php';
require_once 'Inscripcion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Campos requeridos básicos
    $requiredFields = [
        'tipoDocumento', 'numeroDocumento', 'nombre', 'barrio', 'direccion',
        'telefono', 'correo', 'fechaNacimiento', 'sisben', 'puntajeSisben',
        'victima', 'discapacidad', 'identidad', 'escolaridad'
    ];

    // Verificar si escolaridad es primaria o bachiller para requerir hastaQueGrado
    if (isset($_POST['escolaridad']) && in_array($_POST['escolaridad'], ['Primaria', 'Bachiller'])) {
        $requiredFields[] = 'hastaQueGrado';
    }

    // Verificación de campos requeridos
    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            echo json_encode(["status" => "error", "message" => "Falta el campo: $field"]);
            exit;
        }
    }

    $database = new Database();
    $db = $database->getConnection();
    $inscripcion = new Inscripcion($db);

    // Sanitización de los datos
    $inscripcion->tipoDocumento = htmlspecialchars(strip_tags($_POST['tipoDocumento']));
    $inscripcion->numeroDocumento = htmlspecialchars(strip_tags($_POST['numeroDocumento']));
    $inscripcion->nombre = htmlspecialchars(strip_tags($_POST['nombre']));
    $inscripcion->barrio = htmlspecialchars(strip_tags($_POST['barrio']));
    $inscripcion->direccion = htmlspecialchars(strip_tags($_POST['direccion']));
    $inscripcion->telefono = htmlspecialchars(strip_tags($_POST['telefono']));
    $inscripcion->correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $inscripcion->fechaNacimiento = htmlspecialchars(strip_tags($_POST['fechaNacimiento']));
    $inscripcion->sisben = htmlspecialchars(strip_tags($_POST['sisben']));
    $inscripcion->puntajeSisben = htmlspecialchars(strip_tags($_POST['puntajeSisben']));
    $inscripcion->victima = htmlspecialchars(strip_tags($_POST['victima']));
    $inscripcion->discapacidad = htmlspecialchars(strip_tags($_POST['discapacidad']));
    $inscripcion->identidad = htmlspecialchars(strip_tags($_POST['identidad']));
    $inscripcion->escolaridad = htmlspecialchars(strip_tags($_POST['escolaridad']));

    // Manejo del campo tipoDiscapacidad
    $inscripcion->tipoDiscapacidad = (strtolower($_POST['discapacidad']) === "sí" && isset($_POST['tipoDiscapacidad'])) 
        ? htmlspecialchars(strip_tags($_POST['tipoDiscapacidad'])) 
        : NULL;

    // Manejo del campo hastaQueGrado
    if (isset($_POST['hastaQueGrado']) && !empty(trim($_POST['hastaQueGrado']))) {
        $inscripcion->hastaQueGrado = htmlspecialchars(strip_tags($_POST['hastaQueGrado']));
    } else {
        $inscripcion->hastaQueGrado = NULL; // Si no aplica, se deja NULL
    }

    // Intentar crear la inscripción
    if ($inscripcion->crear()) {
        echo json_encode(["status" => "success", "message" => "Inscripción creada exitosamente"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al crear la inscripción"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
}
?>