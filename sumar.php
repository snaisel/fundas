<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'class.php';
$conn = getdb();
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
        <div class="container">
            <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
                <a href="index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
                    <svg class="bi me-2" width="40" height="32"><use xlink:href="index.php"></use></svg>
                    <span class="fs-4">Fundas Conexionred</span>
                </a>

                <ul class="nav nav-pills">
                    <li class="nav-item"><a href="index.php" class="nav-link">Inicio</a></li>
                    <li class="nav-item"><a href="opciones.php" class="nav-link">Opciones</a></li>
                    <li class="nav-item"><a href="acciones.php" class="nav-link">Fundas</a></li>
                    <li class="nav-item"><a href="modelos.php" class="nav-link">Modelos</a></li>
                    <li class="nav-item"><a href="sumar.php" class="nav-link active" aria-current="page">Sumar</a></li>
                    <li class="nav-item"><a href="restar.php" class="nav-link">Restar</a></li>
                </ul>
            </header>

            <form action="functions.php" method="post" id="formularioSumar">
                <input type="text" name="textoSumar" id="textoSumar">
                <input type="submit" name="submitSumar" id="submitSumar" value="Sumar">
            </form>
            <?php
            if (isset($_SESSION['variables']['codigo'])) {
                echo "<div class='alert alert-info'>" . $_SESSION['variables']['codigo'] . " ahora tiene " . $_SESSION['variables']['stock'] . "</div>";
            }

            $sql = "SELECT SUM(stock.stock) AS total_stock FROM stock";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                // Obtener el resultado
                $row = $result->fetch_assoc();
                $total_stock = $row['total_stock'];
                echo "El total de stock es: " . $total_stock;
            } else {
                echo "No se encontraron resultados.";
            }
            $conn->close();
            ?>

        </div>
    </body>
</html>