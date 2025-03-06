<?php
require_once 'database.php';

// Conectar con la base de datos
$database = new Database();
$db = $database->getConnection();

// Consulta para obtener los datos
$query = "SELECT * FROM inscripciones";
$stmt = $db->prepare($query);
$stmt->execute();
$inscripciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Inscripciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style2.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="custom-container">
            <h2 class="mb-3 text-center">Lista de Inscripciones</h2>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Tipo Documento</th>
                            <th>Número Documento</th>
                            <th>Nombre</th>
                            <th>Barrio</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Fecha Nacimiento</th>
                            <th>Sisbén</th>
                            <th>Puntaje Sisbén</th>
                            <th>Víctima Conflicto</th>
                            <th>Discapacidad</th>
                            <th>Tipo Discapacidad</th>
                            <th>Identidad</th>
                            <th>Escolaridad</th>
                            <th>Hasta Qué Grado</th>
                            <th>Nombre Acudiente</th>
                            <th>Teléfono Acudiente</th>
                            <th>Correo Acudiente</th>
                            <th>Barrio Acudiente</th>
                            <th>Programa Estudio</th>
                            <th>Persona que Postula</th>
                            <th>Nombre de quien Postula</th>
                            <th>Teléfono de quien Postula</th>
                            <th>Correo de quien Postula</th>
                            <th>Fecha Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inscripciones as $inscripcion) : ?>
                            <tr>
                                <td><?= htmlspecialchars($inscripcion['id']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['tipoDocumento']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['numeroDocumento']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['nombre']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['barrio']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['direccion']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['telefono']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['correo']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['fechaNacimiento']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['sisben']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['puntajeSisben']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['victima']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['discapacidad']) ?></td>
                                <td><?= !empty($inscripcion['tipo_discapacidad']) ? htmlspecialchars($inscripcion['tipo_discapacidad']) : "N/A"; ?></td>
                                <td><?= htmlspecialchars($inscripcion['identidad']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['escolaridad']) ?></td>
                                <td><?= !empty($inscripcion['hasta_que_grado']) ? htmlspecialchars($inscripcion['hasta_que_grado']) : "N/A"; ?></td>
                                <td><?= htmlspecialchars($inscripcion['nombreAcudiente']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['telefonoAcudiente']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['correoAcudiente']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['barrioAcudiente']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['programaEstudio']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['personaPostula']) ?></td>
                                <td><?= !empty($inscripcion['nombrePostula']) ? htmlspecialchars($inscripcion['nombrePostula']) : "N/A"; ?></td>
                                <td><?= !empty($inscripcion['telefonoPostula']) ? htmlspecialchars($inscripcion['telefonoPostula']) : "N/A"; ?></td>
                                <td><?= !empty($inscripcion['correoPostula']) ? htmlspecialchars($inscripcion['correoPostula']) : "N/A"; ?></td>
                                <td><?= date("d/m/Y", strtotime($inscripcion['fecha_registro'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div> <!-- Fin table-responsive -->
        </div> <!-- Fin custom-container -->
    </div> <!-- Fin container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>