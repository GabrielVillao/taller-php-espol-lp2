<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Tareas Personal</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <main class="contenedor">
        <h1>Gestor de Tareas Personal</h1>
        <p>Registra una cuenta o inicia sesión para administrar tus tareas.</p>

        <?php if (isset($_SESSION['cedula'])): ?>
            <p><a class="boton-enlace" href="tareas.php">Ir a mis tareas</a></p>
            <p><a href="logout.php">Cerrar sesión</a></p>
        <?php else: ?>
            <p><a class="boton-enlace" href="formulario.php">Registrarse</a></p>
            <p><a href="ingreso.php">Iniciar sesión</a></p>
        <?php endif; ?>
    </main>
</body>
</html>
