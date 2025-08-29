 <style>
            .even { background: #eeeeee;}
            .title{font-weight: bold;}
            .table-striped>tbody>tr.trchecked {background: #ffff99;}
            /* Extra styles for the cancel button */
            .cancelbtn {
                width: auto;
                padding: 10px 18px;
                background-color: #f44336;
            }

            /* Center the image and position the close button */
            .imgcontainer {
                text-align: center;
                margin: 24px 0 12px 0;
                position: relative;
            }

            img.avatar {
                width: 20%;
                border-radius: 50%;
            }
            span.psw {
                float: right;
                padding-top: 16px;
            }

            /* Modal Content/Box */
            .modal-content {
                background-color: #fefefe;
                margin: 5% auto 15% auto; /* 5% from the top, 15% from the bottom and centered */
                border: 1px solid #888;
                width: 80%; /* Could be more or less, depending on screen size */

            }

            /* The Close Button (x) */
            .close {
                position: absolute;
                right: 25px;
                top: 0;
                color: #000;
                font-size: 35px;
                font-weight: bold;
            }

            .close:hover,
            .close:focus {
                color: red;
                cursor: pointer;
            }

            /* Agregando efecto Zoom */
            .animate {
                -webkit-animation: animatezoom 0.6s;
                animation: animatezoom 0.6s
            }

            @-webkit-keyframes animatezoom {
                from {-webkit-transform: scale(0)} 
                to {-webkit-transform: scale(1)}
            }

            @keyframes animatezoom {
                from {transform: scale(0)} 
                to {transform: scale(1)}
            }

            /* Change styles for span and cancel button on extra small screens */
            @media screen and (max-width: 300px) {
                span.psw {
                    display: block;
                    float: none;
                }
                .cancelbtn {
                    width: 100%;
                }
            }
        </style>
<?php
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    
} else {
    echo "<div style='text-align: center; margin-top: 20px;'>Inicia Sesion para acceder a este contenido.<br>";
    ?>
    <!-- login -->
    <div id="id01" class="panel">
        <form class="panel animate" action="checklogin.php" method="post">
            <div class="imgcontainer">
                <span onclick="document.getElementById('id01').style.display = 'none'" class="close" title="Close Modal">&times;</span>
                <img src="img/avatar.png" alt="Avatar" class="avatar">
            </div>

            <div class="container text-center">
                <label for="uname"><b>Usuario</b></label>
                <input type="text" placeholder="Ingresar Usuario" name="username" required>

                <label for="psw"><b>Contraseña</b></label>
                <input type="password" placeholder="Ingresa tu Contraseña" name="password" required>

                <button type="submit">Login</button>
                <label>
                    <input type="checkbox" checked="checked" name="remember"> Recordarme
                </label>
            </div>

            <div class="container" style="background-color:#f1f1f1">

            </div>
        </form>
    </div><!-- Login -->
    <?php
    exit;
}
$now = time();
if ($now > $_SESSION['expire']) {
    session_destroy();
    header('Location: index.php'); //redirige a la página de login
    exit;
}