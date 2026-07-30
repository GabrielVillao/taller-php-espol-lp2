<?php

function archivoTareas(string $usuario): string
{
    if (!preg_match('/^[0-9]{1,10}$/', $usuario)) {
        throw new InvalidArgumentException('Identificador de usuario no válido.');
    }

    return __DIR__ . '/tareas_' . $usuario . '.csv';
}

function leerTodasTareas(string $usuario): array
{
    $ruta = archivoTareas($usuario);
    if (!file_exists($ruta)) {
        return [];
    }

    $archivo = fopen($ruta, 'r');
    if ($archivo === false) {
        return [];
    }

    $tareas = [];
    flock($archivo, LOCK_SH);

    while (($campos = fgetcsv($archivo, null, ',', '"', '')) !== false) {
        if (count($campos) >= 3) {
            $tareas[] = [
                'id' => $campos[0],
                'texto' => $campos[1],
                'estado' => $campos[2]
            ];
        }
    }

    flock($archivo, LOCK_UN);
    fclose($archivo);
    return $tareas;
}

function escribirTodasTareas(string $usuario, array $tareas): bool
{
    $archivo = fopen(archivoTareas($usuario), 'c+');
    if ($archivo === false) {
        return false;
    }

    if (!flock($archivo, LOCK_EX)) {
        fclose($archivo);
        return false;
    }

    ftruncate($archivo, 0);
    rewind($archivo);

    $resultado = true;
    foreach ($tareas as $tarea) {
        if (
            fputcsv(
                $archivo,
                [$tarea['id'], $tarea['texto'], $tarea['estado']],
                ',',
                '"',
                ''
            ) === false
        ) {
            $resultado = false;
            break;
        }
    }

    fflush($archivo);
    flock($archivo, LOCK_UN);
    fclose($archivo);
    return $resultado;
}

function guardarTarea(string $usuario, string $texto): bool
{
    $texto = trim($texto);
    if ($texto === '') {
        return false;
    }

    $archivo = fopen(archivoTareas($usuario), 'a');
    if ($archivo === false) {
        return false;
    }

    $resultado = false;

    if (flock($archivo, LOCK_EX)) {
        $resultado = fputcsv(
            $archivo,
            [bin2hex(random_bytes(8)), $texto, 'pendiente'],
            ',',
            '"',
            ''
        ) !== false;

        fflush($archivo);
        flock($archivo, LOCK_UN);
    }

    fclose($archivo);
    return $resultado;
}

function listarTareas(string $usuario): array
{
    $resultado = [
        'pendientes' => [],
        'completadas' => []
    ];

    foreach (leerTodasTareas($usuario) as $tarea) {
        if ($tarea['estado'] === 'completada') {
            $resultado['completadas'][] = $tarea;
        } else {
            $resultado['pendientes'][] = $tarea;
        }
    }

    return $resultado;
}

function completarTarea(string $usuario, string $id): bool
{
    $tareas = leerTodasTareas($usuario);
    $encontrada = false;

    foreach ($tareas as &$tarea) {
        if ($tarea['id'] === $id) {
            $tarea['estado'] = 'completada';
            $encontrada = true;
            break;
        }
    }
    unset($tarea);

    return $encontrada && escribirTodasTareas($usuario, $tareas);
}

function eliminarTarea(string $usuario, string $id): bool
{
    $tareas = leerTodasTareas($usuario);
    $nuevasTareas = array_values(
        array_filter(
            $tareas,
            static fn(array $tarea): bool => $tarea['id'] !== $id
        )
    );

    if (count($nuevasTareas) === count($tareas)) {
        return false;
    }

    return escribirTodasTareas($usuario, $nuevasTareas);
}
