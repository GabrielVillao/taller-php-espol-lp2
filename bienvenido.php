<?php
session_start();
require_once __DIR__ . '/usuario.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: formulario.php');
    exit;
}

$cedula = trim($_POST['cedula'] ?? '');
$nombre = trim($_POST['nombre'] ?? '');
$estadoCivil = $_POST['estado_civil'] ?? '';
$correo = trim($_POST['correo'] ?? '');
$clave = $_POST['clave'] ?? '';

$estadosPermitidos = ['soltero', 'casado', 'union_libre', 'viudo'];
$errores = [];

if (!preg_match('/^[0-9]{1,10}$/', $cedula)) {
    $errores[] = 'La cédula debe contener únicamente números y máximo 10 dígitos.';
}

if ($nombre === '' || strlen($nombre) > 30) {
    $errores[] = 'El nombre es obligatorio y debe tener máximo 30 caracteres.';
}

if (!in_array($estadoCivil, $estadosPermitidos, true)) {
    $errores[] = 'Seleccione un estado civil válido.';
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $errores[] = 'Ingrese un correo electrónico válido.';
}

if (strlen($clave) < 6) {
    $errores[] = 'La clave debe tener mínimo 6 caracteres.';
}

if (!$errores && validar($cedula)) {
    header('Location: ingreso.php');
    exit;
}

if (!$errores) {
    $datos = [
        'cedula' => $cedula,
        'nombre' => $nombre,
        'estado_civil' => $estadoCivil,
        'correo' => $correo,
        'clave_hash' => password_hash($clave, PASSWORD_DEFAULT)
    ];

    if (!guardar($datos)) {
        $errores[] = 'No fue posible guardar el usuario. Intente nuevamente.';
    } else {
        session_regenerate_id(true);
        $_SESSION['usuario'] = $nombre;
        $_SESSION['cedula'] = $cedula;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado del registro</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <main class="contenedor">
        <?php if ($errores): ?>
            <h1>No se pudo completar el registro</h1>
            <ul class="error">
                <?php foreach ($errores as $error): ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
            <p><a href="formulario.php">Volver al formulario</a></p>
        <?php else: ?>
            <h1>USUARIO REGISTRADO</h1>
            <p>Bienvenido, <?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') ?>.</p>
            <p><a class="boton-enlace" href="tareas.php">Ir a mis tareas</a></p>
            <p><a href="index.php">Volver al menú</a></p>
        <?php endif; ?>
    </main>
</body>
</html>
