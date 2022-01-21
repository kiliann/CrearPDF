<?php

class Conexion{


    function __construct() {
        include "configuracionBD.php";



        $this->mysqli = new mysqli(SERVIDOR,USUARIO, PASSWORD, DB);
    }
    public function consultas($consulta){

        return  $this->resultado=$this->mysqli->query($consulta);
    }

    public function extraerFila($resultado){
        return $this->fila =mysqli_fetch_array($resultado);
    }
    public function consultasMultiple($consulta){

        $this->resultado=$this->mysqli->multi_query($consulta);
    }

    public function error(){

        return  $this->mysqli->error;
    }

    public function errno(){

        return  $this->mysqli->errno;
    }

    public function cerrarConexion(){
        $this->mysqli->close();
    }
}