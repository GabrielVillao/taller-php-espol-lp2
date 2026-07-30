<?php
session_start();

if (!isset($_SESSION['cedula'])) {
    header('Location: ingreso.php');
    exit;
}

require_once __DIR__ . '/tarea.php';

$usuario = (string) $_SESSION['cedula'];
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'agregar') {
        $texto = $_POST['texto'] ?? '';
        guardarTarea($usuario, $texto);
    } elseif ($accion === 'completar') {
        $id = $_POST['id'] ?? '';
        completarTarea($usuario, $id);
    } elseif ($accion === 'eliminar') {
        $id = $_POST['id'] ?? '';
        eliminarTarea($usuario, $id);
    }

    header('Location: tareas.php');
    exit;
}

$tareas = listarTareas($usuario);
$nombreVisible = $_SESSION['usuario'] ?? $usuario;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis tareas</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <main class="contenedor">
        <div class="cabecera-tareas">
            <div>
                <h1>Mis tareas</h1>
                <p>Usuario: <?= htmlspecialchars((string) $nombreVisible, ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <a href="logout.php">Cerrar sesión</a>
        </div>

        <form class="formulario-tarea" method="POST" action="tareas.php">
            <input type="hidden" name="accion" value="agregar">

            <label for="texto">Nueva tarea:</label>
            <div class="fila-agregar">
                <input id="texto" type="text" name="texto" maxlength="200" required>
                <button type="submit">Agregar</button>
            </div>
        </form>

        <section>
            <h2>Pendientes</h2>
            <?php if (!$tareas['pendientes']): ?>
                <p class="vacio">No hay tareas pendientes.</p>
            <?php else: ?>
                <ul class="lista-tareas">
                    <?php foreach ($tareas['pendientes'] as $tarea): ?>
                        <li>
                            <span><?= htmlspecialchars($tarea['texto'], ENT_QUOTES, 'UTF-8') ?></span>
                            <div class="acciones-tarea">
                                <form method="POST" action="tareas.php">
                                    <input type="hidden" name="accion" value="completar">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($tarea['id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit">Completar</button>
                                </form>

                                <form method="POST" action="tareas.php">
                                    <input type="hidden" name="accion" value="eliminar">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($tarea['id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <button class="peligro" type="submit">Eliminar</button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>

        <section>
            <h2>Completadas</h2>
            <?php if (!$tareas['completadas']): ?>
                <p class="vacio">No hay tareas completadas.</p>
            <?php else: ?>
                <ul class="lista-tareas completadas">
                    <?php foreach ($tareas['completadas'] as $tarea): ?>
                        <li>
                            <span><?= htmlspecialchars($tarea['texto'], ENT_QUOTES, 'UTF-8') ?></span>
                            <div class="acciones-tarea">
                                <button type="button" disabled>Completada</button>

                                <form method="POST" action="tareas.php">
                                    <input type="hidden" name="accion" value="eliminar">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($tarea['id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <button class="peligro" type="submit">Eliminar</button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
