<?php
require 'class.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (isset($_POST['changeModelos'])) {
    if (isset($_POST['idMarca']) && empty($_POST['idYear'])) {
        $con = getdb();
        $retuntext = "";
        $Sql = "SELECT * FROM modelo WHERE `idMarca` = " . $_POST['idMarca'] . " ORDER BY idModelo ASC";
        $result = mysqli_query($con, $Sql);
        if (mysqli_num_rows($result) > 0) {
            echo '<option value = "">elige modelo</option>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value = " . $row['idModelo'] . ">" . $row['nombreModelo'] . "</option>";
            }
        }
    }
    if (isset($_POST['idYear']) && empty($_POST['idMarca'])) {
        $con = getdb();
        $retuntext = "";
        $Sql = "SELECT * FROM modelo WHERE `idYear` = " . $_POST['idYear'] . " ORDER BY idModelo ASC";
        $result = mysqli_query($con, $Sql);
        if (mysqli_num_rows($result) > 0) {
            echo '<option value = "">elige modelo</option>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value = " . $row['idModelo'] . ">" . $row['nombreModelo'] . "</option>";
            }
        }
    }
    if (isset($_POST['idYear']) && isset($_POST['idMarca'])) {
        $con = getdb();
        $Sql = "SELECT * FROM modelo WHERE `idYear` = " . $_POST['idYear'] . " AND `idMarca` = " . $_POST['idMarca'] . " ORDER BY idModelo ASC";
        $result = mysqli_query($con, $Sql);
        if (mysqli_num_rows($result) > 0) {
            echo '<option value = "">elige modelo</option>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value = " . $row['idModelo'] . ">" . $row['nombreModelo'] . "</option>";
            }
        } else {
            echo "<option value =''>Modelo no disponible</option>";
        }
    }
    if (isset($_POST['idModelo'])) {
        echo Year::select_year(Year::get_idYear_by_modelo($_POST['idModelo']));
    }
}
if (isset($_POST['refColor']) && ($_POST['refTipo'] != "") && ($_POST['refModelo'] != "")) {
    $stock = Stock::get_stock_items($_POST['refModelo'], $_POST['refTipo'], $_POST['refColor']);
    echo '<input type="number" name="stock" value="' . $stock . '" required>';
}
if (isset($_POST['refColoroption'])) {
    if (Color::get_color_by_ref($_POST['refColoroption'])) {
        echo "La referencia " . $_POST['refColoroption'] . " es el color " . Color::get_color_by_ref($_POST['refColoroption']);
    }
}
if (!empty($_POST["keyword"])) {
    $con = getdb();
    $query = "SELECT * FROM modelo WHERE nombreModelo like '" . $_POST["keyword"] . "%' ORDER BY nombreModelo LIMIT 0,6";
    $result = mysqli_query($con, $query);
    if (!empty($result)) {
        if (mysqli_num_rows($result) > 0) {
            ?>
            <div id="model-list" class="list-group">
                <small>
                    Modelos existentes
                </small>
                <?php
                foreach ($result as $row) {
                    ?>
                    <a href="#" class="list-group-item list-group-item-action"
                        onclick="selectModel('<?php echo $row["nombreModelo"]; ?>');"><?php echo $row["nombreModelo"]; ?></a>
                <?php } ?>
            </div>
            <?php
        }
    }
}
if (!empty($_POST["nombreModelo"])) {
    $con = getdb();
    $query = "SELECT * FROM modelo WHERE nombreModelo = '" . $_POST["nombreModelo"] . "'";
    $result = mysqli_query($con, $query);
    if (!empty($result)) {
        foreach ($result as $row) {
            echo json_encode($row);
        }
    }
}
if (!empty($_POST["idStock"])) {
    $con = getdb();
    $query = "DELETE FROM `stock` WHERE `idStock` = '" . $_POST["idStock"] . "'";
    $result = mysqli_query($con, $query);
    if (!empty($result)) {
        foreach ($result as $row) {
            echo $row['refModelo'];
        }
    }
}
if (!empty($_POST["idStockEditar"])) {
    $con = getdb();
    $sql = "SELECT * FROM `stock` WHERE `idStock` = " . $_POST["idStockEditar"];
    $result = mysqli_query($con, $sql);
    if (!empty($result)) {
        if (mysqli_num_rows($result) > 0) {
            foreach ($result as $row) {
                ?>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-sm-4">
                            <input type="hidden" value="<?php echo $_POST["idStockEditar"]; ?>" name="idStock">
                            <?php
                            echo "Marcas" . Marca::select_marcas(Marca::get_idMarca_by_modelo($row['idModelo']));
                            echo "Año" . Year::select_year(Year::get_idYear_by_modelo($row['idModelo']));
                            echo "Modelo";
                            echo Modelo::select_modelos_by_id($row['idModelo']);
                            ?>
                        </div>
                        <div class="form-group col-sm-4"><?php
                        echo "Tipo" . Tipo::select_tipo($row['idTipo']);
                        echo "Color" . Color::select_color($row['idColor']);
                        ?>
                        </div>
                        <div class="form-group col-sm-4">
                            Stock
                            <?php
                            echo '<div id="stock"><input type="number" name="stock" value="' . $row['stock'] . '" required></div>';
                            ?>
                            Relacionados
                            <div id="relacionados">
                                <input type="radio" id="si" value="1" name="usarrel" <?php if ($row['usarRel'] == 1)
                                    echo " checked"; ?>><label for="si">Si</label>
                                <input type="radio" id="no" value="0" name="usarrel" <?php if ($row['usarRel'] == 0)
                                    echo " checked"; ?>><label for="no">No</label>
                            </div>
                            <button class="btn btn-primary" type="submit" id="enviarStock" name="enviarStock">Enviar</button>
                            <button class="btn btn-secondary" type="button" id="resetFormulario">Resetear Campos</button>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo 'No hay resultados. Comprueba el Codigo introducido';
        }
    }
}
if (isset($_POST['resetFormulario'])) {
    unset($_SESSION['variables']);
    ?>
    <div class="card-body">
        <div class="row">
            <div class="form-group col-sm-4">
                <?php
                echo "Marcas" . Marca::select_marcas();
                echo "Año" . Year::select_year();
                ?>
                Modelo
                <select name="selectModelos" class="form-select" id="selectModelos">
                    <option value="">Selecciona primero la marca</option>
                </select>
            </div>
            <div class="form-group col-sm-4">
                <?php echo "Tipo" . Tipo::select_tipo();
                echo "Color" . Color::select_color(); ?>
            </div>
            <div class="form-group col-sm-4">Stock
                <div id="stock"></div>
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
    <?php
}
if (isset($_POST['idRelEditar'])) {
    $con = getdb();
    $query = "DELETE FROM `relacionados` WHERE `idRel` = '" . $_POST["idRelEditar"] . "'";
    $result = mysqli_query($con, $query);
    if (!empty($result)) {
        foreach ($result as $row) {
            echo $row['idRelEditar'];
        }
    }
}
if (isset($_POST['idMarcaListado'])) {
    ?>
    <div class="card-body">
        <h5 class="card-title">Modelos</h5>
        <?php
        Modelo::get_modelos($_POST['idMarcaListado']);
        ?>
        <a href="opciones.php" class="btn btn-primary">Añadir</a>
    </div>
    <?php
}
if (isset($_POST['idYearListado'])) {
    ?>
    <div class="card-body">
        <h5 class="card-title">Modelos</h5>
        <?php
        Modelo::get_modelos(null, $_POST['idYearListado']);
        ?>
        <a href="opciones.php" class="btn btn-primary">Añadir</a>
    </div>
    <?php
}
if (isset($_POST['idMarcaFilter']) && isset($_POST['idYearFilter'])) {
    ?>
    <div class="card-body">
        <h5 class="card-title">Modelos</h5>
        <?php
        Modelo::get_modelos($_POST['idMarcaFilter'], $_POST['idYearFilter']);
        ?>
        <a href="opciones.php" class="btn btn-primary">Añadir</a>
    </div>
    <?php

}
if (isset($_POST['noFilters'])) {
    echo "<div class='card-body'>";
    echo "<h5 class='card-title'>Modelos</h5>";
    echo Modelo::get_modelos();
    echo "</div>";
    echo "<div class='card-footer'>";
    echo "<a href='opciones.php' class='btn btn-primary'>Añadir</a>";
    echo "</div>";
}
if (isset($_POST['parametro'])) {
    if (isset($_POST['model']) && $_POST['model'] != "" && !isset($_POST['page'])) {
        echo Stock::get_fundas($_POST['parametro'], $_POST['ascdesc'], 1, 20, $_POST['model']);
    } else if (isset($_POST['page']) && !isset($_POST['model'])) {
        echo Stock::get_fundas($_POST['parametro'], $_POST['ascdesc'], $_POST['page']);
    } else if (isset($_POST['model']) && isset($_POST['page'])) {
        echo Stock::get_fundas($_POST['parametro'], $_POST['ascdesc'], $_POST['page'], 20, $_POST['model']);
    } else {
        echo Stock::get_fundas($_POST['parametro'], $_POST['ascdesc']);
    }
}


if (isset($_POST['idMarcaEditar'])) {
    $idMarca = $_POST['idMarcaEditar'];
    $conn = getdb();

    // Consulta SQL
    $sql = "SELECT * FROM marca WHERE idMarca = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idMarca);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'No se encontró la marca']);
    }
    $stmt->close();
    $conn->close();
}
if (isset($_POST['idYearEditar'])) {
    $idYear = $_POST['idYearEditar'];
    $conn = getdb();
    // Consulta SQL
    $sql = "SELECT * FROM year WHERE idYear = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idYear);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'No se encontró el año']);
    }
    $stmt->close();
    $conn->close();
}
if (isset($_POST['idModeloEditar'])) {
    $conn = getdb();
    // Consulta SQL
    $sql = "SELECT * FROM modelo WHERE idModelo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_POST['idModeloEditar']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'No se encontró la marca']);
    }
    $stmt->close();
    $conn->close();
}

if (isset($_POST['idTipoEditar'])) {
    $conn = getdb();
    $idTipo = $_POST['idTipoEditar'];
    $sql = "SELECT * FROM tipo WHERE idTipo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idTipo);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'No se encontró el tipo']);
    }

    $stmt->close();
    $conn->close();
}
if (isset($_POST['idColorEditar'])) {
    $conn = getdb();
    $idColor = $_POST['idColorEditar'];
    $sql = "SELECT * FROM color WHERE idColor = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idColor);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'No se encontró el tipo']);
    }

    $stmt->close();
    $conn->close();
}

if (isset($_POST['idMarcaEliminar'])) {
    $con = getdb();
    $query = "DELETE FROM `marca` WHERE `idMarca` = '" . $_POST["idMarcaEliminar"] . "'";
    $result = mysqli_query($con, $query);
}
if (isset($_POST['idYearEliminar'])) {
    $con = getdb();
    $query = "DELETE FROM `year` WHERE `idYear` = '" . $_POST["idYearEliminar"] . "'";
    $result = mysqli_query($con, $query);
}
if (isset($_POST['idModeloEliminar'])) {
    $con = getdb();
    $query = "DELETE FROM `modelo` WHERE `idModelo` = '" . $_POST["idModeloEliminar"] . "'";
    $result = mysqli_query($con, $query);
}
if (isset($_POST['idTipoEliminar'])) {
    $con = getdb();
    $query = "DELETE FROM `tipo` WHERE `idTipo` = '" . $_POST["idTipoEliminar"] . "'";
    $result = mysqli_query($con, $query);
}
if (isset($_POST['idColorEliminar'])) {
    $con = getdb();
    $query = "DELETE FROM `color` WHERE `idColor` = '" . $_POST["idColorEliminar"] . "'";
    $result = mysqli_query($con, $query);
}