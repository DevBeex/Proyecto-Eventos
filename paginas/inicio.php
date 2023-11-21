<div>
    <!-- Contenido de inicio.php -->

    <?php
    // Verificar si hay un mensaje en la URL
    if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'exito') {
        // Mostrar el mensaje utilizando JavaScript
        echo '<script>alert("¡Inicio de sesión exitoso!");</script>';
    }

    // Verificar si el usuario ha iniciado sesión
    if (isset($_SESSION['usuario'])) {
        // Mostrar un saludo personalizado
        echo '<p>Hola, ' . $_SESSION['usuario']['nombre'] . '!</p>';
    }
    ?>
</div>
