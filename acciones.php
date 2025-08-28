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
        <div
            class="container">
            <?php include 'header.php'; ?>
            <div class="text-center" id="cargando">
                <div class="spinner-border text-info" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <main class="d-none" id="mainContent">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Crear o Modificar funda</h3>
                    </div>
                    <form action="functions.php" method="post" class="needs-validation" id="formularioStock">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <?php
                                    if (isset($_SESSION['variables']['modelo'])) {
                                        echo "Marcas" . Marca::select_marcas($_SESSION['variables']['marca']);
                                        echo "Año" . Year::select_year($_SESSION['variables']['year']);
                                    } else {
                                        echo "Marcas" . Marca::select_marcas();
                                        echo "Año" . Year::select_year();
                                    }
                                    echo "Modelo";
                                    ?>
                                    <?php
                                    if (isset($_SESSION['variables']['modelo'])) {
                                        echo Modelo::select_modelos_by_id($_SESSION['variables']['modelo']);
                                    } else {
                                        ?>
                                            <select name="selectModelos" class='form-select' id="selectModelos"> <option value="">Selecciona primero la marca</option>

                                        </select>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-sm-4"><?php
                                if (isset($_SESSION['variables']['tipo'])) {
                                    echo "Tipo" . Tipo::select_tipo($_SESSION['variables']['tipo']);
                                } else {
                                    echo "Tipo" . Tipo::select_tipo();
                                }
                                ?>
                                    <?php
                                    if (isset($_SESSION['variables']['color'])) {
                                        echo "Color" . Color::select_color($_SESSION['variables']['color']);
                                    } else {
                                        echo "Color" . Color::select_color();
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
                                        <div id="stock"></div>
                                    <?php } ?>
                                    Usar Relacionados
                                    <div id="relacionados" class="mb-3">
                                        <input type="radio" id="si" value="1" name="usarrel"><label for="si">Si</label>
                                        <input type="radio" id="no" value="0" name="usarrel" checked><label for="no">No</label>
                                    </div>
                                    <button class="btn btn-primary" type="submit" id="enviarStock" name="enviarStock">Enviar</button>
                                    <button class="btn btn-secondary" type="button" id="resetFormulario">Resetear Campos</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row align-items-center mt-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Comprobar funda</h3>
                            </div>
                            <div class="card-body" id="comprobarCodigo">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <input type="text" name="codigoFunda" id="codigoFunda" class="codigoFunda ">
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-primary codigoFunda" id="enviarCodigo">Comprobar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Exportar Todo</h3>
                            </div>
                            <div class="card-body">
                                <form action='functions.php' class="row align-items-center" method='post'>
                                    <div class="col-auto">
                                        <input type="checkbox" name="stock0" id="stock0">
                                        <label for="stock0">Incluir Fundas con Stock 0</label>
                                    </div>
                                    <div class="col-auto">
                                        <button type='button' class="btn btn-primary" name='exportar' id="exportarBtn">
                                            Exportar <i class="bi bi-arrow-right-short"></i>
                                            <span id="exportarSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Exportar por fecha</h3>
                            </div>
                            <div class="card-body" id="datepickerDiv">
                                <form action='functions.php' method='post' class="row align-items-center">
                                    <div class="col-auto">
                                        <input type="text" name="fecha" id="datepicker" class="datepicker">
                                        <div class="form-group">
                                            <input type="checkbox" name="stock0fecha" id="stock0fecha">
                                            <label for="stock0fecha">Incluir Fundas con Stock 0</label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-primary fecha" name="datepickerSubmit" id="datepickerSubmit">Exportar por fecha
                                            <span id="exportarFechaSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="tablastock"><?php
                    echo Stock::get_fundas();
                    ?>
                </div>
                <script>
                    $(document).ready(function () {
$('#cargando').hide();
$('#mainContent').removeClass('d-none');
});
                </script>
            </div>
        </main>
    </body>

</html>

