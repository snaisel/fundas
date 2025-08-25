<?php

/**
 * Classes and helper functions for the application
 *
 * @author Nestor Gago Cabrera
 */
session_start();
if (!file_exists("config.php")) {
    header("Location: install.php");
}

function getdb() {
    $config = include("config.php");
    $servername = $config['host'];
    $username = $config['user'];
    $password = $config['pass'];
    $db = $config['db'];

    try {
        $conn = mysqli_connect($servername, $username, $password, $db);
        //echo "Connected successfully"; 
    } catch (exception $e) {
        echo "<br><b>Connection failed: " . $e->getMessage();
        echo "</b>";
    }
    return $conn;
}

function select_marcas($marca = false) {
    $con = getdb();
    $retuntext = "";
    $Sql = "SELECT * FROM marca";
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        $retuntext .= "No hay marcas registradas";
    } else {
        $retuntext .= "<select class='form-select' name='marcas' id='marcas' required><option value=''>--Elegir--</option>";
        while ($row = mysqli_fetch_assoc($result)) {
            if ($marca && $marca == $row['refMarca']) {
                $retuntext .= "<option value=" . $row['refMarca'] . " selected>" . $row['nombreMarca'] . "</option>";
            } else {
                $retuntext .= "<option value=" . $row['refMarca'] . ">" . $row['nombreMarca'] . "</option>";
            }
        }

        $retuntext .= "</select>";
    }
    return $retuntext;
}

function get_marca_id($ref) {
    $con = getdb();
    $Sql = "SELECT * FROM marca WHERE `refMarca` = " . $ref;
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        return false;
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            return $row['idMarca'];
        }
    }
}

function get_marca_name($ref) {
    $con = getdb();
    $Sql = "SELECT * FROM marca WHERE `refMarca` = " . $ref;
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        return false;
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            return $row['nombreMarca'];
        }
    }
}

function get_marca_name_by_id($id) {
    $con = getdb();
    $Sql = "SELECT * FROM marca WHERE `idMarca` = " . $id;
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        return false;
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            return $row['nombreMarca'];
        }
    }
}
function get_marcas_by_year($ref) {
    $con = getdb();
    $Sql = "SELECT * FROM modelo WHERE `refYear` = " . $ref . " GROUP BY refMarca";
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        return false;
    } else {
        $marcas = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $marcas[] = $row['refMarca'];
        }
        return array_unique($marcas);
    }
}
function select_year($year = 0) {
    $con = getdb();
    $retuntext = "";
    $Sql = "SELECT * FROM year ORDER BY `year`.`refYear` DESC ";
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        $retuntext .= "No hay años registradas";
    } else {
        $retuntext .= "<select class='form-select' name='year' id='year' required><option value=''>--Elegir--</option>";
        while ($row = mysqli_fetch_assoc($result)) {
            $retuntext .= "<option value=" . $row['refYear'];
            if ($year == $row['refYear']) {
                $retuntext .= " selected ";
            }
            $retuntext .= ">" . $row['year'] . "</option>";
        }
        $retuntext .= "</select>";
    }
    mysqli_close($con);
    return $retuntext;
}
function get_year($ref) {
    $con = getdb();
    $Sql = "SELECT * FROM year WHERE `refYear` = " . $ref;
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        return false;
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            return $row['year'];
        }
    }
}
function get_year_by_id($id) {
    $con = getdb();
    $Sql = "SELECT * FROM year WHERE `idYear` = " . $id;
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        return false;
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            return $row['year'];
        }
    }
}

function select_modelos($modelo = false) {
    $con = getdb();
    $retuntext = "";
    if ($modelo) {
        $Sql = "SELECT * FROM modelo WHERE refMarca = " . substr($modelo, 0, 2) . " ORDER BY idModelo ASC";
    } else {
        $Sql = "SELECT * FROM modelo ORDER BY idModelo ASC";
    }
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        $retuntext .= "No hay modelos registradas";
    } else {
        $retuntext .= "<select class='form-select' name='modelos' id='modelos' required><option value=''>--Elegir--</option>";
        while ($row = mysqli_fetch_assoc($result)) {
            $retuntext .= "<option value='" . $row['refMarca'] . $row['refYear'] . $row['refModelo'] . "'";
            if ($modelo && $modelo == $row['refMarca'] . $row['refYear'] . $row['refModelo']) {
                $retuntext .= " selected >";
            } else {
                $retuntext .= ">";
            }
            $retuntext .= $row['nombreModelo'] . "</option>";
        }
        $retuntext .= "</select>";
    }
    return $retuntext;
}

function select_modelos_multiple() {
    $con = getdb();
    $retuntext = "";
    $Sql = "SELECT * FROM modelo ORDER BY `refMarca`, `refYear`, `nombreModelo` ASC";
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        $retuntext .= "No hay modelos registradas";
    } else {
        $retuntext .= "<select id='seleccionarRels' class='form-select' name='relmodelos[]' multiple>";
        while ($row = mysqli_fetch_assoc($result)) {
            $retuntext .= "<option class='modeloRel' id=" . $row['nombreModelo'] . " value=" . $row['refMarca'] . $row['refYear'] . $row['refModelo'] . ">" . $row['nombreModelo'] . "</option>";
        }

        $retuntext .= "</select>";
    }
    return $retuntext;
}

function select_tipo($tipo = false) {
    $con = getdb();
    $retuntext = "";
    $Sql = "SELECT * FROM tipo";
    $result = mysqli_query($con, $Sql);
    if (mysqli_num_rows($result) > 0) {
        $retuntext .= "<select class='form-select' name='tipo' id='tipo' required><option value=''>--Elegir--</option>";
        while ($row = mysqli_fetch_assoc($result)) {
            $retuntext .= "<option value=" . $row['refTipo'];
            if ($tipo && $tipo == $row['refTipo']) {
                $retuntext .= " selected ";
            }
            $retuntext .= ">" . $row['nombreTipo'] . "</option>";
        }

        $retuntext .= "</select>";
    } else {
        $retuntext .= "No hay tipos registradas";
    }
    mysqli_close($con);
    return $retuntext;
}

function get_tipo($ref) {
    $con = getdb();
    $retuntext = "";
    $Sql = "SELECT * FROM tipo WHERE `refTipo` = " . $ref;
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        return false;
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            return $row['nombreTipo'];
        }
    }
}

function get_precio($ref) {
    $con = getdb();
    $retuntext = "";
    $Sql = "SELECT * FROM tipo WHERE `refTipo` = " . $ref;
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        return false;
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            return $row['pvp'];
        }
    }
}

function select_color($color = false) {
    $con = getdb();
    $retuntext = "";
    $Sql = "SELECT * FROM color";
    $result = mysqli_query($con, $Sql);
    if (mysqli_num_rows($result) > 0) {
        $retuntext .= "<select class='form-select' name='color' id='color' required><option value=''>--Elegir--</option>";
        while ($row = mysqli_fetch_assoc($result)) {
            $retuntext .= "<option value=" . $row['refColor'];
            if ($color && $color == $row['refColor']) {
                $retuntext .= " selected";
            }
            $retuntext .= ">" . $row['nombreColor'] . "</option>";
        }

        $retuntext .= "</select>";
    } else {
        $retuntext .= "No hay años registradas";
    }
    mysqli_close($con);
    return $retuntext;
}

function get_modelo($modelo) {
    $con = getdb();
    $Sql = "SELECT * FROM modelo WHERE `refMarca`=" . substr($modelo, 0, 2) . " AND `refYear` = " . substr($modelo, 2, 2) . " AND `refModelo`=" . substr($modelo, 4, 2);
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        return false;
    } else {
        $row = mysqli_fetch_assoc($result);
        return $row['nombreModelo'];
    }
}
function get_modelo_by_id($idModelo) {
    $con = getdb();
    $Sql = "SELECT * FROM modelo WHERE `idModelo` = " . intval($idModelo);
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        return false;
    } else {
        $row = mysqli_fetch_assoc($result);
        return $row['nombreModelo'];
    }
}
function get_marca_by_modelo($ref) {
    $con = getdb();
    $Sql = "SELECT refMarca FROM modelo WHERE `refMarca`=" . substr($ref, 0, 2) . " AND `refYear` = " . substr($ref, 2, 2) . " AND `refModelo`=" . substr($ref, 4, 2) . " LIMIT 1";
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        return false;
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            $marca = $row['refMarca'];
        }
        return $marca;
    }
}
function get_modelo_filtrado($modelo) {
    $search = array("Galaxy ", "GALAXY ", "Redmi Note ", "REDMI NOTE ", "Redmi ", "REDMI ", "Xiaomi ", "XIAOMI ", "Mi ", "MI ", "Xperia ");
    foreach ($search as $filtro) {

        if (strpos($modelo, $filtro) !== false) {
            return str_replace($filtro, "", $modelo);
        } else {
            return $modelo;
        }
    }
}
function get_modelos($refMarca = null, $refYear = null) {
    $con = getdb();
    $marca = "";
    $sql = "SELECT * FROM `modelo`";
    if ($refMarca && !$refYear) {
        $sql .= " WHERE `refMarca` = '$refMarca'";
    } else if ($refYear && !$refMarca) {
        $sql .= " WHERE `refYear` = '$refYear'";
    } else if ($refYear && $refMarca) {
        $sql .= " WHERE `refMarca` = '$refMarca' AND `refYear` = '$refYear'";
    }
    $sql .= " ORDER BY `refMarca` ASC, `refYear` DESC, `nombreModelo`";
    $result = mysqli_query($con, $sql);
    if ($refMarca) {
        echo "<button class='btn btn-outline-secondary modeloFilter' id='marcaFilter' value='" . $refMarca . "'>" . get_marca_name($refMarca) . "</button>";
    }
    if ($refYear) {
        echo "<button class='btn btn-outline-secondary modeloFilter' id='yearFilter' value='" . $refYear . "'>" . get_year($refYear) . "</button>";
    }
    if (mysqli_num_rows($result) == 0) {

        echo "<div class='alert alert-warning'>No hay modelos disponibles</div>";
    } else {

        echo "<ul class='list-group' style='height:250px;overflow-y: scroll;margin:10px auto;'>";
        if (!$refMarca) {
            echo "<li><ul>";
        }
        while ($row = mysqli_fetch_assoc($result)) {
            if (!$refMarca) {
                if ($marca != $row['refMarca']) {
                    $marca = $row['refMarca'];
                    echo "</ul></li><li class='list-group-item'><h5>" . get_marca_name($marca) . "</h5><ul class='list-group'>";
                }
            }
            echo "<li class='list-group-item'>" . $row['refMarca'] . $row['refYear'] . $row['refModelo'] . " - " . $row['nombreModelo'];
            echo "<div><button type=button name='editarModelo' value=" . $row['idModelo'] . " class='botonEditarModelos btn btn-sm btn-success'>Editar</button>";
            echo "<button type=button name='eliminarModelo' value=" . $row['idModelo'] . " class='botonEliminarModelos btn btn-sm btn-danger'>Eliminar</button>";
            echo "<div></li>";
        }
        echo "</ul>";
    }
}
function get_color($ref) {
    $con = getdb();
    $Sql = "SELECT * FROM color WHERE `refColor` = " . $ref;
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        return false;
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            return $row['nombreColor'];
        }
    }
}

function get_thumb($ref) {
    $con = getdb();
    $Sql = "SELECT * FROM color WHERE `refColor` = " . $ref;
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        return false;
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            return $row['thumb'];
        }
    }
}

function get_stock($modelo, $tipo, $color) {
    $con = getdb();
    $Sql = "SELECT * FROM stock WHERE `refModel`=" . $modelo . " AND `refTipo` = " . $tipo . " AND `refColor`=" . $color;
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        return false;
    } else {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                return $row['idStock'];
            }
        }
    }
}

function get_stock_items($modelo, $tipo, $color) {
    $con = getdb();
    $Sql = "SELECT * FROM stock WHERE `refModel`=" . $modelo . " AND `refTipo` = " . $tipo . " AND `refColor`=" . $color;
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        return false;
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            return $row['stock'];
        }
    }
}

function get_fundas($parametro = "idStock", $orderby = "DESC", $page = 1, $limit = 20, $modelo = "") {
    $con = getdb();
    $query = $con->query("SELECT COUNT(*) as rowNum FROM stock");
    $result = $query->fetch_assoc();
    $rowCount = $result['rowNum'];
    $pages = $rowCount / $limit;

    if ($rowCount % $limit > 0)
        $pages++;
    $actualPage = $limit * ($page - 1);
    if ($modelo == "") {
        $Sql = "SELECT * FROM stock ORDER BY " . $parametro . " " . $orderby . " LIMIT " . $actualPage . ", " . $limit;
    } else {
        $query = $con->query("SELECT COUNT(*) as rowNum FROM stock WHERE `refModel`=" . $modelo);
        $result = $query->fetch_assoc();
        $rowCount = $result['rowNum'];
        $pages = $rowCount / $limit;
        if ($rowCount % $limit > 0)
            $pages++;
        $actualPage = $limit * ($page - 1);
        $Sql = "SELECT * FROM stock  WHERE `refModel`=" . $modelo . " ORDER BY " . $parametro . " " . $orderby . " LIMIT " . $actualPage . ", " . $limit;
    }
    $result = mysqli_query($con, $Sql);
    $to = mysqli_num_rows($result) + $actualPage;
    if (!$result) {
        return false;
    } else {
        $html = "";
        $html .= "<div class='row mt-2 mb-2'><div class='col'>Filtrar por modelo <i class='bi bi-funnel'></i> " . select_model_stock($modelo);
        $html .= "</div><div class='col text-end'>Mostrando del " . $actualPage . " al " . $to . " de " . $rowCount . "</div></div>";
        if (get_relaciones_title($modelo) && $modelo != "") {
            $relacionados = get_relaciones_title($modelo);
            $html .= "<div class='alert alert-sm alert-info'>Modelos relacionados: " . $relacionados . "</div>";

        }
        $html .= "<table class='table table-striped' style='width:100%;'><thead><tr><th><a href='#' class='ordenar active' id='idStock'>Codigo</a></th><th><a href='#' class='ordenar'  id='idMarca'>Marca</a></th><th><a href='#' class='ordenar'  id='refModel'>Modelo</a></th><th><a href='#' class='ordenar'  id='refTipo'>Tipo</a></th><th><a href='#' class='ordenar'  id='refColor'>Color</a></th><th>Precio</th><th>Rel</th><th><a href='#' class='ordenar'  id='stock'>Stock</a></th><th><a href='#' class='ordenar'  id='modificado'>Modificado</a></th><th>Acciones</th></tr></thead><tbody id='cuerpostock' class='" . $orderby . "'>";
        while ($row = mysqli_fetch_assoc($result)) {
            $html .= get_funda_row($row['idStock'], $row['refModel'], $row['refTipo'], $row['refColor'], $row['usarRel'], $row['stock'], $row['modificado']);
        }
        $html .= "</tbody></table>";
        if ($rowCount == 0) {
            $html .= " <div class='alert alert-warning'>No hay resultados para esta funda</div>";
        }
        $html .= pagination($page, $pages, $actualPage, $rowCount, $to);
    }
    return $html;
}
function get_funda_row($idStock, $refModel, $refTipo, $refColor, $usarrel, $stock, $modificado) {
    $codigo = str_pad($idStock, 6, '0', STR_PAD_LEFT);
    $usarrelacion = "No";
    $relacionados = get_modelo($refModel);
    if ($usarrel == 1) {
        $usarrelacion = "Si";
        if (get_relaciones_title($refModel)) {
            $relacionados = get_relaciones_title($refModel);
        }
    }
    $html = "<tr id=fila" . $idStock . "><td>" . $codigo . "</td><td>" . get_marca_name(substr($refModel, 0, 2)) . "</td><td>" . $relacionados . "</td><td>" . get_tipo($refTipo) . "</td><td>" . get_color($refColor) . " <span style='width:16px;height:16px;display: inline-block;background:" . get_thumb($refColor) . ";'> </span></td><td>" . get_precio($refTipo) . "€</td><td>" . $usarrelacion . "</td><td>" . $stock . "</td><td>" . $modificado . "</td><td><button class='btn btn-info botoneditar' value='" . $idStock . "' name='editar'>Editar <i class='bi bi-pencil'></i></button> <button class='botoneliminar btn btn-danger' value='" . $idStock . "' name='eliminar'>Eliminar <i class='bi bi-trash'></i></button></td></tr>";
    return $html;
}
function get_fundas_array_by_model($model) {
    $con = getdb();
    $Sql = "SELECT * FROM stock WHERE `refModel`=" . $model;
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        return false;
    } else {
        $fundas = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $fundas[] = $row;
        }
        return $fundas;
    }
}

function get_fundas_csv($modelo = NULL) {

    $con = getdb();
    if (is_null($modelo)) {
        $Sql = "SELECT * FROM stock ORDER BY idMarca, refModel ";
    } else {
        $Sql = "SELECT * FROM stock  WHERE `refModel`=" . $modelo;
    }
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        return false;
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            $tipo = str_pad($row['refTipo'], 2, '0', STR_PAD_LEFT);
            $color = str_pad($row['refColor'], 2, '0', STR_PAD_LEFT);
            $usarrel = "No";
            $relacionados = get_modelo($row['refModel']);
            if ($row['usarRel'] == 1) {
                $usarrel = "Si";
                if (get_relaciones_title($row['refModel'])) {
                    $relacionados = get_relaciones_title($row['refModel']);
                }
            }
            $codigo = str_pad($row['idStock'], 6, '0', STR_PAD_LEFT);
            //$codigo = $row['refModel'] . "1" . $tipo . $color;
            //for ($i = 0; $i < $row['stock'] ; $i++) {
            $html[] = array(
                "codigo" => $codigo,
                "marca" => get_marca_name(substr($row['refModel'], 0, 2)),
                "modelo" => $relacionados,
                "tipo" => get_tipo($row['refTipo']),
                "color" => get_color($row['refColor']),
                "precio" => get_precio($row['refTipo']),
                "stock" => $row['stock'],
            );
            //}
        }
    }
    return $html;
}

function get_fundas_csv_fechas($fecha) {
    $con = getdb();
    $Sql = "SELECT * FROM stock  WHERE `modificado` >='" . $fecha . " 00:00:00' AND `modificado` < '" . $fecha . " 23:59:59'";
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        return false;
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            $tipo = str_pad($row['refTipo'], 2, '0', STR_PAD_LEFT);
            $color = str_pad($row['refColor'], 2, '0', STR_PAD_LEFT);
            $usarrel = "No";
            $relacionados = get_modelo($row['refModel']);
            if ($row['usarRel'] == 1) {
                $usarrel = "Si";
                if (get_relaciones_title($row['refModel'])) {
                    $relacionados = get_relaciones_title($row['refModel']);
                }
            }
            $codigo = str_pad($row['idStock'], 6, '0', STR_PAD_LEFT);
            //$codigo = $row['refModel'] . "1" . $tipo . $color;
            //for ($i = 0; $i < $row['stock'] ; $i++) {
            $html[] = array(
                "codigo" => $codigo,
                "marca" => get_marca_name(substr($row['refModel'], 0, 2)),
                "modelo" => $relacionados,
                "tipo" => get_tipo($row['refTipo']),
                "color" => get_color($row['refColor']),
                "precio" => get_precio($row['refTipo']),
                "stock" => $row['stock'],
            );
            //}
        }
    }
    return $html;
}

function get_relaciones($modelo = 0, $array = null) {
    $con = getdb();

    if ($modelo == 0) {
        $Sql = "SELECT * FROM `relacionados`";
    } else {
        $Sql = "SELECT * FROM `relacionados`  WHERE `referencias` like '%" . $modelo . "%'";
    }
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        return false;
    } else {
        if ($array) {
            $relacionados = array();
        } else {
            $relacionados = " ";
        }
        while ($row = mysqli_fetch_assoc($result)) {
            if ($array) {
                if ($row['referencias'] != $modelo) {
                    $relacionados = unserialize($row['referencias']);
                }
            } else {
                $relacionados .= "<div id='relacionados" . $row['idRel'] . "' class='eliminarRelaciones mb-5 col-lg-2 col-md-3 col-sm-6 '><h6>Referencia " . $row['idRel'] . "</h6><ul class='list-group'>";
                $referencias = unserialize($row['referencias']);
                if (is_array($referencias)) {
                    foreach ($referencias as $referencia) {
                        $relacionados .= "<li class='list-group-item'>" . get_marca_name(get_marca_by_modelo($referencia)) . " " . get_modelo($referencia) . "</li>";
                    }
                } else {
                    $relacionados .= "<li class='list-group-item'>" . get_marca_name(get_marca_by_modelo($referencias)) . " " . get_modelo($referencias) . "</li>";
                }
                $relacionados .= "</ul><button class='btn btn-danger eliminarRelacion' value='" . $row['idRel'] . "'>Eliminar <i class='bi bi-trash'></i></button></div>";
            }
        }
        if ($relacionados == " " && !$array) {
            $relacionados = "<div class='alert alert-warning'>No hay relaciones registradas</div>";
        }

        return $relacionados;
    }
}

function get_stock_by_idModelo($idModelo) {
    $total = 0;
    $con = getdb();
    $Sql = "SELECT * FROM `stock` WHERE `idModelo`=" . intval($idModelo);
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        return false;
    } else {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $total += $row['stock'];
            }
        }
        return $total;
    }
}

function get_relaciones_title($modelo = 0) {
    $con = getdb();
    if ($modelo == 0) {
        $Sql = "SELECT * FROM `relacionados`";
    } else {
        $Sql = "SELECT * FROM `relacionados`  WHERE `referencias` like '%" . $modelo . "%'";
    }
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        return false;
    } else {
        if (mysqli_num_rows($result) > 0) {
            $relacionados = "";
            while ($row = mysqli_fetch_assoc($result)) {
                $referencias = unserialize($row['referencias']);
                if (is_array($referencias)) {
                    foreach ($referencias as $key => $referencia) {
                        if ($key === array_key_first($referencias)) {
                            $relacionados .= get_modelo($referencia);
                        } else {
                            $relacionados .= "/";
                            $relacionados .= get_modelo_filtrado(get_modelo($referencia));
                        }
                    }
                } else {
                    $relacionados .= get_modelo($referencias);
                }
            }
            return $relacionados;
        } else {
            return false;
        }
    }
}

function get_rel_id($modelo) {
    $con = getdb();
    $Sql = "SELECT * FROM `relacionados`  WHERE `referencias` LIKE '%" . $modelo . "%' ";
    //$Sql = "SELECT * FROM `relacionados` WHERE `referencias` LIKE '%111626%'";
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        return 0;
    } else {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                return $row['idRel'];
            }
        } else {
            return 0;
        }
    }
}

function array2csv(array &$array) {
    if (count($array) == 0) {
        return null;
    }
    ob_start();
    $df = fopen("php://output", 'w');
    fputcsv($df, array_keys(reset($array)), ';', '"');
    foreach ($array as $row) {
        fputcsv($df, $row, ';', '"');
    }
    fclose($df);
    return ob_get_clean();
}

function download_send_headers($filename) {
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download  
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}

function select_model_stock($modelo = "") {
    $con = getdb();
    //    $Sql = "SELECT DISTINCT `refModel` FROM `stock` ORDER BY `idMarca`, `refModel`";
//    $result = mysqli_query($con, $Sql);
//    if (!$result) {
//        return false;
//    } else {
//        if (mysqli_num_rows($result) > 0) {
//            $relacionados = "";
//            $idmarca = 0;
//            $i = 0;
//            $html = "<select id='filterModel' name='filtroModel' class='form-control choices-single'><option value=''>Todos</option>";
//            while ($row = mysqli_fetch_assoc($result)) {
//                $i++;
//                if ($idmarca != substr($row['refModel'], 0, 2)) {
//                    if ($idmarca != 0)
//                        $html .= "</optgroup>";
//                    $idmarca = substr($row['refModel'], 0, 2);
//                    $html .= "<optgroup label='" . get_marca_name(substr($row['refModel'], 0, 2)) . "'>";
//                }
//                $html .= "<option value='" . $row['refModel'] . "' ";
//                if ($modelo == $row['refModel']) {
//                    $html .= "selected ";
//                }
//                $html .= "' >" . get_modelo($row['refModel']) . "</option>";
//                if ($i == mysqli_num_rows($result)) {
//                    $html .= "</optgroup>";
//                }
//            }
//            $html .= "</select>";
//        }
//        return $html;
//    }
    $Sql = "SELECT * FROM `modelo` ORDER BY `refMarca`, `refYear`";
    $result = mysqli_query($con, $Sql);
    if (!$result) {
        return false;
    } else {
        if (mysqli_num_rows($result) > 0) {
            $relacionados = "";
            $idmarca = 0;
            $i = 0;
            $html = "<select id='filterModel' name='filtroModel' class='form-control choices-single'><option value=''>Todos</option>";
            while ($row = mysqli_fetch_assoc($result)) {
                $i++;
                $refModel = $row['refMarca'] . $row['refYear'] . $row['refModelo'];
                if ($idmarca != $row['refMarca']) {
                    if ($idmarca != 0) {
                        $html .= "</optgroup>";
                    }
                    $idmarca = $row['refMarca'];
                    $html .= "<optgroup label='" . get_marca_name($row['refMarca']) . "'>";
                }
                $html .= "<option value='" . $refModel . "' ";
                if ($modelo == $refModel) {
                    $html .= "selected ";
                }
                $html .= "' >" . get_modelo($refModel) . "</option>";
                if ($i == mysqli_num_rows($result)) {
                    $html .= "</optgroup>";
                }
            }
            $html .= "</select>";
        }
        return $html;
    }
}

function get_tabla_modelos($parametro = "idModelo", $orderby = "ASC", $page = 1, $limit = 20, $marca = NULL, $year = NULL, $modelo = NULL, $idModelo = NULL) {
    $con = getdb();
    $query = $con->query("SELECT COUNT(*) as rowNum FROM modelo");
    $result = $query->fetch_assoc();
    $rowCount = $result['rowNum'];
    $pages = $rowCount / $limit;
    if ($rowCount % $limit > 0)
        $pages++;
    $actualPage = $limit * ($page - 1);
    if (is_null($idModelo)) {
        if ($parametro == "stock") {
            $Sql = "SELECT m.*, SUM(s.stock) AS totalStock FROM modelo m LEFT JOIN stock s ON s.idModelo = m.idModelo GROUP BY s.idModelo ORDER BY totalStock " . $orderby . " LIMIT " . $actualPage . ", " . $limit;
        } else {
            $Sql = "SELECT * FROM modelo ORDER BY " . $parametro . " " . $orderby . " LIMIT " . $actualPage . ", " . $limit;
        }
    } else {
        $query = $con->query("SELECT COUNT(*) as rowNum FROM modelo WHERE `idModelo`=" . $idModelo);
        $result = $query->fetch_assoc();
        $rowCount = $result['rowNum'];
        $pages = $rowCount / $limit;
        if ($rowCount % $limit > 0)
            $pages++;
        $actualPage = $limit * ($page - 1);
        $Sql = "SELECT * FROM modelo  WHERE `idModelo`=" . $idModelo . " ORDER BY " . $parametro . " " . $orderby . " LIMIT " . $actualPage . ", " . $limit;
    }
    $result = mysqli_query($con, $Sql);
    $to = mysqli_num_rows($result) + $actualPage;
    if (!$result) {
        return false;//por aqui
    } else {
        $html = '<br>Mostrando del ' . $actualPage . ' al ' . $to . ' de ' . $rowCount;
        $html .= '<table class="table table-striped" style="width:100%;">';
        $html .= '<thead id="headModelos"><tr id="orderby" class="' . $orderby . '"><th><a href=# class="order modelos active" id="idModelo">Id</a></th><th><a href=# class="order modelos" id="refMarca">Marca</a></th><th><a href=# class="order modelos" id="refYear">Año</a></th><th><a href=# class="order modelos" id="refModelo">Modelo</a></th><th><a href=# class="order modelos" id="refCompleta">Ref</a></th><th><a href=# class="order modelos" id="stock">Stock</a></th><th>Relacionados</th><th>Acciones<span class="badge bg-warning text-dark">En construccion</span></th></tr></thead>';
        $html .= '<tbody id="cuerpoModelos">';

        while ($row = mysqli_fetch_assoc($result)) {
            $model = $row['refMarca'] . $row['refYear'] . $row['refModelo'];
            $html .= "<tr id=filaModelos" . $row['idModelo'] . "><td>" . $row['idModelo'] . "</td><td>" . get_marca_name($row['refMarca']) . "</td><td>" . $row['refYear'] . "</td><td>" . get_modelo_by_id($row['idModelo']) . "</td><td>" . $row['refMarca'] . $row['refYear'] . $row['refModelo'] . "</td>";
            $html .= "<td>";
            if ($parametro == "stock") {
                $html .= $row['totalStock'];
            } else {
                $html .= get_stock_by_idModelo($row['idModelo']);
            }
            $html .= "</td><td>" . get_relaciones_title($model) . "</td>";
            //$html .= "<td><button class='btn btn-info botoneditar' value='" . $row['idModelo'] . "' name='editar'>Editar <i class='bi bi-pencil'></i></button> <button class='botoneliminar btn btn-danger' value='" . $row['idModelo'] . "' name='eliminar'>Eliminar <i class='bi bi-trash'></i></button></td></tr>";
        }
        $html .= '</tbody></table>';
        $html .= pagination($page, $pages, $actualPage, $rowCount, $to);
        $html .= "";
    }
    return $html;
}

function pagination($page, $pages, $actualPage, $rowCount, $to) {
    $html = "<div><ul class='pagination flex-wrap'>";
    for ($i = 1; $i < $pages; $i++) {
        $html .= '<li class="page-item"><a class="page-link';
        if ($page == $i)
            $html .= ' active';
        $html .= '" id="' . $i . '" href="#">' . $i . '</a></li>';
    }
    $html .= "</ul></div>";
    $html .= "<br>Mostrando del " . $actualPage . " al " . $to . " de " . $rowCount;
    return $html;
}

function encrypt_pass($password) {
    $options = array(
        'cost' => 12,
    );
    $hashedpass = password_hash($password, PASSWORD_BCRYPT, $options);
    return $hashedpass;
}

function show_img($str) {
    $imgs = explode("|", $str);
    $imagenes = "";
    foreach ($imgs as $img) {
        $imagenes .= '<a class="group1" href=' . $img . '><img src="' . $img . '" style="width:80px; height:auto; display:inline-block; padding:5px;" /></a>';
    }
    return $imagenes;
}
