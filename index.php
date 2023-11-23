<?php require_once './clases/eventos.class.php';
session_start();
// var_dump($_SESSION);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Mi Sitio Web</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="app">
        <?php include 'componentes/navbar.php'; ?>
        <?php include 'paginas/login.php'; ?>
        <div id="content-container">
            <?php include 'paginas/inicio.php'; ?> <!-- Página de inicio como predeterminada -->
        </div>
    </div>

    <script src="script.js" defer></script>
</body>
</html>
