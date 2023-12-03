<?php
require_once 'clases/favoritos.class.php';
require_once 'clases/respuestas.class.php';

$_favorite = new favorite;
$_responses = new responses;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir datos
    $postBody = file_get_contents("php://input");

    // Verificar si se recibió algún dato
    if (empty($postBody)) {
        $dataArray = $_responses->error_400("No se recibio ningun json"); // Bad Request
    } else {
        $dataArray = $_favorite->addFavorite($postBody);
    }
    // Devolver respuestas
    header('Content-Type: application/json');
    if (isset($dataArray["result"]["error_id"])) {
        $responseCode = $dataArray["result"]["error_id"];
        http_response_code(200);
    } else {
        http_response_code(200);
    }
    echo json_encode($dataArray);
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Recibir datos
    $postBody = file_get_contents("php://input");

    // Verificar si se recibió algún dato
    if (empty($postBody)) {
        $dataArray = $_responses->error_400("No se recibió ningún JSON"); // Bad Request
    } else {
        $dataArray = $_favorite->removeFavorite($postBody);
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
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Obtener el ID del usuario desde la consulta
    $userId = $_GET['userId'] ?? null;
    $action = $_GET['action'] ?? null;

    if ($action == 'getAll') {
        $dataArray = $_favorite->getAllFavorites();
    } else {
        // Validar que se proporcionó el ID del usuario
        if (empty($userId)) {
            $dataArray = $_responses->error_400("No se proporcionó el ID del usuario"); // Bad Request
        } else {
            $dataArray = $_favorite->getUserFavorites($userId);
        }
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
