<?php

require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class Assistant extends conection
{
    public function assignAssistant($json)
    {
        $data = json_decode($json, true);
        $eventId = $data['eventId'];
        $userId = $data['userId'];
        $_responses = new responses;

        // Validar que $eventId y $userId no estén vacíos
        if (empty($eventId) || empty($userId)) {
            return $_responses->error_400("Error al obtener ID de evento o ID de usuario"); // Bad Request
        }

        // Verificar si el evento existe
        if (!$this->eventExists($eventId)) {
            return $_responses->error_400("El evento no existe"); // Bad Request
        }

        // Verificar si el usuario existe
        if (!$this->userExists($userId)) {
            return $_responses->error_400("El usuario no existe"); // Bad Request
        }

        // Verificar si el asistente ya está asignado al evento
        if ($this->isAssistantAssigned($eventId, $userId)) {
            return $_responses->error_400("El asistente ya está asignado a este evento");
        }

        // Asignar el asistente al evento
        $query = "INSERT INTO asistente (idEvento, idUsuarioAsistente) VALUES ('$eventId', '$userId')";
        $result = parent::nonQuery($query);

        if ($result) {
            // Éxito al asignar el asistente
            $response = $_responses->response;
            $response["result"] = array("message" => "Asistente asignado exitosamente al evento");
            return $response;
        } else {
            // Error interno del servidor
            return $_responses->error_500("Error al asignar el asistente al evento");
        }
    }

    public function getEventParticipants($eventId)
    {
        $_responses = new responses;

        // Validar que el evento existe
        if (!$this->eventExists($eventId)) {
            return $_responses->error_400("El evento no existe"); // Bad Request
        }

        // Obtener la lista de asistentes para el evento
        $query = "SELECT u.* FROM usuario u
            INNER JOIN asistente a ON u.idUsuario = a.idUsuarioAsistente
            WHERE a.idEvento = '$eventId'";
        $result = parent::getData($query);


        // Éxito al obtener la lista de asistentes
        $response = $_responses->response;
        $response["result"] = $result;
        return $response;
    }

    private function isAssistantAssigned($eventId, $userId)
    {
        // Verificar si el asistente ya está asignado al evento
        $query = "SELECT * FROM asistente WHERE idEvento = '$eventId' AND idUsuarioAsistente = '$userId'";
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

    private function userExists($userId)
    {
        // Verificar si el usuario existe
        $query = "SELECT idUsuario FROM usuario WHERE idUsuario = '$userId'";
        $result = parent::getData($query);

        return !empty($result);
    }
}
