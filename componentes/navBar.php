<?php
// Verificar si la sesión de usuario está definida y tiene una clave 'rol'
if (isset($_SESSION['usuario']) && isset($_SESSION['usuario']['rol'])) {
    $rol = $_SESSION['usuario']['rol'];

    echo '<header>';
    echo '<nav class="navbar">';
    echo '<span class="hamburger-btn material-symbols-rounded">menu</span>';
    echo '<a href="#inicio" class="logo">';
    echo '<img src="images/logo.png" alt="logo">';
    echo '<h2>Eventos FISC</h2>';
    echo '</a>';
    echo '<ul class="links">';
    echo '<span class="close-btn material-symbols-rounded">close</span>';

    // Mostrar enlaces específicos para administradores
    if ($rol === 'administrador') {
        echo '<li><a href="#inicio">Inicio</a></li>';
        echo '<li><a href="#eventosAdmin">Eventos admin</a></li>';
        echo '<li><a href="#usuarios">Usuarios</a></li>';
        echo '<li><a href="#api">API</a></li>';
    } else {
        // Mostrar enlaces comunes para usuarios autenticados no administradores
        echo '<li><a href="#inicio">Inicio</a></li>';
        echo '<li><a href="#eventos">Eventos</a></li>';
        echo '<li><a href="#misEventos">Mis eventos</a></li>';
        echo '<li><a href="#apuntados">Apuntados</a></li>';
        echo '<li><a href="#favoritos">Favoritos</a></li>';
        echo '<li><a href="#miPerfil">Mi perfil</a></li>';
    }

    echo '</ul>';
    echo '<form action="autenticacion.php" method="POST" id="logoutForm">';
    echo '<input type="hidden" name="action" value="logout">';
    echo '<button class="logout-btn" type="submit">CERRAR SESION</button>';
    echo '</form>';
    echo '</nav>';
    echo '</header>';
} else {
    echo '<header>';
    echo '<nav class="navbar">';
    echo '<span class="hamburger-btn material-symbols-rounded">menu</span>';
    echo '<a href="#inicio" class="logo">';
    echo '<img src="images/logo.png" alt="logo">';
    echo '<h2>Eventos FISC</h2>';
    echo '</a>';
    echo '<ul class="links">';
    echo '<span class="close-btn material-symbols-rounded">close</span>';
    echo '<li><a href="#inicio">Inicio</a></li>';
    echo '<li><a href="#eventos">Eventos</a></li>';
    echo '<li><a href="#misEventos">Mis eventos</a></li>';
    echo '<li><a href="#apuntados">Apuntados</a></li>';
    echo '<li><a href="#favoritos">Favoritos</a></li>';
    echo '<li><a href="#miPerfil">Mi perfil</a></li>';
    echo '<li><a href="#api">API</a></li>';
    echo '</ul>';
    echo '<button class="login-btn">INICIAR SESION</button>';
    echo '</nav>';
    echo '</header>';
}
?>
