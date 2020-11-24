<?php
session_start();
if (!isset($_GET['id'])) {
    header("Location:libros.php");
}
$id = $_GET['id'];
require "../vendor/autoload.php";

use Clases\Autores;
use Clases\Libros;

$esteLibro = new Libros();
$esteLibro->setId_libro($id);
$datosLibro = $esteLibro->read();
$esteLibro = null;

//meto en variable todos los campos de Libro
$titulo = $datosLibro->titulo;
$autor = $datosLibro->autor;
$isbn = $datosLibro->isbn;
$portada = $datosLibro->portada;

//recuperamos nombre y apellidos del autor a partir de $autor
$esteAutor = new Autores();
$esteAutor->setId_autor($autor);
$datosAutor = $esteAutor->read();
$esteAutor = null;
$nombreAutor = $datosAutor->nombre;
$apellidosAutor = $datosAutor->apellidos;

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
    <title>Detalle</title>
</head>

<body style="background-color:darksalmon">
    <h3 class="text-center my-3">Detalle Autor</h3>
    <div class="container">
        <div class="card text-white bg-dark mb-3 m-auto" style="max-width: 68rem;">
            <div class="card-header text-center">Libro</div>
            <div class="card-body">
                <img src="<?php echo $portada ?>" height='110rem' width='110rem' class='float-right img-thumbnail rounded'>
                <p class="card-text mb-2">Código: <?php echo $id; ?></p>
                <p class="card-text mb-2">Título: <?php echo $titulo; ?></p>
                <p class="card-text mb-2">Isbn: <?php echo $isbn; ?></p>
                <p class="card-text mb-2">Autor: <?php echo $apellidosAutor. ", ".$nombreAutor; ?></p>
                <p class="text-center">
                    <a href="libros.php" class="btn btn-info"><i class="fas fa-home mr-2"></i>Inicio</a>
                </p>
            </div>
        </div>
    </div>
</body>

</html>