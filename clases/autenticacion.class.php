<?php
require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';
session_start();

class Auth extends conection
{

    public function login($json)
    {
        $_responses = new responses;
        $data = json_decode($json, true);

        if (!isset($data["correoElectronico"]) || !isset($data["contrasena"])) {
            // Error
            return $_responses->error_400();
        } else {
            $correoElectronico = $data["correoElectronico"];
            $contrasena = $data["contrasena"];
            $contrasena = parent::encript($contrasena);
            $userData = $this->getUserData($correoElectronico);

            if ($userData) {
                // Verificar si la contraseña es igual
                if ($contrasena == $userData[0]["contrasena"]) {
                    // La contraseña es igual
                    // Almacenar datos del usuario en sesión
                    $_SESSION['usuario'] = array(
                        'idUsuario' => $userData[0]['idUsuario'],
                        'nombre' => $userData[0]['nombre'],
                        'apellido' => $userData[0]['apellido'],
                        'correoElectronico' => $userData[0]['correoElectronico'],
                        'rol' => $userData[0]['rol']
                    );

                    $result = $_responses->response;
                    $result['result'] = array(
                        "message" => "Login exitoso para el usuario '$correoElectronico'"
                    );

                    return $result;
                } else {
                    // La contraseña no es igual
                    // Mostrar un mensaje de error como una alerta
                    echo '<script>alert("Usuario no encontrado o contraseña no válida");</script>';
                    echo '<script>window.location.href = "index.php";</script>';
                    exit();
                }
            } else {
                // No existe el usuario o no hay contraseña
                // Mostrar un mensaje de error como una alerta
                echo '<script>alert("Usuario no encontrado o contraseña no válida");</script>';
                echo '<script>window.location.href = "index.php";</script>';
                // return $_responses->error_200("Usuario no encontrado o contraseña no válida");
                exit();
            }
        }
    }

    public function registerUser($json)
    {
        $_responses = new Responses;
        $data = json_decode($json, true);

        // Verifica que los datos necesarios estén presentes
        if (
            !isset($data["nombre"]) ||
            !isset($data["apellido"]) ||
            !isset($data["correoElectronico"]) ||
            !isset($data["contrasena"]) ||
            !isset($data["rol"])
        ) {
            return $_responses->error_400();
        }

        $nombre = $data["nombre"];
        $apellido = $data["apellido"];
        $correoElectronico = $data["correoElectronico"];
        $contrasena = $data["contrasena"];
        $rol = $data["rol"];

        // Encripta la contraseña
        $contrasenaEncriptada = parent::encript($contrasena);

        try {
            // Realiza la inserción en la base de datos
            $query = "INSERT INTO usuario (nombre, apellido, correoElectronico, contrasena, rol) VALUES ('$nombre', '$apellido', '$correoElectronico', '$contrasenaEncriptada', '$rol');";
            $insertedUserId = parent::nonQueryId($query);

            if ($insertedUserId > 0) {
                $result = $_responses->response;
                $result['result'] = array(
                    "message" => "Usuario registrado exitosamente",
                    "userId" => $insertedUserId
                );
                return $result;
            } else {
                return $_responses->error_500("Error interno del servidor, no hemos podido registrar el usuario");
            }
        } catch (mysqli_sql_exception $e) {
            // Aquí capturas la excepción de duplicación
            if ($e->getCode() == 1062) {
                return $_responses->error_400("El correo electrónico ya está registrado. Por favor, utiliza otro.");
            } else {
                return $_responses->error_500("Error interno del servidor: " . $e->getMessage());
            }
        }
    }

    public function editUser($json)
    {
        $_responses = new Responses;
        $data = json_decode($json, true);

        // Verifica que los datos necesarios estén presentes
        if (
            !isset($data["idUsuario"]) ||
            !isset($data["nombre"]) ||
            !isset($data["apellido"]) ||
            !isset($data["correoElectronico"]) ||
            !isset($data["contrasena"])
        ) {
            return $_responses->error_400();
        }

        $idUsuario = $data["idUsuario"];
        $nombre = $data["nombre"];
        $apellido = $data["apellido"];
        $correoElectronico = $data["correoElectronico"];
        $contrasena = $data["contrasena"];

        // Encripta la contraseña
        $contrasenaEncriptada = parent::encript($contrasena);

        // Actualiza los datos en la base de datos
        $query = "UPDATE usuario SET nombre = '$nombre', apellido = '$apellido', correoElectronico = '$correoElectronico', contrasena = '$contrasenaEncriptada' WHERE idUsuario = '$idUsuario';";
        $updatedRows = parent::nonQuery($query);

        if ($updatedRows > 0) {
            $userData = $this->getUserData($correoElectronico);
            $_SESSION['usuario']['idUsuario'] = $userData[0]['idUsuario'];
            $_SESSION['usuario']['nombre'] = $userData[0]['nombre'];
            $_SESSION['usuario']['apellido'] = $userData[0]['apellido'];
            $_SESSION['usuario']['correoElectronico'] = $userData[0]['correoElectronico'];

            $result = $_responses->response;
            $result['result'] = array(
                "message" => "Usuario '$correoElectronico' actualizado exitosamente"
            );
            return $result;
        } else {
            return $_responses->error_500("Error interno del servidor, no hemos podido actualizar el usuario puede ser por el correo o que no actualizo ningun dato");
        }
    }

    private function getUserData($email)
    {
        $query = "SELECT * FROM usuario WHERE correoElectronico = '$email';";
        $data = parent::getData($query);
        if (isset($data[0]["idUsuario"])) {
            return $data;
        } else {
            return 0;
        }
    }

    public function getAllUsers()
    {
        $_responses = new Responses;

        try {
            $query = "SELECT * FROM usuario;";
            $users = parent::getData($query);

            $result = $_responses->response;
            $result['result'] = array(
                "users" => $users
            );

            return $result;
        } catch (Exception $e) {
            return $_responses->error_500("Error interno del servidor: " . $e->getMessage());
        }
    }

    public function changeToAdmin($idUsuario)
    {
        return $this->changeUserRole($idUsuario, 'administrador');
    }

    public function changeToUser($idUsuario)
    {
        return $this->changeUserRole($idUsuario, 'usuario');
    }

    private function changeUserRole($idUsuario, $newRole)
    {
        $_responses = new Responses;

        // Verifica que los datos necesarios estén presentes
        if (empty($idUsuario)) {
            return $_responses->error_400();
        }

        // Actualiza el rol del usuario en la base de datos
        $query = "UPDATE usuario SET rol = '$newRole' WHERE idUsuario = '$idUsuario';";
        $updatedRows = parent::nonQuery($query);

        if ($updatedRows) {
            $result = $_responses->response;
            $result['result'] = array(
                "message" => "Rol del usuario actualizado exitosamente a '$newRole'"
            );
            return $result;
        } else {
            return $_responses->error_500("Error interno del servidor, no hemos podido actualizar el rol del usuario");
        }
    }
}
