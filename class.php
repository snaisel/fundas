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
class Marca {
    public $idMarca = null;
    public $nombreMarca;
    public $refMarca;

    public function __construct($idMarca, $nombreMarca, $refMarca) {
        $this->idMarca = $idMarca;
        $this->nombreMarca = $nombreMarca;
        $this->refMarca = $refMarca;
    }
    public function set_marca() {
        if ($this->check_nombreMarca()) {
            $message = "El nombre de la marca ya existe.";
            return $message;
        }
        if ($this->check_refMarca()) {
            $message = "La referencia de la marca ya existe.";
            return $message;
        }
        $conn = getdb();
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $sql = "INSERT INTO marca (nombreMarca, refMarca) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt->bind_param("si", $this->nombreMarca, $this->refMarca);
        if ($stmt->execute()) {
            $message = "Registro actualizado exitosamente";
        } else {
            $message = "Error al actualizar: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
        return $message;
    }
    public function update_marca() {
        if ($this->check_nombreMarca()) {
            $message = "El nombre de la marca ya existe.";
            return $message;
        }
        if ($this->check_refMarca()) {
            $message = "La referencia de la marca ya existe.";
            return $message;
        }
        $conn = getdb();
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $sql = "UPDATE marca SET nombreMarca = ?, refMarca = ? WHERE idMarca = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt->bind_param("sii", $this->nombreMarca, $this->refMarca, $this->idMarca);
        if ($stmt->execute()) {
            //echo "Registro actualizado exitosamente";
            $message = "Registro $this->nombreMarca actualizado exitosamente";
        } else {
            //echo "Error al actualizar: " . $stmt->error;
            $message = "Error al actualizar: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
        return $message;
    }
    private function check_refMarca() {
        $conn = getdb();
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        if ($this->idMarca) {
            $sql = "SELECT * FROM marca WHERE refMarca = ? AND idMarca != ?";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }
            $stmt->bind_param("ii", $this->refMarca, $this->idMarca);
        } else {
            $sql = "SELECT * FROM marca WHERE refMarca = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }
            $stmt->bind_param("i", $this->refMarca);
        }
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        $conn->close();
        return $exists;
    }
    private function check_nombreMarca() {
        $conn = getdb();
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        if ($this->idMarca) {
            $sql = "SELECT * FROM marca WHERE nombreMarca = ? AND idMarca != ?";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }
            $stmt->bind_param("si", $this->nombreMarca, $this->idMarca);
        } else {
            $sql = "SELECT * FROM marca WHERE nombreMarca = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }
            $stmt->bind_param("s", $this->nombreMarca);
        }
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        $conn->close();
        return $exists;
    }
    public static function select_marcas($marca = false) {
        $con = getdb();
        $retuntext = "";
        $Sql = "SELECT * FROM marca";
        $result = mysqli_query($con, $Sql);
        if (!$result) {
            $retuntext .= "No hay marcas registradas";
        } else {
            $retuntext .= "<select class='form-select' name='selectMarcas' id='selectMarcas' required><option value=''>--Elegir--</option>";
            while ($row = mysqli_fetch_assoc($result)) {
                if ($marca && $marca == $row['idMarca']) {
                    $retuntext .= "<option value=" . $row['idMarca'] . " selected>" . $row['nombreMarca'] . "</option>";
                } else {
                    $retuntext .= "<option value=" . $row['idMarca'] . ">" . $row['nombreMarca'] . "</option>";
                }
            }

            $retuntext .= "</select>";
        }
        return $retuntext;
    }

    public static function get_marca_name_by_id($id) {
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
    public static function get_marca_id($ref) {
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
    public static function get_marca_name($ref) {
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
    public static function get_marcas_by_year($id) {
        $con = getdb();
        $Sql = "SELECT * FROM modelo WHERE `idYear` = " . $id . " GROUP BY idMarca";
        $result = mysqli_query($con, $Sql);
        if (!$result) {
            return false;
        } else {
            $marcas = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $marcas[] = $row['idMarca'];
            }
            return array_unique($marcas);
        }
    }
    public static function get_nombreMarca_by_idModelo($idModelo) {
        $con = getdb();
        $Sql = "SELECT * FROM modelo m JOIN marca ma ON m.idMarca = ma.idMarca WHERE `idModelo` = " . $idModelo;
        $result = mysqli_query($con, $Sql);
        if (!$result) {
            return false;
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                return $row['nombreMarca'];
            }
        }
    }
    public static function get_idMarca_by_modelo($id) {
        $con = getdb();
        $Sql = "SELECT idMarca FROM  modelo WHERE `idModelo`=" . $id . " LIMIT 1";
        $result = mysqli_query($con, $Sql);
        if (!$result) {
            return false;
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                return $marca = $row['idMarca'];
            }
        }
    }

}

class Year {
    public $idYear;
    public $year;
    public $refYear;

    public function __construct($idYear, $year, $refYear) {
        $this->idYear = $idYear;
        $this->year = $year;
        $this->refYear = $refYear;
    }
    public function set_year() {
        if ($this->check_nombreYear()) {
            $message = "El nombre del year ya existe.";
            return $message;
        }
        if ($this->check_refYear()) {
            $message = "La referencia del year ya existe.";
            return $message;
        }
        $conn = getdb();
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $sql = "INSERT INTO year (year, refYear) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt->bind_param("ss", $this->year, $this->refYear);
        if ($stmt->execute()) {
            $message = "Registro actualizado exitosamente";
        } else {
            $message = "Error al actualizar: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
        return $message;
    }
    public function update_year() {
        if ($this->check_nombreYear()) {
            $message = "El nombre del year ya existe.";
            return $message;
        }
        if ($this->check_refYear()) {
            $message = "La referencia del year ya existe.";
            return $message;
        }
        $conn = getdb();
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $sql = "UPDATE year SET year = ?, refYear = ? WHERE idYear = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt->bind_param("iii", $this->year, $this->refYear, $this->idYear);
        if ($stmt->execute()) {
            //echo "Registro actualizado exitosamente";
            $message = "Registro $this->year actualizado exitosamente";
        } else {
            //echo "Error al actualizar: " . $stmt->error;
            $message = "Error al actualizar: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
        return $message;
    }
    private function check_refYear() {
        $conn = getdb();
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        if ($this->idYear) {
            $sql = "SELECT * FROM year WHERE refYear = ? AND idYear != ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }
            $stmt->bind_param("ii", $this->refYear, $this->idYear);
        } else {
            $stmt = $conn->prepare("SELECT * FROM year WHERE refYear = ?");
            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }
            $stmt->bind_param("i", $this->refYear);
        }
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        $conn->close();
        return $exists;
    }
    private function check_nombreYear() {
        $conn = getdb();
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        if ($this->idYear) {
            $sql = "SELECT * FROM year WHERE year = ? AND idYear != ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }
            $stmt->bind_param("ii", $this->year, $this->idYear);
        } else {
            $stmt = $conn->prepare("SELECT * FROM year WHERE year = ?");
            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }
            $stmt->bind_param("i", $this->year);
        }
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        $conn->close();
        return $exists;
    }
    public static function select_year($idYear = 0) {
        $con = getdb();
        $retuntext = "";
        $Sql = "SELECT * FROM year ORDER BY `year`.`refYear` DESC ";
        $result = mysqli_query($con, $Sql);
        if (!$result) {
            $retuntext .= "No hay años registradas";
        } else {
            $retuntext .= "<select class='form-select' name='selectYear' id='selectYear' required><option value=''>--Elegir--</option>";
            while ($row = mysqli_fetch_assoc($result)) {
                $retuntext .= "<option value=" . $row['idYear'];
                if ($idYear == $row['idYear']) {
                    $retuntext .= " selected ";
                }
                $retuntext .= ">" . $row['year'] . "</option>";
            }
            $retuntext .= "</select>";
        }
        mysqli_close($con);
        return $retuntext;
    }
    public static function get_year($ref) {
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
    public static function get_year_by_id($id) {
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
    public static function get_idYear_by_modelo($id) {
        $con = getdb();
        $sql = "SELECT idYear FROM modelo WHERE idModelo=" . $id . " LIMIT 1";
        $result = mysqli_query($con, $sql);
        if (!$result) {
            return false;
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                return $row['idYear'];
            }
        }
    }
}
class Modelo {
    public $idModelo;
    public $nombreModelo;
    public $idMarca;
    public $idYear;
    public $refModelo;

    public function __construct($idModelo, $nombreModelo, $idMarca, $idYear, $refModelo) {
        $this->idModelo = $idModelo;
        $this->nombreModelo = $nombreModelo;
        $this->idMarca = $idMarca;
        $this->idYear = $idYear;
        $this->refModelo = $refModelo;
    }
    public function set_modelo() {
        if ($this->check_nombreModelo()) {
            $message = "El nombre del modelo ya existe.";
            return $message;
        }
        if ($this->check_refModelo()) {
            $message = "La referencia del modelo ya existe.";
            return $message;
        }
        $conn = getdb();
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $sql = "INSERT INTO modelo (nombreModelo, idMarca, idYear, refModelo) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt->bind_param("siii", $this->nombreModelo, $this->idMarca, $this->idYear, $this->refModelo);
        if ($stmt->execute()) {
            $message = "Registro actualizado exitosamente";
        } else {
            $message = "Error al actualizar: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
        return $message;
    }
    public function update_modelo() {
        if ($this->check_nombreModelo()) {
            $message = "El nombre del modelo ya existe.";
            return $message;
        }
        if ($this->check_refModelo()) {
            $message = "La referencia del modelo ya existe.";
            return $message;
        }
        $conn = getdb();
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $sql = "UPDATE modelo SET nombreModelo = ?, idMarca = ?, idYear = ?, refModelo = ? WHERE idModelo = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt->bind_param("siiii", $this->nombreModelo, $this->idMarca, $this->idYear, $this->refModelo, $this->idModelo);
        if ($stmt->execute()) {
            //echo "Registro actualizado exitosamente";
            $message = "Registro $this->nombreModelo actualizado exitosamente";
        } else {
            //echo "Error al actualizar: " . $stmt->error;
            $message = "Error al actualizar: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
        return $message;
    }
    private function check_refModelo() {
        $conn = getdb();
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        if ($this->idModelo) {
            $sql = "SELECT * FROM modelo WHERE refModelo = ? AND idModelo != ? AND idMarca = ? AND idYear = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }
            $stmt->bind_param("iiii", $this->refModelo, $this->idModelo, $this->idMarca, $this->idYear);
        } else {
            $stmt = $conn->prepare("SELECT * FROM modelo WHERE refModelo = ? AND idMarca = ? AND idYear = ?");
            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }
            $stmt->bind_param("iii", $this->refModelo, $this->idMarca, $this->idYear);
        }
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        $conn->close();
        return $exists;
    }
    private function check_nombreModelo() {
        $conn = getdb();
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        if ($this->idModelo) {
            $sql = "SELECT * FROM modelo WHERE nombreModelo = ? AND idModelo != ?";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }
            $stmt->bind_param("si", $this->nombreModelo, $this->idModelo);
        } else {
            $sql = "SELECT * FROM modelo WHERE nombreModelo = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }
            $stmt->bind_param("s", $this->nombreModelo);
        }
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        $conn->close();
        return $exists;
    }
    public static function select_modelos($idModelo = NULL, $idMarca = NULL) {
        $con = getdb();
        $retuntext = "";
        if ($idMarca) {
            $Sql = "SELECT * FROM modelo WHERE idMarca = " . $idMarca . " ORDER BY idModelo ASC";
        } else {
            $Sql = "SELECT * FROM modelo ORDER BY idModelo ASC";
        }
        $result = mysqli_query($con, $Sql);
        if (!$result) {
            $retuntext .= "No hay modelos registradas";
        } else {
            $retuntext .= "<select class='form-select' name='selectModelos' id='selectModelos' required><option value=''>--Elegir--</option>";
            while ($row = mysqli_fetch_assoc($result)) {
                $retuntext .= "<option value='" . $row['idModelo'] . "'";
                if ($idModelo && $idModelo == $row['idModelo']) {
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
    public static function select_modelos_by_id($modelo = false) {
        $con = getdb();
        $retuntext = "";
        if ($modelo) {
            $Sql = "SELECT * FROM modelo WHERE idModelo = " . $modelo . " ORDER BY idModelo ASC";
        } else {
            $Sql = "SELECT * FROM modelo ORDER BY idModelo ASC";
        }
        $result = mysqli_query($con, $Sql);
        if (!$result) {
            $retuntext .= "No hay modelos registradas";
        } else {
            $retuntext .= "<select class='form-select' name='selectModelos' id='selectModelos' required><option value=''>--Elegir--</option>";
            while ($row = mysqli_fetch_assoc($result)) {
                $retuntext .= "<option value='" . $row['idModelo'] . "'";
                if ($modelo && $modelo == $row['idModelo']) {
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
    public static function select_modelos_multiple() {
        $con = getdb();
        $retuntext = "";
        $Sql = "SELECT * FROM modelo m inner join marca ma on m.idMarca = ma.idMarca inner join year y on m.idYear = y.idYear ORDER BY `refMarca` ASC, `refYear` DESC, refModelo ASC";
        $result = mysqli_query($con, $Sql);
        if (!$result) {
            $retuntext .= "No hay modelos registradas";
        } else {
            $retuntext .= "<select id='seleccionarRels' class='form-select' name='relmodelos[]' multiple>";
            while ($row = mysqli_fetch_assoc($result)) {
                $retuntext .= "<option class='modeloRel' id=" . $row['idModelo'] . " value=" . $row['idModelo'] . ">" . $row['nombreModelo'] . "</option>";
            }

            $retuntext .= "</select>";
        }
        return $retuntext;
    }
    public static function select_model_stock($modelo = "") {
        $con = getdb();
        $Sql = "SELECT * FROM `modelo` ORDER BY `idMarca`, `idYear`";
        $result = mysqli_query($con, $Sql);
        if (!$result) {
            return false;
        } else {
            if (mysqli_num_rows($result) > 0) {
                $idmarca = 0;
                $i = 0;
                $html = "<select id='filterModel' name='filtroModel' class='form-control choices-single'><option value=''>Todos</option>";
                while ($row = mysqli_fetch_assoc($result)) {
                    $i++;

                    if ($idmarca != $row['idMarca']) {
                        if ($idmarca != 0) {
                            $html .= "</optgroup>";
                        }
                        $idmarca = $row['idMarca'];
                        $html .= "<optgroup label='" . Marca::get_marca_name_by_id($row['idMarca']) . "'>";
                    }
                    $html .= "<option value='" . $row['idModelo'] . "' ";
                    if ($modelo == $row['idModelo']) {
                        $html .= "selected ";
                    }
                    $html .= "' >" . Modelo::get_modelo($row['idModelo']) . "</option>";
                    if ($i == mysqli_num_rows($result)) {
                        $html .= "</optgroup>";
                    }
                }
                $html .= "</select>";
            }
            return $html;
        }
    }
    public static function get_modelo($id) {
        $con = getdb();
        $Sql = "SELECT * FROM modelo WHERE idModelo = " . $id;
        $result = mysqli_query($con, $Sql);
        if (!$result) {
            return false;
        } else {
            $row = mysqli_fetch_assoc($result);
            return $row['nombreModelo'];
        }
    }
    public static function get_modelos($idMarca = null, $idYear = null) {
        $con = getdb();
        $marca = "";
        $sql = "SELECT * FROM `modelo`";
        if ($idMarca && !$idYear) {
            $sql .= " WHERE `idMarca` = '$idMarca'";
        } else if ($idYear && !$idMarca) {
            $sql .= " WHERE `idYear` = '$idYear'";
        } else if ($idYear && $idMarca) {
            $sql .= " WHERE `idMarca` = '$idMarca' AND `idYear` = '$idYear'";
        }
        $sql .= " ORDER BY `idMarca` ASC, `idYear` DESC, `nombreModelo`";
        $result = mysqli_query($con, $sql);
        if ($idMarca) {
            echo "<button class='btn btn-outline-secondary modeloFilter' id='marcaFilter' value='" . $idMarca . "'>" . Marca::get_marca_name_by_id($idMarca) . "</button>";
        }
        if ($idYear) {
            echo "<button class='btn btn-outline-secondary modeloFilter' id='yearFilter' value='" . $idYear . "'>" . Year::get_year_by_id($idYear) . "</button>";
        }
        if (mysqli_num_rows($result) == 0) {

            echo "<div class='alert alert-warning'>No hay modelos disponibles</div>";
        } else {

            echo "<ul class='list-group' style='height:250px;overflow-y: scroll;margin:10px auto;'>";
            if (!$idMarca) {
                echo "<li><ul>";
            }
            while ($row = mysqli_fetch_assoc($result)) {
                if (!$idMarca) {
                    if ($marca != $row['idMarca']) {
                        $marca = $row['idMarca'];
                        echo "</ul></li><li class='list-group-item'><h5>" . Marca::get_marca_name_by_id($marca) . "</h5><ul class='list-group'>";
                    }
                }
                echo "<li class='list-group-item'><a href='modelos.php?get_tabla=modelos&idModelo=" . $row['idModelo'] . "' class='btn btn-primary resumenModelo' id='" . $row['idModelo'] . "'>" . Modelo::get_refCompleta_by_idModelo($row['idModelo']) . " - " . $row['nombreModelo'] . "</a>";
                echo "<div><button type=button name='editarModelo' value=" . $row['idModelo'] . " class='botonEditarModelos btn btn-sm btn-success'>Editar</button>";
                echo "<button type=button name='eliminarModelo' value=" . $row['idModelo'] . " class='botonEliminarModelos btn btn-sm btn-danger'>Eliminar</button>";
                echo "<div></li>";
            }
            echo "</ul>";
        }
    }
    public static function get_modelo_filtrado($modelo) {
        $search = array("Galaxy ", "GALAXY ", "Redmi Note ", "REDMI NOTE ", "Redmi ", "REDMI ", "Xiaomi ", "XIAOMI ", "Mi ", "MI ", "Xperia ");
        foreach ($search as $filtro) {
            if (strpos($modelo, $filtro) !== false) {
                return str_replace($filtro, "", $modelo);
            } else {
                return $modelo;
            }
        }
    }
    public static function get_refCompleta_by_idModelo($idModelo) {
        $con = getdb();
        $Sql = "SELECT marca.refMarca, year.refYear, modelo.refModelo
        FROM modelo 
        INNER JOIN marca ON modelo.idMarca = marca.idMarca 
        INNER JOIN year ON modelo.idYear = year.idYear 
        WHERE modelo.idModelo = " . intval($idModelo);
        $result = mysqli_query($con, $Sql);
        if (!$result) {
            return false;
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                $refCompleta = $row['refMarca'] . $row['refYear'] . $row['refModelo'];
                return $refCompleta;
            }
        }
    }
    public static function get_refs_by_idModelo($idModelo) {
        $con = getdb();
        $Sql = "SELECT * FROM modelo join marca on modelo.idMarca = marca.idMarca join year on modelo.idYear = year.idYear WHERE `idModelo` = " . intval($idModelo);
        $result = mysqli_query($con, $Sql);
        if (!$result) {
            return false;
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                $refCompleta['refMarca'] = $row['refMarca'];
                $refCompleta['refYear'] = $row['refYear'];
                $refCompleta['refModelo'] = $row['refModelo'];
                return $refCompleta;
            }
        }
    }
    public static function get_tabla_modelos($parametro = "idModelo", $orderby = "ASC", $page = 1, $limit = 20, $marca = NULL, $year = NULL, $modelo = NULL, $idModelo = NULL) {
        $con = getdb();
        if (is_null($idModelo)) {
            $query = $con->query("SELECT COUNT(*) as rowNum FROM modelo");
            $result = $query->fetch_assoc();
            $rowCount = $result['rowNum'];
            $pages = $rowCount / $limit;
            if ($rowCount % $limit > 0)
                $pages++;
            $actualPage = $limit * ($page - 1);
            switch ($parametro) {
                case "stock":
                    $Sql = "SELECT m.*, SUM(s.stock) AS totalStock FROM modelo m LEFT JOIN stock s ON s.idModelo = m.idModelo GROUP BY s.idModelo ORDER BY totalStock " . $orderby . " LIMIT " . $actualPage . ", " . $limit;
                    break;
                case "refCompleta":
                    $Sql = "SELECT * FROM modelo m JOIN marca ma ON m.idMarca = ma.idMarca JOIN year y ON m.idYear = y.idYear ORDER BY refMarca " . $orderby . ", refYear " . $orderby . ", refModelo " . $orderby . ", refModelo " . $orderby . " LIMIT " . $actualPage . ", " . $limit;
                    break;
                case "idMarca":
                    $Sql = "SELECT * FROM modelo m JOIN marca ma ON m.idMarca = ma.idMarca JOIN year y ON m.idYear = y.idYear ORDER BY nombreMarca " . $orderby . ", refYear , refModelo " . $orderby . ", refModelo LIMIT " . $actualPage . ", " . $limit;
                    break;
                case "idYear":
                    $Sql = "SELECT * FROM modelo m JOIN marca ma ON m.idMarca = ma.idMarca JOIN year y ON m.idYear = y.idYear ORDER BY year " . $orderby . ", refMarca, refModelo " . $orderby . " LIMIT " . $actualPage . ", " . $limit;
                    break;
                default:
                    $Sql = "SELECT * FROM modelo m ORDER BY " . $parametro . " " . $orderby . " LIMIT " . $actualPage . ", " . $limit;
            }
            $result = mysqli_query($con, $Sql);
            $to = mysqli_num_rows($result) + $actualPage;
            if (!$result) {
                return false;
            } else {
                $html = '<br>Mostrando del ' . $actualPage . ' al ' . $to . ' de ' . $rowCount;
                $html .= '<table class="table table-striped" style="width:100%;">';
                $html .= '<thead id="headModelos"><tr id="orderby" class="' . $orderby . '"><th><a href=# class="order modelos active" id="idModelo">Id</a></th><th><a href=# class="order modelos" id="idMarca">Marca</a></th><th><a href=# class="order modelos" id="idYear">Año</a></th><th><a href=# class="order modelos" id="nombreModelo">Modelo</a></th><th><a href=# class="order modelos" id="refCompleta">Ref</a></th><th><a href=# class="order modelos" id="stock">Stock</a></th><th>Relacionados</th><th>Acciones</th></tr></thead>';
                $html .= '<tbody id="cuerpoModelos">';
                while ($row = mysqli_fetch_assoc($result)) {
                    $model = Modelo::get_refCompleta_by_idModelo($row['idModelo']);
                    $html .= "<tr id=filaModelos" . $row['idModelo'] . "><td>" . $row['idModelo'] . "</td><td>" . Marca::get_marca_name_by_id($row['idMarca']) . "</td><td>" . Year::get_year_by_id($row['idYear']) . "</td><td>" . Modelo::get_modelo($row['idModelo']) . "</td><td>" . $model . "</td>";
                    $html .= "<td>";
                    if ($parametro == "stock") {
                        $html .= $row['totalStock'];
                    } else {
                        $html .= Stock::get_stock_by_idModelo($row['idModelo']);
                    }
                    $html .= "</td><td>" . Relacionados::get_relaciones_title($row['idModelo']) . "</td>";
                    $html .= "<td><button class='btn btn-info botonresumen' value='" . $row['idModelo'] . "' name='resumen'>Resumen <i class='bi bi-pencil'></i></button> <button class='botoneliminar btn btn-danger' value='" . $row['idModelo'] . "' name='eliminar'>Eliminar Fundas<i class='bi bi-trash'></i></button></td></tr>";
                }
                $html .= '</tbody></table>';
                $html .= pagination($page, $pages, $actualPage, $rowCount, $to);
                $html .= "";
            }
        } else {
            $Sql = "SELECT * FROM modelo  WHERE `idModelo`=" . $idModelo . " ORDER BY " . $parametro . " " . $orderby;
            $result = mysqli_query($con, $Sql);
            $html = '<table class="table table-striped" style="width:100%;">';
            $html .= '<thead id="headModelos"><tr id="orderby" class="' . $orderby . '"><th><span class="order modelos active" id="idModelo">Id</span></th><th><span class="order modelos" id="idMarca">Marca</span></th><th><span class="order modelos" id="idYear">Año</span></th><th><span class="order modelos" id="nombreModelo">Modelo</span></th><th><span class="order modelos" id="refCompleta">Ref</span></th><th><span class="order modelos" id="stock">Stock</span></th><th>Relacionados</th><th>Acciones</th></tr></thead>';
            $html .= '<tbody id="cuerpoModelos">';
            while ($row = mysqli_fetch_assoc($result)) {
                $model = Modelo::get_refCompleta_by_idModelo($row['idModelo']);
                $html .= "<tr id=filaModelos" . $row['idModelo'] . "><td>" . $row['idModelo'] . "</td><td>" . Marca::get_marca_name_by_id($row['idMarca']) . "</td><td>" . Year::get_year_by_id($row['idYear']) . "</td><td>" . Modelo::get_modelo($row['idModelo']) . "</td><td>" . $model . "</td>";
                $html .= "<td>";
                $html .= Stock::get_stock_by_idModelo($row['idModelo']);
                $html .= "</td><td>" . Relacionados::get_relaciones_title($row['idModelo']) . "</td>";
                $html .= "<td><button class='btn btn-info botonresumen' value='" . $row['idModelo'] . "' name='resumen'>Resumen <i class='bi bi-pencil'></i></button> <button class='botoneliminar btn btn-danger' value='" . $row['idModelo'] . "' name='eliminar'>Eliminar Fundas<i class='bi bi-trash'></i></button></td></tr>";
            }
            $html .= '</tbody></table>';
        }
        return $html;
    }
}

class Tipo {
    public $idTipo;
    public $nombreTipo;
    public $pvp;
    public $refTipo;
    public function __construct($idTipo, $nombreTipo, $pvp, $refTipo) {
        $this->idTipo = $idTipo;
        $this->nombreTipo = $nombreTipo;
        $this->pvp = $pvp;
        $this->refTipo = $refTipo;
    }
    public function set_tipo() {
        if ($this->check_nombreTipo()) {
            $message = "El nombre del tipo ya existe.";
            return $message;
        }
        if ($this->check_refTipo()) {
            $message = "La referencia del tipo ya existe.";
            return $message;
        }
        $conn = getdb();
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        $sql = "INSERT INTO tipo (nombreTipo, refTipo, pvp) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt->bind_param("sid", $this->nombreTipo, $this->refTipo, $this->pvp);
        if ($stmt->execute()) {
            $message = "Registro actualizado exitosamente";
        } else {
            $message = "Error al actualizar: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
        return $message;
    }
    public function update_tipo() {
        if ($this->check_nombreTipo()) {
            $message = "El nombre del tipo ya existe.";
            return $message;
        }
        if ($this->check_refTipo()) {
            $message = "La referencia del tipo ya existe.";
            return $message;
        }
        $conn = getdb();
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $sql = "UPDATE tipo SET nombreTipo = ?, refTipo = ?, pvp = ? WHERE idTipo = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt->bind_param("sidi", $this->nombreTipo, $this->refTipo, $this->pvp, $this->idTipo);
        if ($stmt->execute()) {
            //echo "Registro actualizado exitosamente";
            $message = "Registro $this->nombreTipo actualizado exitosamente";
        } else {
            //echo "Error al actualizar: " . $stmt->error;
            $message = "Error al actualizar: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
        return $message;
    }
    private function check_refTipo() {
        $conn = getdb();
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        if ($this->idTipo) {
            $sql = "SELECT * FROM tipo WHERE refTipo = ? AND idTipo != ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }
            $stmt->bind_param("ii", $this->refTipo, $this->idTipo);
        } else {
            $stmt = $conn->prepare("SELECT * FROM tipo WHERE refTipo = ?");
            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }
            $stmt->bind_param("i", $this->refTipo);
        }
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        $conn->close();
        return $exists;
    }
    private function check_nombreTipo() {
        $conn = getdb();
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        if ($this->idTipo) {
            $sql = "SELECT * FROM tipo WHERE nombreTipo = ? AND idTipo != ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }
            $stmt->bind_param("si", $this->nombreTipo, $this->idTipo);
        } else {
            $stmt = $conn->prepare("SELECT * FROM tipo WHERE nombreTipo = ?");
            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }
            $stmt->bind_param("s", $this->nombreTipo);
        }
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        $conn->close();
        return $exists;
    }

    public static function select_tipo($tipo = false) {
        $con = getdb();
        $retuntext = "";
        $Sql = "SELECT * FROM tipo";
        $result = mysqli_query($con, $Sql);
        if (mysqli_num_rows($result) > 0) {
            $retuntext .= "<select class='form-select' name='tipo' id='tipo' required><option value=''>--Elegir--</option>";
            while ($row = mysqli_fetch_assoc($result)) {
                $retuntext .= "<option value=" . $row['idTipo'];
                if ($tipo && $tipo == $row['idTipo']) {
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
    public static function get_tipo($id) {
        $con = getdb();
        $Sql = "SELECT * FROM tipo WHERE `idTipo` = " . $id;
        $result = mysqli_query($con, $Sql);
        if (!$result) {
            return false;
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                return $row['nombreTipo'];
            }
        }
    }
    public static function get_precio($id) {
        $con = getdb();
        $retuntext = "";
        $Sql = "SELECT * FROM tipo WHERE `idTipo` = " . $id;
        $result = mysqli_query($con, $Sql);
        if (!$result) {
            return false;
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                return $row['pvp'];
            }
        }
    }
}
class Color {
    public $idColor;
    public $nombreColor;
    public $refColor;
    public $thumb;
    public function __construct($idColor, $nombreColor, $refColor, $thumb) {
        $this->idColor = $idColor;
        $this->nombreColor = $nombreColor;
        $this->refColor = $refColor;
        $this->thumb = $thumb;
    }
    public function set_color() {
        if ($this->check_nombreColor()) {
            $message = "El nombre del color ya existe.";
            return $message;
        }
        if ($this->check_refColor()) {
            $message = "La referencia del color ya existe.";
            return $message;
        }
        $conn = getdb();
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $sql = "INSERT INTO color (nombreColor, refColor, thumb) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt->bind_param("sis", $this->nombreColor, $this->refColor, $this->thumb);
        if ($stmt->execute()) {
            $message = "Registro actualizado exitosamente";
        } else {
            $message = "Error al actualizar: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
        return $message;
    }
    public function update_color() {
        if ($this->check_nombreColor()) {
            $message = "El nombre del color ya existe.";
            return $message;
        }
        if ($this->check_refColor()) {
            $message = "La referencia del color ya existe.";
            return $message;
        }
        $conn = getdb();
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $sql = "UPDATE color SET nombreColor = ?, refColor = ?, thumb = ? WHERE idColor = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt->bind_param("sisi", $this->nombreColor, $this->refColor, $this->thumb, $this->idColor);
        if ($stmt->execute()) {
            //echo "Registro actualizado exitosamente";
            $message = "Registro $this->nombreColor actualizado exitosamente";
        } else {
            //echo "Error al actualizar: " . $stmt->error;
            $message = "Error al actualizar: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
        return $message;
    }
    private function check_refColor() {
        $conn = getdb();
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        if ($this->idColor) {
            $sql = "SELECT * FROM color WHERE refColor = ? AND idColor != ?";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }
            $stmt->bind_param("ii", $this->refColor, $this->idColor);
        } else {
            $stmt = $conn->prepare("SELECT * FROM color WHERE refColor = ? ");
            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }
            $stmt->bind_param("i", $this->refColor);
        }
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        $conn->close();
        return $exists;
    }
    private function check_nombreColor() {
        $conn = getdb();
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        if ($this->idColor) {
            $sql = "SELECT * FROM color WHERE nombreColor = ? AND idColor != ?";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }
            $stmt->bind_param("si", $this->nombreColor, $this->idColor);
        } else {
            $stmt = $conn->prepare("SELECT * FROM color WHERE nombreColor = ? ");
            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }
            $stmt->bind_param("s", $this->nombreColor);
        }
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        $conn->close();
        return $exists;
    }
    public static function select_color($color = false) {
        $con = getdb();
        $retuntext = "";
        $Sql = "SELECT * FROM color";
        $result = mysqli_query($con, $Sql);
        if (mysqli_num_rows($result) > 0) {
            $retuntext .= "<select class='form-select' name='color' id='color' required><option value=''>--Elegir--</option>";
            while ($row = mysqli_fetch_assoc($result)) {
                $retuntext .= "<option value=" . $row['idColor'];
                if ($color && $color == $row['idColor']) {
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
    public static function get_color($id) {
        $con = getdb();
        $Sql = "SELECT * FROM color WHERE `idColor` = " . $id;
        $result = mysqli_query($con, $Sql);
        if (!$result) {
            return false;
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                return $row['nombreColor'];
            }
        }
    }
    public static function get_color_by_ref($ref) {
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
    public static function get_color_ref($id) {
        $con = getdb();
        $Sql = "SELECT * FROM color WHERE `idColor` = " . $id;
        $result = mysqli_query($con, $Sql);
        if (!$result) {
            return false;
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                return $row['refColor'];
            }
        }
    }

    public static function get_thumb($id) {
        $con = getdb();
        $Sql = "SELECT * FROM color WHERE `idColor` = " . $id;
        $result = mysqli_query($con, $Sql);
        if (!$result) {
            return false;
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                return $row['thumb'];
            }
        }
    }
}
class Stock {
    public $idStock;
    public $idModelo;
    public $idTipo;
    public $idColor;
    public $stock;
    public $usarRel;
    public $idRel;
    public $modificado;
    public function __construct($idStock, $idModelo, $idTipo, $idColor, $stock, $usarRel, $idRel, $modificado) {
        $this->idStock = $idStock;
        $this->idModelo = $idModelo;
        $this->idTipo = $idTipo;
        $this->idColor = $idColor;
        $this->stock = $stock;
        $this->usarRel = $usarRel;
        $this->idRel = $idRel;
        $this->modificado = $modificado;
    }
    public function set_stock() {
        $conn = getdb();
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $sql = "INSERT INTO stock (idModelo, idTipo, idColor, stock, usarRel, idRel, modificado) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt->bind_param("iiiiiis", $this->idModelo, $this->idTipo, $this->idColor, $this->stock, $this->usarRel, $this->idRel, $this->modificado);
        if ($stmt->execute()) {
            $message = "Registro actualizado exitosamente";
        } else {
            $message = "Error al actualizar: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
        return $message;
    }
    public function update_stock() {
        $conn = getdb();
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $sql = "UPDATE stock SET idModelo = ?, idTipo = ?, idColor = ?, stock = ?, usarRel = ?, idRel = ?, modificado = ? WHERE idStock = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt->bind_param("iiiiiisi", $this->idModelo, $this->idTipo, $this->idColor, $this->stock, $this->usarRel, $this->idRel, $this->modificado, $this->idStock);
        if ($stmt->execute()) {
            //echo "Registro actualizado exitosamente";
            $message = "Registro $this->idStock actualizado exitosamente";
        } else {
            //echo "Error al actualizar: " . $stmt->error;
            $message = "Error al actualizar: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
        return $message;
    }

    public static function get_stock($modelo, $tipo, $color) {
        $con = getdb();
        $Sql = "SELECT * FROM stock WHERE `idModelo`=" . $modelo . " AND `idTipo` = " . $tipo . " AND `idColor`=" . $color;
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
    public static function get_stock_items($modelo, $tipo, $color) {
        $con = getdb();
        $Sql = "SELECT * FROM stock WHERE `idModelo`=" . $modelo . " AND `idTipo` = " . $tipo . " AND `idColor`=" . $color;
        $result = mysqli_query($con, $Sql);
        if (!$result) {
            return false;
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                return $row['stock'];
            }
        }
    }

    public static function get_fundas($parametro = "idStock", $orderby = "DESC", $page = 1, $limit = 20, $modelo = "") {
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
            $query = $con->query("SELECT COUNT(*) as rowNum FROM stock WHERE `idModelo`=" . $modelo);
            $result = $query->fetch_assoc();
            $rowCount = $result['rowNum'];
            $pages = $rowCount / $limit;
            if ($rowCount % $limit > 0)
                $pages++;
            $actualPage = $limit * ($page - 1);
            $Sql = "SELECT * FROM stock  WHERE `idModelo`=" . $modelo . " ORDER BY " . $parametro . " " . $orderby . " LIMIT " . $actualPage . ", " . $limit;
        }
        $result = mysqli_query($con, $Sql);
        $to = mysqli_num_rows($result) + $actualPage;
        if (!$result) {
            return false;
        } else {
            $html = "";
            $html .= "<div class='row mt-2 mb-2'><div class='col'>Filtrar por modelo <i class='bi bi-funnel'></i> " . Modelo::select_model_stock($modelo) . "</div>";
            $html .= "<div class='col text-end'>Mostrando del " . $actualPage . " al " . $to . " de " . $rowCount . "</div></div>";
            if (Relacionados::get_relaciones_title($modelo) && $modelo != "") {
                $relacionados = Relacionados::get_relaciones_title($modelo);
                $html .= "<div class='alert alert-sm alert-info'>Modelos relacionados: " . $relacionados . "</div>";

            }

            $html .= "<table class='table table-striped' style='width:100%;'>
            <thead><tr><th><a href='#' class='ordenar active' id='idStock'>ID</a></th><th><a href='#' class='ordenar active' id='codigo'>Codigo</a></th><th><a href='#' class='ordenar'  id='idMarca'>Marca</a></th><th><a href='#' class='ordenar'  id='refModel'>Modelo</a></th><th><a href='#' class='ordenar'  id='refTipo'>Tipo</a></th><th><a href='#' class='ordenar'  id='refColor'>Color</a></th><th>Precio</th><th>Rel</th><th><a href='#' class='ordenar'  id='stock'>Stock</a></th><th><a href='#' class='ordenar'  id='modificado'>Modificado</a></th><th>Acciones</th></tr></thead><tbody id='cuerpostock' class='" . $orderby . "'>";
            if ($rowCount == 0) {
                $html .= " <div class='alert alert-warning'>No hay resultados para esta funda</div>";
            } else {
                while ($row = mysqli_fetch_assoc($result)) {
                    $html .= Stock::get_funda_row($row['idStock'], $row['idModelo'], $row['idTipo'], $row['idColor'], $row['usarRel'], $row['stock'], $row['modificado']);
                }
            }
            $html .= "</tbody></table>";
            $html .= pagination($page, $pages, $actualPage, $rowCount, $to);

        }
        return $html;
    }

    public static function get_funda_row($idStock, $idModelo, $idTipo, $idColor, $usarrel, $stock, $modificado) {
        $codigo = Modelo::get_refCompleta_by_idModelo($idModelo);
        $usarrelacion = "No";
        $relacionados = Modelo::get_modelo($idModelo);
        if ($usarrel == 1) {
            $usarrelacion = "Si";
            if (Relacionados::get_relaciones_title($idModelo)) {
                $relacionados = Relacionados::get_relaciones_title($idModelo);
            }
        }
        $html = "<tr id=fila" . $idStock . "><td>" . $idStock . "</td><td>" . $codigo . "</td><td>" . Marca::get_nombreMarca_by_idModelo($idModelo) . "</td><td>" . $relacionados . "</td><td>" . Tipo::get_tipo($idTipo) . "</td><td>" . Color::get_color($idColor) . " <span style='width:16px;height:16px;display: inline-block;background:" . Color::get_thumb($idColor) . ";'> </span></td><td>" . Tipo::get_precio($idTipo) . "€</td><td>" . $usarrelacion . "</td><td>" . $stock . "</td><td>" . $modificado . "</td><td><button class='btn btn-info botoneditar' value='" . $idStock . "' name='editar'>Editar <i class='bi bi-pencil'></i></button> <button class='botoneliminar btn btn-danger' value='" . $idStock . "' name='eliminar'>Eliminar <i class='bi bi-trash'></i></button></td></tr>";
        return $html;
    }
    public static function get_fundas_array_by_model($id) {
        $con = getdb();
        $Sql = "SELECT * FROM stock WHERE `idModelo`=" . $id;
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
    public static function get_stock_by_idModelo($idModelo) {
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
    public static function get_resumen_modelo($idModelo) {
        $con = getdb();
        $Sql = "SELECT * FROM stock WHERE idModelo=" . $idModelo . " ORDER BY idTipo, idColor";
        $result = mysqli_query($con, $Sql);
        if (mysqli_num_rows($result) > 0) {
            $html = '<table class="table table-striped" style="width:100%;">';
            $html .= '<thead><tr><th>ID</th><th>Tipo</th><th>Color</th><th>Precio</th><th>Stock</th></tr></thead>';
            $html .= '<tbody>';
            while ($row = mysqli_fetch_assoc($result)) {
                $html .= '<tr><td>' . $row['idStock'] . '</td><td>' . Tipo::get_tipo($row['idTipo']) . '</td><td>' . Color::get_color($row['idColor']) . '</td><td>' . Tipo::get_precio($row['idTipo']) . '€</td><td>' . $row['stock'] . '</td></tr>';
            }
            $html .= '</tbody></table>';
        } else {
            $html = "<div class='alert alert-warning'>No hay fundas registradas para este modelo</div>";
        }
        return $html;
    }
    public static function get_refCompleta($idStock) {
        $con = getdb();
        $Sql = "SELECT * FROM stock s INNER JOIN modelo m ON s.idModelo = m.idModelo INNER JOIN marca ma ON m.idMarca = ma.idMarca INNER JOIN year y ON m.idYear = y.idYear INNER JOIN tipo t ON s.idTipo = t.idTipo  INNER JOIN color c ON s.idColor = c.idColor WHERE s.idStock=" . intval($idStock);
        $result = mysqli_query($con, $Sql);
        if (!$result) {
            return false;
        } else {
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $refMarca = str_pad(intval($row['refMarca']), 2, "0", STR_PAD_LEFT);
                $refYear = str_pad(intval($row['refYear']), 2, "0", STR_PAD_LEFT);
                $refModelo = str_pad(intval($row['refModelo']), 2, "0", STR_PAD_LEFT);
                $refTipo = str_pad(intval($row['refTipo']), 2, "0", STR_PAD_LEFT);
                $refColor = str_pad(intval($row['refColor']), 2, "0", STR_PAD_LEFT);
                return intval($refMarca . $refYear . $refModelo . $refTipo . $refColor);
            }
            return false;
        }
    }

}
class Relacionados {
    public $idRel;
    public $idsRelacionados;
    public function __construct($idRel, $idsRelacionados) {
        $this->idRel = $idRel;
        $this->idsRelacionados = $idsRelacionados;
    }
    public function set_relacion() {
        $conn = getdb();
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        $sql = "INSERT INTO relacionados ( idsRelacionados) VALUES (?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt->bind_param("s", $this->idsRelacionados);
        if ($stmt->execute()) {
            $message = "Registro actualizado exitosamente";
        } else {
            $message = "Error al actualizar: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
        return $message;
    }
    public function update_relacion() {
        $conn = getdb();
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $sql = "UPDATE relacionados SET idsRelacionados = ? WHERE idRel = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt->bind_param("si", $this->idsRelacionados, $this->idRel);
        if ($stmt->execute()) {
            //echo "Registro actualizado exitosamente";
            $message = "Registro $this->idRel actualizado exitosamente";
        } else {
            //echo "Error al actualizar: " . $stmt->error;
            $message = "Error al actualizar: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
        return $message;
    }
    public static function get_relaciones($modelo = 0, $array = null) {
        $con = getdb();

        if ($modelo == 0) {
            $Sql = "SELECT * FROM `relacionados`";
        } else {
            $Sql = "SELECT idRel FROM relacionados WHERE idsRelacionados LIKE '%;s:" . strlen($modelo) . ":\"$modelo\";%'";
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
                    if ($row['idsRelacionados'] != $modelo) {
                        $relacionados = unserialize($row['idsRelacionados']);
                    }
                } else {
                    $relacionados .= "<div id='relacionados" . $row['idRel'] . "' class='eliminarRelaciones mb-5 col-lg-2 col-md-3 col-sm-6 '><h6>Referencia " . $row['idRel'] . "</h6><ul class='list-group'>";
                    $referencias = unserialize($row['idsRelacionados']);
                    if (is_array($referencias)) {
                        foreach ($referencias as $referencia) {
                            $relacionados .= "<li class='list-group-item'>" . Marca::get_nombreMarca_by_idModelo($referencia) . " " . Modelo::get_modelo($referencia) . "</li>";
                        }
                    }
                    // } else {
                    //     $relacionados .= "<li class='list-group-item'>" . Marca::get_nombreMarca_by_idModelo($referencias) . " " . Modelo::get_modelo($referencias) . "</li>";
                    // }
                    $relacionados .= "</ul><button class='btn btn-danger eliminarRelacion' value='" . $row['idRel'] . "'>Eliminar <i class='bi bi-trash'></i></button></div>";
                }
            }
            if ($relacionados == " " && !$array) {
                $relacionados = "<div class='alert alert-warning'>No hay relaciones registradas</div>";
            }

            return $relacionados;
        }
    }
    public static function get_rel_id($modelo) {
        $con = getdb();
        $Sql = "SELECT idRel FROM relacionados WHERE idsRelacionados LIKE '%;s:" . strlen($modelo) . ":\"$modelo\";%'";
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
    public static function get_relaciones_title($modelo = 0) {
        $con = getdb();
        if ($modelo == 0) {
            $Sql = "SELECT * FROM `relacionados`";
        } else {
            $Sql = "SELECT * FROM relacionados WHERE idsRelacionados LIKE '%;s:" . strlen($modelo) . ":\"$modelo\";%'";
        }
        $result = mysqli_query($con, $Sql);
        if (!$result) {
            return false;
        } else {
            if (mysqli_num_rows($result) > 0) {
                $relacionados = "";
                while ($row = mysqli_fetch_assoc($result)) {
                    $referencias = unserialize($row['idsRelacionados']);
                    if (is_array($referencias)) {
                        foreach ($referencias as $key => $referencia) {
                            if ($key === array_key_first($referencias)) {
                                $relacionados .= Modelo::get_modelo($referencia);
                            } else {
                                $relacionados .= "/";
                                $relacionados .= Modelo::get_modelo_filtrado(Modelo::get_modelo($referencia));
                            }
                        }
                    } else {
                        $relacionados .= Modelo::get_modelo($referencias);
                    }
                }
                return $relacionados;
            } else {
                return false;
            }
        }
    }

}






function get_fundas_csv($modelo = NULL, $stock0 = false) {
    $con = getdb();
    if (is_null($modelo)) {
        $Sql = "SELECT * FROM stock s INNER JOIN modelo m ON s.idModelo = m.idModelo";
        if (!$stock0) {
            $Sql .= " WHERE s.stock != 0";
        }
        $Sql .= " ORDER BY m.idMarca, m.refModelo";
    } else {
        $Sql = "SELECT * FROM stock WHERE `idModelo`=" . $modelo;
        if (!$stock0) {
            $Sql .= " AND stock != 0";
        }
    }
    $result = mysqli_query($con, $Sql);
    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            $usarrel = "No";
            $relacionados = Modelo::get_modelo($row['idModelo']);
            if ($row['usarRel'] == 1) {
                $usarrel = "Si";
                if (Relacionados::get_relaciones_title($row['idModelo'])) {
                    $relacionados = Relacionados::get_relaciones_title($row['idModelo']);
                }
            }
            $html[] = array(
                "codigo" => $row['idStock'],
                "refCompleta" => Stock::get_refCompleta($row['idStock']),
                "marca" => Marca::get_nombreMarca_by_idModelo($row['idModelo']),
                "modelo" => $relacionados,
                "tipo" => Tipo::get_tipo($row['idTipo']),
                "color" => Color::get_color($row['idColor']),
                "precio" => Tipo::get_precio($row['idTipo']),
                "stock" => $row['stock'],
            );
        }
    }
    return $html;
}

function get_fundas_csv_fechas($fecha, $stock0 = false) {
    $con = getdb();
    $Sql = "SELECT * FROM stock  WHERE `modificado` >='" . $fecha . " 00:00:00'";
    if (!$stock0) {
        $Sql .= " AND stock != 0";
    }
    $result = mysqli_query($con, $Sql);
    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            $usarrel = "No";
            $relacionados = Modelo::get_modelo($row['idModelo']);
            if ($row['usarRel'] == 1) {
                $usarrel = "Si";
                if (Relacionados::get_relaciones_title($row['idModelo'])) {
                    $relacionados = Relacionados::get_relaciones_title($row['idModelo']);
                }
            }
            $html[] = array(
                "codigo" => $row['idStock'],
                "refCompleta" => Stock::get_refCompleta($row['idStock']),
                "marca" => Marca::get_nombreMarca_by_idModelo($row['idModelo']),
                "modelo" => $relacionados,
                "tipo" => Tipo::get_tipo($row['idTipo']),
                "color" => Color::get_color($row['idColor']),
                "precio" => Tipo::get_precio($row['idTipo']),
                "stock" => $row['stock'],
            );
        }
    }
    return $html;
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
