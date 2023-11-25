<?php
require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class lugar extends conection
{
    public function getPlaces($entrada)
    {
        $_responses = new responses();

        // Consulta para buscar lugares que coincidan con la entrada del usuario
        $query = "SELECT idLugar, nombreLugar FROM lugar WHERE nombreLugar LIKE '%$entrada%'";
        $resultado = parent::getData($query);

        if ($resultado) {
            // Éxito al obtener los eventos
            $response = $_responses->response;
            $response["result"] = $resultado;
            return $response;
        } else {
            // Error interno del servidor
            return $_responses->error_500("no se pudieron obtener la lista de lugares");
        }
    }
}
