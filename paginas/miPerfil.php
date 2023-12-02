<?php
session_start();

if (isset($_SESSION['usuario'])) {
    // Aquí va el código para mostrar el formulario de edición del perfil
?>
    <div class="form-container">
    <div class="form-container">
    <form class="edit-user-form" method="post" enctype="multipart/form-data" onsubmit="return validarEdicionUsuario()">
        <h2>Editar datos de cuenta</h2>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo $_SESSION['usuario']['nombre']; ?>" required>

        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" value="<?php echo $_SESSION['usuario']['apellido']; ?>" required>

        <label for="correoElectronico">Correo Electrónico:</label>
        <input type="text" id="correoElectronico" name="correoElectronico" value="<?php echo $_SESSION['usuario']['correoElectronico']; ?>" required pattern="^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">

        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required>
        <input type="hidden" id="action" name="action" value="editUser"> <!-- Campo oculto para el idLugar -->
        <input type="hidden" id="idUsuario" name="idUsuario" value="<?php echo $_SESSION['usuario']['idUsuario']; ?>"> <!-- Campo oculto para el idLugar -->

        <button type="submit">Guardar cambios</button>
    </form>
</div>
    </div>
<?php
} else {
    // Si el usuario no ha iniciado sesión, mostrar un mensaje
?>
    <div class="error-message">
        <p>Debes iniciar sesión para editar tu perfil.</p>
    </div>
<?php
}
?>
