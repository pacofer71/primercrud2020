<?php
namespace Clases;
require "../vendor/autoload.php";

use PDO;
use PDOException;
use Faker\Factory;
use Clases\Autores;

class Libros extends Conexion{
    private $id_libro;
    private $titulo;
    private $isbn;
    private $autor;
    private $portada;

    public function __construct()
    {
        parent::__construct();
    }

    
    //Setters -----------------------------------------------------

    /**
     * Set the value of id_libro
     *
     * @return  self
     */ 
    public function setId_libro($id_libro)
    {
        $this->id_libro = $id_libro;

        return $this;
    }

    /**
     * Set the value of titulo
     *
     * @return  self
     */ 
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Set the value of isbn
     *
     * @return  self
     */ 
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * Set the value of autor
     *
     * @return  self
     */ 
    public function setAutor($autor)
    {
        $this->autor = $autor;

        return $this;
    }

    /**
     * Set the value of portada
     *
     * @return  self
     */ 
    public function setPortada($portada)
    {
        $this->portada = $portada;

        return $this;
    }

    //CRUD --------------------------------------------------------
    public function create(){
        $c1="insert into libros(titulo, autor, isbn, portada) values (:t, :a, :i, :p)";
        $c2="insert into libros(titulo, autor, isbn) values (:t, :a, :i)";
        $array = [':t'=>$this->titulo, ':a'=>$this->autor, ':i'=>$this->isbn];
        if(isset($this->portada)){
            $array[":p"]=$this->portada;
            $stmt=parent::$conexion->prepare($c1);
        }else{
            $stmt=parent::$conexion->prepare($c2);
        }
        try{
            $stmt->execute($array);
        }catch(PDOException $ex){
            die("Error al crear Libro>".$ex->getMessage());
        }

    }
    //--------------------------------------------------------------------------
    public function read(){
        $c="select * from libros where id_libro=:i";
        $stmt=parent::$conexion->prepare($c);
        try{
            $stmt->execute([
                ':i'=>$this->id_libro
            ]);
        }catch(PDOException $ex){
            die("Error al recuperar el libro: ".$ex->getMessage());
        }
        $fila=$stmt->fetch(PDO::FETCH_OBJ);
        return $fila;

    }
    //----------------------------------------------------------------------------------------------------
    public function update(){
        $u="update libros set titulo=:t, isbn=:i, portada=:p, autor=:a where id_libro=:id";
        $stmt=parent::$conexion->prepare($u);
        try{
            $stmt->execute([
                ':t'=>$this->titulo,
                 ':i'=>$this->isbn,
                 ':p'=>$this->portada,
                 ':a'=>$this->autor,
                 ':id'=>$this->id_libro,

            ]);
        }catch(PDOException $ex){
            die("Error al Actualizar el libro: ".$ex->getMessage());
        }

    }
    public function delete(){
        $d="delete from libros where id_libro=:i";
        $stmt=parent::$conexion->prepare($d);
        try{
            $stmt->execute([
                ':i'=>$this->id_libro
            ]);
        }catch(PDOException $ex){
            die("Error al borrar el libro: ".$ex->getMessage());
        }

    }
    //Otros métodos -------------------------------------------------
    public function totalReg(){
        $con="select count(*) as total from libros";
        $stmt=parent::$conexion->prepare($con);
        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("Error al devolver el total de libros, ".$ex->getMessage());
        }
        //return $stmt->fetch(PDO::FETCH_OBJ)->total;
        $fila=$stmt->fetch(PDO::FETCH_OBJ);
        return $fila->total;

    }
    //Rellenando la tabla --------------------------------------------
    public function rellenarLibros($cant){
        $autor=new Autores();
        $id=$autor->devolverIDs();
        if($this->totalReg()==0){
            $faker = Factory::create('es_ES');
            for($i=0; $i<$cant; $i++){
                $titulo=$faker->sentence($nbWords=5, $variableNbWords=true);
                $isbn=$faker->unique()->isbn13;
                $autor=$id[$faker->numberBetween($min=0, $max=count($id)-1)];
                $inser="insert into libros(titulo, isbn, autor) values(:t, :i, :a)";
                $stmt=parent::$conexion->prepare($inser);
                try{
                    $stmt->execute([
                        ':t'=>$titulo,
                        ':i'=>$isbn,
                        ':a'=>$autor
                    ]);
                }catch(PDOException $ex){
                    die("Error al crear los libros de prueba: ".$ex->getMessage());
                }



            }
        }
    }
    //----------------------------------------------------------------------------
    public function recuperarTodos($inf, $cant){
        $con="select libros.*, autores.nombre, autores.apellidos from 
        libros, autores where autor=id_autor order by autores.apellidos, titulo limit $inf, $cant";
        $stmt=parent::$conexion->prepare($con);
        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("Error al recuperar los libros de prueba: ".$ex->getMessage());
        }
        return $stmt;
    }
    //----------------------------------------------------------------------------------------------------
    public function recuprarPortada(){
        $c="select portada from libros where id_libro=:i";
        $stmt=parent::$conexion->prepare($c);
        try{
            $stmt->execute([
                    ':i'=>$this->id_libro
            ]);
        }catch(PDOException $ex){
            die("Error al recuperar portada: ".$ex->getMessage());
        }
        //return $stmt->fetch(PDO::FETCH_OBJ)->portada
        $fila=$stmt->fetch(PDO::FETCH_OBJ);
        $portada=$fila->portada;
        return $portada;


    }


}