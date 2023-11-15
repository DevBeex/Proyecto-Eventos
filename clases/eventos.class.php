<?php
require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class event extends conection
{
    //atributos de evento: idEvento, nombre, descripcion, fecha, hora, idUsuarioOrganizador, idLugarm imagenEvento

    private function getImageExtension($base64String)
    {
        $data = explode(',', $base64String);
        $mime = substr($data[0], 5, -7);
        $extensions = [
            'jpeg' => 'jpg',
            'jpg' => 'jpg',
            'png' => 'png',
            // Agrega más extensiones según tus necesidades
        ];
        return $extensions[$mime] ?? 'jpg';
    }

    private function saveImageToServer($base64String, $destinationPath)
    {
        $decodedImage = base64_decode($base64String);
        return file_put_contents($destinationPath, $decodedImage);
    }

    private function validateFields($data, $requiredFields)
    {
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return false;
            }
        }
        return true;
    }

    private function getImagePath($eventId)
    {
        $query = "SELECT imagenEvento FROM evento WHERE idEvento = '$eventId'";
        $result = parent::getData($query);

        if ($result && isset($result[0]['imagenEvento'])) {
            return $result[0]['imagenEvento'];
        }

        return null;
    }

    public function createEvent($json)
    {
        $_responses = new responses;
        $data = json_decode($json, true);

        if (!isset($data['nombre']) || !isset($data['descripcion']) || !isset($data['fecha']) || !isset($data['hora']) || !isset($data['idUsuarioOrganizador']) || !isset($data['idLugar']) || !isset($data['imagenEvento'])) {
            return $_responses->error_400(); // Bad Request
        }

        // Recoger datos del formulario
        $nombre = $data['nombre'];
        $descripcion = $data['descripcion'];
        $fecha = $data['fecha'];
        $hora = $data['hora'];
        $idUsuarioOrganizador = $data['idUsuarioOrganizador'];
        $idLugar = $data['idLugar'];
        $imagenEvento = $data['imagenEvento'];

        // Ruta donde se guardará la imagen en el servidor
        $rutaDestino = './imagenes/';

        // Verificar si la carpeta existe, si no, intentar crearla
        if (!file_exists($rutaDestino) && !mkdir($rutaDestino, 0777, true)) {
            return $_responses->error_500("Error al crear la carpeta de imágenes en el servidor"); // Internal Server Error
        }

        // Nombre único para la imagen
        $nombreImagen = uniqid() . '_' . $nombre . '.jpg';  // Cambia la extensión según el tipo de imagen que estás manejando

        // Ruta completa del archivo en el servidor
        $rutaCompleta = $rutaDestino . $nombreImagen;

        // Guardar la imagen en el servidor
        if ($this->saveImageToServer($imagenEvento, $rutaCompleta)) {
            // Insertar el evento en la base de datos
            $query = "INSERT INTO evento (nombre, descripcion, fecha, hora, idUsuarioOrganizador, idLugar, imagenEvento) VALUES ('$nombre', '$descripcion', '$fecha', '$hora', '$idUsuarioOrganizador', '$idLugar', '$rutaCompleta')";
            $result = parent::nonQuery($query);

            if ($result) {
                $mensaje = $_responses->response;
                $mensaje['result'] = array(
                    "message" => "Evento [$nombre] registrado exitosamente",
                    "organizador" => "id del organizador: [$idUsuarioOrganizador]"
                );
                return $mensaje;
            } else {
                // Eliminar la imagen si no se puede insertar el evento en la base de datos
                unlink($rutaCompleta);
                return $_responses->error_500("No hemos podido registrar el evento debido a algún problema, posiblemente de atributos"); // Internal Server Error
            }
        } else {
            return $_responses->error_500("Error al guardar la imagen en el servidor"); // Internal Server Error
        }
    }

    public function editEvent($eventId, $json)
    {
        $_responses = new responses;
        $data = json_decode($json, true);

        // Verificar si todos los campos necesarios están presentes
        $requiredFields = ['nombre', 'descripcion', 'fecha', 'hora', 'idLugar'];
        if (!$this->validateFields($data, $requiredFields)) {
            return $_responses->error_400(); // Bad Request
        }

        // Recoger datos del formulario
        $nombre = $data["nombre"];
        $descripcion = $data["descripcion"];
        $fecha = $data["fecha"];
        $hora = $data["hora"];
        $idLugar = $data["idLugar"];

        // Verificar si se proporcionó una nueva imagen
        $imagenEvento = $data['imagenEvento'] ?? null;

        // Ruta donde se guardará la imagen en el servidor
        $rutaDestino = './imagenes/';

        // Obtener la ruta actual de la imagen en la base de datos
        $rutaImagenAnterior = $this->getImagePath($eventId);

        // Actualizar la imagen solo si se proporciona una nueva
        if ($imagenEvento) {
            // Nombre único para la imagen
            $nombreImagen = uniqid() . '_' . $nombre . '.' . $this->getImageExtension($imagenEvento);

            // Ruta completa del archivo en el servidor
            $rutaCompleta = $rutaDestino . $nombreImagen;

            // Guardar la nueva imagen en el servidor
            if ($this->saveImageToServer($imagenEvento, $rutaCompleta)) {
                // Actualizar la base de datos con la nueva ruta de la imagen
                $query = "UPDATE evento SET nombre = '$nombre', descripcion = '$descripcion', fecha = '$fecha', hora = '$hora', idLugar = '$idLugar', imagenEvento = '$rutaCompleta' WHERE idEvento = '$eventId'";
                // Eliminar la imagen anterior si la actualización es exitosa
                if (file_exists($rutaImagenAnterior)) {
                    unlink($rutaImagenAnterior);
                }
            } else {
                return $_responses->error_500("Error al guardar la nueva imagen en el servidor"); // Internal Server Error
            }
        } else {
            // No se proporcionó una nueva imagen, solo actualizar otros campos
            $query = "UPDATE evento SET nombre = '$nombre', descripcion = '$descripcion', fecha = '$fecha', hora = '$hora', idLugar = '$idLugar' WHERE idEvento = '$eventId'";
        }

        // Realizar la actualización en la base de datos
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

    public function deleteEvent($eventId)
    {
        $_responses = new responses;

        // Validar que $eventId no esté vacío
        if (empty($eventId)) {
            return $_responses->error_400(); // Bad Request
        }

        // Obtener la ruta de la imagen antes de eliminar el evento
        $rutaImagen = $this->getImagePath($eventId);

        // Antes de eliminar el evento, elimina los registros relacionados en la tabla EventoFavorito
        $queryDeleteFavoritos = "DELETE FROM eventofavorito WHERE idEvento = '$eventId'";
        $resultDeleteFavoritos = parent::nonQuery($queryDeleteFavoritos);

        // Antes de eliminar el evento, elimina los registros relacionados en la tabla Asistente
        $queryDeleteAsistentes = "DELETE FROM asistente WHERE idEvento = '$eventId'";
        $resultDeleteAsistentes = parent::nonQuery($queryDeleteAsistentes);

        // Después de eliminar los registros en EventoFavorito y asistente, eliminar el evento
        $queryDeleteEvento = "DELETE FROM evento WHERE idEvento = '$eventId'";
        $resultDeleteEvento = parent::nonQuery($queryDeleteEvento);

        if ($resultDeleteEvento) {
            // Éxito al eliminar el evento

            // Eliminar la imagen asociada al evento si existe
            if ($rutaImagen && file_exists($rutaImagen)) {
                unlink($rutaImagen);
            }

            $response = $_responses->response;
            $response["result"] = array("message" => "Evento eliminado exitosamente");
            return $response;
        } else {
            // Error interno del servidor
            return $_responses->error_500("Error al eliminar el evento");
        }
    }



    public function getAllEvents()
    {
        $_responses = new responses;

        // Obtener todos los eventos de la base de datos
        $query = "SELECT * FROM evento";
        $events = parent::getData($query);

        if ($events) {
            // Éxito al obtener los eventos
            $response = $_responses->response;
            $response["result"] = $events;
            return $response;
        } else {
            // Error interno del servidor
            return $_responses->error_500();
        }
    }

    public function getEventsByUser($userId)
    {
        $_responses = new responses;

        // Validar que $userId no esté vacío
        if (empty($userId)) {
            return $_responses->error_400(); // Bad Request
        }

        // Consultar eventos creados por el usuario
        $query = "SELECT * FROM evento WHERE idUsuarioOrganizador = '$userId'";
        $events = parent::getData($query);

        if ($events) {
            // Éxito al obtener eventos
            $response = $_responses->response;
            $response["result"] = array("events" => $events);
            return $response;
        } else {
            // Error interno del servidor
            return $_responses->error_500("El usuario no tiene eventos creados");
        }
    }
}
