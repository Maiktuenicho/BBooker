<?php
    // Inicia la sesión si no está iniciada
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Verifica si hay un usuario en la sesión
    if(isset($_SESSION["usuario"])) {
        // Obtén el usuario de la sesión
        $user = $_SESSION["usuario"];

        // Ejecuta la consulta para obtener la información del usuario
        require_once 'conexion.php';
        try{
            $result = $conex->query("SELECT usuario, nombre_completo, correo, telefono FROM usuarios WHERE correo='$user'");
        } catch(mysqli_sql_exception $error_select){
            echo "Error al recuperar los productos" . $error_select->__toString();
        }
    } else {
        // Si no hay usuario en la sesión, realiza alguna acción (redireccionar, mostrar un mensaje, etc.)
        echo "No hay usuario en la sesión.";
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Perfil</title>
        <meta charset="UTF-8">
        <meta name="author" content="Agustín Mercado Prieto" />
        <meta name="copyright" content="Agustín Mercado Prieto" />
        <meta name="description" content="Listado de CRUD en PHP" />
        <meta name="keyword" content="PHP,CRUD,Productos,Cocina" />
        <style>
            /* Estilos CSS */

           /* Estilo para el cuerpo de la página */
            body {
                font-family: 'Overpass', sans-serif;
                font-weight: normal;
                font-size: 100%;
                color: #464645;
                margin: 0;
                background-color: #F4F4F4;
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
            .th {
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
            .td {
                font-family: 'Gabriola';
                font-weight: bolder;
                font-size: 22px;
                background-color: #C3BEB1;
                border-bottom-width: 2px;
                border-bottom-style: solid;
                border-bottom-color: #464645;
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
                width: 120px;
            }

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

            .button-link button:hover {
                background-color: #3c4245;
            }

            header {
            background-color: #9A8A62;
            color: white;
            padding: 5px;
            text-align: center;
            }

            h1 {
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
        </style>

        <!-- Enlace a la hoja de estilos de Font Awesome -->
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

                    $consulta_usuario = $conex->query("SELECT usuario FROM usuarios WHERE correo = '$correo';");
                    $usuario = $consulta_usuario->fetch_row();
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

        <!-- Contenedor principal -->
        <div class="contenedor">
            <!-- Contenedor centrado -->
            <div class="contenedorcentrado">
                <!-- Título del contenedor centrado -->
                <h1>MI USUARIO</h1>

                <!-- Tabla para mostrar la información del usuario -->
                <table border="0">
                    <?php
                        //Vamos a recorrer las lineas que nos devuelve la query con la condicion de "Mientras recorrer las filas de la consulta de datos" mostralos
                        while ($filas = $result->fetch_row()){
                            echo "<tr>";
                            echo "  <th class='center th'>USUARIO</td>";
                            echo "  <td class='center td'>".$filas[0]."</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "  <th class='center th'>Nombre completo</td>";
                            echo "  <td class='center td'>".$filas[1]."</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "  <th class='center th'>Correo</td>";
                            echo "  <td class='center td'>".$filas[2]."</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "  <th class='center th'>Teléfono</td>";
                            echo "  <td class='center td'>".$filas[3]."</td>";
                            echo "</tr>";
                        } 
                    ?>
                </table>
                <!-- Botón para actualizar -->
                <a href='actualizar.php' class='button-link'>
                    <button class='button update-button'>
                        <span>Actualizar</span>
                        <i class='fas fa-sync-alt'></i> 
                    </button>
                </a>

                <!-- Botón para volver -->
                <a href='inicio.php' class='button-link'>
                    <button class='button back-button'>
                        <span>Volver</span>
                        <i class='fas fa-arrow-left'></i> 
                    </button>
                </a>
            </div>
        </div>
    </body>
</html>
