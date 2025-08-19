<?php
require 'class.php';
include_once 'Pagination.php';
?>
<html>
    <head>
        <title>Sistema de Fundas Conexionred</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"/>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

        <script src="js.js" type="text/javascript"></script>
    </head>
    <body>
        <?php require 'login.php'; ?>
        <div class="container">
            <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
                <a href="index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
                    <svg class="bi me-2" width="40" height="32"><use xlink:href="index.php"></use></svg>
                    <span class="fs-4">Fundas Conexionred</span>
                </a>

                <ul class="nav nav-pills">
                    <li class="nav-item"><a href="index.php" class="nav-link" aria-current="page">Inicio</a></li>
                    <li class="nav-item"><a href="opciones.php" class="nav-link ">Opciones</a></li>
                    <li class="nav-item"><a href="acciones.php" class="nav-link active">Fundas</a></li>
                    <li class="nav-item"><a href="modelos.php" class="nav-link">Modelos</a></li>
                    <li class="nav-item"><a href="sumar.php" class="nav-link">Sumar</a></li>
                    <li class="nav-item"><a href="restar.php" class="nav-link">Restar</a></li>
                </ul>
            </header>
            <main>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Crear o Modificar funda</h3>
                    </div>
                    <form action="functions.php" method="post" class="needs-validation" id="formularioStock" >
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <?php
                                    if (isset($_SESSION['variables']['modelo'])) {
                                        echo "Marcas" . select_marcas(substr($_SESSION['variables']['modelo'], 0, 2));
                                        echo "Año" . select_year(substr($_SESSION['variables']['modelo'], 2, 2));
                                    } else {
                                        echo "Marcas" . select_marcas();
                                        echo "Año" . select_year();
                                    }
                                    echo "Modelo";
                                    ?>
                                    <?php
                                    if (isset($_SESSION['variables']['modelo'])) {
                                        echo select_modelos($_SESSION['variables']['modelo']);
                                    } else {
                                        ?>
                                        <select name="modelos" class='form-select' id="modelos">
                                            <option value ="">Selecciona primero la marca</option>

                                        </select>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-sm-4">
                                    <?php
                                    if (isset($_SESSION['variables']['tipo'])) {
                                        echo "Tipo" . select_tipo($_SESSION['variables']['tipo']);
                                    } else {
                                        echo "Tipo" . select_tipo();
                                    }
                                    ?>
                                    <?php
                                    if (isset($_SESSION['variables']['color'])) {
                                        echo "Color" . select_color($_SESSION['variables']['color']);
                                    } else {
                                        echo "Color" . select_color();
                                    }
                                    ?>
                                </div>
                                <div class="form-group col-sm-4">
                                    Stock
                                    <?php
                                    if (isset($_SESSION['variables']['stock'])) {
                                        echo '<div id="stock"><input type="number" name="stock" value="' . $_SESSION['variables']['stock'] . '" required></div>';
                                    } else {
                                        ?>
                                        <div id="stock">

                                        </div>
                                    <?php } ?>
                                    Relacionados
                                    <div id="relacionados">
                                        <input type="radio" id="si" value="1" name="usarrel"><label for="si">Si</label>
                                        <input type="radio" id="no" value="0" name="usarrel" checked><label for="no">No</label>
                                    </div>
                                    <button class="btn btn-primary" type="submit" id="enviarStock" name="enviarStock">Enviar</button>
                                </div>
                            </div>
                        </div>
                    </form> 
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Comprobar funda</h3>
                    </div>
                    <div class="card-body" id="comprobarCodigo">
                        <input type="text" name="codigoFunda" id="codigoFunda" class="codigoFunda"> <button type="submit" class="btn btn-primary codigoFunda" id="enviarCodigo">Comprobar</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action='functions.php' method ='post'><button type='submit' class="btn btn-primary" name='exportar'>Exportar Todo <i class="bi bi-arrow-right-short"></i></button></form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Exportar por fecha</h3>
                    </div>
                    <div class="card-body" id="datepickerDiv">
                        <form action='functions.php' method ='post'>
                            <input type="text" name="fecha" id="datepicker" class="datepicker"> <button type="submit" class="btn btn-primary fecha" name="datepickerSubmit" id="datepickerSubmit">Exportar por fecha</button>
                        </form>
                    </div>
                </div>
                

                <div id="tablastock">
                    <?php
                    echo get_fundas();
                    ?>
                </div>
        </div>
    </main>
</div>
</body>
</html>