<?php
require_once 'clases/eventos.class.php';
require_once 'clases/respuestas.class.php';

$_event = new event;
$_responses = new responses;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $eventId = $_POST['idEvento'];
    $modoEvento = $_POST['modoEvento'];

    if ($modoEvento == 'crear'){
        // Verifica si se ha cargado una imagen
        if (isset($_FILES['imagenEvento']['tmp_name']) && !empty($_FILES['imagenEvento']['tmp_name'])) {
            // Si hay una imagen cargada, conviértela a base64
            $imagenEvento = base64_encode(file_get_contents($_FILES['imagenEvento']['tmp_name']));
        } else {
            // Si no hay imagen cargada, utiliza la imagen predeterminada en base64
            $imagenEvento = base64_encode(file_get_contents('images/default.jpg'));
        }
    }else if ($modoEvento == 'editar'){
        if (isset($_FILES['imagenEvento']['tmp_name']) && !empty($_FILES['imagenEvento']['tmp_name'])) {
            // Si hay una imagen cargada, conviértela a base64
            $imagenEvento = base64_encode(file_get_contents($_FILES['imagenEvento']['tmp_name']));
        } else {
            // Si no hay imagen cargada, utiliza la imagen predeterminada en base64
            $imagenEvento = null;
        }
    }

    $requestData = array(
        'nombre' => $_POST['nombre'],
        'descripcion' => $_POST['descripcion'],
        'hora' => $_POST['hora'],
        'fecha' => $_POST['fecha'],
        'idLugar' => $_POST['idLugar'],
        'imagenEvento' => $imagenEvento,
        'idUsuarioOrganizador' => $_POST['idUsuarioOrganizador']
    );

    $jsonRequestData = json_encode($requestData);

    // Validar el modo del evento (crear o editar)
    $modoEvento = isset($_POST['modoEvento']) ? $_POST['modoEvento'] : '';

    if ($modoEvento === 'editar') {
        // Enviar los datos al método editEvent
        $dataArray = $_event->editEvent($eventId, $jsonRequestData);
    } elseif ($modoEvento === 'crear') {
        // Enviar los datos al método createEvent
        $dataArray = $_event->createEvent($jsonRequestData);
    } else {
        // Manejar un caso no válido
        $dataArray = array("result" => array("error_id" => "modo_invalido"));
    }

    // Devolver respuestas
    header('Content-Type: application/json');

    if (isset($dataArray["result"]["error_id"])) {
        $responseCode = $dataArray["result"]["error_id"];
        http_response_code(200);
        echo json_encode($dataArray);
    } else {
        // Éxito al crear o editar el evento
        echo json_encode($dataArray["result"]);
    }
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
