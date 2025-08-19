<?php
require 'class.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['idTipoEliminar'])) {
    $con = getdb();
    $idTipo = $_POST['idTipoEliminar'];
    
    $sql = "DELETE FROM tipo WHERE idTipo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idTipo);

    if ($stmt->execute()) {
        echo "Tipo eliminado exitosamente";
    } else {
        echo "Error al eliminar el tipo: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "ID no recibido correctamente";
}
?>