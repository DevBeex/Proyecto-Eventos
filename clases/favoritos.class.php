<?php
require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class favorite extends conection
{

    public function addFavorite($json)
    {
        $_responses = new responses;
        $data = json_decode($json, true);
        $eventId = $data['eventId'];
        $userId = $data['userId'];

        // Validar que $eventId y $userId no estén vacíos
        if (empty($eventId) || empty($userId)) {
            return $_responses->error_400("Error al obtener id event o user id"); // Bad Request
        }

        // Verificar si el evento existe
        if (!$this->eventExists($eventId)) {
            return $_responses->error_400("El evento no existe"); // Bad Request
        }

        // Verificar si el evento ya está en favoritos para este usuario
        if ($this->isEventInFavorites($eventId, $userId)) {
            // El evento ya está en favoritos, devolver mensaje o código de estado apropiado
            return $_responses->error_400("El evento ya está en favoritos para este usuario");
        }

        // Agregar el evento a favoritos para el usuario
        $query = "INSERT INTO eventofavorito (idEvento, idUsuario) VALUES ('$eventId', '$userId')";
        $result = parent::nonQuery($query);

        if ($result) {
            // Éxito al agregar a favoritos
            $response = $_responses->response;
            $response["result"] = array("message" => "Evento agregado a favoritos exitosamente");
            return $response;
        } else {
            // Error interno del servidor
            return $_responses->error_500("Error al agregar el evento a favoritos, puede que este evento ya no exista");
        }
    }

    public function removeFavorite($json) {
        $_responses = new responses;
        $data = json_decode($json, true);
        $eventId = $data['eventId'];
        $userId = $data['userId'];
    
        // Validar que $eventId y $userId no estén vacíos
        if (empty($eventId) || empty($userId)) {
            return $_responses->error_400("Error al obtener id event o user id"); // Bad Request
        }
    
        // Verificar si el evento existe
        if (!$this->eventExists($eventId)) {
            return $_responses->error_400("El evento no existe"); // Bad Request
        }
    
        // Verificar si el evento está en favoritos para este usuario
        if (!$this->isEventInFavorites($eventId, $userId)) {
            // El evento no está en favoritos, devolver mensaje o código de estado apropiado
            return $_responses->error_400("El evento no está en favoritos para este usuario");
        }
    
        // Eliminar el evento de favoritos para el usuario
        $query = "DELETE FROM eventofavorito WHERE idEvento = '$eventId' AND idUsuario = '$userId'";
        $result = parent::nonQuery($query);
    
        if ($result) {
            // Éxito al remover de favoritos
            $response = $_responses->response;
            $response["result"] = array("message" => "Evento eliminado de favoritos exitosamente");
            return $response;
        } else {
            // Error interno del servidor
            return $_responses->error_500("Error al eliminar el evento de favoritos");
        }
    }

    public function getUserFavorites($userId)
{
    $_responses = new responses;

    // Validar que $userId no esté vacío
    if (empty($userId)) {
        return $_responses->error_400("Error al obtener el ID del usuario"); // Bad Request
    }

    // Obtener todos los eventos favoritos del usuario
    $query = "SELECT e.idEvento, e.nombre, e.descripcion, e.fecha, e.hora, e.idUsuarioOrganizador, e.idLugar, e.imagenEvento
              FROM eventofavorito ef
              JOIN evento e ON ef.idEvento = e.idEvento
              WHERE ef.idUsuario = '$userId'";
    $result = parent::getData($query);

    if (!empty($result)) {
        // Éxito al obtener los eventos favoritos
        $response = $_responses->response;
        $response["result"] = $result;
        return $response;
    } else {
        // No se encontraron eventos favoritos para el usuario
        return $_responses->error_400("No se encontraron eventos favoritos para este usuario");
    }
}

    
    private function isEventInFavorites($eventId, $userId)
    {
        // Verificar si el evento ya está en favoritos para este usuario
        $query = "SELECT * FROM eventofavorito WHERE idEvento = '$eventId' AND idUsuario = '$userId'";
        $result = parent::getData($query);

        return !empty($result);
    }

    private function eventExists($eventId)
    {
        // Verificar si el evento existe
        $query = "SELECT idEvento FROM evento WHERE idEvento = '$eventId'";
        $result = parent::getData($query);

        return !empty($result);
    }

    public function getAllFavorites()
{
    $_responses = new responses;

    // Obtener todos los registros de la tabla eventofavorito
    $query = "SELECT * FROM eventofavorito";
    $result = parent::getData($query);

    if (!empty($result)) {
        // Éxito al obtener todos los registros de favoritos
        $response = $_responses->response;
        $response["result"] = $result;
        return $response;
    } else {
        // No se encontraron registros de favoritos
        return $_responses->error_400("No se encontraron registros en la tabla de favoritos");
    }
}

}
