<?php
session_start();
require_once __DIR__ . '/usuario.php';
require_once __DIR__ . '/registros.php';

if (isset($_SESSION['cedula'])) {
    header('Location: tareas.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = trim($_POST['cedula'] ?? '');
    $clave = $_POST['clave'] ?? '';

    if (autenticar($cedula, $clave)) {
        session_regenerate_id(true);
        $_SESSION['cedula'] = $cedula;
        $_SESSION['usuario'] = $cedula;

        header('Location: tareas.php');
        exit;
    }

    registrarFallo($cedula);
    $error = 'Cédula o contraseña incorrecta.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <main class="contenedor">
        <h1>Ingreso</h1>

        <?php if ($error !== ''): ?>
            <p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <form method="POST" action="ingreso.php">
            <label for="cedula">Cédula:</label>
            <input id="cedula" type="text" name="cedula" maxlength="10" required>

            <label for="clave">Contraseña:</label>
            <input id="clave" type="password" name="clave" required>

            <input type="submit" value="Ingresar">
        </form>

        <p class="ayuda">
            <a href="formulario.php">Crear una cuenta</a> ·
            <a href="index.php">Volver al menú</a>
        </p>
    </main>
</body>
</html>
