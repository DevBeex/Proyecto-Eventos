<?php

// Verificar si la cookie 'correoElectronico' existe
if (isset($_COOKIE['correoElectronico'])) {
    $correoElectronicoCookieValue = $_COOKIE['correoElectronico'];
} else {
    $correoElectronicoCookieValue = "";
}

// Verificar si la cookie 'contrasena' existe
if (isset($_COOKIE['contrasena'])) {
    $contrasenaCookieValue = $_COOKIE['contrasena'];
} else {
    $contrasenaCookieValue = "";
}
?>
<div class="blur-bg-overlay"></div>
<div class="form-popup">
    <span class="close-btn material-symbols-rounded">close</span>
    <div class="form-box login">
        <div class="form-details">

        </div>
        <div class="form-content">
            <h2>Iniciar Sesion</h2>
            <form id="loginForm" action="autenticacion.php" method="POST">
                <div class="input-field">
                    <input type="text" name="correoElectronico" required pattern="^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" value="<?= $correoElectronicoCookieValue ?>">
                    <label>Email</label>
                </div>
                <div class="input-field">
                    <input type="password" name="contrasena" required value="<?= $contrasenaCookieValue ?>">
                    <label>Clave</label>
                </div>

                <input type="hidden" name="action" value="login">
                <input type="hidden" name="redirectPage" id="redirectPage" value="">
                <button type="submit">Iniciar sesión</button>
            </form>
            <div class="bottom-link">
                ¿No tienes una cuenta?
                <a href="#" id="signup-link">Registrarse</a>
            </div>
        </div>
    </div>

    <div class="form-box signup">
        <div class="form-details"></div>
        <div class="form-content">
            <h2>Registrarse</h2>
            <form id="signupForm" action="autenticacion.php" method="POST">
                <div class="input-field">
                    <input type="text" name="nombre" required>
                    <label>Nombre</label>
                </div>
                <div class="input-field">
                    <input type="text" name="apellido" required>
                    <label>Apellido</label>
                </div>
                <div class="input-field">
                    <input type="text" name="correoElectronico" required pattern="^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                    <label>Ingresa tu email</label>
                </div>
                <div class="input-field">
                    <input type="password" name="contrasena" required>
                    <label>Ingresa tu clave</label>
                </div>
                <input type="hidden" name="action" value="register">
                <input type="hidden" name="rol" value="usuario">
                <button type="submit">Registrarse</button>
            </form>
            <div class="bottom-link">
                ¿Ya tienes una cuenta?
                <a href="#" id="login-link">Inicia sesión</a>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('loginForm').addEventListener('submit', function(event) {
        // Evita que el formulario se envíe automáticamente
        event.preventDefault();

        // Lógica de validación para el formulario de inicio de sesión
        if (validarFormulario(this)) {
            // Si la validación es exitosa, puedes realizar otras acciones antes de enviar el formulario
            this.submit(); // Envía el formulario
        }
    });

    document.getElementById('signupForm').addEventListener('submit', function(event) {
        // Evita que el formulario se envíe automáticamente
        event.preventDefault();

        // Lógica de validación para el formulario de registro
        if (validarFormulario(this)) {
            // Si la validación es exitosa, puedes realizar otras acciones antes de enviar el formulario
            this.submit(); // Envía el formulario
        }
    });

    function validarFormulario(formulario) {
        // Lógica de validación para el formato de correo electrónico
        var correoInput = formulario.querySelector('[name="correoElectronico"]');
        var regex = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;
        var esCorreoValido = regex.test(correoInput.value.trim());

        if (!esCorreoValido) {
            // Muestra un mensaje de error o realiza alguna acción adicional
            alert('Correo electrónico no válido');
            return false; // Detiene la ejecución y evita que se envíe el formulario
        }

        // Agrega más lógica de validación si es necesario

        return true; // Permite enviar el formulario si todas las validaciones son exitosas
    }
</script>