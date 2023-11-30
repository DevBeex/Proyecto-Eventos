<div>
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
            echo '<script>alert("¡Inicio de sesión exitoso!");
            window.location.href = "index.php";</script>';

            exit();
        } elseif ($_GET['mensaje'] === 'registro_exito') {
            // Mostrar un mensaje de registro exitoso sin verificar la sesión del usuario
            echo '<script>alert("¡Registro exitoso!"); 
            window.location.href = "index.php";</script>';
            exit();
        }
    }

    // Verificar si el usuario ha iniciado sesión
    if (isset($_SESSION['usuario'])) {
        // Mostrar un saludo personalizado
        echo '<p>Hola, ' . $_SESSION['usuario']['nombre'] . '!</p>';
        // Imprimir todo el contenido de $_SESSION['usuario']
        echo '<pre>';
        print_r($_SESSION['usuario']);
        echo '</pre>';
    } else {
        echo "Hola Invitado";
    }
    ?>

</div>

