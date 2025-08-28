<?php

require 'class.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['idMarca']) && isset($_POST['nombreMarca']) && isset($_POST['refMarca'])) {
    $marca = new Marca($_POST['idMarca'], $_POST['nombreMarca'], $_POST['refMarca']);
    echo $marca->update_marca();  
} else if (isset($_POST['idYear']) && isset($_POST['yearName'])) {
    $year = new Year($_POST['idYear'], $_POST['yearName'], $_POST['refYear']);
    echo $year->update_year();
} else if (isset($_POST['idModelo']) && isset($_POST['nombreModelo']) && isset($_POST['refModelo'])) {
    $modelo = new Modelo($_POST['idModelo'], $_POST['nombreModelo'], $_POST['marcas'], $_POST['year'], $_POST['refModelo']);
    echo $modelo->update_modelo();
} else if (isset($_POST['idTipo']) && isset($_POST['nombreTipo']) && isset($_POST['refTipo']) && isset($_POST['pvp'])) {
    $tipo = new Tipo($_POST['idTipo'], $_POST['nombreTipo'], $_POST['pvp'],$_POST['refTipo']);
    echo $tipo->update_tipo();
} else if (isset($_POST['idColor']) && isset($_POST['nombreColor']) && isset($_POST['refColor']) && isset($_POST['thumb'])) {
    $color = new Color($_POST['idColor'], $_POST['nombreColor'], $_POST['refColor'], $_POST['thumb']);
    echo $color->update_color();
} else {
    echo "Datos no recibidos correctamente";
}
