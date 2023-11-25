<?php
require_once 'clases/eventos.class.php';
require_once 'clases/respuestas.class.php';

$_event = new event;
$_responses = new responses;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $requestData = array(
        'nombre' => $_POST['nombre'],
        'descripcion' => $_POST['descripcion'],
        'hora' => $_POST['hora'],
        'fecha' => $_POST['fecha'],
        'idLugar' => $_POST['idLugar'],
        'imagenEvento' => base64_encode(file_get_contents($_FILES['imagenEvento']['tmp_name'])),
        'idUsuarioOrganizador' => $_POST['idUsuarioOrganizador']
    );
    $jsonRequestData = json_encode($requestData);
    
    // Enviar los datos al método createEvent
    $dataArray = $_event->createEvent($jsonRequestData);

    // Devolver respuestas
    header('Content-Type: application/json');
    if (isset($dataArray["result"]["error_id"])) {
        $responseCode = $dataArray["result"]["error_id"];
        http_response_code($responseCode);
    } else {
        http_response_code(200);
    }
    echo json_encode($dataArray);
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {

    // Código para manejar solicitudes PUT
    // Recibir datos
    $putData = file_get_contents("php://input");
    $data = json_decode($putData, true);

    // Verificar si idEvento está presente en los datos decodificados
    if (isset($data['idEvento'])) {
        $eventId = $data['idEvento'];
    } else {
        echo "idEvento no está presente en los datos decodificados.";
    }

    // Enviar los datos al método editEvent
    $dataArray = $_event->editEvent($eventId, $putData);

    // Devolver respuestas
    header('Content-Type: application/json');
    if (isset($dataArray["result"]["error_id"])) {
        $responseCode = $dataArray["result"]["error_id"];
        http_response_code($responseCode);
    } else {
        http_response_code(200);
    }
    echo json_encode($dataArray);
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Obtener idEvento de la URL
    $postBody = file_get_contents("php://input");
    $data = json_decode($postBody, true);
    $eventId = $data['idEvento'];
    $dataArray = $_event->deleteEvent($eventId);

    // Devolver respuestas
    header('Content-Type: application/json');
    if (isset($dataArray["result"]["error_id"])) {
        $responseCode = $dataArray["result"]["error_id"];
        http_response_code($responseCode);
    } else {
        http_response_code(200);
    }
    echo json_encode($dataArray);
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        switch ($action) {
            case 'get-all-events':
                // Enviar los datos al método getAllEvents
                $dataArray = $_event->getAllEvents();
                break;
            case 'get-user-events-created':
                // Obtener el id del usuario de la URL
                $userId = $_GET['userId'];
                // Llamar al método getEventsByUser
                $dataArray = $_event->getEventsByUser($userId);
                break;
            default:
                // Acción no válida
                $dataArray = $_responses->error_400(); // Bad Request
        }

        // Devolver respuestas
        header('Content-Type: application/json');
        if (isset($dataArray["result"]["error_id"])) {
            $responseCode = $dataArray["result"]["error_id"];
            http_response_code($responseCode);
        } else {
            http_response_code(200);
        }
        echo json_encode($dataArray);
    } else {
        $dataArray = $_responses->error_400(); // Bad Request
        header('Content-Type: application/json');
        http_response_code($dataArray["result"]["error_id"]);
        echo json_encode($dataArray);
    }
} else {
    header('Content-Type: application/json');
    $dataArray = $_responses->error_405();
    echo json_encode($dataArray);
}
