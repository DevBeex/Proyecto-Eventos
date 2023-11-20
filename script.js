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
