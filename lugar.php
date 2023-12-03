<?php
require_once 'clases/lugares.class.php';
require_once 'clases/respuestas.class.php';

$_lugar = new Lugar();
$_responses = new Responses();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $dataArray = $_lugar->getLugares();

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
    header('Content-Type: application/json');
    $dataArray = $_responses->error_405();
    echo json_encode($dataArray);
}
?>
