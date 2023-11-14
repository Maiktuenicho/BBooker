<?php
// Iniciar la sesión 
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tus Reservas</title>
    <style>
        /* Estilos CSS para la página */

        /* Estilo para el cuerpo de la página */
        body {
            font-family: 'Overpass', sans-serif;
            font-weight: normal;
            font-size: 100%;
            color: #464645;
            margin: 0;
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

        /* Contenedor principal de la página */
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

        /* Contenedor centralizado de la página */
        .contenedorcentrado {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
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
            margin-top: 20px;
        }

        /* Estilos para las tablas */
        table {
            border-collapse: collapse;
            border-radius: 12px;
            overflow: hidden;
            width: 100%;
            margin-top: 20px;
        }

        /* Estilo para los encabezados de las tablas */
        th {
            text-align: center;
            font-family: 'Lucida Handwriting';
            font-size: 15px;
            background-color: #9A8A63;
            color: #F4F4F4;
            border-bottom-width: 2px;
            border-bottom-style: solid;
            padding: 5px;
        }

        /* Estilo para las celdas de las tablas */
        td {
            font-family: 'Gabriola';
            font-weight: bolder;
            font-size: 22px;
            background-color: #C3BEB1;
            border-bottom-width: 2px;
            border-bottom-style: solid;
            border-bottom-color: #464645;
        }

        /* Estilo para centrar texto en las tablas */
        .center {
            text-align: center;
            padding-left: 8px;
            padding-right: 8px;
        }

        /* Estilos para los botones */
        .button {
            width: 70%;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
            font-family: 'Overpass', sans-serif;
            font-size: 80%;
            color: #1b262c;
            width: 70%;
            height: 40px;
            border: none;
            border-radius: 3px 3px 3px 3px;
            -moz-border-radius: 3px 3px 3px 3px;
            -webkit-border-radius: 3px 3px 3px 3px;
            background-color: #F4F4F4;
            margin-top: 10px;
        }

        /* Estilos para el texto en los botones */
        .button span {
            z-index: 1;
            color: #1b262c;
            transition: color 0.3s ease;
        }

        /* Estilos para los iconos en los botones */
        .button i {
            margin-left: 10px;
            color: #fff;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        /* Cambiar color del texto al pasar el ratón sobre el botón */
        .button:hover span {
            color: #000;
        }

        /* Mostrar iconos al pasar el ratón sobre el botón */
        .button:hover i {
            opacity: 1;
            color: #000;
        }

        #volver-atras {
            text-align: center;
            margin-top: 20px;
            position: relative;
            overflow: hidden;
        }

        #volver-atras button {
            cursor: pointer;
            background-color: #9A8A62;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            position: relative;
            overflow: hidden;
            transition: background-color 0.3s ease; /* Transición para el cambio de color de fondo */
        }

        #volver-atras button:hover {
            background-color: #7B6B4C; /* Color de fondo al pasar el ratón */
        }

        #volver-atras button span {
            z-index: 1;
            color: #1b262c;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        #volver-atras button i {
            margin-left: 10px;
            color: #fff;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        #volver-atras button:hover span {
            color: #fff;
        }

        #volver-atras button:hover i {
            opacity: 1;
        }

        /* Estilos para los enlaces de los botones */
        .button-link {
            text-decoration: none;
        }

        /* Estilos para los botones enlaces */
        .button-link button {
            font-family: 'Overpass', sans-serif;
            font-size: 110%;
            color: #1b262c;
            width: 120%;
            height: 40px;
            border: none;
            border-radius: 3px 3px 3px 3px;
            -moz-border-radius: 3px 3px 3px 3px;
            -webkit-border-radius: 3px 3px 3px 3px;
            background-color: #dfcdc3;
            margin-top: 10px;
        }

        /* Cambiar color de fondo al pasar el ratón sobre el botón enlace */
        .button-link button:hover {
            background-color: #3c4245;
        }

        

    </style>
    
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">

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
    <?php
    // Verificar si el usuario ha iniciado sesión
    if (isset($_SESSION["usuario"])) {
        // Obtener el nombre de usuario desde la sesión
        $correo = $_SESSION["usuario"];

        // Conexion con la base de datos
        require_once 'conexion.php';

        // Obtener el ID del usuario
        $consultaUsuario = $conex->query("SELECT id_usuario FROM usuarios WHERE correo = '$correo';");
        $idUsuario = $consultaUsuario->fetch_row()[0];

        // Manejar el borrado si se ha enviado el formulario
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_reserva"])) {
            $idReserva = $_POST["id_reserva"];
            
            try {
                // Realizar la eliminación de la reserva
                $conex->query("DELETE FROM reservas WHERE ID_RESERVA = '$idReserva';");

                // Mostrar mensaje de eliminación exitosa
                $mensajeExito = "La reserva se ha eliminado correctamente.";
            } catch (mysqli_sql_exception $error_borrar) {
                // Mostrar mensaje de error en caso de falla
                echo "Error al borrar la reserva" . $error_borrar->__toString();
            }
        }

        // Consulta para obtener las reservas del usuario
        $consultaReservas = $conex->query("SELECT ID_RESERVA, HORA_INICIO, HORA_FIN, FECHA, ID_PISTA FROM reservas WHERE id_usuario = '$idUsuario' AND fecha < CURRENT_DATE;");

        // Mostrar las reservas
        if ($consultaReservas->num_rows > 0) {
            echo "<div class='contenedor'>";
            echo "<div class='contenedorcentrado'>";
            echo "<h1>Estas son tus reservas</h1>";
            echo "<table border='1'>";
            echo "<tr><th>HORA_INICIO</th><th>HORA_FIN</th><th>FECHA</th><th>PISTA</th><th>Acciones</th></tr>";

            while ($row = $consultaReservas->fetch_assoc()) {
                // Calcular la hora fin sumando una hora a la hora de inicio
                $horaInicio = new DateTime($row["HORA_INICIO"]);
                $horaFinCalculada = $horaInicio->modify('+1 hour')->format('H:i:s');

                // Obtener el nombre de la pista
                $idPista = $row["ID_PISTA"];
                $consultaPista = $conex->query("SELECT nombre_pista FROM pistas WHERE ID_PISTA = '$idPista';");

                // Verificar si hay resultados en la consulta
                if ($consultaPista->num_rows > 0) {
                    $nombrePista = $consultaPista->fetch_row()[0];
                } else {
                    // Manejar el caso en que no hay resultados
                    $nombrePista = "Nombre no disponible";
                }

                echo "<tr><td>" . $row["HORA_INICIO"] . "</td><td>" . $horaFinCalculada . "</td><td>" . $row["FECHA"] . "</td><td>" . $nombrePista . "</td>";
                
                // Agregar formulario y botones para cada reserva
                echo "<td>";

                // Agregar botón para modificar
                echo "<form method='post' action='modificar_reserva.php'>";
                echo "<input type='hidden' name='id_reserva' value='" . $row["ID_RESERVA"] . "'>";
                echo "<input type='hidden' name='hora_inicio' value='" . $row["HORA_INICIO"] . "'>";
                echo "<input type='hidden' name='hora_fin' value='" . $row["HORA_FIN"] . "'>";
                echo "<input type='hidden' name='fecha' value='" . $row["FECHA"] . "'>";
                echo "<input type='hidden' name='id_pista' value='" . $row["ID_PISTA"] . "'>";

                // Agregar botón para eliminar
                echo "<form method='post' action=''>";
                echo "<input type='hidden' name='id_reserva' value='" . $row["ID_RESERVA"] . "'>";
                // Estilo para el botón de eliminar
                echo "<button class='button delete-button' type='submit' name='eliminar'>";
                echo "<span>Eliminar</span>";
                echo "<i class='fas fa-times'></i>"; // Icono de X
                echo "</button>";
                echo "</form>";
                
                echo "</td></tr>";
            }

            echo "</table>";

            // Mostrar mensaje de éxito si existe
            if (isset($mensajeExito)) {
                echo "<p style='color: green;'>$mensajeExito</p>";
            }

            // botón para volver al inicio
            echo "<div id='volver-atras'>";
            echo "<a href='inicio.php'>";
            echo "<button>";
            echo "<span>Volver</span>";
            echo "<i class='fas fa-arrow-left'></i>";
            echo "</button>";
            echo "</a>";
            echo "</div>";


            echo "</div>"; // Cierre del div contenedorcentrado
            echo "</div>"; // Cierre del div contenedor
        } else {
            echo "<div class='contenedor'>";
            echo "<div class='contenedorcentrado'>";

            echo "<p>No tienes reservas.</p>";

            // botón para volver al inicio
            echo "<div id='volver-atras'>";
            echo "<a href='inicio.php'>";
            echo "<button>";
            echo "<span>Volver</span>";
            echo "<i class='fas fa-arrow-left'></i>";
            echo "</button>";
            echo "</a>";
            echo "</div>";


            echo "</div>"; // Cierre del div contenedorcentrado
            echo "</div>"; // Cierre del div contenedor
        }

    } else {
        // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
        header("Location: login.php");
        exit(); // Asegura que el script se detenga después de redirigir
    }
    ?>
</body>
</html>
