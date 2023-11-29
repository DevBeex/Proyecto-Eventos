const navbarMenu = document.querySelector(".navbar .links");
const hamburgerBtn = document.querySelector(".hamburger-btn");
const hideMenuBtn = navbarMenu.querySelector(".close-btn");
const showPopupBtn = document.querySelector(".login-btn") || null;
const formPopup = document.querySelector(".form-popup");
const hidePopupBtn = formPopup.querySelector(".close-btn");
const signupLoginLink = formPopup.querySelectorAll(".bottom-link a");
const contentContainer = document.getElementById("content-container");
var openModalBtn = document.getElementById('openModalBtn');
let overlay;

function showMessage(message, iconClass, messageType) {
    // Crear overlay
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.classList.add('modal-overlay');
        document.body.appendChild(overlay);
    }

    // Establecer el color de fondo y la clase según el tipo de mensaje
    const modalBackgroundColor = messageType === 'success' ? '#4CAF50' : '#f44336';
    const modalColorClass = messageType === 'success' ? 'modal-success' : 'modal-error';

    // Crear modal con contenido dinámico
    const modalContent = `
        <div class="modal ${modalColorClass}" style="background-color: ${modalBackgroundColor};">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <div class="modal-content">
                <i class="${iconClass}"></i>
                <p>${message}</p>
            </div>
        </div>
    `;

    // Agregar modal al final del body
    document.body.insertAdjacentHTML('beforeend', modalContent);

    // Mostrar overlay y modal
    overlay.style.display = 'block';
    const modal = document.querySelector(`.modal.${modalColorClass}`);
    modal.style.display = 'block';

    // Estilos adicionales para centrar y ajustar el tamaño del icono
    const modalContentElement = modal.querySelector('.modal-content');
    modalContentElement.style.display = 'flex';
    modalContentElement.style.flexDirection = 'column';
    modalContentElement.style.alignItems = 'center';
    modalContentElement.style.textAlign = 'center';

    // Ajustar el tamaño del icono
    const iconElement = modal.querySelector('i');
    iconElement.style.fontSize = '2em'; // Ajusta el tamaño del icono según tus preferencias

    // Evitar que el clic en el fondo difuminado se propague al contenedor principal
    overlay.addEventListener('click', function (event) {
        event.stopPropagation();
    });
}


// Show mobile menu
hamburgerBtn.addEventListener("click", () => {
    navbarMenu.classList.toggle("show-menu");
});


// Hide mobile menu
hideMenuBtn.addEventListener("click", () => hamburgerBtn.click());

// Show login popup
if (showPopupBtn != null) {
    showPopupBtn.addEventListener("click", () => {
        document.body.classList.toggle("show-popup");
    });
}


// Hide login popup
hidePopupBtn.addEventListener("click", () => showPopupBtn.click());

// Show or hide signup form
signupLoginLink.forEach(link => {
    link.addEventListener("click", (e) => {
        e.preventDefault();
        formPopup.classList[link.id === 'signup-link' ? 'add' : 'remove']("show-signup");
    });
});

// Manejar clics en el menú de navegación
navbarMenu.addEventListener("click", (event) => {
    if (event.target.tagName === "A") {
        event.preventDefault();
        const pageName = event.target.getAttribute("href").substring(1); // Obtener el nombre de la página sin el #
        loadPage(pageName);
    }
});

// Función para cargar una página en el contenedor principal
function loadPage(pageName) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            contentContainer.innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", `paginas/${pageName}.php`, true);
    xhttp.send();
}

window.addEventListener("scroll", function () {
    var navbar = document.querySelector(".navbar");

    if (window.scrollY > 50) {
        navbar.classList.add("scroll");
    } else {
        navbar.classList.remove("scroll");
    }

    console.log(navbar.classList.contains("scroll")); // Agrega este console.log para verificar
});


// Función para manejar la lógica de añadir a favoritos
function añadirAFavoritos(idEvento, userId) {
    // Verificar si el usuario ha iniciado sesión
    if (userId) {
        // Realizar la petición AJAX para agregar el evento a favoritos
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "favorito.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");

        const data = {
            eventId: idEvento,
            userId: userId
        };

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    // La petición fue exitosa
                    const response = JSON.parse(xhr.responseText);

                    if (response.status === 'ok') {
                        // La operación fue exitosa, mostrar un mensaje de éxito
                        console.log('Operación exitosa:', response.result.message);
                        showMessage(response.result.message, 'fas fa-check-circle', 'success');
                    } else {
                        // La operación falló, mostrar un mensaje de error
                        console.log('Error en la operación:', response.result.error_msg);
                        showMessage(response.result.error_msg, 'fas fa-exclamation-circle', 'error');
                    }
                } else {
                    // La petición falló, puedes mostrar un mensaje de error
                    console.log('Error en la petición:', xhr.status, xhr.statusText);
                    showMessage('Ya esta agregado a favoritos', 'fas fa-exclamation-circle', 'error');
                }
            }
        };

        xhr.send(JSON.stringify(data));

    } else {
        // El usuario no ha iniciado sesión, mostrar un modal o realizar acciones adicionales
        console.log('Usuario no ha iniciado sesión');
        showLoginModal();
    }
}

// Nueva función para manejar la lógica de quitar de favoritos
function quitarDeFavoritos(eventId, userId) {
    // Realizar la petición AJAX para quitar el evento de favoritos
    const xhr = new XMLHttpRequest();
    xhr.open("DELETE", "favorito.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    const data = {
        eventId: eventId,
        userId: userId
    };

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                // La petición fue exitosa
                const response = JSON.parse(xhr.responseText);

                if (response.status === 'ok') {
                    // La operación fue exitosa, mostrar un mensaje de éxito
                    console.log('Operación exitosa:', response.result.message);
                    // Puedes realizar acciones adicionales aquí, como actualizar la interfaz de usuario
                    showMessage(response.result.message, 'fas fa-check-circle', 'success');

                    // Cargar nuevamente el contenido del tab de "Favoritos"
                    loadPage('favoritos');
                } else {
                    // La operación falló, mostrar un mensaje de error
                    console.log('Error en la operación:', response.result.error_msg);
                    showMessage(response.result.error_msg, 'fas fa-exclamation-circle', 'error');
                }
            } else {
                // La petición falló, puedes mostrar un mensaje de error
                console.log('Error en la petición:', xhr.status, xhr.statusText);
                showMessage('Error en la petición', 'fas fa-exclamation-circle', 'error');
            }
        }
    };

    xhr.send(JSON.stringify(data));
}

function handleEventAction(eventId, action, userId) {
    // Verificar si el usuario ha iniciado sesión
    if (userId) {
        // El usuario ha iniciado sesión, puedes continuar con el flujo normal
        if (action === 'apuntarse') {
            apuntarseAlEvento(eventId, userId);
        } else if (action === 'favoritos') {
            añadirAFavoritos(eventId, userId);
        } else if (action === 'quitarFavoritos') {
            quitarDeFavoritos(eventId, userId);
        } else if (action === 'quitarApuntados') {
            quitarApuntado(eventId, userId);
        }else if (action === 'eliminarEvento') {
            eliminarEvento(eventId);
        }
    } else {
        // El usuario no ha iniciado sesión, mostrar un modal o realizar acciones adicionales
        showLoginModal();
    }
}

function eliminarEvento(eventId){
    // Realizar la petición AJAX para quitar el evento de favoritos
    const xhr = new XMLHttpRequest();
    xhr.open("DELETE", "evento.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    const data = {
        idEvento: eventId
    };

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                // La petición fue exitosa
                const response = JSON.parse(xhr.responseText);

                if (response.status === 'ok') {
                    // La operación fue exitosa, mostrar un mensaje de éxito
                    console.log('Operación exitosa:', response.result.message);
                    // Puedes realizar acciones adicionales aquí, como actualizar la interfaz de usuario
                    showMessage(response.result.message, 'fas fa-check-circle', 'success');

                    // Cargar nuevamente el contenido del tab de "Favoritos"
                    loadPage('misEventos');
                } else if (response.status === 'error'){
                    // La operación falló, mostrar un mensaje de error
                    console.log('Error en la operación:', response.result.error_msg);
                    showMessage(response.result.error_msg, 'fas fa-exclamation-circle', 'error');
                }
            } else {
                // La petición falló, puedes mostrar un mensaje de error
                console.log('Error en la petición:', xhr.status, xhr.statusText);
                showMessage('Error en la petición', 'fas fa-exclamation-circle', 'error');
            }
        }
    };

    xhr.send(JSON.stringify(data));
}

function showLoginModal() {
    // Crear el fondo difuminado
    const overlay = document.createElement('div');
    overlay.classList.add('modal-overlay');
    document.body.appendChild(overlay);

    // Crear el modal y su contenido
    const modalContent = `
        <div class="modal">
            <span class="close-btn" id="closeLoginModalBtn">&times;</span>
            <div class="modal-content">
                <p>Debes iniciar sesión para apuntarte a eventos o añadir a favoritos.</p>
            </div>
        </div>
    `;

    // Agregar el modal al final del body
    document.body.insertAdjacentHTML('beforeend', modalContent);

    // Mostrar el fondo y el modal
    overlay.style.display = 'block';
    document.querySelector('.modal').style.display = 'block';

    // Evitar que el clic en el fondo difuminado se propague al contenedor principal
    overlay.addEventListener('click', function (event) {
        event.stopPropagation();
    });

    // Asignar el evento onclick directamente
    const closeLoginModalBtn = document.getElementById('closeLoginModalBtn');
    closeLoginModalBtn.addEventListener('click', function () {
        // Ocultar overlay y todos los modales
        if (overlay) {
            overlay.style.display = 'none';
        }

        // Buscar y eliminar todos los modales existentes
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.parentNode.removeChild(modal);
        });
    });
}


function closeModal() {
    // Ocultar overlay y todos los modales
    overlay.style.display = 'none';

    // Buscar y eliminar todos los modales existentes
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.parentNode.removeChild(modal);
    });
}



function apuntarseAlEvento(idEvento, userId) {
    // Realizar la petición AJAX para apuntarse al evento
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "asistente.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    const data = {
        action: 'apuntarse',
        eventId: idEvento,
        userId: userId
    };

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                // La petición fue exitosa
                const response = JSON.parse(xhr.responseText);

                if (response.status === 'ok') {
                    // La operación fue exitosa, mostrar un mensaje de éxito
                    console.log('Operación exitosa:', response.result.message);
                    showMessage(response.result.message, 'fas fa-check-circle', 'success');
                } else if (response.status === 'error') {
                    // La operación falló, mostrar un mensaje de error específico
                    console.log('Error en la operación:', response.result.error_msg);
                    showMessage(response.result.error_msg, 'fas fa-exclamation-circle', 'error');
                }
            } else {
                // La petición falló, mostrar un mensaje de error genérico
                console.log('Error en la petición:', xhr.status, xhr.statusText);
                showMessage('Ya estas apuntado al evento', 'fas fa-exclamation-circle', 'error');
            }
        }
    };

    xhr.send(JSON.stringify(data));
}


function quitarApuntado(idEvento, userId) {
    // Verificar si el usuario ha iniciado sesión
    if (userId) {
        // Realizar la petición AJAX para quitar apuntado del evento
        const xhr = new XMLHttpRequest();
        xhr.open("DELETE", "asistente.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");

        const data = {
            eventId: idEvento,
            userId: userId
        };

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                // Independientemente del código de estado, parsear la respuesta JSON
                const response = JSON.parse(xhr.responseText);

                if (xhr.status === 200) {
                    // La petición fue exitosa
                    if (response.status === 'ok') {
                        // La operación fue exitosa, mostrar un mensaje de éxito
                        console.log('Operación exitosa:', response.result.message);
                        // Puedes realizar acciones adicionales aquí, como actualizar la interfaz de usuario
                        showMessage(response.result.message, 'fas fa-check-circle', 'success');

                        // Puedes realizar acciones adicionales aquí, como actualizar la interfaz de usuario
                        // Por ejemplo, cargar nuevamente el contenido del tab de "Mis Eventos"
                        loadPage('apuntados');
                    } else if (response.status === 'error') {
                        // La operación falló, mostrar un mensaje de error específico
                        console.log('Error en la operación:', response.result.error_msg);
                        showMessage(response.result.error_msg, 'fas fa-exclamation-circle', 'error');
                    }
                } else {
                    // La petición falló, mostrar un mensaje de error genérico
                    console.log('Error en la petición:', xhr.status, xhr.statusText);
                    showMessage(response.result.error_msg || 'Error en la petición', 'fas fa-exclamation-circle', 'error');
                }
            }
        };

        xhr.send(JSON.stringify(data));

    } else {
        // El usuario no ha iniciado sesión, mostrar un modal o realizar acciones adicionales
        console.log('Usuario no ha iniciado sesión');
        showLoginModal();
    }
}


function openCreateEventModal(evento) {
    console.log('hola', evento);
    // Obtén el modal por su ID
    var createEventModal = document.getElementById('createEventModal');
    document.getElementById('modalOverlay').style.display = 'block';

    // Asocia las funciones con los eventos específicos del modal
    document.getElementById('imagenEvento').addEventListener('change', handleFileSelection);
    document.getElementById('dropZone').addEventListener('dragover', handleDragOver);
    document.getElementById('dropZone').addEventListener('drop', handleFileDrop);

    // Limpia los campos y la vista previa si es un nuevo evento
    if (!evento) {
        clearForm();
    }

    if (createEventModal) {
        // Muestra el modal estableciendo su estilo de visualización en 'block'
        createEventModal.style.display = 'block';
        // Ajusta la posición vertical del modal
        createEventModal.style.marginTop = '60px'; // Ajusta la cantidad de píxeles según tus necesidades

        // Verifica si se proporciona un evento para editar
        if (evento) {
            var h2Element = document.getElementById('createEventModal').querySelector('h2');
            h2Element.textContent = 'Editar Evento: ' + evento.nombre;
            // Preenchir el formulario con los datos del evento
            var datalist = document.getElementById("suggestionList");
            datalist.innerHTML = "";
            var option = document.createElement("option");
            option.value = evento.nombreLugar;
            option.textContent = evento.nombreLugar;
            document.getElementById('botonCreateOrUpdate').innerHTML = "Editar Evento";
            document.getElementById('nombre').value = evento.nombre;
            document.getElementById('descripcion').value = evento.descripcion;
            document.getElementById('fecha').value = evento.fecha;
            document.getElementById('hora').value = evento.hora;
            document.getElementById('nombreLugar').value = evento.nombreLugar;
            document.getElementById('idLugar').value = evento.idLugar;
            document.getElementById('idUsuarioOrganizador').value = evento.idUsuarioOrganizador;
            document.getElementById('idEvento').value = evento.idEvento;

            // Establecer el modo como 'editar' en el campo oculto
            document.getElementById('modoEvento').value = 'editar';

            // Crear la etiqueta img y mostrar la imagen anterior
            var imagenEventoPreview = document.createElement('img');
            imagenEventoPreview.id = 'imagenEventoPreview';
            imagenEventoPreview.src = evento.imagenEvento;
            imagenEventoPreview.alt = 'Imagen del evento';

            // Estilos para limitar el tamaño del preview y centrarlo
            imagenEventoPreview.style.maxWidth = '25%';
            imagenEventoPreview.style.height = 'auto';
            imagenEventoPreview.style.display = 'block'; // Hacer que la imagen sea un bloque para centrarla
            imagenEventoPreview.style.margin = 'auto'; // Centrar horizontalmente

            // Insertar la etiqueta img en el documento
            var previewContainer = document.getElementById('previewContainer');
            previewContainer.innerHTML = ''; // Limpiar cualquier contenido previo
            previewContainer.appendChild(imagenEventoPreview);

            // Agregar un mensaje de "Imagen actual"
            var mensajeImagenActual = document.createElement('p');
            mensajeImagenActual.textContent = 'Imagen actual - Haz clic aqui o en seleccionar archivo para cargar una nueva';
            mensajeImagenActual.style.textAlign = 'center'; // Centrar el texto
            previewContainer.appendChild(mensajeImagenActual);

            // Simular clic en el campo input al hacer clic en la imagen
            imagenEventoPreview.addEventListener('click', function () {
                document.getElementById('imagenEvento').click();
            });

        } else {
            // Establecer el modo como 'crear' en el campo oculto
            document.getElementById('modoEvento').value = 'crear';
        }

        // Agregar un evento 'DOMContentLoaded' específico para el modal
        createEventModal.addEventListener('DOMContentLoaded', function () {
            // Agrega un oyente de eventos al input de archivo
            var imagenEventoInput = document.getElementById('imagenEvento');
            imagenEventoInput.addEventListener('change', handleFileSelection);

            // También agrega un oyente de eventos al botón de selección de archivo para manejar el clic
            var seleccionarArchivoBtn = document.querySelector('.custom-file-input');
            seleccionarArchivoBtn.addEventListener('click', function () {
                // Disparar manualmente el evento 'change' en el input de archivo
                imagenEventoInput.click();
            });
        });
    }

    function clearForm() {
        // Obtener el formulario
        var form = document.getElementById('createEventForm');

        // Limpiar los campos del formulario
        form.reset();

        // Limpiar la vista previa de la imagen
        var previewContainer = document.getElementById('previewContainer');
        previewContainer.innerHTML = '';

        // Restablecer el texto del h2 a su valor predeterminado
        var h2Element = document.getElementById('createEventModal').querySelector('h2');
        h2Element.textContent = 'Crear Nuevo Evento';
    }
}



function closeCreateEventModal() {
    // Obtén el modal por su ID
    document.getElementById('createEventModal').style.display = 'none';
    document.getElementById('modalOverlay').style.display = 'none';
    var createEventModal = document.getElementById('createEventModal');

    // Verifica si el modal existe
    if (createEventModal) {
        // Oculta el modal estableciendo su estilo de visualización en 'none'
        createEventModal.style.display = 'none';
    }
}

function handleDragOver(event) {
    event.preventDefault();
    const dropZone = document.getElementById('dropZone');
    dropZone.classList.add('drag-over');
}

function handleFileDrop(event) {
    event.preventDefault();

    // Obtener la información del archivo
    const file = event.dataTransfer.files[0];
    const fileNameElement = document.getElementById("fileName");

    // Validar que sea una imagen
    if (file && file.type.startsWith("image/")) {
        // Aquí puedes realizar acciones adicionales si es necesario

        // Actualizar el nombre del archivo
        fileNameElement.textContent = `Nombre del archivo: ${file.name}`;
        fileNameElement.style.color = "green";
    } else {
        // Actualizar el mensaje en caso de un archivo no válido
        fileNameElement.textContent = "Por favor, selecciona una imagen válida.";
        fileNameElement.style.color = "red";
    }
}

function handleFileSelection() {
    const dropZone = document.getElementById('dropZone');
    dropZone.classList.remove('drag-over');

    // Obtener la información del archivo
    const file = document.getElementById('imagenEvento').files[0];
    const fileNameElement = document.getElementById("fileName");

    // Validar que sea una imagen
    if (file && file.type.startsWith("image/")) {
        // Actualizar el nombre del archivo
        fileNameElement.textContent = `Nombre del archivo: ${file.name}`;
        fileNameElement.style.color = "green";
    } else {
        // Actualizar el mensaje en caso de un archivo no válido
        fileNameElement.textContent = "Por favor, selecciona una imagen válida.";
        fileNameElement.style.color = "red";
    }
}

async function searchPlace(input) {
    var datalist = document.getElementById("suggestionList");
    var hiddenInput = document.getElementById("idLugar");
    datalist.innerHTML = ""; // Limpiar sugerencias anteriores

    var inputValue = input.value.trim();
    if (inputValue.length >= 4) {
        try {
            // Realizar búsqueda o sugerencias aquí, por ejemplo, desde el servidor
            var response = await fetch(`lugar.php?input=${inputValue}`);
            var contentType = response.headers.get("content-type");

            if (contentType && contentType.includes("application/json")) {
                // Respuesta JSON
                var data = await response.json();

                // Verificar si hay errores en la respuesta
                if (data.status === "ok") {
                    // Agregar sugerencias al datalist
                    data.result.forEach(function (place) {
                        var option = document.createElement("option");

                        // Asignar el idLugar al value y mostrar el nombre en el contenido
                        option.value = place.nombreLugar;
                        option.textContent = place.nombreLugar;

                        datalist.appendChild(option);
                        console.log(option.value);
                    });

                    // Agregar el valor de idLugar al campo oculto
                    if (data.result.length > 0) {
                        hiddenInput.value = data.result[0].idLugar; // Asignar el idLugar de la primera sugerencia
                        console.log("Valor de idLugar asignado:", data.result[0].idLugar);
                    } else {
                        hiddenInput.value = ""; // No hay sugerencias, limpiar el valor
                        console.log("No hay sugerencias, valor de idLugar limpio.");
                    }


                    // Agregar un console.log para imprimir las sugerencias
                    console.log("Sugerencias:", data.result);
                } else {
                    // Manejar el caso de error
                    console.error("Error en la respuesta del servidor:", data.result.error_msg);
                }
            } else {
                // Respuesta no JSON (puede ser HTML)
                console.error("Respuesta no JSON:", await response.text());
                // Puedes manejar el contenido HTML según tus necesidades
            }
        } catch (error) {
            console.error("Error:", error.message);
        }
    } else {
        // Limpiar el valor del campo oculto si la longitud de entrada no es suficiente
        hiddenInput.value = "";
    }
}

function submitEventForm() {
    var eventId = document.getElementById('idEvento').value;
    var modoEvento = document.getElementById('modoEvento').value;
    var nombreEvento = document.getElementById('nombre').value;
    var descripcionEvento = document.getElementById('descripcion').value;
    var fechaEvento = document.getElementById('fecha').value;
    var horaEvento = document.getElementById('hora').value;
    var idLugarEvento = document.getElementById('idLugar').value;
    var imagenEventoInput = document.getElementById('imagenEvento');
    var idUsuarioOrganizador = document.getElementById('idUsuarioOrganizador').value;
    var modoEvento = document.getElementById('modoEvento').value;

    // Asegúrate de obtener la imagen correctamente si es necesaria
    var imagenEvento = imagenEventoInput.files.length > 0 ? imagenEventoInput.files[0] : null;

    // Obtener el valor del campo oculto idEvento
    var idEvento = document.getElementById('idEvento').value;

    // Crea un objeto FormData
    const formData = new FormData();

    // Agrega los campos al FormData solo si hay una imagen
    if (imagenEvento) {
        formData.append('imagenEvento', imagenEvento);
    }

    // Agrega los demás campos al FormData
    formData.append('idEvento', idEvento);
    formData.append('nombre', nombreEvento);
    formData.append('descripcion', descripcionEvento);
    formData.append('fecha', fechaEvento);
    formData.append('hora', horaEvento);
    formData.append('idLugar', idLugarEvento);
    formData.append('idUsuarioOrganizador', idUsuarioOrganizador);
    formData.append('modoEvento', modoEvento);


    // Determina la URL y el método en función del modoEvento
    const url = modoEvento === 'editar' ? `evento.php?idEvento=${eventId}` : 'evento.php';
    const method = modoEvento === 'editar' ? 'POST' : 'POST';

    // Realiza la solicitud fetch
    fetch(url, {
        method: method,
        body: formData,
    })
        .then(response => response.json())
        .then(data => handleEventCreation(data))
        .catch(error => console.error('Error al procesar el evento:', error))
        .finally(() => closeCreateEventModal());
}


function handleEventCreation(response) {
    // Analizar la respuesta JSON
    try {
        const redirectUrl = response.redirect_url;

        if (redirectUrl) {
            // Cambiar la vista utilizando loadPage
            loadPage(redirectUrl);

            // Mostrar mensaje de éxito con el mensaje de response
            showMessage(response.message || 'Evento creado con éxito', 'fas fa-check-circle', 'success');
        }
    } catch (error) {
        console.error("Error al analizar la respuesta JSON: ", error);

        // Mostrar mensaje de error con el mensaje de response
        showMessage(response.message || 'Error al crear el evento', 'fas fa-exclamation-circle', 'error');
    }
}

