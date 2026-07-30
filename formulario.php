<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <main class="contenedor">
        <h1>Formulario de Registro</h1>

        <form method="POST" action="bienvenido.php">
            <label for="cedula">Cédula:</label>
            <input
                id="cedula"
                type="text"
                name="cedula"
                maxlength="10"
                pattern="[0-9]{1,10}"
                title="Ingrese únicamente números, máximo 10 dígitos"
                required
            >

            <label for="nombre">Nombre:</label>
            <input id="nombre" type="text" name="nombre" maxlength="30" required>

            <label for="estado_civil">Estado Civil:</label>
            <select id="estado_civil" name="estado_civil" required>
                <option value="" selected disabled>Seleccione una opción</option>
                <option value="soltero">Soltero</option>
                <option value="casado">Casado</option>
                <option value="union_libre">Unión libre</option>
                <option value="viudo">Viudo</option>
            </select>

            <label for="correo">Correo:</label>
            <input id="correo" type="email" name="correo" required>

            <label for="clave">Clave:</label>
            <input id="clave" type="password" name="clave" minlength="6" required>

            <div class="acciones-formulario">
                <input type="submit" value="Registrar">
                <input type="reset" value="Resetear">
            </div>
        </form>

        <p class="ayuda"><a href="index.php">Volver al menú</a></p>
    </main>
</body>
</html>
