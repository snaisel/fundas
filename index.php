<?php
/**
 * Sistema de Inventarización de fundas
 * @author Nestor Gago Cabrera
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (isset($_POST['logout'])) {
    session_start();
    session_destroy();
    header("Location: index.php");
    exit;
}
require 'class.php';
$con = getdb();
if (!$con) {
    echo '<!DOCTYPE html>
    <html>
    <head>
        <title>Error de conexión</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-5">
            <div class="alert alert-danger">
                <strong>Error:</strong> No se pudo conectar a la base de datos. Por favor, revise la configuración en <code>config.php</code> o contacte al administrador.
            </div>
        </div>
    </body>
    </html>';
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Sistema de Fundas Conexionred</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="js.js" type="text/javascript"></script>
        <script src="js-index.js" type="text/javascript"></script>
    </head>
    <body>
        <?php require 'login.php'; ?>
        <div
            class="container">
            <?php include 'header.php'; ?>
            <main>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Marcas
                                    <span class="badge bg-secondary">Pulsa en la marca para filtrar el modelo</span>
                                </h5>
                                <?php
                                $sql = "SELECT * FROM `marca` ORDER BY refMarca";
                                $result = mysqli_query($con, $sql);
                                if (mysqli_num_rows($result) == 0) {
                                    echo "<div class='alert alert-warning'>No hay marcas disponibles</div>";
                                } else {
                                        echo "<div id='listadoMarcas' class='list-group' style='height:250px;overflow-y: scroll;margin:10px auto;'>";
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<li class='list-group-item list-group-item-action'>";
                                            echo "<button type=button value=" . $row['refMarca'] . " class=' btn btn-secondary botonListadoMarcas'>" . $row['refMarca'] . " - " . $row['nombreMarca'] . "</button>";
                                            echo "<button type=button name='editarMarca' value=" . $row['idMarca'] . " class='botonEditarMarcas btn btn-success'>Editar</button>";
                                        echo "<button type=button name='eliminarMarca' value=" . $row['idMarca'] . " class='botonEliminarMarcas btn btn-danger'>Eliminar</button>";
                                        echo "</li>";
                                    }

                                    echo "</div>";
                                }
                                ?>
                                <!-- Modal editar Marca -->
                                <div class="modal fade" id="editarMarcaModal" tabindex="-1" aria-labelledby="editarMarcaModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editarMarcaModalLabel">Editar Marca</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="formEditarMarca">
                                                    <input type="hidden" id="idMarca" name="idMarca">
                                                    <div class="mb-3">
                                                        <label for="nombreMarca" class="form-label">Nombre de la Marca</label>
                                                        <input type="text" class="form-control" id="nombreMarca" name="nombreMarca" required>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                <button type="button" class="btn btn-primary" id="guardarCambiosMarca">Guardar cambios</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="opciones.php" class="btn btn-primary">Añadir</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Años</h5>
                                <?php
                                $sql = "SELECT * FROM `year`";
                                $result = mysqli_query($con, $sql);
                                if (mysqli_num_rows($result) == 0) {
                                    echo "<div class='alert alert-warning'>No hay años disponibles</div>";
                                } else {
                                    echo "<ul class='list-group' style='height:250px;overflow-y: scroll;margin:10px auto;'>";
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<li class='list-group-item'>" . $row['refYear'] . " - " . $row['year'] . "</li>";
                                    }
                                    echo "</ul>";
                                }
                                ?>

                            </div>
                            <div class="card-footer">
                                <a href="opciones.php" class="btn btn-primary">Añadir</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card" id="listadoModelos">
                            <div class="card-body">
                                <h5 class="card-title">Modelos</h5>
                                <?php
                                $marca = "";
                                $sql = "SELECT * FROM `modelo` ORDER BY `refMarca` ASC, `refYear` DESC, `nombreModelo`";
                                $result = mysqli_query($con, $sql);
                                if (mysqli_num_rows($result) == 0) {
                                    echo "<div class='alert alert-warning'>No hay modelos disponibles</div>";
                                } else {
                                    echo "<ul class='list-group' style='height:250px;overflow-y: scroll;margin:10px auto;'><li><ul>";
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        if ($marca != $row['refMarca']) {
                                            $marca = $row['refMarca'];
                                            echo "</ul></li><li class='list-group-item'>" . get_marca_name($marca) . "<ul class='list-group'>";
                                        }
                                        echo "<li class='list-group-item'>" . $row['refMarca'] . $row['refYear'] . $row['refModelo'] . " - " . $row['nombreModelo'];
                                        echo "<div><button type=button name='editarModelo' value=" . $row['idModelo'] . " class='botonEditarModelos btn btn-sm btn-success'>Editar</button>";
                                        echo "<button type=button name='eliminarMarca' value=" . $row['idModelo'] . " class='botonEliminarModelos btn btn-sm btn-danger'>Eliminar</button>";
                                        echo "<div></li>";
                                    }
                                    echo "</ul>";
                                }
                                ?>

                            </div>
                            <div class="card-footer">
                                <a href="opciones.php" class="btn btn-primary">Añadir</a>
                            </div>
                        </div>
                        <!-- Modal editar Modelo -->
                        <div class="modal fade" id="editarModeloModal" tabindex="-1" aria-labelledby="editarModeloModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editarModeloModalLabel">Editar Modelo</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formEditarModelo">
                                            <input type="hidden" id="idModelo" name="idModelo">
                                            <div class="mb-3">
                                                <label for="nombreModelo" class="form-label">Nombre del Modelo</label>
                                                <input type="text" class="form-control" id="nombreModelo" name="nombreModelo" required>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="button" class="btn btn-primary" id="guardarCambiosModelo">Guardar cambios</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Tipos</h5>
                                <?php
                                $sql = "SELECT * FROM `tipo`";
                                $result = mysqli_query($con, $sql);
                                if (mysqli_num_rows($result) == 0) {
                                    echo "<span class='alert alert-warning'>No hay tipos disponibles</span>";
                                } else {
                                    echo "<ul class='list-group' id='listadoTipos' style='height:250px;overflow-y: scroll;margin:10px auto;'>";
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<li class='list-group-item'>" . $row['refTipo'] . " - " . $row['nombreTipo'] . " - " . $row['pvp'] . "<div> "
                                            . "<button type='button' name='editarTipo' value='" . $row['idTipo'] . "' class='botonEditarTipos btn btn-sm btn-success' data-bs-toggle='modal' data-bs-target='#editarTipoModal'>Editar</button>"
                                            . "<button type='button' name='eliminarTipo' value='" . $row['idTipo'] . "' class='botonEliminarTipos btn btn-sm btn-danger'>Eliminar</button>"
                                            . "</div></li>";
                                    }
                                    echo "</ul>";
                                }
                                ?>
                                <!-- Modal para editar tipo -->
                                <div class="modal fade" id="editarTipoModal" tabindex="-1" aria-labelledby="editarTipoModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editarTipoModalLabel">Editar Tipo</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="formEditarTipo">
                                                    <input type="hidden" id="idTipo" name="idTipo">
                                                    <div class="mb-3">
                                                        <label for="nombreTipo" class="form-label">Nombre Tipo</label>
                                                        <input type="text" class="form-control" id="nombreTipo" name="nombreTipo">

                                                        <label for="pvp" class="form-label">PVP</label>
                                                        <input type="text" class="form-control" id="pvp" name="pvp">
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                <button type="button" id="guardarCambiosTipo" class="btn btn-primary">Guardar Cambios</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a href="opciones.php" class="btn btn-primary">Añadir</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Colores</h5>
                                <?php
                                $sql = "SELECT * FROM `color`";
                                $result = mysqli_query($con, $sql);
                                if (mysqli_num_rows($result) == 0) {
                                    echo "<span class='alert alert-warning'>No hay colores disponibles</span>";
                                } else {
                                    echo "<ul class='list-group' id='listadoColores' style='height:250px;overflow-y: scroll;margin:10px auto;'>";
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<li class='list-group-item' >" . $row['nombreColor'] . " - " . $row['refColor'] . " <span style='width:16px;height:16px;display: inline-block;background:" . $row['thumb'] . ";'> </span></li>" . "<div> "
                                            . "<button type='button' name='editarColor' value='" . $row['idColor'] . "' class='botonEditarColores btn btn-sm btn-success' data-bs-toggle='modal' data-bs-target='#editarColorModal'>Editar</button>"
                                            . "<button type='button' name='eliminarTipo' value='" . $row['idColor'] . "' class='botonEliminarColores btn btn-sm btn-danger'>Eliminar</button>"
                                            . "</div></li>";
                                    }
                                    echo "</ul>";
                                }
                                ?>
                                <!-- Modal para editar tipo -->
                                <div class="modal fade" id="editarColorModal" tabindex="-1" aria-labelledby="editarColorModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editarColorModalLabel">Editar Color</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="formEditarColor">
                                                    <input type="hidden" id="idColor" name="idColor">
                                                    <div class="mb-3">
                                                        <label for="nombreColor" class="form-label">Nombre Color</label>
                                                        <input type="text" class="form-control" id="nombreColor" name="nombreColor">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="thumb" class="form-label">thumb</label>
                                                        <input type="text" class="form-control" id="thumb" name="thumb">
                                                    </div>
                                                    <button type="button" id="guardarCambiosColor" class="btn btn-primary">Guardar Cambios</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a href="opciones.php" class="btn btn-primary">Añadir</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body" id="GrupoRelacionados">
                                <h5 class="card-title">Relaciones</h5>
                                <?php
                                echo get_relaciones();
                                ?>
                                <a href="opciones.php" class="btn btn-primary">Añadir</a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

    </body>
</html>

