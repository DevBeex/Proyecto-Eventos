<?php
require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';
session_start();

class Auth extends conection {

    public function login($json) {
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
                    
                    return $_responses->error_200("Contraseña inválida");
                }
            } else {
                // No existe el usuario
                return $_responses->error_200("El usuario '$correoElectronico' no existe");
            }
        }
    }

    public function registerUser($json) {
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
    
    

    private function getUserData($email) {
        $query = "SELECT * FROM usuario WHERE correoElectronico = '$email';";
        $data = parent::getData($query);
        if (isset($data[0]["idUsuario"])) {
            return $data;
        } else {
            return 0;
        }
    }


}

?>
