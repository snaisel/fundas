<?php
// install.php - Simple installer for fundas
if (file_exists('config.php') && (!isset($_POST['step']) || $_POST['step'] !== 'admin_create')) {
    echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Instalador Fundas</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"></head><body><div class="container mt-5"><div class="alert alert-success">La instalación ya está realizada.<br>Para gestionar usuarios y configuración, use <b><a href="admin_config.php">admin_config.php</a></b>.<br>Por seguridad, puede borrar este archivo.</div></div></body></html>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['step'])) {
    $step = $_POST['step'];
    if ($step == 'db') {
        $servername = $_POST['servername'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $db = $_POST['db'];
        // Desactivar excepciones de mysqli para manejar errores manualmente
        if (function_exists('mysqli_report')) { mysqli_report(MYSQLI_REPORT_OFF); }
        $conn = @mysqli_connect($servername, $username, $password);
        if (!$conn) {
            $error = 'Error de conexión: Usuario o contraseña incorrectos, o el servidor MySQL no está disponible.';
        } else {
            // Crear base de datos si no existe
            mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            mysqli_select_db($conn, $db);
            $sqls = [
                // CREATE TABLES
                "CREATE TABLE IF NOT EXISTS `color` ( `idColor` int(11) NOT NULL, `nombreColor` varchar(50) NOT NULL, `refColor` int(11) NOT NULL, `thumb` varchar(150) NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
                "CREATE TABLE IF NOT EXISTS `marca` ( `idMarca` int(11) NOT NULL, `nombreMarca` varchar(50) NOT NULL, `refMarca` int(2) NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
                "CREATE TABLE IF NOT EXISTS `modelo` ( `idModelo` int(11) NOT NULL, `nombreModelo` varchar(80) NOT NULL, `idMarca` int(11) NOT NULL, `idYear` int(11) NOT NULL, `refModelo` int(2) NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
                "CREATE TABLE IF NOT EXISTS `relacionados` ( `idRel` int(11) NOT NULL, `idsRelacionados` mediumtext NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
                "CREATE TABLE IF NOT EXISTS `stock` ( `idStock` int(11) NOT NULL, `idModelo` int(11) NOT NULL, `idTipo` int(11) NOT NULL, `idColor` int(11) NOT NULL, `stock` int(2) NOT NULL, `usarRel` tinyint(1) NOT NULL DEFAULT 0, `idRel` int(11) NOT NULL, `modificado` date DEFAULT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
                "CREATE TABLE IF NOT EXISTS `tipo` ( `idTipo` int(11) NOT NULL, `nombreTipo` varchar(50) NOT NULL, `refTipo` int(2) NOT NULL, `pvp` decimal(10,2) NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
                "CREATE TABLE IF NOT EXISTS `usuarios` ( `idUsuario` int(3) NOT NULL, `usuario` varchar(25) NOT NULL, `password` varchar(128) NOT NULL, `lastLog` datetime NOT NULL DEFAULT current_timestamp() ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;",
                "CREATE TABLE IF NOT EXISTS `year` ( `idYear` int(2) NOT NULL, `year` int(4) NOT NULL, `refYear` int(2) NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;",
                // ALTER TABLES (add primary keys, unique, keys)
                "ALTER TABLE `color` ADD PRIMARY KEY (`idColor`), ADD UNIQUE KEY `refColor` (`refColor`);",
                "ALTER TABLE `marca` ADD PRIMARY KEY (`idMarca`), ADD UNIQUE KEY `refMarca` (`refMarca`);",
                "ALTER TABLE `modelo` ADD PRIMARY KEY (`idModelo`);",
                "ALTER TABLE `relacionados` ADD PRIMARY KEY (`idRel`);",
                "ALTER TABLE `stock` ADD PRIMARY KEY (`idStock`);",
                "ALTER TABLE `tipo` ADD PRIMARY KEY (`idTipo`), ADD UNIQUE KEY `refTipo` (`refTipo`);",
                "ALTER TABLE `usuarios` ADD PRIMARY KEY (`idUsuario`);",
                "ALTER TABLE `year` ADD PRIMARY KEY (`idYear`), ADD UNIQUE KEY `refYear` (`refYear`);",
                // ALTER TABLES (set auto_increment)
                "ALTER TABLE `color` MODIFY `idColor` int(11) NOT NULL AUTO_INCREMENT;",
                "ALTER TABLE `marca` MODIFY `idMarca` int(11) NOT NULL AUTO_INCREMENT;",
                "ALTER TABLE `modelo` MODIFY `idModelo` int(11) NOT NULL AUTO_INCREMENT;",
                "ALTER TABLE `relacionados` MODIFY `idRel` int(11) NOT NULL AUTO_INCREMENT;",
                "ALTER TABLE `stock` MODIFY `idStock` int(11) NOT NULL AUTO_INCREMENT;",
                "ALTER TABLE `tipo` MODIFY `idTipo` int(11) NOT NULL AUTO_INCREMENT;",
                "ALTER TABLE `usuarios` MODIFY `idUsuario` int(3) NOT NULL AUTO_INCREMENT;",
                "ALTER TABLE `year` MODIFY `idYear` int(2) NOT NULL AUTO_INCREMENT;"
            ];
            $ok = true;
            foreach ($sqls as $sql) {
                if (!mysqli_query($conn, $sql)) {
                    $ok = false;
                    $error = 'Error creando tablas: ' . mysqli_error($conn);
                    break;
                }
            }
            if ($ok) {
                // Crear config.php para uso en class.php
                $configContent = "<?php\nreturn [\n    'host' => '" . addslashes($servername) . "',\n    'user' => '" . addslashes($username) . "',\n    'pass' => '" . addslashes($password) . "',\n    'db'   => '" . addslashes($db) . "'\n];\n";
                file_put_contents('config.php', $configContent);
                // Guardar config temporal para siguiente paso
                file_put_contents('install_config.php', $configContent);
                $next = true;
            }
        }
    } elseif ($step == 'admin_create') {
        // Crear usuario admin
        if (!file_exists('install_config.php')) {
            $error = 'Falta la configuración de la base de datos.';
        } else {
            $config = include 'install_config.php';
            $conn = @mysqli_connect($config['host'], $config['user'], $config['pass'], $config['db']);
            if (!$conn) {
                $error = 'Error de conexión: ' . mysqli_connect_error();
            } else {
                $adminuser = trim($_POST['adminuser']);
                $adminpass = $_POST['adminpass'];
                if (empty($adminuser) || empty($adminpass)) {
                    $error = 'Usuario y contraseña requeridos.';
                } else {
                    // Verificar si ya existe un usuario admin
                    $check = mysqli_query($conn, "SELECT * FROM usuarios WHERE usuario='" . mysqli_real_escape_string($conn, $adminuser) . "'");
                    if (mysqli_num_rows($check) > 0) {
                        $error = 'El usuario ya existe.';
                    } else {
                        $hash = password_hash($adminpass, PASSWORD_DEFAULT);
                        $insert_sql = "INSERT INTO usuarios (usuario, password, lastLog) VALUES ('" . mysqli_real_escape_string($conn, $adminuser) . "', '" . mysqli_real_escape_string($conn, $hash) . "', NOW())";
                        $insert = mysqli_query($conn, $insert_sql);
                        if ($insert) {
                            $done = true;
                            @unlink('install_config.php');
                        } else {
                            $error = 'Error creando usuario admin: ' . mysqli_error($conn) . '<br><small>SQL: ' . htmlspecialchars($insert_sql) . '</small>';
                        }
                    }
                }
            }
        }
    }
    ?>
    <!DOCTYPE html>
<?php } ?>
<html lang="es">
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <meta charset="UTF-8">
        <title>Instalador Fundas</title>
        <style>
            body {
                font-family: sans-serif;
                max-width: 500px;
                margin: 40px auto;
            }
            form {
                margin: 20px 0;
            }
            input,
            button {
                padding: 8px;
                margin: 5px 0;
                width: 100%;
            }
        </style>
    </head>
    <body>
        <div class="container mt-5">
            <h2>Instalador Fundas</h2>
            <?php if (!empty($error)) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            } ?>
            <?php if (!empty($success)) {
                echo '<div class="alert alert-success">' . $success . '</div>';
            } ?>
            <?php if (!empty($done)) {
                echo '<div class="alert alert-success">¡Instalación completada!<br>Se ha creado <b>config.php</b> para la conexión a la base de datos, , use <b><a href="admin_config.php">admin_config.php</a></b>.<br><b>Por seguridad, borre install.php.</b></div>';
                exit;
            } ?>
                <form method="post" class="form mt-4" autocomplete="off"> <input type="hidden" name="step" value="db">
                <label class="form-label">Servidor MySQL:</label><input class="form-control" name="servername" value="localhost" required>
                <label class="form-label">Usuario:</label><input class="form-control" name="username" value="root" required>
                <label class="form-label">Contraseña:</label><input class="form-control" name="password" type="password">
                <label class="form-label">Base de datos:</label><input class="form-control" name="db" value="fundas" required>
                <button type="submit" class="btn btn-primary mt-2">Crear tablas y continuar</button>
            </form>
            <?php if (!empty($next)) { ?>
                <form method="post" class="form mt-4">
                    <input type="hidden" name="step" value="admin_create">
                    <label class="form-label">Crear usuario administrador:</label>
                    <input class="form-control" name="adminuser" placeholder="Usuario" required>
                    <input class="form-control" name="adminpass" type="password" placeholder="Contraseña" required>
                    <button type="submit" class="btn btn-success mt-2">Crear usuario admin</button>
                </form>
            <?php } ?>
        </div>
    </body>
</html>

