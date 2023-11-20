<?php
include_once('../clases/eventos.class.php');

$_event = new event;

// Obtener el array de eventos
$dataArray = $_event->getAllEvents();

// Verificar si la solicitud fue exitosa
if ($dataArray['status'] === 'ok') {
    // Obtener el array de eventos desde el resultado
    $eventos = $dataArray['result'];

?>

    <div class="card-list">
        <?php foreach ($eventos as $evento) { ?>
            <a href="#" class="card-item" data-id="<?php echo $evento['idEvento']; ?>">
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
                $queryLugar = "SELECT nombreLugar, ciudad, estado, pais FROM lugar WHERE idLugar = '$idLugar'";
                $resultLugar = $_event->getData($queryLugar);

                if ($resultLugar && count($resultLugar) > 0) {
                    $lugar = $resultLugar[0];
                    echo "<p>Lugar: {$lugar['nombreLugar']}, {$lugar['ciudad']}, {$lugar['estado']}, {$lugar['pais']}</p>";
                } else {
                    echo "<p>Lugar no encontrado</p>";
                }
                ?>

                <!-- Agrega más detalles según sea necesario -->


                <!-- Icono para apuntarse al evento -->
                <span class="icon-container" onclick="apuntarseAlEvento(<?php echo $evento['idEvento']; ?>)">
                    <i class="fas fa-check card-icon"></i>
                </span>

                <!-- Icono para añadir a favoritos -->
                <span class="icon-container" onclick="añadirAFavoritos(<?php echo $evento['idEvento']; ?>)">
                    <i class="fas fa-star card-icon"></i>
                </span>



            </a>
        <?php } ?>
    </div>

<?php
} else {
    // Manejar el caso en que la solicitud no fue exitosa
    echo "Error al obtener eventos.";
}
?>