<?php

require_once 'clases/asistentes.class.php';
require_once 'clases/respuestas.class.php';

$_assistant = new Assistant;
$_responses = new responses;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir datos
    $postBody = file_get_contents("php://input");

    $dataArray = $_assistant->assignAssistant($postBody);
    
    // Devolver respuestas
    header('Content-Type: application/json');
    if (isset($dataArray["result"]["error_id"])) {
        $responseCode = $dataArray["result"]["error_id"];
        http_response_code($responseCode);
    } else {
        http_response_code(200);
    }
    echo json_encode($dataArray);
}elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Obtener el ID del evento desde la URL
    $eventId = $_GET['eventId'] ?? null;

    // Validar que se proporcionó el ID del evento
    if (!$eventId) {
        $dataArray = $_responses->error_400("No se proporcionó el ID del evento"); // Bad Request
    } else {
        $dataArray = $_assistant->getEventParticipants($eventId);
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
    // Método no permitido
    header('Content-Type: application/json');
    $dataArray = $_responses->error_405();
    http_response_code($dataArray["result"]["error_id"]);
    echo json_encode($dataArray);
}
?>
