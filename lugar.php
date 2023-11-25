<?php
require_once 'clases/lugares.class.php';
require_once 'clases/respuestas.class.php';

$_lugar = new lugar;
$_responses = new responses;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (isset($_GET['input'])) {

        $dataArray = $_lugar->getPlaces($_GET['input']);
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
