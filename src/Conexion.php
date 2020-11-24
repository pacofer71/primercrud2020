<?php

namespace Clases;

use PDO;
use PDOException;


class Conexion{
    protected static $conexion;
    
    public function __construct(){
        if(self::$conexion==null){
            self::crearConexion();
        }
    }
    
    private static function crearConexion(){
        $ops=parse_ini_file("../config.ini");
        $user=$ops["usuario"]; //usuario de la base de datos
        $pass=$ops["pass"]; //pass de ese usuario
        $base=$ops["base"]; //base de datos a la que me quiero conectar
        //creo el dsn
        $dsn="mysql:host=localhost;dbname=$base;charset=utf8mb4";
        try{
            self::$conexion = new PDO($dsn, $user, $pass);
            self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }catch(PDOException $ex){
            die("Error al conectar a la BBDD, mensaje: ".$ex->getMessage());
        }
    }
    //---------------------------------------------------------------------
}
