<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'class.php';

$con = getdb();
$hoy = date('Y-m-d', time());
$table = "";
$row = 1;
if (isset($_POST['addmarca'])) {
    $marca = new Marca(null, $_POST['marca'], $_POST['marcaref']);
    $response = $marca->set_marca();

    echo "<script type=\"text/javascript\">alert(\"$response\");window.location = \"opciones.php\"</script>";
}
if (isset($_POST['addyear'])) {
    $year = new Year(null, $_POST['yearname'], $_POST['yearref']);
    $response = $year->set_year();

    echo "<script type=\"text/javascript\">alert(\"$response\");window.location = \"opciones.php\"</script>";
}
if (isset($_POST['addcolor'])) {
    $color = new Color(null, $_POST['color'], $_POST['colorref'], $_POST['thumb']);
    $response = $color->set_color();

    echo "<script type=\"text/javascript\">alert(\"$response\");window.location = \"opciones.php\"</script>";
}
if (isset($_POST['addtipo'])) {
    $tipo = new Tipo(null, $_POST['tipo'], $_POST['pvp'], $_POST['tiporef']);
    $response = $tipo->set_tipo();

    echo "<script type=\"text/javascript\">alert(\"$response\");window.location = \"opciones.php\"</script>";
}
if (isset($_POST['addmodelo'])) {
    $modelo = new Modelo(null, $_POST['modelo'], $_POST['marcas'], $_POST['year'], $_POST['modeloref']);
    $message = $modelo->set_modelo();
    echo "<script type=\"text/javascript\">alert(\"$message\");window.location = \"opciones.php\"
               </script>";
}
if (isset($_POST['relmodelos'])) {
    // Sanitize and validate each value to allow only integers
    $relmodelos = array_filter($_POST['relmodelos'], function ($value) {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    });
    $relmodelos = array_map('intval', $relmodelos);
    $relacionados = new Relacionados(null, serialize($relmodelos));
    $response = $relacionados->set_relacion();

    echo "<script type=\"text/javascript\">alert(\"$response\");window.location = \"opciones.php\"</script>";
}
if (isset($_POST['enviarStock'])) {
    $_SESSION['variables'] = array(
        "modelo" => $_POST['selectModelos'],
        "marca" => $_POST['selectMarcas'],
        "year" => $_POST['selectYear'],
        "tipo" => $_POST['tipo'],
        "color" => $_POST['color'],
        "stock" => $_POST['stock'],
        "usarrel" => $_POST['usarrel'],
    );
    $fecha = date('Y-m-d');
    if (isset($_POST['idStock'])) {
        $idStock = $_POST['idStock'];
    } else {
        $idStock = Stock::get_stock($_POST['selectModelos'], $_POST['tipo'], $_POST['color']);
    }
    $idMarca = $_POST['selectMarcas'];
    $idRel = 0;
    if ($_POST['usarrel'] == 1) {
        $idRel = Relacionados::get_rel_id($_POST['selectModelos']);
    }
    if ($idStock) {
        $stock = new Stock($idStock, $_POST['selectModelos'], $_POST['tipo'], $_POST['color'], $_POST['stock'], $_POST['usarrel'], $idRel, $fecha);
        $result = $stock->update_stock();
        echo "<script type=\"text/javascript\">alert(\" " . $result . "!!!.\");window.location = \"acciones.php\"</script>";
    } else {
        $stock = new Stock(null, $_POST['selectModelos'], $_POST['tipo'], $_POST['color'], $_POST['stock'], $_POST['usarrel'], $idRel, $fecha);
        $result = $stock->set_stock();
        echo "<script type=\"text/javascript\">alert(\" " . $result . "!!!.\");window.location = \"acciones.php\"</script>";
    }
}
if (isset($_POST['exportar'])) {
    if (isset($_POST['stock0'])) {
        $array = get_fundas_csv(null, true);
    } else {
        $array = get_fundas_csv();
    }
    if ($array) {
        download_send_headers("data_export_" . date("Y-m-d") . ".csv");
        echo array2csv($array);
        exit;
    } else {
        http_response_code(400);
        echo "No se encontraron resultados.";
        exit;
    }
}
if (isset($_POST['exportarByModel'])) {
    if (isset($_POST['stock0modelo'])) {
        $array = get_fundas_csv($_POST['model'], true);
    } else {
        $array = get_fundas_csv($_POST['model']);
    }
    if ($array) {
        download_send_headers("data_export_" . date("Y-m-d") . ".csv");
        echo array2csv($array);
        die();
    } else {
        echo "<script type=\"text/javascript\">alert(\"No se encontraron resultados.\");window.location = \"acciones.php\"</script>";
    }
}
if (isset($_POST['datepickerSubmit'])) {
    $fecha = date_create($_POST['fecha']);
    $fecha = date_format($fecha, "Y-m-d");
    if (isset($_POST['stock0fecha'])) {
        $array = get_fundas_csv_fechas($fecha, true);
    } else {
        $array = get_fundas_csv_fechas($fecha);
    }
    if ($array) {
        download_send_headers("data_export_" . date("Y-m-d") . ".csv");
        echo array2csv($array);
        die();
    } else {
        echo "<script type=\"text/javascript\">alert(\"No se encontraron resultados.\");window.location = \"acciones.php\"</script>";
    }
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
        $id = Marca::get_marca_id($_POST['marcas']);
        $sql = "UPDATE `stock` SET `stock`= 0, `modificado`='" . date('Y-m-d') . "' WHERE `idMarca` = " . $id;
        $result1 = mysqli_query($con, $sql);
        echo "<script type=\"text/javascript\">alert(\"La marca " . Marca::get_marca_name($_POST['marcas']) . " ha sido puesta a 0.\");window.location = \"restar.php\"</script>";
    } else {
        echo "<script type=\"text/javascript\">alert(\"No se ha hecho nada. No se ha elegido nada.\");window.location = \"restar.php\"</script>";
    }
}
