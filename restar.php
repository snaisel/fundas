<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'class.php';
$con = getdb();
?>
<html>
    <head>
        <title>Sistema de Fundas Conexionred</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="js.js" type="text/javascript"></script>
        <script>
            function confirmarEnvio(event) { // Prevenir el envío automático del formulario
event.preventDefault();

// Mostrar el prompt y capturar la entrada del usuario
let respuesta = prompt('Por favor, escribe "Reset" para confirmar el envío:');

// Comprobar si la respuesta es correcta
if (respuesta === "Reset") { // Enviar el formulario
document.getElementById("formularioReset").submit();
} else { // Recargar la página y mostrar el mensaje
alert('Cambios no realizados porque no has escrito "Reset".');
location.reload();
}
}
        </script>
    </head>
    <body>
        <?php require 'login.php'; ?>
        <div
            class="container">
            <?php include 'header.php'; ?>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h2>Restar Stock</h2>
                    <p>Introduce el código del modelo a restar.</p>
                    <form action="functions.php" method="post" id="formularioRestar">
                        <input type="text" name="textoRestar" id="textoRestar">
                        <input type="submit" name="submitRestar" id="submitRestar" value="Restar">
                    </form>
                    <?php
                    if (isset($_SESSION['variables']['codigo'])) {
                        echo "<div class='alert alert-info'>" . $_SESSION['variables']['codigo'] . " ahora tiene " . $_SESSION['variables']['stock'] . "</div>";
                    }
                    ?>
                    <hr>
                    <form action="functions.php" method="post" id="formularioReset">
                        <div class="col-sm-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="resetAll" id="resetAll">
                                <label class="form-check-label" for="resetAll">
                                    Resetear Todos</label>
                            </div>
                            <div class="form-group"><?php echo Marca::select_marcas(); ?></div>
                        </div>
                        <input type="hidden" name="submitReset" value="reseteo"><br>
                        <input type="submit" value="Enviar" id="submitReset" onclick="confirmarEnvio(event);">
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>

