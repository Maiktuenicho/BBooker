<?php
// Iniciar la sesión
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas de Pistas de Baloncesto</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F4F4F4;
        }

        header {
            background-color: #9A8A62;
            color: white;
            padding: 5px;
            text-align: center;
        }

        nav {
            background-color: #444;
            color: white;
            padding: 5px;
            text-align: right;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 5px;
            font-weight: bold;
            transition: color 0.3s; /* Agregamos la transición al color */
        }

        nav a:hover {
            color: #9A8A62; /* Cambiamos el color al pasar el ratón */
        }

        .Container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            height: 100vh; /* Ajusta la altura al 100% de la ventana para centrar verticalmente */
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .button12 {
            cursor: pointer;
            background-color: #9A8A62; /* Cambia el color de fondo del botón */
            width: 330px;
            height: 64px;
            padding: 20px 50px;
            line-height: 64px;
            position: relative;
            margin: 10px;
            display: inline-block;
            text-align: left;
        }

        .button12 span {
            color: #fff;
            display: inline-block;
            padding-left: 35px;
            text-transform: uppercase;
            font: bold 18px/66px Arial;
            transform: scaleX(0.6);
            letter-spacing: 3px;
            transform-origin: center left;
            transition: color 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .button12 em {
            position: absolute;
            height: 1px;
            background: #fff;
            width: 47%;
            right: 23px;
            top: 50%;
            transform: scaleX(0.25);
            -webkit-transform: scaleX(0.25);
            transform-origin: center right;
            transition: all 0.3s ease;
            z-index: 1;
        }

        .button12:before,
        .button12:after {
            content: '';
            background: #fff;
            height: 50%;
            width: 0;
            position: absolute;
            transition: 0.3s cubic-bezier(0.785, 0.135, 0.15, 0.86);
            -webkit-transition: 0.3s cubic-bezier(0.785, 0.135, 0.15, 0.86);
        }

        .button12:before {
            top: 0;
            left: 0;
            right: auto;
        }

        .button12:after {
            bottom: 0;
            right: 0;
            left: auto;
        }

        .button12:hover:before {
            width: 100%;
            right: 0;
            left: auto;
        }

        .button12:hover:after {
            width: 100%;
            left: 0;
            right: auto;
        }

        .button12:hover span {
            color: #000;
        }

        .button12:hover em {
            background: #000;
            transform: scaleX(.51);
            transform: scaleX(.51);
        }
    </style>
</head>
<body>
    <header>
        <?php
            // Verificar si el usuario ha iniciado sesión
            if (isset($_SESSION["usuario"])) {
                // Obtener el nombre de usuario desde la sesión
                $correo = $_SESSION["usuario"];

                // Conexion con la base de datos
                require_once 'conexion.php';

                $consulta_usuario =$conex->query("SELECT usuario FROM usuarios WHERE correo = '$correo';");
                $usuario=$consulta_usuario->fetch_row();
                echo "<h1>";
                echo "Bienvenido a BBooker $usuario[0]";
                echo "</h1>";

            } else {
                // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
                header("Location: login.php");
                exit(); // Asegura que el script se detenga después de redirigir
            }
        ?>
    </header>

    <nav>
        <a id="perfil-link" href="perfil.php">
            <i class="perfil-link fa fa-solid fa-user"></i>
                Perfil
        </a>
        <a id="cerrar-sesion" href="login.php">
            <i class="cerrar-sesion fa fa-solid fa-arrow-right"></i>
                Salir
        </a>
    </nav>

    <div class="button-container">
        <a href="calendario.php" class="button12">
            <em> </em>   
            <span>
                Disponibilidad
            </span>
        </a>
        <br>
        <a href="mis_reservas.php" class="button12">
            <em></em>   
            <span>
                Mis reservas
            </span>
        </a>
        <br>
        <a href="historial.php" class="button12">
            <em></em>   
            <span>
                Historial
            </span>
        </a>
    </div>
</body>
</html>
