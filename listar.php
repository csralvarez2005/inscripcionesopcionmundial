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
    <style>
        .custom-container {
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            max-width: 100%;
            overflow: hidden;
        }

        .table-responsive {
            max-height: 400px; /* Altura máxima con scroll */
            overflow-y: auto;  /* Scroll vertical */
            overflow-x: auto;  /* Scroll horizontal si es necesario */
        }
    </style>
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
                            <th>Tipo Discapacidad</th> <!-- NUEVA COLUMNA -->
                            <th>Identidad</th>
                            <th>Escolaridad</th>
                            <th>Fecha Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inscripciones as $inscripcion) : ?>
                            <tr>
                                <td><?= htmlspecialchars($inscripcion['id']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['tipo_documento']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['numero_documento']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['nombre']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['barrio']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['direccion']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['telefono']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['correo']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['fecha_nacimiento']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['sisben']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['puntaje_sisben']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['victima_conflicto']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['discapacidad']) ?></td>
                                <td>
                                    <?= !empty($inscripcion['tipo_discapacidad']) ? htmlspecialchars($inscripcion['tipo_discapacidad']) : "N/A"; ?>
                                </td> <!-- NUEVO CAMPO -->
                                <td><?= htmlspecialchars($inscripcion['identidad']) ?></td>
                                <td><?= htmlspecialchars($inscripcion['escolaridad']) ?></td>
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