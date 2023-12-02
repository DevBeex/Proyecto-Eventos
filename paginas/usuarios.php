<?php
// Incluir la clase de eventos
include_once('../clases/autenticacion.class.php');
$_auth = new Auth;
?>

<div class="centered-container">
    <div class="admin-message">
        <h2>Cambia el rol de los usuarios</h2>
    </div>
</div>

<div class="card-list">
    <?php
    $usuariosResponse = $_auth->getAllUsers();

    if ($usuariosResponse['status'] == 'ok') {
        $usuarios = $usuariosResponse['result']['users'];

        foreach ($usuarios as $usuario) {
    ?>
            <div class="card-item" data-id="<?php echo $usuario['idUsuario']; ?>">
                <span class="user-role"><?php echo $usuario['rol']; ?></span>
                <h3><?php echo "{$usuario['nombre']} {$usuario['apellido']}"; ?></h3>
                <p>Correo Electrónico: <?php echo $usuario['correoElectronico']; ?></p>

                <!-- Icono para cambiar el rol del usuario -->
                <span class="icon-container" onclick="handleChangeUserRole(<?php echo $usuario['idUsuario']?>, '<?php echo $usuario['rol'] ?>')">

                    <i class="fas fa-user-cog card-icon"></i>
                </span>
            </div>
    <?php
        }
    } else {
        // Mostrar un mensaje estilizado si no hay usuarios
        echo "<div class='error-message'>No hay usuarios registrados.</div>";
    }
    ?>
</div>

<style>
    .card-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        max-width: 1250px;
        margin: 50px auto;
        padding: 20px;
        gap: 20px;
    }
</style>