<?php

class conection {
    private $server;
    private $user;
    private $password;
    private $database;
    private $port;
    private $conection;

    function __construct(){
        $listdata = $this->dataConection();
        foreach ($listdata as $key => $value) {
            $this->server = $value['server'];
            $this->user = $value['user'];
            $this->password = $value['password'];
            $this->database = $value['database'];
            $this->port = $value['port'];
        }
        $this->conection = new mysqli($this->server, $this->user, $this->password, $this->database, $this->port);
        if ($this->conection->connect_errno){
            echo "algo no funciona en la conexion";
            die();
        }
    }

    private function dataConection(){
        $direction = dirname(__FILE__);
        $jsondata = file_get_contents($direction . "/" . "config");
        return json_decode($jsondata, true);
    }

    private function convertUTF8($array){
        array_walk_recursive($array, function(&$item, $key){
            if(!mb_detect_encoding($item, 'utf-8', true)){
                $item = utf8_encode($item);
            }
        });
        return $array;
    }

    public function getData($query){
        $result = $this->conection->query($query);
        $resultArray = array();
        foreach ($result as $key ) {
            $resultArray[] = $key;
        }
        return $this->convertUTF8($resultArray);
    }

    //Para guardar, eliminar, actualizar
    public function nonQuery($query){
        $result = $this->conection->query($query);
        return $this->conection->affected_rows;
    }

    //ademas de guardar, eliminar y actualizar devuelve el id
    public function nonQueryId($query){
        $result = $this->conection->query($query);
        $filas = $this->conection->affected_rows;
        if ($filas >= 1){
            return $this->conection->insert_id;
        }else{
            return 0;
        }
    }

    protected function encript($string){
        return md5($string);

    }
}   
?>