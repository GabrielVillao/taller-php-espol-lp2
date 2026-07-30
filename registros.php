<?php

function registrarFallo(string $usuario): void
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'IP_DESCONOCIDA';
    $fecha = date('Y-m-d H:i:s');
    $linea = $usuario . ',' . $ip . ',' . $fecha . PHP_EOL;

    file_put_contents(__DIR__ . '/logs.txt', $linea, FILE_APPEND | LOCK_EX);
}
