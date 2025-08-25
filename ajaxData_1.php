<?php
require 'class.php';
if (isset($_POST['parametro'])) {
    if ($_POST['parametro'] == "stock") {
        if (!isset($_POST['page'])) {
            echo get_tabla_modelos("stock", $_POST['orderby'], 1, 20, NULL, NULL, NULL, NULL);
        } else if (isset($_POST['page'])) {
            echo get_tabla_modelos("stock", $_POST['orderby'], $_POST['page']);
        }
    } else if ($_POST['parametro'] == "refCompleta") {
        $parametro = "refMarca {$_POST['orderby']}, refYear {$_POST['orderby']}, refModelo ";
        if (isset($_POST['model']) && $_POST['model'] != "" && !isset($_POST['page'])) {
            echo get_tabla_modelos($parametro, $_POST['orderby'], 1, 20, $_POST['model']);
        } else if (isset($_POST['page']) && !isset($_POST['model'])) {
            echo get_tabla_modelos($parametro, $_POST['orderby'], $_POST['page']);
        } else if (isset($_POST['model']) && isset($_POST['page'])) {
            echo get_tabla_modelos($parametro, $_POST['orderby'], $_POST['page'], 20, $_POST['model']);
        } else {
            echo get_tabla_modelos($parametro, $_POST['orderby']);
        }
    } else {
        if (isset($_POST['model']) && $_POST['model'] != "" && !isset($_POST['page'])) {
            echo get_tabla_modelos($_POST['parametro'], $_POST['orderby'], 1, 20, $_POST['model']);
        } else if (isset($_POST['page']) && !isset($_POST['model'])) {
            echo get_tabla_modelos($_POST['parametro'], $_POST['orderby'], $_POST['page']);
        } else if (isset($_POST['model']) && isset($_POST['page'])) {
            echo get_tabla_modelos($_POST['parametro'], $_POST['orderby'], $_POST['page'], 20, $_POST['model']);
        } else {
            echo get_tabla_modelos($_POST['parametro'], $_POST['orderby']);
        }
    }
    //    $con = getdb();
//    $Sql = "SELECT * FROM stock  ORDER BY " . $_POST['parametro'] . " " . $orderby;
//    $result = mysqli_query($con, $Sql);
//
//    $html = "<table class='table table-striped' style='width:100%;'><thead><tr><th><a href='#' class='ordenar' id='idStock'>Codigo</a></th><th><a href='#' class='ordenar' id='idMarca'>Marca</a></th><th><a href='#' class='ordenar' id='refModel'>Modelo</a></th><th><a href='#' class='ordenar' id='refTipo'>Tipo</a></th><th><a href='#' class='ordenar' id='refColor'>Color</a></th><th>Precio</th><th>Rel</th><th><a href='#' class='ordenar' id='stock'>Stock</a></th><th>Acciones</th></tr></thead><tbody id='cuerpostock' class='" . $orderby . "'>";
//    while ($row = mysqli_fetch_assoc($result)) {
//        $tipo = str_pad($row['refTipo'], 2, '0', STR_PAD_LEFT);
//        $color = str_pad($row['refColor'], 2, '0', STR_PAD_LEFT);
//        $codigo = str_pad($row['idStock'], 6, '0', STR_PAD_LEFT);
//        $usarrel = "No";
//        $relacionados = get_modelo($row['refModel']);
//        if ($row['usarRel'] == 1) {
//            $usarrel = "Si";
//            if (get_relaciones_title($row['refModel'])) {
//                $relacionados = get_relaciones_title($row['refModel']);
//            }
//        }
//        // $codigo = $row['refModel'] . "1" . $tipo . $color;
//        //$codigo = "<img alt='".$codigo."' src='barcode.php?text=".$codigo."&print=true' />";
//        $html .= "<tr id=fila" . $row['idStock'] . "><td>" . $codigo . "</td><td>" . get_marca_name(substr($row['refModel'], 0, 2)) . "</td><td>" . $relacionados . "</td><td>" . get_tipo($row['refTipo']) . "</td><td>" . get_color($row['refColor']) . "</td><td>" . get_precio($row['refTipo']) . "€</td><td>" . $usarrel . "</td><td>" . $row['stock'] . "</td><td><button class='btn btn-info botoneditar' value='" . $row['idStock'] . "' name='editar'>Editar <i class='bi bi-pencil'></i></button> <button class='botoneliminar btn btn-danger' value='" . $row['idStock'] . "' name='eliminar'>Eliminar <i class='bi bi-trash'></i></button></td></tr>";
//    }
//    $html .= "</tbody></table>";
//    echo $html;
}
if (isset($_POST['modalResumenModelos'])) {
    if (isset($_POST['idModelo']) && $_POST['idModelo'] != "") {
        echo get_resumen_modelo($_POST['idModelo']);
    } else {
        echo "<p>Error: No se ha recibido idModelo</p>";
    }
}
if (isset($_POST['borrarFundas'])) {
    if (isset($_POST['idModelo']) && $_POST['idModelo'] != "") {
        $con = getdb();
        $query = "DELETE FROM `stock` WHERE `idModelo` = '" . $_POST["idModelo"] . "'";
        $result = mysqli_query($con, $query);
        if (!empty($result)) {
            echo get_tabla_modelos();
        }
    } else {
        echo "<p>Error: No se ha recibido idModelo</p>";
    }
}