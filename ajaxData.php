<?php
require 'class.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['refMarca']) && empty($_POST['refYear'])) {
    $con = getdb();
    $retuntext = "";
    $Sql = "SELECT * FROM modelo WHERE `refMarca` = " . $_POST['refMarca'] . " ORDER BY idModelo ASC";
    $result = mysqli_query($con, $Sql);
    if (mysqli_num_rows($result) > 0) {
        echo '<option value = "">elige modelo</option>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value = " . $row['refMarca'] . $row['refYear'] . $row['refModelo'] . ">" . $row['nombreModelo'] . "</option>";
        }
    }
}
if (isset($_POST['refYear']) && empty($_POST['refMarca'])) {
    $con = getdb();
    $retuntext = "";
    $Sql = "SELECT * FROM modelo WHERE `refYear` = " . $_POST['refYear'] . " ORDER BY idModelo ASC";
    $result = mysqli_query($con, $Sql);
    if (mysqli_num_rows($result) > 0) {
        echo '<option value = "">elige modelo</option>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value = " . $row['refMarca'] . $row['refYear'] . $row['refModelo'] . ">" . $row['nombreModelo'] . "</option>";
        }
    }
}
if (isset($_POST['refYear']) && isset($_POST['refMarca'])) {
    $con = getdb();
    $Sql = "SELECT * FROM modelo WHERE `refYear` = " . $_POST['refYear'] . " AND `refMarca` = " . $_POST['refMarca'] . " ORDER BY idModelo ASC";
    $result = mysqli_query($con, $Sql);
    if (mysqli_num_rows($result) > 0) {
        echo '<option value = "">elige modelo</option>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value = " . $row['refMarca'] . $row['refYear'] . $row['refModelo'] . ">" . $row['nombreModelo'] . "</option>";
        }
    } else {
        echo "<option value =''>Modelo no disponible</option>";
    }
}
if (isset($_POST['refModel'])) {
    echo select_year(substr($_POST['refModel'], 2, 2));
}
if (isset($_POST['refColor'])) {
    $stock = get_stock_items($_POST['refModelo'], $_POST['refTipo'], $_POST['refColor']);
    echo '<input type="number" name="stock" value="' . $stock . '" required>';
}
if (isset($_POST['refColoroption'])) {
    if (get_color($_POST['refColoroption'])) {
        echo "La referencia " . $_POST['refColoroption'] . " es el color " . get_color($_POST['refColoroption']);
    }
}
if (!empty($_POST["keyword"])) {
    $con = getdb();
    $query = "SELECT * FROM modelo WHERE nombreModelo like '" . $_POST["keyword"] . "%' ORDER BY nombreModelo LIMIT 0,6";
    $result = mysqli_query($con, $query);
    if (!empty($result)) {
        if (mysqli_num_rows($result) > 0) {
            ?>
            <div
                id="model-list" class="list-group">
                <?php
                foreach ($result as $row) {
                    ?>
                    <a href="#" class="list-group-item list-group-item-action" onclick="selectModel('<?php echo $row["nombreModelo"]; ?>');"><?php echo $row["nombreModelo"]; ?></a>
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
                        <div class="form-group col-sm-3">
                            <input
                            type="hidden" value="<?php echo $_POST["idStockEditar"]; ?>" name="idStock">
                        <?php
                        echo "Marcas" . select_marcas(substr($row['refModel'], 0, 2));
                        echo "Año" . select_year(substr($row['refModel'], 2, 2));
                        echo "Modelo";
                        echo select_modelos($row['refModel']);
                        ?>
                        </div>
                        <div
                            class="form-group col-sm-3"><?php
                            echo "Tipo" . select_tipo($row['refTipo']);
                            ?>
                        </div>
                        <div
                            class="form-group col-sm-3"><?php
                            echo "Color" . select_color($row['refColor']);
                            ?>
                        </div>
                        <div class="form-group col-sm-3">
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
                            <button class="btn btn-primary" type="submit" id="enviarStock">Enviar</button>
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
        get_modelos($_POST['idMarcaListado']);
        ?>
        <a href="opciones.php" class="btn btn-primary">Añadir</a>
    </div>
    <?php
}
if (isset($_POST['refYearListado'])) {
    ?>
    <div class="card-body">
        <h5 class="card-title">Modelos</h5>
        <?php
        get_modelos(null, $_POST['refYearListado']);
        ?>
        <a href="opciones.php" class="btn btn-primary">Añadir</a>
    </div>
    <?php
}
if (isset($_POST['refMarcaFilter']) && isset($_POST['refYearFilter'])) {
    ?>
    <div class="card-body">
        <h5 class="card-title">Modelos</h5>
        <?php
        get_modelos($_POST['refMarcaFilter'], $_POST['refYearFilter']);
        ?>
        <a href="opciones.php" class="btn btn-primary">Añadir</a>
    </div>
    <?php

}
if (isset($_POST['noFilters'])) {
    echo "<div class='card-body'>";
    echo "<h5 class='card-title'>Modelos</h5>";
    echo get_modelos();
    echo "</div>";
    echo "<div class='card-footer'>";
    echo "<a href='opciones.php' class='btn btn-primary'>Añadir</a>";
    echo "</div>";
}
if (isset($_POST['parametro'])) {
    if (isset($_POST['model']) && $_POST['model'] != "" && !isset($_POST['page'])) {
        echo get_fundas($_POST['parametro'], $_POST['ascdesc'], 1, 20, $_POST['model']);
    } else if (isset($_POST['page']) && !isset($_POST['model'])) {
        echo get_fundas($_POST['parametro'], $_POST['ascdesc'], $_POST['page']);
    } else if (isset($_POST['model']) && isset($_POST['page'])) {
        echo get_fundas($_POST['parametro'], $_POST['ascdesc'], $_POST['page'], 20, $_POST['model']);
    } else {
        echo get_fundas($_POST['parametro'], $_POST['ascdesc']);
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
    $idModelo = $_POST['idModeloEditar'];
    $conn = getdb();
    // Consulta SQL
    $sql = "SELECT * FROM modelo WHERE idModelo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idModelo);
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