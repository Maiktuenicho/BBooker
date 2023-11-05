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
            background-color: #5f6769;
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
            background-image: url(../imagenes/fondo_difuminado_login.jpg);
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
            background-color: #5f6769;
            border-radius: 10px;
            box-shadow: 0px 0px 5px 5px rgba(0, 0, 0, 0.15);
            padding: 30px;
            box-sizing: border-box;
            margin: auto; 
            text-align: center;
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

        .button-link {
            text-decoration: none;
        }

        .button-link button {
            font-family: 'Overpass', sans-serif;
            font-size: 110%;
            color: #1b262c;
            width: 20%;
            height: 40px;
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
            font-size: 110%;
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
            margin-top: 20px;
        }
    </style>
</head>
<body>

<br><br>

<div class="contenedor">
    <div class="contenedorcentrado">
        <h3>ACTUALIZAR USUARIO</h3>
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

            <button type="submit">Actualizar</button>
        </form>
        <div class="update-message">
            <?php
            if (isset($_POST["usuario"])) {
                echo "Datos actualizados correctamente.";
            }
            ?>
        </div>
        <a href='inicio.php' class='button-link'>
            <button class='button' style='vertical-align:middle'>
                <span>Volver</span>
            </button>
        </a>
    </div>
</div>
</body>
</html>
