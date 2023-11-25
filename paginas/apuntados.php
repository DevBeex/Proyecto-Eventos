<?php
include_once('../clases/asistentes.class.php');

session_start();

$_assistant = new Assistant;

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['usuario'])) {
    // Obtener el array de eventos
    $dataArray = $_assistant->getUserEvents($_SESSION['usuario']['idUsuario']); // Utiliza el método getUserEvents

    // Verificar si la solicitud fue exitosa
    if ($dataArray['status'] === 'ok') {
        // Obtener el array de eventos desde el resultado
        $eventos = $dataArray['result'];

        // Verificar si hay eventos asignados
        if (!empty($eventos)) {
?>

            <div class="card-list">
                <?php foreach ($eventos as $evento) : ?>
                    <div class="card-item" data-id="<?= $evento['idEvento']; ?>">
                        <!-- Puedes ajustar el src de la imagen según la lógica de tu aplicación -->
                        <?php
                        $imagenEvento = isset($evento['imagenEvento']) ? str_replace('./', '', $evento['imagenEvento']) : 'images/default.jpg';
                        ?>
                        <img src="<?= $imagenEvento; ?>" alt="Card Image">
                        <span class="event-type"><?= $evento['nombre']; ?></span>
                        <h3><?= $evento['descripcion']; ?></h3>
                        <p>Fecha: <?= $evento['fecha']; ?></p>
                        <p>Hora: <?= $evento['hora']; ?></p>

                        <?php
                        // Obtener el nombre del organizador
                        $idOrganizador = $evento['idUsuarioOrganizador'];
                        $queryOrganizador = "SELECT nombre, apellido FROM usuario WHERE idUsuario = '$idOrganizador'";
                        $resultOrganizador = $_assistant->getData($queryOrganizador);

                        if ($resultOrganizador && count($resultOrganizador) > 0) :
                            $organizador = $resultOrganizador[0];
                        ?>
                            <p>Organizador: <?= "{$organizador['nombre']} {$organizador['apellido']}"; ?></p>
                        <?php else : ?>
                            <p>Organizador no encontrado</p>
                        <?php endif; ?>

                        <?php
                        // Obtener la información del lugar
                        $idLugar = $evento['idLugar'];
                        $queryLugar = "SELECT nombreLugar, ciudad, estado, pais FROM lugar WHERE idLugar = '$idLugar'";
                        $resultLugar = $_assistant->getData($queryLugar);

                        if ($resultLugar && count($resultLugar) > 0) :
                            $lugar = $resultLugar[0];
                        ?>
                            <p>Lugar: <?= "{$lugar['nombreLugar']}, {$lugar['ciudad']}, {$lugar['estado']}, {$lugar['pais']}"; ?></p>
                        <?php else : ?>
                            <p>Lugar no encontrado</p>
                        <?php endif; ?>

                        <!-- Agrega más detalles según sea necesario -->

                        <!-- Icono para quitar de favoritos -->
                        <span class="icon-container" onclick="handleEventAction(<?= $evento['idEvento']; ?>, 'quitarApuntados', <?= isset($_SESSION['usuario']['idUsuario']) ? $_SESSION['usuario']['idUsuario'] : 'null'; ?>)">
                            <i class="fas fa-user-minus card-icon"></i>
                        </span>

                    </div>
                <?php endforeach; ?>
            </div>

        <?php
        } else {
            // Mostrar mensaje si no hay eventos asignados
        ?>
            <div class="error-message">
                <p>No estás asignado a ningún evento.</p>
            </div>
        <?php
        }
    } else {
        // Manejar el caso en que no se pudieron obtener los eventos
        ?>
        <div class="error-message">
            <p>Error al obtener los eventos. Por favor, inténtalo de nuevo más tarde.</p>
        </div>
    <?php
    }
} else {
    // El usuario no ha iniciado sesión, muestra un mensaje
    ?>
    <div class="error-message">
        <p>Debes iniciar sesión para ver los eventos a los que estás asignado.</p>
    </div>
<?php
}
?>