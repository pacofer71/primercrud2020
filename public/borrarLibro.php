<?php
session_start();
if(!isset($_POST['id'])){
    header('Location:libros.php');
    die();
}
require "../vendor/autoload.php";
use Clases\Libros;

$id=$_POST['id'];
$libro=new Libros();
$libro->setId_libro($id);
$portada=$libro->recuprarPortada();
//$nombre=basename($portada);
//die("Portada=".$portada." Nombre=".$nombre);
//si portada NO es default.jpg voy a borrar el archivo de imagen y luego la entra en la bbdd

$libro->delete();

if(basename($portada)!="default.jpg"){
    unlink($portada);
}

$libro=null;
$_SESSION['mensaje']="Libro Borrado correctamente.";
header("Location:libros.php");