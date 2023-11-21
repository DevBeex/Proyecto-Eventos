const navbarMenu = document.querySelector(".navbar .links");
const hamburgerBtn = document.querySelector(".hamburger-btn");
const hideMenuBtn = navbarMenu.querySelector(".close-btn");
const showPopupBtn = document.querySelector(".login-btn");
const formPopup = document.querySelector(".form-popup");
const hidePopupBtn = formPopup.querySelector(".close-btn");
const signupLoginLink = formPopup.querySelectorAll(".bottom-link a");
const contentContainer = document.getElementById("content-container");

// Show mobile menu
hamburgerBtn.addEventListener("click", () => {
    navbarMenu.classList.toggle("show-menu");
});

// Hide mobile menu
hideMenuBtn.addEventListener("click", () =>  hamburgerBtn.click());

// Show login popup
showPopupBtn.addEventListener("click", () => {
    document.body.classList.toggle("show-popup");
});

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
    xhttp.onreadystatechange = function() {
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

// Función para manejar la lógica de apuntarse al evento
function apuntarseAlEvento(idEvento) {
    // Aquí puedes realizar las acciones necesarias
    console.log(`Apuntándote al evento ${idEvento}`);
}

// Función para manejar la lógica de añadir a favoritos
function añadirAFavoritos(idEvento) {
    // Aquí puedes realizar las acciones necesarias
    console.log(`Añadiendo a favoritos el evento ${idEvento}`);
}

function handleEventAction(eventId, action) {
    // Verificar si el usuario ha iniciado sesión
    const userId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;

    if (userId) {
        // El usuario ha iniciado sesión, puedes continuar con el flujo normal
        if (action === 'apuntarse') {
            apuntarseAlEvento(eventId);
        } else if (action === 'favoritos') {
            añadirAFavoritos(eventId);
        }
    } else {
        // El usuario no ha iniciado sesión, mostrar un modal o realizar acciones adicionales
        showLoginModal();
    }
}

function showLoginModal() {
    // Crear el fondo difuminado
    const overlay = document.createElement('div');
    overlay.classList.add('modal-overlay');
    document.body.appendChild(overlay);

    // Crear el modal y su contenido
    const modalContent = `
        <div class="modal">
            <span class="close-btn" onclick="closeModal()">&times;</span>
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
}


function closeModal() {
    // Ocultar el fondo y el modal
    const overlay = document.querySelector('.modal-overlay');
    const modal = document.querySelector('.modal');
    
    if (overlay && modal) {
        overlay.style.display = 'none';
        modal.style.display = 'none';

        // Eliminar el fondo difuminado y el modal del DOM
        overlay.remove();
        modal.remove();
    }
}

// Agrega esta función para mostrar mensajes
function showMessage(message, iconClass, messageType) {
    const modalContent = `
        <div class="modal">
            <div class="modal-content ${messageType}">
                <span class="close-btn" onclick="closeModal()">&times;</span>
                <i class="${iconClass}"></i>
                <p>${message}</p>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', modalContent);
}

// ... Otro código JavaScript ...