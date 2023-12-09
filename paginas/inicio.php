<div class="mi-caja-personalizada">
    <!-- Contenido de inicio.php -->

    <?php
    // Verificar si la sesión ya está iniciada
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Verificar si hay un mensaje en la URL
    if (isset($_GET['mensaje'])) {
        if ($_GET['mensaje'] === 'exito') {
            // Mostrar el mensaje de inicio de sesión exitoso
            echo '<script>alert("¡Inicio de sesión exitoso!"); window.location.href = "index.php";</script>';
            exit();
        } elseif ($_GET['mensaje'] === 'registro_exito') {
            // Mostrar un mensaje de registro exitoso sin verificar la sesión del usuario
            echo '<script>alert("¡Registro exitoso!"); window.location.href = "index.php";</script>';
            exit();
        }
    }
    ?>

    <!-- Agregar la imagen con estilos CSS -->
    <img src="images/inicio.png" style="width: 100%; height: 100%; object-fit: cover;" alt="Descripción de la imagen">

</div>


