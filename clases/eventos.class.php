<?php
require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class event extends conection{
    //atributos de evento: idEvento, nombre, descripcion, fecha, hora, idUsuarioOrganizador, idLugar

    public function createEvent($json) {
        $_responses = new responses;
        $data = json_decode($json, true);

        if (!isset($data['nombre']) || !isset($data['descripcion']) || !isset($data['fecha']) || !isset($data['hora']) || !isset($data['idUsuarioOrganizador']) || !isset($data['idLugar'])) {
            return $_responses->error_400(); // Bad Request
        }

        // Recoger datos del formulario
        $nombre = $data['nombre'];
        $descripcion = $data['descripcion'];
        $fecha = $data['fecha'];
        $hora = $data['hora'];
        $idUsuarioOrganizador = $data['idUsuarioOrganizador'];
        $idLugar = $data['idLugar'];

        // Insertar el evento en la base de datos
        $query = "INSERT INTO evento (nombre, descripcion, fecha, hora, idUsuarioOrganizador, idLugar) VALUES ('$nombre', '$descripcion', '$fecha', '$hora', '$idUsuarioOrganizador', '$idLugar')";
        $result = parent::nonQuery($query);

        if ($result) {
            $mensaje = $_responses->response;
            $mensaje['result'] = array(
                "message" => "Evento [". $data['nombre'] . "] registrado exitosamente",
                "organizador" => "id del organizador: [". $data['idUsuarioOrganizador']. "]"
            );
            return $mensaje;
        } else {
            return $_responses->error_500("No hemos podido registrar el evento debido a algun problema posiblemente de atributos"); // Internal Server Error
        }
    }

    public function editEvent($eventId, $json) {
        $_responses = new responses;
        $data = json_decode($json, true);

        if (!isset($data["nombre"]) || !isset($data["descripcion"]) || !isset($data["fecha"]) || !isset($data["hora"]) || !isset($data["idLugar"])) {
            // Faltan parámetros
            return $_responses->error_400();
        } else {
            $nombre = $data["nombre"];
            $descripcion = $data["descripcion"];
            $fecha = $data["fecha"];
            $hora = $data["hora"];
            $idLugar = $data["idLugar"];

            // Realizar la actualización en la base de datos usando $event_id
            $query = "UPDATE evento SET nombre = '$nombre', descripcion = '$descripcion', fecha = '$fecha', hora = '$hora', idLugar = '$idLugar' WHERE idEvento = '$eventId'";
            $result = parent::nonQuery($query);

            if ($result) {
                // Éxito al editar el evento
                $response = $_responses->response;
                $response["result"] = array("message" => "Evento $nombre editado exitosamente");
                return $response;
            } else {
                // Error interno del servidor
                return $_responses->error_500();
            }
        }
    }

    public function deleteEvent($eventId) {
        $_responses = new responses;
    
        // Validar que $eventId no esté vacío
        if (empty($eventId)) {
            return $_responses->error_400(); // Bad Request
        }
    
        // Antes de eliminar el evento, elimina los registros relacionados en la tabla EventoFavorito
        $queryDeleteFavoritos = "DELETE FROM eventofavorito WHERE idEvento = '$eventId'";
        $resultDeleteFavoritos = parent::nonQuery($queryDeleteFavoritos);
    
        if (!$resultDeleteFavoritos) {
            // Si hubo un error al eliminar los registros en EventoFavorito, devolver error
            return $_responses->error_500();
        }
    
        // Antes de eliminar el evento, elimina los registros relacionados en la tabla Asistente
        $queryDeleteAsistentes = "DELETE FROM asistente WHERE idEvento = '$eventId'";
        $resultDeleteAsistentes = parent::nonQuery($queryDeleteAsistentes);
    
        if (!$resultDeleteAsistentes) {
            // Si hubo un error al eliminar los registros en asistente, devolver error
            return $_responses->error_500();
        }
    
        // Después de eliminar los registros en EventoFavorito y asistente, eliminar el evento
        $queryDeleteEvento = "DELETE FROM evento WHERE idEvento = '$eventId'";
        $resultDeleteEvento = parent::nonQuery($queryDeleteEvento);
    
        if ($resultDeleteEvento) {
            // Éxito al eliminar el evento
            $response = $_responses->response;
            $response["result"] = array("message" => "Evento eliminado exitosamente");
            return $response;
        } else {
            // Error interno del servidor
            return $_responses->error_500();
        }
    }
    

    
}


?>