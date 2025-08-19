<!-- 
Creado por Luisetfree & tecnosetfree
Web: http://luisetfree.over-blog.es
Facebook:https://www.facebook.com/tecnosetfree/
Twitter: @tecnosetfree
Apoyanos con tus visitas y comentarios en nuestras redes sociales para seguir avanzando y traer contenido de calidad.

-->
<?php
session_start();
?>

<?php
include 'class.php';

$conexion = getdb();

if ($conexion->connect_error) {
    die("La conexion falló: " . $conexion->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM usuarios WHERE usuario = '$username'";

$result = $conexion->query($sql);

if ($result->num_rows > 0) {
    
}

$row = $result->fetch_array(MYSQLI_ASSOC);
if (password_verify($password, $row['password'])) {
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $username;
    $_SESSION['start'] = time();
    if (!$_POST['remember'])
        $_SESSION['expire'] = $_SESSION['start'] + (60 * 60);
    else
        $_SESSION['expire'] = $_SESSION['start'] + (1000 * 60);

    echo "Bienvenido! " . $_SESSION['username'];
   
    $sqlm = "UPDATE `usuarios` SET `lastLog`='" . date('Ymd', time()) . "' WHERE `idUsuario` = " . $row['idUsuario'];
    $resultlog = mysqli_query($conexion, $sqlm);
    mysqli_close($conexion);
    header('Location: index.php');
} else {
    echo "Username o Password son incorrectos.<br>";
       echo "<br><a href='index.php'>Volver a Intentarlo</a>";
}
mysqli_close($conexion);
?>