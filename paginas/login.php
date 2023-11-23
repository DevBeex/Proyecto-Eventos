<div class="blur-bg-overlay"></div>
<div class="form-popup">
    <span class="close-btn material-symbols-rounded">close</span>
    <div class="form-box login">
        <div class="form-details">

        </div>
        <div class="form-content">
            <h2>Iniciar Sesion</h2>
            <form action="autenticacion.php" method="POST">
                <div class="input-field">
                    <input type="text" name="correoElectronico" required>
                    <label>Email</label>
                </div>
                <div class="input-field">
                    <input type="password" name="contrasena" required>
                    <label>Clave</label>
                </div>
                <a href="#" class="forgot-pass-link">Forgot password?</a>
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
            <form action="autenticacion.php" method="POST">
                <div class="input-field">
                    <input type="text" name="nombre" required>
                    <label>Nombre</label>
                </div>
                <div class="input-field">
                    <input type="text" name="apellido" required>
                    <label>Apellido</label>
                </div>
                <div class="input-field">
                    <input type="text" name="correoElectronico" required>
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