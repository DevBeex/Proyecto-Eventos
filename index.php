<?php require_once './clases/eventos.class.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.8">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" type="image/png" href="images/evento.png">
    <title>Eventos FISC</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="app">
    <?php session_start(); ?>
        <?php include 'componentes/navbar.php'; ?>
        <?php include 'paginas/login.php'; ?>
        <div id="content-container">
            <?php include 'paginas/inicio.php'; ?> <!-- Página de inicio como predeterminada -->
        </div>
    </div>

    <script src="script.js" defer></script>
</body>
</html>