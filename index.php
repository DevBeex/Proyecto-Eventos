<?php
// Este es tu archivo principal que redirige a las páginas según sea necesario
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'login':
        include('paginas/login.php');
        break;
    case 'register':
        include 'paginas/registrar.php';
        break;
    default:
        include 'paginas/inicio.php';
        break;
}
?>
