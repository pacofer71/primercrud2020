<?php

namespace Clases;

require "../vendor/autoload.php";

use PDOException;
use PDO;

class Autores extends Conexion
{
    private $id_autor;
    private $apellidos;
    private $nombre;

    public function __construct()
    {
        parent::__construct();
    }

    //-----------CRUD
    public function create()
    {
        $consulta="insert into autores(nombre, apellidos) values(:n, :a)";
        $stmt=parent::$conexion->prepare($consulta);
        try{
            $stmt->execute([
                ':n'=>$this->nombre,
                ':a'=>$this->apellidos
            ]);
        }catch(PDOException $ex){
            die("Error al insertar un autor: ".$ex->getMessage());
        }
    }
    //-------------------------------------------------
    public function read()
    {
        $con="select * from autores where id_autor=:i";
        $stmt=parent::$conexion->prepare($con);
        try{
            $stmt->execute([
                ':i'=>$this->id_autor
            ]);
        }catch(PDOException $ex){
            die("Error al recuperar este autor: ".$ex->getMessage());
        } 
        return $stmt->fetch(PDO::FETCH_OBJ);  //devuelve una unica fila

    }
    public function update()
    {
        $con="update autores set nombre=:n, apellidos=:a where id_autor=:id";
        $stmt=parent::$conexion->prepare($con);
        try{
            $stmt->execute([
                ':n'=>$this->nombre,
                ':a'=>$this->apellidos,
                ':id'=>$this->id_autor
            ]);
        }catch(PDOException $ex){
            die("Error al Actualizar autor: ".$ex->getMessage());
        }

    }
    public function delete()
    {
        $cons="delete from autores where id_autor=:i";
        $stmt=parent::$conexion->prepare($cons);
        try{
            $stmt->execute([
                ':i'=>$this->id_autor
            ]);
        }catch(PDOException $ex){
            die("Error al BORRAR autor: ".$ex->getMessage());
        }
    }
    //--------setters
    public function setId_autor($i)
    {
        $this->id_autor = $i;
    }
    public function setNombre($n)
    {
        $this->nombre = $n;
    }
    public function setApellidos($a)
    {
        $this->apellidos = $a;
    }

    // -------------------------------------------------
    public function recuperarTodos($inf, $cant)
    {
        //$consulta="select * from autores order by apellidos, id_autor limit :i, :c";
        $consulta="select * from autores order by apellidos, id_autor limit $inf, $cant";
        $stmt=parent::$conexion->prepare($consulta);
        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error al recuperar autores: " . $ex->getMessage());
        }
        return $stmt;

    }
    //-------------------------------------------------------------------
    public function rellenarAutores($cantidad)
    {
        if ($this->totalReg()==0) {
            //faker meteremos $cantidad de autores
            $faker = \Faker\Factory::create('es_ES');
            for ($i = 0; $i < $cantidad; $i++) {
                $nom = $faker->firstName();
                $ape = $faker->lastName . " " . $faker->lastName();
                $consulta = "insert into autores(apellidos, nombre) values (:a, :n)";
                $stmt = parent::$conexion->prepare($consulta);
                try {
                    $stmt->execute([
                        ':a' => $ape,
                        ':n' => $nom
                    ]);
                } catch (PDOException $ex) {
                    die("Error al rellenar autores: " . $ex->getMessage());
                }
            }
        }
    }
    //------------------------------------------------------------
    public function hayAutores()
    {
        $con = "select count(*) as total from autores";
        //hago la consulta
        $resultado = parent::$conexion->query($con);
        if ($resultado->fetchColumn() > 0) return true;
        return false;
    }
    //------------------------------------------------------------
    public function totalReg(){
        $con="select count(*) as total from autores";
        $stmt=parent::$conexion->prepare($con);
        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("Error al devolver el total de autores, ".$ex->getMessage());
        }
        //return $stmt->fetch(PDO::FETCH_OBJ)->total;
        $fila=$stmt->fetch(PDO::FETCH_OBJ);
        return $fila->total;

    }
    //---------------------------------------------------------------------------------
    public function existeId(){
        $con="select count(*) as total from autores where id_autor={$this->id_autor}";
        $stmt=parent::$conexion->prepare($con);
        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("Error al comprobar si id existe, ".$ex->getMessage());
        }
        $total=$stmt->fetch(PDO::FETCH_OBJ)->total;
        return $total;
    }
    //------Devolver id de todos los autores;
    public function devolverIDs(){
        $con="select id_autor from autores";
        $stmt=parent::$conexion->prepare($con);
        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("Error al devolver id's si id existe, ".$ex->getMessage());
        }
        $id=[];
        while($fila=$stmt->fetch(PDO::FETCH_OBJ)){
            $id[]=$fila->id_autor;
        }
        return $id;

    }
    //------------------------------------------------------------------------------
    
}
