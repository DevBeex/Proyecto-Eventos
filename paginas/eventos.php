<?php
include_once('../clases/eventos.class.php');

session_start();

$_event = new event;

// Obtener el array de eventos
$dataArray = $_event->getAllEvents();

// Verificar si la solicitud fue exitosa
if ($dataArray['status'] === 'ok') {
    // Obtener el array de eventos desde el resultado
    $eventos = $dataArray['result'];
    if (count($eventos) > 0) {
?>

<div class="card-list">
    <?php foreach ($eventos as $evento) { ?>
        <div class="card-item" data-id="<?php echo $evento['idEvento']; ?>">
            <!-- Puedes ajustar el src de la imagen según la lógica de tu aplicación -->
            <?php
            $imagenEvento = isset($evento['imagenEvento']) ? str_replace('./', '', $evento['imagenEvento']) : 'images/default.jpg';
            ?>
            <img src="<?php echo $imagenEvento; ?>" alt="Card Image">
            <span class="event-type"><?php echo $evento['nombre']; ?></span>
            <h3><?php echo $evento['descripcion']; ?></h3>
            <p>Fecha: <?php echo $evento['fecha']; ?></p>
            <p>Hora: <?php echo $evento['hora']; ?></p>

            <?php
            // Obtener el nombre del organizador
            $idOrganizador = $evento['idUsuarioOrganizador'];
            $queryOrganizador = "SELECT nombre, apellido FROM usuario WHERE idUsuario = '$idOrganizador'";
            $resultOrganizador = $_event->getData($queryOrganizador);

            if ($resultOrganizador && count($resultOrganizador) > 0) {
                $organizador = $resultOrganizador[0];
                echo "<p>Organizador: {$organizador['nombre']} {$organizador['apellido']}</p>";
            } else {
                echo "<p>Organizador no encontrado</p>";
            }

            // Obtener la información del lugar
            $idLugar = $evento['idLugar'];
                $queryLugar = "SELECT nombreLugar, direccion FROM lugar WHERE idLugar = '$idLugar'";
                $resultLugar = $_event->getData($queryLugar);

                if ($resultLugar && count($resultLugar) > 0):
                    $lugar = $resultLugar[0];
                    $evento['nombreLugar'] = $lugar['nombreLugar'];
                    ?>
                    <p>Lugar:
                        <?= "{$lugar['nombreLugar']}, {$lugar['direccion']}"; ?>
                    </p>
                <?php else: ?>
                    <p>Lugar no encontrado</p>
                <?php endif; ?>
            

            <!-- Agrega más detalles según sea necesario -->

            <!-- Icono para apuntarse al evento -->
            <span class="icon-container" onclick="handleEventAction(<?php echo $evento['idEvento']; ?>, 'apuntarse', <?php echo isset($_SESSION['usuario']['idUsuario']) ? $_SESSION['usuario']['idUsuario'] : 'null'; ?>)">
                <i class="fa-solid fa-user-plus"></i>
            </span>

            <!-- Icono para añadir a favoritos -->
            <span class="icon-container" onclick="handleEventAction(<?php echo $evento['idEvento']; ?>, 'favoritos', <?php echo isset($_SESSION['usuario']['idUsuario']) ? $_SESSION['usuario']['idUsuario'] : 'null'; ?>)">
                <i class="fas fa-star card-icon"></i>
            </span>
        </div>
    <?php } ?>
</div>

<?php
    } else {
        // Mostrar un mensaje estilizado si no hay eventos creados
        echo "<div class='error-message'>No hay eventos creados.</div>";
    }
} else {
    // Mostrar un mensaje estilizado en caso de error al obtener eventos
    echo "<div class='error-message'>No hay eventos creados</div>";
}
?>
