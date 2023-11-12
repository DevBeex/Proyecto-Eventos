<?php
require_once 'clases/eventos.class.php';
require_once 'clases/respuestas.class.php';

$_event = new event;
$_responses = new responses;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir datos
    $postBody = file_get_contents("php://input");

    // Enviar los datos al método createEvent
    $dataArray = $_event->createEvent($postBody);

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
    $dataArray = $_event->editEvent($eventId,$putData);

    // Devolver respuestas
    header('Content-Type: application/json');
    if (isset($dataArray["result"]["error_id"])) {
        $responseCode = $dataArray["result"]["error_id"];
        http_response_code($responseCode);
    } else {
        http_response_code(200);
    }
    echo json_encode($dataArray);

}elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
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
}else {
    header('Content-Type: application/json');
    $dataArray = $_responses->error_405();
    echo json_encode($dataArray);
}


?>