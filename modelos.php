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
            <?php include 'header.php'; ?>
            <main>
                <div id="tablamodelos">
                <?php
                if (!empty($_GET)) {;
                    echo get_tabla_modelos("idModelo", "ASC", 1, 20,null,null,null,$_GET['idModelo']);
                    echo "<a class='btn btn-primary' href='modelos.php'>Ver todos los modelos</a>";
                }
                else{
                echo get_tabla_modelos();
                }
                ?>
                </div>
                <!-- Modal editar Modelo -->
                        <div class="modal fade" id="resumenModelo" tabindex="-1" aria-labelledby="resumenModeloModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="resumenModeloModalLabel">Resumen del Modelo</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                     
                                    </div>
                                    <div class="modal-footer">
                                    </div>
                                </div>
                            </div>
                        </div>
            </main>
            <script>
                $(document).ready(function() {
                    $('#tablamodelos').on('click', '.botonresumen', function() {
                        var idModelo = $(this).val();
                        $('#resumenModelo').modal('show');
                        $.ajax({
                            url: 'ajaxData_1.php',
                            type: 'post',
                            data: {modalResumenModelos:true, idModelo: idModelo},
                            success: function(response) {
                                $('#resumenModelo .modal-body').html(response);
                            }
                        });
                    });
                    $('#tablamodelos').on('click', '.botoneliminar', function() {
                        if (confirm("¿Estás seguro de que deseas eliminar todas las fundas de este modelo?")) {
                            var idModelo = $(this).val();
                            $.ajax({
                                url: 'ajaxData_1.php',
                                type: 'post',
                                data: {borrarFundas:true, idModelo: idModelo},
                                success: function(response) {
                                    // Recargar la tabla de modelos después de eliminar
                                    $('#tablamodelos').html(response);
                                }
                            });
                        }
                    });
                });
            </script>
    </body>
</html>