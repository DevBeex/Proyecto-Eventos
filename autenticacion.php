<?php
require_once 'clases/autenticacion.class.php';
require_once 'clases/respuestas.class.php';

$_auth = new auth;
$_responses = new responses;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Verificar si se proporciona la acción
    if (!isset($_POST['action'])) {
        header('Content-Type: application/json');
        $dataArray = $_responses->error_400("No se proporcionó la acción correctamente."); // Bad Request
        echo json_encode($dataArray);
        exit;
    }

    // Acceder directamente a $_POST
    $action = $_POST['action'];

    switch ($action) {
        case 'login':
            $requestData = array(
                'action' => $_POST['action'],
                'correoElectronico' => $_POST['correoElectronico'],
                'contrasena' => $_POST['contrasena']
                // Puedes agregar más variables aquí si es necesario
            );
            $jsonRequestData = json_encode($requestData);
            $dataArray = $_auth->login($jsonRequestData);
            // Verificar si el inicio de sesión fue exitoso
            if ($dataArray['status'] === 'ok') {
                $_SESSION['mensaje'] = "¡Inicio de sesión exitoso para el usuario '$correoElectronico'!";
                // Redirigir a inicio.php con el mensaje como parámetro de URL
                // header('Location: index.php?mensaje=exito');
                echo '<script>';
                echo 'window.location.href = "index.php?mensaje=exito";';
                echo '</script>';
                exit();
            }
            break;
        case 'register':
            $requestData = array(
                'action' => $_POST['action'],
                'nombre' => $_POST['nombre'],
                'apellido' => $_POST['apellido'],
                'correoElectronico' => $_POST['correoElectronico'],
                'contrasena' => $_POST['contrasena'],
                'rol' => $_POST['rol']
                // Puedes agregar más variables aquí si es necesario
            );
            $jsonRequestData = json_encode($requestData);
            $dataArray = $_auth->registerUser($jsonRequestData);

            // Verificar si el registro fue exitoso
            if ($dataArray['status'] === 'ok') {
                // Redirigir a inicio.php con el mensaje como parámetro de URL
                header('Location: index.php?mensaje=registro_exito');
                exit();
            } else {
                // Verificar si hay un error relacionado con el correo electrónico duplicado
                if ($dataArray['result']['error_id'] === '400') {
                    // Mostrar la alerta directamente en el HTML
                    echo '<script>alert("¡Error en el registro! El correo electrónico ya está registrado. Por favor, utiliza otro.");</script>';
                    // Redirigir a la página de inicio o a donde desees
                    echo '<script>window.location.href = "index.php";</script>';
                    exit();
                } else {
                    // Mostrar una alerta de registro fallido por otros motivos
                    echo '<script>alert("¡Error en el registro! Por favor, inténtalo de nuevo.");</script>';
                }
            }
            break;

        case 'logout':
            // Archivo para cerrar la sesión del usuario
            session_destroy();

            // Respuesta de éxito (puedes personalizar según tus necesidades)
            $response = array(
                'status' => 'success',
                'message' => 'Sesión cerrada con éxito',
            );

            // Muestra la alerta antes de redirigir
            echo '<script>alert("Cierre de sesión exitoso");</script>';
            echo '<script>window.setTimeout(function(){ window.location.href = "index.php"; }, 1000);</script>';
            exit();
            break;

        case 'editUser':
            $requestData = array(
                'action' => $_POST['action'],
                'idUsuario' => $_POST['idUsuario'],
                'nombre' => $_POST['nombre'],
                'apellido' => $_POST['apellido'],
                'correoElectronico' => $_POST['correoElectronico'],
                'contrasena' => $_POST['contrasena']
            );
            $jsonRequestData = json_encode($requestData);
            $dataArray = $_auth->editUser($jsonRequestData);
            break;

        case 'changeAdmin':
            $idUsuario = $_POST['idUsuario'];
            $dataArray = $_auth->changeToAdmin($idUsuario);
            break;

        case 'changeUsuario':
            $idUsuario = $_POST['idUsuario'];
            $dataArray = $_auth->changeToUser($idUsuario);
            break;                                                                                                                                                                                                                                          

        default:
            $dataArray = $_responses->error_400(); // Bad Request
            break;
    }


    //Devolvemos respuestas
    header('Content-Type: application/json');

    if (isset($dataArray["result"]["error_id"])) {
        $responseCode = $dataArray["result"]["error_id"];
        http_response_code(200);
    } else {
        http_response_code(200);
    }
    echo json_encode($dataArray);
}else if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    $dataArray = $_auth->getAllUsers();

    //Devolvemos respuestas
    header('Content-Type: application/json');

    if (isset($dataArray["result"]["error_id"])) {
        $responseCode = $dataArray["result"]["error_id"];
        http_response_code(200);
    } else {
        http_response_code(200);
    }
    echo json_encode($dataArray);

} else {
    header('Content-Type: application/json');
    $dataArray = $_responses->error_405();
    echo json_encode($dataArray);
}
