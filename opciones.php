<?php
require 'class.php';
?>
<html>
    <head>
        <title>Sistema de Fundas Conexionred</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="js.js" type="text/javascript"></script>
    </head>
    <body>
        <?php require 'login.php'; ?>
        <div
            class="container">
            <?php include 'header.php'; ?>
            <div class="wrapper">
                <div class="row">
                    <div class="col-md-4" id="crearmodelo">
                        <form action="functions.php" method="post" class="needs-validation">
                            <h4>Modelo</h4>
                            <span>Marca</span>
                            <?php echo Marca::select_marcas(); ?>
                            <span>Año</span>
                            <?php echo Year::select_year(); ?>
                            <span>Modelo</span>
                            <div class="frmSearch">
                                <input type="text" class="form-control" id="search-box" placeholder="Modelo" name="modelo" required>
                                <div id="suggesstion-box" class="float-start position-absolute bg-secondary text-white mt-1"></div>
                            </div>
                            <span>Ref</span><input type="number" id="modeloref" min="10" max="99" class="form-control" placeholder="modeloref" name="modeloref" required>
                            <p>La referencia son dos números puede ser cualquiera, si ya existe, no dejará crear y advertirá de referencia ya existente</p>
                            <p>La referencia es parte de un código de 8 dígitos que comienza por el año-marca-modelo-tipo</p>
                            <div class="valid-feedback">
                                Correcto
                            </div>
                            <button class="btn btn-primary" type="submit" name="addmodelo">Enviar</button>
                        </form>
                    </div>
                    <div class="col-md-4" id="crearmarca">
                        <form action="functions.php" method="post" class="needs-validation">
                            <h4>Marca</h4>
                            <span>Nombre</span>
                            <input type="text" class="form-control mb-2" id="marcaname" placeholder="marca" name="marca" required>
                            <span>Referencia</span>
                            <input type="number" class="form-control mb-2" id="marcaref" min="10" max="99" placeholder="marcaref" name="marcaref" required>
                            <button class="btn btn-primary" type="submit" name="addmarca">Enviar</button>
                        </form>
                    </div>
                    <div class="col-md-4" id="crearyear">
                        <form action="functions.php" method="post" class="needs-validation">
                            <h4>Año</h4>
                            <span>Año</span><input type="number" min="2000" max="9999" class="form-control mb-2" id="yearname" placeholder="Año" name="yearname" required>
                            <span>Referencia</span><input type="number" min="10" class="form-control mb-2" id="yearref" placeholder="yearref" name="yearref" required>

                            <button class="btn btn-primary" type="submit" name="addyear">Enviar</button>
                        </form>
                    </div>

                </div>
                <div class="row row g-3 ">
                    <div class="col-md-4" id="creartipo">
                        <form action="functions.php" method="post" class="needs-validation">
                            <h4>Tipo</h4>
                            <span>Nombre</span>
                            <input type="text" class="form-control mb-2" placeholder="tipo" name="tipo" required>
                            <span>Referencia</span>
                            <input type="number" class="form-control mb-2" min="10" max="99" placeholder="tiporef" name="tiporef" required>
                            <span>PVP</span>
                            <div class="input-group mb-2">
                                <span class="input-group-text" id="inputGroupPrepend">€</span><input type="number" class="form-control" placeholder="pvp" name="pvp" required>
                            </div>
                            <button class="btn btn-primary" type="submit" name="addtipo">Enviar</button>
                        </form>
                    </div>
                    <div class="col-md-4" id="crearColor">
                        <form action="functions.php" method="post" class="needs-validation">
                            <h4>Color</h4>
                            <span>Nombre</span>
                            <input type="text" class="form-control mb-2" placeholder="Color" name="color" required>
                            <span>Referencia</span>
                            <input type="number" class="form-control mb-2" placeholder="colorref" name="colorref" id="colorref" required>
                            <div id="colorreftext">&nbsp;</div>
                            <span>Color</span>
                            <input type="color" class="form-control mb-2" name="thumb" required>
                            <button class="btn btn-primary" type="submit" name="addcolor">Enviar</button>
                        </form>
                    </div>
                    <div class="col-md-4 crearRelacionados">
                        <form action="functions.php" method="post" class="needs-validation">
                            <h4>Relacionados</h4>
                            <p>Pulsa ctrl para elegir varios</p>
                            <div class="row">

                                <div
                                    class="col-sm-12"><?php
                                    echo Modelo::select_modelos_multiple();
                                    ?>
                                </div>
                                <div id="resultadosRel" class="alert alert-primary"></div>
                            </div>
                            <button class="btn btn-primary" type="submit">Enviar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
