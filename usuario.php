<?php

function rutaUsuarios(): string
{
    return __DIR__ . '/usuarios.csv';
}

function guardar(array $datos): bool
{
    $archivo = fopen(rutaUsuarios(), 'a');
    if ($archivo === false) {
        return false;
    }

    $guardado = false;

    if (flock($archivo, LOCK_EX)) {
        $guardado = fputcsv(
            $archivo,
            [
                $datos['cedula'],
                $datos['nombre'],
                $datos['estado_civil'],
                $datos['correo'],
                $datos['clave_hash']
            ],
            ',',
            '"',
            ''
        ) !== false;

        fflush($archivo);
        flock($archivo, LOCK_UN);
    }

    fclose($archivo);
    return $guardado;
}

function validar(string $cedula): bool
{
    $ruta = rutaUsuarios();
    if (!file_exists($ruta)) {
        return false;
    }

    $archivo = fopen($ruta, 'r');
    if ($archivo === false) {
        return false;
    }

    flock($archivo, LOCK_SH);

    while (($campos = fgetcsv($archivo, null, ',', '"', '')) !== false) {
        if (count($campos) >= 5 && $campos[0] === $cedula) {
            flock($archivo, LOCK_UN);
            fclose($archivo);
            return true;
        }
    }

    flock($archivo, LOCK_UN);
    fclose($archivo);
    return false;
}

function autenticar(string $cedula, string $contrasena): bool
{
    $ruta = rutaUsuarios();
    if (!file_exists($ruta)) {
        return false;
    }

    $archivo = fopen($ruta, 'r');
    if ($archivo === false) {
        return false;
    }

    flock($archivo, LOCK_SH);

    while (($campos = fgetcsv($archivo, null, ',', '"', '')) !== false) {
        if (
            count($campos) >= 5
            && $campos[0] === $cedula
            && password_verify($contrasena, $campos[4])
        ) {
            flock($archivo, LOCK_UN);
            fclose($archivo);
            return true;
        }
    }

    flock($archivo, LOCK_UN);
    fclose($archivo);
    return false;
}
