<?php
require_once 'clases/autenticacion.class.php';
require_once 'clases/respuestas.class.php';

$_auth = new auth;
$_responses = new responses;

if ($_SERVER['REQUEST_METHOD']== 'POST'){
    //recibir datos
    $postBody = file_get_contents("php://input");
    $data = json_decode($postBody, true);
 
    // Verificar si se proporciona la acción
    if (!isset($data['action'])) {
        header('Content-Type: application/json');
        $dataArray = $_responses->error_400(); // Bad Request
        echo json_encode($dataArray);
        exit;
    }

    // Determinar la acción y llamar al método correspondiente
    $action = $data['action'];
    switch ($action) {
        case 'login':
            $dataArray = $_auth->login($postBody);
            break;

        case 'register':
            $dataArray = $_auth->registerUser($postBody);
            break;

        default:
            $dataArray = $_responses->error_400(); // Bad Request
            break;
    }
    



    //Devolvemos respuestas
    header('Content-Type: application/json');

    if(isset($dataArray["result"]["error_id"])){
        $responseCode = $dataArray["result"]["error_id"];
        http_response_code($responseCode);
    }else{
        http_response_code(200);
    }
    echo json_encode($dataArray);

}else {
    header('Content-Type: application/json');
    $dataArray = $_responses->error_405();
    echo json_encode($dataArray);
}


?>