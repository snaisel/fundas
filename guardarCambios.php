<?php

require 'class.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verifica que los datos POST estén presentes
if (isset($_POST['idMarca']) && isset($_POST['nombreMarca'])) {
    $idMarca = $_POST['idMarca'];
    $nombreMarca = $_POST['nombreMarca'];

    // Conexión a la base de datos
    $conn = getdb();

    // Verifica la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Actualizar la marca
    $sql = "UPDATE marca SET nombreMarca = ? WHERE idMarca = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("si", $nombreMarca, $idMarca);
    if ($stmt->execute()) {
        echo "Registro actualizado exitosamente";
    } else {
        echo "Error al actualizar: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
} else {
    echo "Datos no recibidos correctamente";
}
if (isset($_POST['idModelo']) && isset($_POST['nombreModelo'])) {
    $idModelo = $_POST['idModelo'];
    $nombreModelo = $_POST['nombreModelo'];
// Conexión a la base de datos
    $conn = getdb();
// Verifica la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Actualizar la marca
    $sql = "UPDATE modelo SET nombreModelo = ? WHERE idModelo = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param("si", $nombreModelo, $idModelo);

    if ($stmt->execute()) {
        echo "Registro actualizado exitosamente";
    } else {
        echo "Error al actualizar: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Datos no recibidos correctamente";
}

if (isset($_POST['idTipo']) && isset($_POST['nombreTipo']) && isset($_POST['pvp'])) {
    $conn = getdb();
    $idTipo = $_POST['idTipo'];
    $nombreTipo = $_POST['nombreTipo'];
    $pvp = $_POST['pvp'];

    $sql = "UPDATE tipo SET nombreTipo = ?, pvp = ? WHERE idTipo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nombreTipo, $pvp, $idTipo);

    if ($stmt->execute()) {
        echo "Tipo actualizado exitosamente";
    } else {
        echo "Error al actualizar el tipo: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Datos no recibidos correctamente";
}
if (isset($_POST['idColor']) && isset($_POST['nombreColor'])) {
    $conn = getdb();
    $idColor = $_POST['idColor'];
    $nombreColor = $_POST['nombreColor'];
    $thumb = $_POST['thumb'];

    $sql = "UPDATE color SET nombreColor = ?, thumb = ? WHERE idColor = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nombreColor, $thumb, $idColor);

    if ($stmt->execute()) {
        echo "Color actualizado exitosamente";
    } else {
        echo "Error al actualizar el color: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Datos no recibidos correctamente";
}
