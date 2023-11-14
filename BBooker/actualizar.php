<?php
// Inicia la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica si hay un usuario en la sesión
if (isset($_SESSION["usuario"])) {
    // Obtén el usuario de la sesión
    $user = $_SESSION["usuario"];

    // Ejecuta la consulta para obtener la información del usuario
    require_once 'conexion.php';

    try {
        $result = $conex->query("SELECT usuario, nombre_completo, correo, telefono FROM usuarios WHERE correo='$user'");
    } catch (mysqli_sql_exception $error_select) {
        echo "Error al recuperar los productos" . $error_select->__toString();
    }
} else {
    // Si no hay usuario en la sesión, realiza alguna acción (redireccionar, mostrar un mensaje, etc.)
    echo "No hay usuario en la sesión.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los valores del formulario
    $usuario = $_POST["usuario"];
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $telefono = $_POST["telefono"];

    // Actualizar los datos en la base de datos
    $updateQuery = "UPDATE usuarios SET usuario='$usuario', nombre_completo='$nombre', correo='$correo', telefono='$telefono' WHERE correo='$user'";

    if ($conex->query($updateQuery) === TRUE) {
        // Recargar los datos actualizados después de la actualización
        $result = $conex->query("SELECT usuario, nombre_completo, correo, telefono FROM usuarios WHERE correo='$correo'");
    } else {
        echo "Error al actualizar los datos: " . $conex->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Actualizar Usuario</title>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Overpass', sans-serif;
            font-weight: normal;
            font-size: 100%;
            color: #1b262c;
            margin: 0;
            background-color: #F4F4F4;
        }

        .contenedor {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 0;
            min-width: 100vw;
            width: 100%;
            height: 100%;
            background-position: center;
            background-size: cover;
        }

        .contenedorcentrado {
            display: auto;
            align-items: center;
            justify-content: center;
            flex-direction: row;
            min-width: 380px;
            max-width: 900px;
            width: 90%;
            background-color: #F4F4F4;
            border-radius: 10px;
            box-shadow: 0px 0px 5px 5px rgba(0, 0, 0, 0.15);
            padding: 30px;
            box-sizing: border-box;
            margin: auto; 
            text-align: center;
        }

        .formulario-actualizar{
            width: 100%;
            max-width: 320px;
            min-width: 320px;
            padding: 30px 30px 50px 30px;
            background-color: #9A8A63;
            -webkit-box-shadow: 0px 0px 5px 5px rgba(0, 0, 0, 0.15);
            -moz-box-shadow: 0px 0px 5px 5px rgba(0, 0, 0, 0.15);
            box-shadow: 0px 0px 5px 5px rgba(0, 0, 0, 0.15);
            border-radius: 3px 3px 3px 3px;
            -moz-border-radius: 3px 3px 3px 3px;
            -webkit-border-radius: 3px 3px 3px 3px;
            box-sizing: border-box;
            opacity: 1;
            filter: alpha(opacity=1);
            margin: auto;
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

        table {
            border-collapse: collapse;
            border-radius: 12px;
            overflow: hidden;
            width: 700px;
            margin: auto;
            margin-top: 20px; 
        }

        th {
            text-align: center;
            font-family: 'Lucida Handwriting';
            font-size: 15px;
            background-color: #18100D;
            border-bottom-width: 2px;
            border-bottom-style: solid;
            border-bottom-color: aliceblue;
            color: aliceblue;
            padding: 5px;
        }

        td {
            font-family: 'Gabriola';
            font-weight: bolder;
            font-size: 22px;
            background-color: #FEFDF8;
            border-bottom-width: 2px;
            border-bottom-style: solid;
            border-bottom-color: black;
        }

        .center {
            text-align: center;
            padding-left: 8px;
            padding-right: 8px;
        }

        /* Estilo para el botón de actualizar */
        .button.update-button {
                position: relative;
                overflow: hidden;
            }

            .button.update-button span {
                z-index: 1;
                color: #1b262c; /* Color inicial de las letras */
                transition: color 0.3s ease; /* Transición para el cambio de color */
            }

            .button.update-button i {
                margin-left: 10px;
                color: #fff;
                opacity: 0; /* Inicia con opacidad 0 */
                transition: opacity 0.3s ease;
            }

            /* Cambiar el color del texto al pasar el ratón sobre el botón */
            .button.update-button:hover span {
                color: #fff;
            }

            /* Mostrar el icono al pasar el ratón sobre el botón */
            .button.update-button:hover i {
                opacity: 1;
            }

            /* Estilo para el botón de volver */
            .button.back-button {
                position: relative;
                overflow: hidden;
            }

            .button.back-button span {
                z-index: 1;
                color: #1b262c; /* Color inicial de las letras */
                transition: color 0.3s ease; /* Transición para el cambio de color */
            }

            .button.back-button i {
                margin-left: 10px;
                color: #fff;
                opacity: 0; /* Inicia con opacidad 0 */
                transition: opacity 0.3s ease;
            }

            /* Cambiar el color del texto al pasar el ratón sobre el botón */
            .button.back-button:hover span {
                color: #fff;
            }

            /* Mostrar el icono al pasar el ratón sobre el botón */
            .button.back-button:hover i {
                opacity: 1;
            }

            /* Estilo para los enlaces de botones */
            .button-link {
                text-decoration: none;
            }

            .button-link button {
                font-family: 'Overpass', sans-serif;
                font-size: 110%;
                color: #1b262c;
                width: 100%; /* Modificado para que tenga el mismo ancho que los inputs */
                height: 40px; /* Establecido la altura para que coincida con los inputs */
                border: none;
                border-radius: 3px 3px 3px 3px;
                -moz-border-radius: 3px 3px 3px 3px;
                -webkit-border-radius: 3px 3px 3px 3px;
                background-color: #dfcdc3;
                margin-top: 10px;
            }

            .button-link button:hover {
                background-color: #3c4245;
            }

        .form-container {
            margin-top: 20px;
            text-align: left;
        }

        .form-container input {
            font-family: 'Overpass', sans-serif;
            font-size: 100%;
            color: #1b262c;
            width: 100%;
            height: 40px;
            margin-bottom: 10px;
            padding: 5px 5px 5px 10px;
            box-sizing: border-box;
            border: none;
            border-radius: 3px 3px 3px 3px;
        }

        .form-container button {
            font-family: 'Overpass', sans-serif;
            font-size: 110%;
            color: #1b262c;
            width: 100%;
            height: 40px;
            border: none;
            border-radius: 3px 3px 3px 3px;
            background-color: #dfcdc3;
            margin-top: 10px;
        }

        .form-container button:hover {
            background-color: #3c4245;
        }

        .update-message {
            font-family: 'Overpass', sans-serif;
            font-size: 18px;
            color: #3c4245;
            margin-top: 10px;
        }

        label{
            font-weight: bold;
            color: #F4F4F4;
        }

    
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        <a id="home-link" href="inicio.php">
            <i class="fa fa-solid fa-home"></i>
                Inicio
        </a>
        <a id="perfil-link" href="perfil.php">
            <i class="perfil-link fa fa-solid fa-user"></i>
                Perfil
        </a>
        <a id="cerrar-sesion" href="login.php">
            <i class="cerrar-sesion fa fa-solid fa-arrow-right"></i>
                Salir
        </a>
    </nav>

    <br><br>

<div class="contenedor">
    <div class="contenedorcentrado">
    <h1>ACTUALIZAR USUARIO</h1>
        <div class="formulario-actualizar">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-container">
            <?php
            // Muestra el formulario con los valores actuales del usuario
            while ($filas = $result->fetch_row()) {
                echo "<label for='usuario'>Usuario:</label>";
                echo "<input type='text' id='usuario' name='usuario' value='{$filas[0]}' required>";

                echo "<label for='nombre'>Nombre Completo:</label>";
                echo "<input type='text' id='nombre' name='nombre' value='{$filas[1]}' required>";

                echo "<label for='correo'>Correo:</label>";
                echo "<input type='email' id='correo' name='correo' value='{$filas[2]}' required>";

                echo "<label for='telefono'>Teléfono:</label>";
                echo "<input type='tel' id='telefono' name='telefono' value='{$filas[3]}' required>";
            }
            ?>
            <button type="submit" class="button update-button">
                <span>Actualizar</span>
                <i class="fas fa-sync-alt"></i>
            </button>
        </form>
        
        <a href='inicio.php' class='button-link'>
            <button class='button back-button' style='vertical-align:middle'>
                <span>Volver</span>
                <i class="fa fa-solid fa-arrow-left"></i>
            </button>
        </a>
        <div class="update-message">
            <?php
            if (isset($_POST["usuario"])) {
                echo "Datos actualizados correctamente.";
            }
            ?>
        </div>
        </div>
    </div>
</div>
</body>
</html>
