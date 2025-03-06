<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'database.php';
require_once 'Inscripcion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredFields = [
        'tipoDocumento', 'numeroDocumento', 'nombre', 'barrio', 'direccion',
        'telefono', 'correo', 'fechaNacimiento', 'sisben', 'puntajeSisben',
        'victima', 'discapacidad', 'identidad', 'escolaridad',
        'nombreAcudiente', 'telefonoAcudiente', 'correoAcudiente', 'barrioAcudiente',
        'programaEstudio', 'personaPostula'
    ];

    if (isset($_POST['escolaridad']) && in_array($_POST['escolaridad'], ['Primaria', 'Bachiller'])) {
        $requiredFields[] = 'hastaQueGrado';
    }

    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            echo json_encode(["status" => "error", "message" => "Falta el campo: $field"]);
            exit;
        }
    }

    $database = new Database();
    $db = $database->getConnection();
    $inscripcion = new Inscripcion($db);

    $inscripcion->tipoDocumento = htmlspecialchars(strip_tags($_POST['tipoDocumento']));
    $inscripcion->numeroDocumento = filter_var($_POST['numeroDocumento'], FILTER_SANITIZE_NUMBER_INT);
    $inscripcion->nombre = htmlspecialchars(strip_tags($_POST['nombre']));
    $inscripcion->barrio = htmlspecialchars(strip_tags($_POST['barrio']));
    $inscripcion->direccion = htmlspecialchars(strip_tags($_POST['direccion']));
    $inscripcion->telefono = filter_var($_POST['telefono'], FILTER_SANITIZE_NUMBER_INT);
    $inscripcion->correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $inscripcion->fechaNacimiento = htmlspecialchars(strip_tags($_POST['fechaNacimiento']));
    $inscripcion->sisben = htmlspecialchars(strip_tags($_POST['sisben']));
    $inscripcion->puntajeSisben = filter_var($_POST['puntajeSisben'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $inscripcion->victima = htmlspecialchars(strip_tags($_POST['victima']));
    $inscripcion->discapacidad = htmlspecialchars(strip_tags($_POST['discapacidad']));
    $inscripcion->identidad = htmlspecialchars(strip_tags($_POST['identidad']));
    $inscripcion->escolaridad = htmlspecialchars(strip_tags($_POST['escolaridad']));
    $inscripcion->tipoDiscapacidad = ($inscripcion->discapacidad === "Sí" && !empty(trim($_POST['tipoDiscapacidad'])))
        ? htmlspecialchars(strip_tags($_POST['tipoDiscapacidad']))
        : NULL;
    $inscripcion->hastaQueGrado = isset($_POST['hastaQueGrado']) && !empty(trim($_POST['hastaQueGrado']))
        ? htmlspecialchars(strip_tags($_POST['hastaQueGrado']))
        : NULL;
    $inscripcion->nombreAcudiente = htmlspecialchars(strip_tags($_POST['nombreAcudiente']));
    $inscripcion->telefonoAcudiente = filter_var($_POST['telefonoAcudiente'], FILTER_SANITIZE_NUMBER_INT);
    $inscripcion->correoAcudiente = filter_var($_POST['correoAcudiente'], FILTER_SANITIZE_EMAIL);
    $inscripcion->barrioAcudiente = htmlspecialchars(strip_tags($_POST['barrioAcudiente']));
    $inscripcion->programaEstudio = htmlspecialchars(strip_tags($_POST['programaEstudio']));
    $inscripcion->personaPostula = !empty(trim($_POST['personaPostula']))
        ? htmlspecialchars(strip_tags($_POST['personaPostula']))
        : "No especificado";

    $inscripcion->nombrePostula = !empty(trim($_POST['nombrePostula']))
        ? htmlspecialchars(strip_tags($_POST['nombrePostula']))
        : NULL;
    $inscripcion->telefonoPostula = !empty(trim($_POST['telefonoPostula']))
        ? filter_var($_POST['telefonoPostula'], FILTER_SANITIZE_NUMBER_INT)
        : NULL;
    $inscripcion->correoPostula = !empty(trim($_POST['correoPostula']))
        ? filter_var($_POST['correoPostula'], FILTER_SANITIZE_EMAIL)
        : NULL;

    if (!filter_var($inscripcion->correo, FILTER_VALIDATE_EMAIL) || ($inscripcion->correoPostula && !filter_var($inscripcion->correoPostula, FILTER_VALIDATE_EMAIL))) {
        echo json_encode(["status" => "error", "message" => "Formato de correo no válido"]);
        exit;
    }

    if (!is_numeric($inscripcion->puntajeSisben)) {
        echo json_encode(["status" => "error", "message" => "El puntaje del Sisbén debe ser un número válido"]);
        exit;
    }

    if ($inscripcion->discapacidad === "Sí" && empty($inscripcion->tipoDiscapacidad)) {
        echo json_encode(["status" => "error", "message" => "Debe especificar el tipo de discapacidad"]);
        exit;
    }

    if ($inscripcion->crear()) {
        echo json_encode(["status" => "success", "message" => "Inscripción creada exitosamente"]);
    } else {
        error_log("Error al registrar la inscripción en la base de datos.");
        echo json_encode(["status" => "error", "message" => "Error al crear la inscripción"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
}
?>
