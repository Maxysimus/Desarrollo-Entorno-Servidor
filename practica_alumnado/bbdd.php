<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conexion = mysqli_connect(
    "localhost",
    "root",
    "",
    "bdeducamos"
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $fechaNac = $_POST['fechaNac'] ?? '';
    $curso = (int) ($_POST['curso1'] ?? 0);
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['pass'] ?? '';

    if (
        $nombre === '' ||
        $apellidos === '' ||
        $fechaNac === '' ||
        $curso < 1 ||
        $curso > 4 ||
        $email === '' ||
        $password === ''
    ) {
        die("Todos los campos son obligatorios.");
    }

    $total = mysqli_query(
        $conexion,
        "SELECT COUNT(*) AS cantidad FROM alumnos"
    );

    $cantidad = (int) mysqli_fetch_assoc($total)['cantidad'];

    if ($cantidad >= 25) {
        die("Ya hay 25 alumnos, no entran más.");
    }

    $consulta = mysqli_prepare(
        $conexion,
        "INSERT INTO alumnos 
        (nombre, apellidos, fechaNac, curso, email, password)
        VALUES (?, ?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $consulta,
        "sssiss",
        $nombre,
        $apellidos,
        $fechaNac,
        $curso,
        $email,
        $password
    );

    mysqli_stmt_execute($consulta);

    mysqli_stmt_close($consulta);
}

$registros = mysqli_query(
    $conexion,
    "SELECT nombre, apellidos, fechaNac, curso FROM alumnos"
);

echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <title>Alumnos matriculados</title>
</head>
<body class="pagina-resultados">
    <main class="lista-resultados">';

while ($reg = mysqli_fetch_assoc($registros)) {

    $fechaNacimiento = new DateTime($reg['fechaNac']);
    $hoy = new DateTime();

    $edad = $hoy->diff($fechaNacimiento)->y;

    echo '<article class="resultado-alumno">';

    echo "Nombre: " .
        htmlspecialchars($reg['nombre']) .
        "<br>";

    echo "Apellido: " .
        htmlspecialchars($reg['apellidos']) .
        "<br>";

    echo "Edad: " . $edad . "<br>";

    echo "Curso: " .
        (int)$reg['curso'] .
        "º de la ESO<br>";

    echo "<hr>";

    echo '</article>';
}

echo '    </main>
</body>
</html>';

mysqli_close($conexion);
?>