<?php
// admin_config.php - User and configuration management panel
session_start();
if (!file_exists('config.php')) {
    die('No config.php found. Please run install.php first.');
}
$config = include 'config.php';
$conn = @mysqli_connect($config['host'], $config['user'], $config['pass'], $config['db']);
if (!$conn) {
    die('Database connection failed.');
}
// Login logic
if (isset($_POST['login_admin'])) {
    $user = $_POST['login_user'];
    $pass = $_POST['login_pass'];
    $sql = "SELECT * FROM usuarios WHERE usuario='" . mysqli_real_escape_string($conn, $user) . "'";
    $res = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($res)) {
        if (password_verify($pass, $row['password'])) {
            $_SESSION['admin_user'] = $row['usuario'];
            $_SESSION['admin_id'] = $row['idUsuario'];
        } else {
            $error = 'Contraseña incorrecta';
        }
    } else {
        $error = 'Usuario no encontrado';
    }
}
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}
// User management actions
if (isset($_SESSION['admin_user']) && isset($_SESSION['admin_id'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['step'])) {
        $step = $_POST['step'];
        // User id 1 can create and delete users, and change any password
        if ($_SESSION['admin_id'] == 1) {
            if ($step == 'admin_create') {
                $adminuser = trim($_POST['adminuser']);
                $adminpass = password_hash($_POST['adminpass'], PASSWORD_BCRYPT);
                // Check unique username
                $check = mysqli_query($conn, "SELECT idUsuario FROM usuarios WHERE usuario='" . mysqli_real_escape_string($conn, $adminuser) . "'");
                if (mysqli_num_rows($check) > 0) {
                    $error = 'El nombre de usuario ya existe.';
                } else {
                    $sql = "INSERT INTO usuarios (Usuario, Password) VALUES ('" . mysqli_real_escape_string($conn, $adminuser) . "', '" . mysqli_real_escape_string($conn, $adminpass) . "')";
                    if (mysqli_query($conn, $sql)) {
                        $success = 'Usuario administrador creado correctamente.';
                    } else {
                        $error = 'Error creando admin: ' . mysqli_error($conn);
                    }
                }
            } elseif ($step == 'admin_pass') {
                $adminuser = $_POST['adminuser'];
                $adminpass = password_hash($_POST['adminpass'], PASSWORD_BCRYPT);
                $sql = "UPDATE usuarios SET password='" . mysqli_real_escape_string($conn, $adminpass) . "' WHERE usuario='" . mysqli_real_escape_string($conn, $adminuser) . "'";
                if (mysqli_query($conn, $sql) && mysqli_affected_rows($conn) > 0) {
                    $success = 'Contraseña actualizada correctamente para ' . htmlspecialchars($adminuser) . '.';
                } else {
                    $error = 'Error actualizando contraseña o usuario no existe.';
                }
            } elseif ($step == 'admin_delete') {
                $id = intval($_POST['id']);
                if ($id == 1) {
                    $error = 'No se puede eliminar el usuario principal.';
                } else {
                    $sql = "DELETE FROM usuarios WHERE idUsuario=$id";
                    if (mysqli_query($conn, $sql)) {
                        $success = 'Usuario eliminado correctamente.';
                    } else {
                        $error = 'Error eliminando usuario: ' . mysqli_error($conn);
                    }
                }
            }
        }
        // All users can change their own password
        if ($step == 'self_pass' && isset($_SESSION['admin_id'])) {
            $adminpass = password_hash($_POST['adminpass'], PASSWORD_BCRYPT);
            $sql = "UPDATE usuarios SET password='" . mysqli_real_escape_string($conn, $adminpass) . "' WHERE idUsuario=" . intval($_SESSION['admin_id']);
            if (mysqli_query($conn, $sql)) {
                $success = 'Contraseña actualizada correctamente.';
            } else {
                $error = 'Error actualizando contraseña.';
            }
        }
    }
}
?><!DOCTYPE html>
<html lang="es">
<head>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <title>Panel de administración</title>
    <style>body{font-family:sans-serif;max-width:600px;margin:40px auto;}form{margin:20px 0;}input,button{padding:8px;margin:5px 0;width:100%;}</style>
</head>
<body>
<h2>Panel de administración</h2>
<?php if (!empty($error)) { echo '<div class="alert alert-danger">'.$error.'</div>'; } ?>
<?php if (!empty($success)) { echo '<div class="alert alert-success">'.$success.'</div>'; } ?>
<?php if (isset($_SESSION['admin_user']) && isset($_SESSION['admin_id'])) { ?>
        <div class="mb-2">Conectado como <b><?php echo htmlspecialchars($_SESSION['admin_user']); ?></b> <a href="?logout=1" class="btn btn-sm btn-outline-danger ms-2">Cerrar sesión</a></div>
        <ul class="nav nav-tabs mb-3" id="adminTab" role="tablist">
            <?php if ($_SESSION['admin_id'] == 1) { ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="create-tab" data-bs-toggle="tab" data-bs-target="#create" type="button" role="tab" aria-controls="create" aria-selected="true">Crear usuario</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="list-tab" data-bs-toggle="tab" data-bs-target="#list" type="button" role="tab" aria-controls="list" aria-selected="false">Lista de usuarios</button>
            </li>
            <?php } ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link<?php if ($_SESSION['admin_id'] != 1) echo ' active'; ?>" id="self-tab" data-bs-toggle="tab" data-bs-target="#self" type="button" role="tab" aria-controls="self" aria-selected="false">Mi cuenta</button>
            </li>
        </ul>
        <div class="tab-content" id="adminTabContent">
            <?php if ($_SESSION['admin_id'] == 1) { ?>
            <div class="tab-pane fade show active p-3" id="create" role="tabpanel" aria-labelledby="create-tab">
                <form method="post">
                        <input type="hidden" name="step" value="admin_create">
                        <label class="form-label">Crear nuevo usuario (único):</label>
                        <input class="form-control" name="adminuser" placeholder="Usuario" required>
                        <input class="form-control" name="adminpass" type="password" placeholder="Contraseña" required>
                        <button type="submit" class="btn btn-success mt-2">Crear usuario</button>
                </form>
            </div>
            <div class="tab-pane fade p-3" id="list" role="tabpanel" aria-labelledby="list-tab">
                <?php
        $res = mysqli_query($conn, "SELECT * FROM usuarios");
        echo '<table class="table table-bordered"><tr><th>ID</th><th>Usuario</th><th>Último acceso</th><th>Acción</th></tr>';
        while ($row = mysqli_fetch_assoc($res)) {
            echo '<tr><td>' . $row['idUsuario'] . '</td><td>' . htmlspecialchars($row['usuario']) . '</td><td>' . $row['lastLog'] . '</td><td>';
            // Only allow delete for users other than id 1
            if ($row['idUsuario'] != 1) {
                echo '<form method="post" class="d-inline-flex align-items-center me-2">';
                echo '<input type="hidden" name="step" value="admin_delete">';
                echo '<input type="hidden" name="id" value="' . $row['idUsuario'] . '">';
                echo '<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'¿Eliminar usuario?\')">Eliminar</button>';
                echo '</form>';
            }
            // Change password for any user
            echo '<form method="post" class="d-inline-flex align-items-center">';
            echo '<input type="hidden" name="step" value="admin_pass">';
            echo '<input type="hidden" name="adminuser" value="' . htmlspecialchars($row['usuario']) . '">';
            echo '<input class="form-control form-control-sm me-1" name="adminpass" type="password" placeholder="Nueva contraseña">';
            echo '<button type="submit" class="btn btn-sm btn-primary">Cambiar contraseña</button>';
            echo '</form>';
            echo '</td></tr>';
        }
        echo '</table>';
                ?>
            </div>
            <?php } ?>
            <div class="tab-pane fade<?php if ($_SESSION['admin_id'] != 1) echo ' show active'; ?> p-3" id="self" role="tabpanel" aria-labelledby="self-tab">
                <form method="post">
                        <input type="hidden" name="step" value="self_pass">
                        <label class="form-label">Cambiar mi contraseña:</label>
                        <input class="form-control" name="adminpass" type="password" placeholder="Nueva contraseña" required>
                        <button type="submit" class="btn btn-warning mt-2">Cambiar contraseña</button>
                </form>
            </div>
        </div>
<?php } else { ?>
    <form method="post" class="mb-3">
        <div class="mb-2">Iniciar sesión como administrador para gestionar usuarios:</div>
        <input class="form-control mb-2" name="login_user" placeholder="Usuario" required>
        <input class="form-control mb-2" name="login_pass" type="password" placeholder="Contraseña" required>
        <button type="submit" name="login_admin" class="btn btn-primary">Iniciar sesión</button>
    </form>
<?php } ?>
</body>
</html>
