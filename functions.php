<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'class.php';

$con = getdb();
$hoy = date('Y-m-d', time());
$table = "";
$row = 1;
if (isset($_POST['addmarca'])) {
    $sql = "SELECT * FROM `marca` WHERE `nombreMarca` = '" . $_POST['marca'] . "'";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        $sqlm = "UPDATE `marca` SET `refMarca`='" . $_POST['marcaref'] . "' WHERE `nombreMarca` = '" . $_POST['marca'] . "'";
        $resultm = mysqli_query($con, $sqlm);
    } else {
        $sql = "SELECT * FROM `marca` WHERE `refMarca` = '" . $_POST['marcaref'] . "'";
        if (mysqli_num_rows($result) > 0) {
            $sqlm = "UPDATE `marca` SET `nombreMarca`='" . $_POST['marca'] . "' WHERE `refMarca` = '" . $_POST['marcaref'] . "'";
            $resultm = mysqli_query($con, $sqlm);
        } else {
            $sqlm = "INSERT INTO `marca` (`nombreMarca`, `refMarca`) VALUES ('" . $_POST['marca'] . "', '" . $_POST['marcaref'] . "')";
            $resultm = mysqli_query($con, $sqlm);
        }
    }
    echo "<script type=\"text/javascript\">alert(\"Marca insertada.\");window.location = \"opciones.php\"
               </script>";
}
if (isset($_POST['yearname'])) {
    $sql = "SELECT * FROM `year` WHERE `year` = '" . $_POST['yearname'] . "'";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        $sqlm = "UPDATE `year` SET `refYear`='" . $_POST['yearref'] . "' WHERE `year` = '" . $_POST['yearname'] . "'";
        $resultm = mysqli_query($con, $sqlm);
    } else {
        $sql = "SELECT * FROM `year` WHERE `refYear` = '" . $_POST['yearref'] . "'";
        if (mysqli_num_rows($result) > 0) {
            $sqlm = "UPDATE `year` SET `year`='" . $_POST['yearname'] . "' WHERE `refYear` = '" . $_POST['yearref'] . "'";
            $resultm = mysqli_query($con, $sqlm);
        } else {
            $sqlm = "INSERT INTO `year` (`year`, `refYear`) VALUES ('" . $_POST['yearname'] . "', '" . $_POST['yearref'] . "')";
            $resultm = mysqli_query($con, $sqlm);
        }
    }
    echo "<script type=\"text/javascript\">alert(\"Año insertado.\");window.location = \"opciones.php\"
               </script>";
}
if (isset($_POST['addcolor'])) {
    $sql = "SELECT * FROM `color` WHERE `nombreColor` = '" . $_POST['color'] . "'";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        $sqlm = "UPDATE `color` SET `refColor`='" . $_POST['colorref'] . "', `thumb`='" . $_POST['thumb'] . "' WHERE `nombreColor` = '" . $_POST['color'] . "'";
        $resultm = mysqli_query($con, $sqlm);
    } else {
        $sql = "SELECT * FROM `color` WHERE `refColor` = '" . $_POST['colorref'] . "'";
        if (mysqli_num_rows($result) > 0) {
            $sqlm = "UPDATE `color` SET `nombreColor`='" . $_POST['color'] . "', `thumb`='" . $_POST['thumb'] . "' WHERE `refColor` = '" . $_POST['colorref'] . "'";
            $resultm = mysqli_query($con, $sqlm);
        } else {
            $sqlm = "INSERT INTO `color` (`nombreColor`, `refColor`, `thumb`) VALUES ('" . $_POST['color'] . "', '" . $_POST['colorref'] . "', '" . $_POST['thumb'] . "')";
            $resultm = mysqli_query($con, $sqlm);
        }
    }
    echo "<script type=\"text/javascript\">alert(\"Color insertada.\");window.location = \"opciones.php\"
               </script>";
}
if (isset($_POST['addtipo'])) {
    $sql = "SELECT * FROM `tipo` WHERE `nombreTipo` = '" . $_POST['tipo'] . "'";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        $sqlm = "UPDATE `tipo` SET `refTipo`='" . $_POST['tiporef'] . "', `pvp`='" . $_POST['pvp'] . "' WHERE `nombreTipo` = '" . $_POST['tipo'] . "'";
        $resultm = mysqli_query($con, $sqlm);
    } else {
        $sql = "SELECT * FROM `tipo` WHERE `refTipo` = '" . $_POST['tiporef'] . "'";
        if (mysqli_num_rows($result) > 0) {
            $sqlm = "UPDATE `tipo` SET `nombreTipo`='" . $_POST['tipo'] . "', `pvp`='" . $_POST['pvp'] . "' WHERE `refTipo` = '" . $_POST['tiporef'] . "'";
            $resultm = mysqli_query($con, $sqlm);
        } else {
            $sqlm = "INSERT INTO `tipo` (`nombreTipo`, `refTipo`, `pvp`) VALUES ('" . $_POST['tipo'] . "', '" . $_POST['tiporef'] . "', '" . $_POST['pvp'] . "')";
            $resultm = mysqli_query($con, $sqlm);
        }
    }
    echo "<script type=\"text/javascript\">alert(\"Tipo insertada.\");window.location = \"opciones.php\"
               </script>";
}
if (isset($_POST['addmodelo'])) {
    $sql = "SELECT * FROM `modelo` WHERE `refModelo`='" . $_POST['modeloref'] . "' AND `refMarca`='" . $_POST['marcas'] . "' AND `refYear`='" . $_POST['year'] . "'";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="alert alert-warning" role="alert">Ya existe la referencia ' . $_POST['modeloref'] . ' del año ' . $_POST['year'] . ' de la marca ' . get_marca_name($_POST['marcas']) . ' </div>';
            echo "<script type=\"text/javascript\">alert(\"Modelo existente.\");window.location = \"opciones.php\"
               </script>";
        }
    } else {
        $sql = "SELECT * FROM `modelo` WHERE `nombreModelo` = '" . $_POST['modelo'] . "'";
        $result = mysqli_query($con, $sql);
        if (mysqli_num_rows($result) > 0) {

            echo "<script type=\"text/javascript\">alert(\"Modelo existente.\");window.location = \"opciones.php\"
               </script>";
        } else {
            $sqlm = "INSERT INTO `modelo` (`nombreModelo`, `refModelo`, `refMarca`, `refYear`) VALUES ('" . $_POST['modelo'] . "', '" . $_POST['modeloref'] . "', '" . $_POST['marcas'] . "', '" . $_POST['year'] . "')";
            $resultm = mysqli_query($con, $sqlm);
            echo "<script type=\"text/javascript\">alert(\"Modelo insertada.\");window.location = \"opciones.php\"
               </script>";
        }
    }
}
if (isset($_POST['relmodelos'])) {
    $sql = "SELECT * FROM `relacionados` WHERE `referencias` = '" . serialize($_POST['relmodelos']) . "'";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        $sqlm = "UPDATE `relacionados` SET `referencias`='" . serialize($_POST['relmodelos']) . "' WHERE `idRel` = '" . $row['idRel'] . "'";
        $resultm = mysqli_query($con, $sqlm);
    } else {
        $sqlm = "INSERT INTO `relacionados` (`referencias`) VALUES ('" . serialize($_POST['relmodelos']) . "')";
        $resultm = mysqli_query($con, $sqlm);
    }
    echo "<script type=\"text/javascript\">alert(\"Referencia insertada.\");window.location = \"opciones.php\"
               </script>";
}
if (isset($_POST['enviarStock'])) {
    $_SESSION['variables'] = array(
        "modelos" => $_POST['modelos'],
        "ref"=>get_refCompleta_by_idModelo($_POST['modelos']),
        "tipo" => $_POST['tipo'],
        "color" => $_POST['color'],
        "stock" => $_POST['stock'],
    );
    if (isset($_POST['idStock'])) {
        $idStock = $_POST['idStock'];
    } else {
        $idStock = get_stock($_POST['modelos'], $_POST['tipo'], $_POST['color']);
    }
    $idMarca = get_marca_id(substr(get_refCompleta_by_idModelo($_POST['modelos']), 0, 2));
    $idRel = 0;
    $fecha = date('Y-m-d');
    if ($_POST['usarrel'] == 1) {
        $idRel = get_rel_id($_POST['modelos']);
    }
    if ($idStock) {
        echo $sql = "UPDATE `stock` SET `refModel`= " . get_refCompleta_by_idModelo($_POST['modelos']) . ",`idModelo`= " . $_POST['modelos'] . ", `refTipo`= " . $_POST['tipo'] . ", `refColor`= " . $_POST['color'] . ", `idMarca`= " . $idMarca . ", `stock`= " . $_POST['stock'] . ", `usarRel` = " . $_POST['usarrel'] . ", `idRel` = " . $idRel . ", `modificado` = '" . $fecha . "' WHERE `idStock`=" . $idStock;
        $result = mysqli_query($con, $sql);
        echo "<script type=\"text/javascript\">alert(\"Funda para " . get_modelo_by_id($_POST['modelos']) . " modificada!!!.\");window.location = \"acciones.php\"</script>";
    } else {
        $sql = "INSERT INTO `stock`(`refModel`, `idModelo`,  `refTipo`, `refColor`, `stock`, `usarRel`, `idMarca`, `idRel`, `modificado`) VALUES (" . get_refCompleta_by_idModelo($_POST['modelos']) . ", " . $_POST['modelos'] . ", " . $_POST['tipo'] . ", " . $_POST['color'] . ", " . $_POST['stock'] . ", " . $_POST['usarrel'] . ", " . $idMarca . ", " . $idRel . ", '" . $fecha . "')";
        $result = mysqli_query($con, $sql);
        echo "<script type=\"text/javascript\">alert(\"Referencia insertada.\");window.location = \"acciones.php\"</script>";
    }
}
if (isset($_POST['exportar'])) {
    download_send_headers("data_export_" . date("Y-m-d") . ".csv");
    $array = get_fundas_csv();
    echo array2csv($array);
    die();
}
if (isset($_POST['exportarByModel'])) {
    download_send_headers("data_export_" . date("Y-m-d") . ".csv");
    $array = get_fundas_csv($_POST['model']);
    echo array2csv($array);
    die();
}
if (isset($_POST['datepickerSubmit'])) {
    download_send_headers("data_export_" . date("Y-m-d") . ".csv");
    $fecha = date_create($_POST['fecha']);
    $fecha = date_format($fecha, "Y-m-d");
    $array = get_fundas_csv_fechas($fecha);
    echo array2csv($array);
    die();
}
if (isset($_POST['submitSumar'])) {
    $sql = "UPDATE `stock` SET `stock`= stock + 1, `modificado` = '" . date('Y-m-d') . "' WHERE `idStock` = " . $_POST['textoSumar'];
    $result1 = mysqli_query($con, $sql);
    $sql = "SELECT * FROM `stock` WHERE idStock =" . $_POST['textoSumar'];
    $result2 = mysqli_query($con, $sql);
    $modelo = mysqli_fetch_assoc($result2);
    $_SESSION['variables'] = array(
        "codigo" => $modelo['idStock'],
        "stock" => $modelo['stock'],
    );
    echo "<script type=\"text/javascript\">;window.location = \"sumar.php\"</script>";
}
if (isset($_POST['submitRestar'])) {
    $sql = "UPDATE `stock` SET `stock`= stock - 1, `modificado`='" . date('Y-m-d') . "' WHERE `idStock` = " . $_POST['textoRestar'] . "  AND `stock` > 0;";
    $result1 = mysqli_query($con, $sql);
    $sql = "SELECT * FROM `stock` WHERE idStock =" . $_POST['textoRestar'];
    $result2 = mysqli_query($con, $sql);
    $modelo = mysqli_fetch_assoc($result2);
    $_SESSION['variables'] = array(
        "codigo" => $modelo['idStock'],
        "stock" => $modelo['stock'],
    );
    echo "<script type=\"text/javascript\">;window.location = \"restar.php\"</script>";
}
if (isset($_POST['submitReset'])) {
    if (isset($_POST['resetAll'])) {
        $sql = "UPDATE `stock` SET `stock`= 0, `modificado`='" . date('Y-m-d') . "'";
        $result1 = mysqli_query($con, $sql);
        echo "<script type=\"text/javascript\">alert(\"Todo ha sido puesto a 0.\");window.location = \"restar.php\"</script>";
    } else if (isset($_POST['marcas']) && $_POST['marcas'] != null) {
        $id = get_marca_id($_POST['marcas']);
        $sql = "UPDATE `stock` SET `stock`= 0, `modificado`='" . date('Y-m-d') . "' WHERE `idMarca` = " . $id;
        $result1 = mysqli_query($con, $sql);
        echo "<script type=\"text/javascript\">alert(\"La marca " . get_marca_name($_POST['marcas']) . " ha sido puesta a 0.\");window.location = \"restar.php\"</script>";
    } else {
        echo "<script type=\"text/javascript\">alert(\"No se ha hecho nada. No se ha elegido nada.\");window.location = \"restar.php\"</script>";
    }
}
