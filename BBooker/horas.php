<?php
// Iniciar la sesión
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horas de Reserva</title>
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

        h2 {
            text-align: center;
            color: #333;
        }

        .actions-column.disabled {
            cursor: not-allowed;
            background-color: #444; /* Cambiado a negro */
            color: white; /* Cambiado a blanco */
        }


        .actions-column, .actions-column.disabled {
            cursor: pointer;
            padding: 10px 20px; /* Ajusta el padding para que se vea como un botón */
            width: 150px; /* Ajusta el ancho con la separación */
            height: 10px;
            text-align: center;
            font-weight: bold;
            background-color: white;
            border-radius: 5px;
            margin-right: 10px; /* Agrega espacio entre los botones */
            margin-bottom: 10px; /* Agrega espacio debajo de los botones */
        }

        .actions-column:hover {
            background-color: #dfcdc3;
        }

        .actions-column.disabled {
            cursor: not-allowed;
            background-color: #444; /* Cambiado a negro */
            color: white; /* Cambiado a blanco */
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
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
            transition: background-color 0.3s ease;
        }

        #volver-atras button:hover {
            background-color: #7B6B4C;
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

        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .row > div {
            margin-right: 10px;
            margin-bottom: 10px;
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

    <?php
        // Conectar a la base de datos
        require_once 'conexion.php';

        // Obtener la fecha y pista seleccionadas
        $fecha_elegida = isset($_GET['fecha']) ? $_GET['fecha'] : null;
        $pista_elegida = isset($_GET['pista']) ? $_GET['pista'] : null;

        // Obtener el ID de la pista
        $id_pista = isset($_GET['id_pista']) ? $_GET['id_pista'] : null;

        // Obtener el nombre de la pista
        $query_nombre_pista = "SELECT nombre_pista FROM pistas WHERE id_pista = '$pista_elegida'";
        $result_nombre_pista = $conex->query($query_nombre_pista);

        // Verificar si la consulta fue exitosa
        if ($result_nombre_pista) {
            $row_nombre_pista = $result_nombre_pista->fetch_assoc();
            $nombre_pista = $row_nombre_pista['nombre_pista'];

            // Consulta para obtener todas las reservas con información del usuario para la pista y fecha seleccionadas
            $query = "SELECT r.hora_inicio, r.fecha
                      FROM reservas r 
                      RIGHT JOIN usuarios u ON r.id_usuario = u.id_usuario
                      WHERE r.id_pista = '$pista_elegida' AND r.fecha = '$fecha_elegida'";

            // Ejecutar la consulta
            $result = $conex->query($query);

            // Verificar si la consulta fue exitosa
            if ($result) {
                // Crear un array asociativo para almacenar todas las horas por fecha
                $horas_reservadas = [];

                // Obtener las horas reservadas desde el resultado de la consulta
                while ($row = $result->fetch_assoc()) {
                    // Obtener la información de la reserva
                    $hora_inicio = $row['hora_inicio'];
                    $fecha_reserva = $row['fecha'];

                    // Agregar la hora de inicio de la reserva al array
                    $horas_reservadas[] = ['hora' => $hora_inicio, 'fecha' => $fecha_reserva];
                }

                echo "<h2>Horas de Reserva para $nombre_pista</h2>";

                echo "<div class='row'>";

                for ($hour = 8; $hour <= 23; $hour++) {
                    $current_time = sprintf("%02d:00", $hour);
                    $status = 'Disponible';
                    $class = '';

                    foreach ($horas_reservadas as $reserva) {
                        $reserva_hora = $reserva['hora'];

                        if (strtotime($reserva_hora) == strtotime($current_time)) {
                            $status = 'Reservada';
                            $class = 'reserved';
                            break;
                        }
                    }

                    echo "<div class=\"$class\">";
                    echo "<div class='actions-column";
                    if ($status === 'Reservada') {
                        echo " disabled";
                    }
                    echo "' onclick=\"";
                    if ($status !== 'Reservada') {
                        echo "location.href='procesar_reserva.php?fecha=$fecha_elegida&hora=$current_time&pista=$pista_elegida&nombre_pista=$nombre_pista&id_pista=$id_pista'";
                    }
                    echo "\">";
                    if ($status === 'Disponible') {
                        echo "$current_time";
                    } else {
                        echo "RESERVADA";
                    }
                    echo "</div>";
                    echo "</div>";
                }

                echo "</div>";

                $result->free();
            } else {
                echo "Error de consulta: " . $conex->error;
            }
        } else {
            echo "Error de consulta para obtener el nombre de la pista: " . $conex->error;
        }

        $conex->close();
    ?>

    <div id="volver-atras">
        <button onclick="window.history.back()">
            <span>Volver Atrás</span>
            <i class="fa fa-solid fa-arrow-left"></i>
        </button>
    </div>
</body>
</html>
