<?php
require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class Lugar extends conection
{
    public function getLugares()
    {
        $_responses = new responses();

        // Consulta para obtener todos los datos de los lugares
        $query = "SELECT * FROM lugar";
        $resultado = parent::getData($query);

        if ($resultado) {
            // Éxito al obtener los lugares
            $response = $_responses->response;
            $response["result"] = $resultado;
            return $response;
        } else {
            // Error interno del servidor
            return $_responses->error_500("No se pudieron obtener los datos de los lugares");
        }
    }
}
