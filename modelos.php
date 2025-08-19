<?php
require 'class.php';

$con = getdb();
?>
<!DOCTYPE html>
    <head>
        <title>Sistema de Fundas Conexionred</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="js.js" type="text/javascript"></script>
    </head>
    <body id="modelos-page">
        <?php require 'login.php'; ?>
        <div class="container">
            <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
                <a href="index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
                    <svg class="bi me-2" width="40" height="32"><use xlink:href="index.php"></use></svg>
                    <span class="fs-4">Fundas Conexionred</span>
                </a>

                <ul class="nav nav-pills" >
                    <li class="nav-item"><a href="index.php" class="nav-link " aria-current="page">Inicio</a></li>
                    <li class="nav-item"><a href="opciones.php" class="nav-link">Opciones</a></li>
                    <li class="nav-item"><a href="acciones.php" class="nav-link">Fundas</a></li>
                    <li class="nav-item"><a href="modelos.php" class="nav-link active">Modelos</a></li>
                    <li class="nav-item"><a href="sumar.php" class="nav-link">Sumar</a></li>
                    <li class="nav-item"><a href="restar.php" class="nav-link">Restar</a></li>
                </ul>
            </header>
            <main>
                <div id="tablamodelos">
                <?php echo get_tabla_modelos() ?>
                </div>
            </main>
    </body>
</html>