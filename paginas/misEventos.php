<?php
session_start();

// Verificar si el usuario está registrado
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    // Si no está registrado, mostrar mensaje y salir del script
?>
    <div class="error-message">
        <p>Debes iniciar sesión para ver tus eventos y crear nuevos.</p>
    </div>
<?php
    exit();
}

// Incluir la clase de eventos
include_once('../clases/eventos.class.php');

$_event = new event;

// Obtener el idUsuario actual desde la sesión
$idUsuario = $_SESSION['usuario']['idUsuario'];

// Obtener el array de eventos del usuario actual
$dataArray = $_event->getEventsByUser($idUsuario);

// Verificar si la solicitud fue exitosa
if ($dataArray['status'] === 'ok') {
    // Obtener el array de eventos desde el resultado
    $eventos = $dataArray['result'];
    // var_dump($eventos);
?>

<!-- Contenedor del botón para ajustar la posición -->
<div class="create-event-button-container">
    <!-- Botón para abrir el modal de crear nuevo evento -->
    <button id="openModalBtn" class="styled-button" onclick="openCreateEventModal()">Crear Evento</button>
</div>

<!-- Fondo difuminado -->
<div class="modal-overlay" id="modalOverlay"></div>

<div id="createEventModal" class="modal-content">
    <span class="close-btn" onclick="closeCreateEventModal()">&times;</span>
    <h2>Crear Nuevo Evento</h2>

    <form id="createEventForm" action="evento.php" method="POST" enctype="multipart/form-data">

        <div class="form-row">
            <label for="nombre">Nombre del Evento:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>

        <div class="form-row">
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" rows="4" required></textarea>
        </div>

        <div class="form-row">
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" required>
        </div>

        <div class="form-row">
            <label for="hora">Hora:</label>
            <input type="time" id="hora" name="hora" required>
        </div>

        <div class="form-row">
            <label for="nombreLugar">Lugar:</label>
            <!-- Cambiado a un datalist para mostrar sugerencias -->
            <input list="suggestionList" id="nombreLugar" name="nombreLugar" oninput="searchPlace(this)" required>
            <datalist id="suggestionList">
                <!-- Aquí se agregarán las sugerencias -->
            </datalist>
            <input type="hidden" id="idLugar" name="idLugar" value=""> <!-- Campo oculto para el idLugar -->
        </div>

        <div>
            <label for="imagenEvento">Imagen del evento:</label>
            <div id="dropZone" class="drop-zone" ondragover="handleDragOver(event)" ondrop="handleFileDrop(event)">
                <p>Arrastra y suelta la imagen aquí o haz clic para seleccionarla</p>
                <label for="imagenEvento" class="custom-file-input">Seleccionar Archivo</label>
                <input type="file" id="imagenEvento" name="imagenEvento" accept="image/*" required style="display: none;">
                <div id="loadingIndicator" class="loading-indicator"></div>
                <p id="fileName" style="color: green; font-weight: bold;"></p>
            </div>
        </div>

        <input type="hidden" id="idUsuarioOrganizador" name="idUsuarioOrganizador" value="<?php echo $_SESSION['usuario']['idUsuario'] ?>">

        <button type="submit" class="styled-button">Crear Evento</button>
    </form>
</div>

<!-- Card list para mostrar eventos -->

<div class="card-list">
    <?php foreach ($eventos['events'] as $evento) { ?>
        <div class="card-item" data-id="<?php echo $evento['idEvento']; ?>">
            <!-- Puedes ajustar el src de la imagen según la lógica de tu aplicación -->
            <?php
            $imagenEvento = isset($evento['imagenEvento']) ? str_replace('./', '', $evento['imagenEvento']) : 'images/default.jpg';
            ?>
            <img src="<?php echo $imagenEvento; ?>" alt="Card Image">
            <span class="event-type"><?php echo isset($evento['nombre']) ? $evento['nombre'] : 'Nombre no disponible'; ?></span>
            <h3><?php echo isset($evento['descripcion']) ? $evento['descripcion'] : 'Descripción no disponible'; ?></h3>
            <!-- Resto de la información del evento... -->

            <!-- Icono para apuntarse al evento -->
            <span class="icon-container" onclick="handleEventAction(<?php echo $evento['idEvento']; ?>, 'apuntarse', <?php echo $idUsuario; ?>)">
                <i class="fa-solid fa-user-plus"></i>
            </span>

            <!-- Icono para añadir a favoritos -->
            <span class="icon-container" onclick="handleEventAction(<?php echo $evento['idEvento']; ?>, 'favoritos', <?php echo $idUsuario; ?>)">
                <i class="fas fa-star card-icon"></i>
            </span>
        </div>
    <?php } ?>
</div>


<?php
} else {
    // Mostrar un mensaje estilizado en caso de error al obtener eventos
    echo "<div class='error-message'>Error al obtener eventos.</div>";
}
?>

<style>
  /* Estilos para el contenedor del botón */
  .create-event-button-container {
    margin-top: 125px; /* Ajusta la distancia desde la parte superior */
  }

  /* Estilos para la lista de tarjetas */
  .card-list {
    margin-top: 10px; /* Ajusta la distancia desde la parte superior */
  }

  /* Estilos para el fondo difuminado */
  .modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Ajusta la opacidad según tus preferencias */
    z-index: 1;
  }

  /* Ajusta la posición y tamaño del modal según sea necesario */
  #createEventModal {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 2;
    /* Otros estilos para el modal... */
  }
</style>