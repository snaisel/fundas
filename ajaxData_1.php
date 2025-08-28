<?php
require 'class.php';
if (isset($_POST['parametro'])) {
    if ($_POST['parametro'] == "stock") {
        if (!isset($_POST['page'])) {
            echo Modelo::get_tabla_modelos("stock", $_POST['orderby'], 1, 20, NULL, NULL, NULL, NULL);
        } else if (isset($_POST['page'])) {
            echo Modelo::get_tabla_modelos("stock", $_POST['orderby'], $_POST['page']);
        }
    } else {
        if (isset($_POST['model']) && $_POST['model'] != "" && !isset($_POST['page'])) {
            echo Modelo::get_tabla_modelos($_POST['parametro'], $_POST['orderby'], 1, 20, $_POST['model']);
        } else if (isset($_POST['page']) && !isset($_POST['model'])) {
            echo Modelo::get_tabla_modelos($_POST['parametro'], $_POST['orderby'], $_POST['page']);
        } else if (isset($_POST['model']) && isset($_POST['page'])) {
            echo Modelo::get_tabla_modelos($_POST['parametro'], $_POST['orderby'], $_POST['page'], 20, $_POST['model']);
        } else {
            echo Modelo::get_tabla_modelos($_POST['parametro'], $_POST['orderby']);
        }
    }
}
if (isset($_POST['modalResumenModelos'])) {
    if (isset($_POST['idModelo']) && $_POST['idModelo'] != "") {
        echo Stock::get_resumen_modelo($_POST['idModelo']);
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
            echo Modelo::get_tabla_modelos();
        }
    } else {
        echo "<p>Error: No se ha recibido idModelo</p>";
    }
}