<header>
    <nav class="navbar">
        <span class="hamburger-btn material-symbols-rounded">menu</span>
        <a href="#inicio" class="logo">
            <img src="images/logo.png" alt="logo">
            <h2>Eventos FISC</h2>
        </a>
        <ul class="links">
            <span class="close-btn material-symbols-rounded">close</span>
            <li><a href="#inicio">Inicio</a></li>
            <li><a href="#eventos">Eventos</a></li>
            <li><a href="#">Mis eventos</a></li>
            <li><a href="#">Apuntados</a></li>
            <li><a href="#favoritos">Favoritos</a></li>
            <li><a href="#">Mi perfil</a></li>
            <li><a href="#">API</a></li>
        </ul>
        <?php
        if (isset($_SESSION['usuario'])) {
            // Si ha iniciado sesión, mostrar el botón de "Cerrar sesión"
            echo '<form action="autenticacion.php" method="POST" id="logoutForm">';
            echo '<input type="hidden" name="action" value="logout">';
            echo '<button class="logout-btn" type="submit">CERRAR SESION</button>';
            echo '</form>';
        } else {
            // Si no ha iniciado sesión, mostrar el botón de "Iniciar sesión"
            echo '<button class="login-btn">INICIAR SESION</button>';
        }
        ?>
    </nav>
</header>

<!-- flujo tocamos iniciar sesion se inicia showloginmodal -->