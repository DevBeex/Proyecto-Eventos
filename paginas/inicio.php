<div>
    <!-- Contenido de inicio.php -->

    <?php
    // Verificar si hay un mensaje en la URL
    if (isset($_GET['mensaje'])) {
        if ($_GET['mensaje'] === 'exito') {
            // Mostrar el mensaje de inicio de sesión exitoso
            echo '<script>alert("¡Inicio de sesión exitoso!");</script>';
        } elseif ($_GET['mensaje'] === 'registro_exito') {
            // Mostrar un mensaje de registro exitoso sin verificar la sesión del usuario
            echo '<script>alert("¡Registro exitoso!");</script>';
        }
    }

    // Verificar si el usuario ha iniciado sesión
    // (Puedes agregar esta comprobación si es necesaria para otros mensajes)
    if (isset($_SESSION['usuario'])) {
        // Mostrar un saludo personalizado
        echo '<p>Hola, ' . $_SESSION['usuario']['nombre'] . '!</p>';
    }
    ?>
</div>

