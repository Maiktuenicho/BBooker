<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["usuario"])) {
    // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location: login.php");
    exit(); // Asegura que el script se detenga después de redirigir
}

// Conexion con la base de datos
require_once 'conexion.php';
require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Variables de reserva
$idReserva = $horaInicio = $horaFin = $fecha = $idPista = "";
$mensaje = ""; // Inicializa la variable de mensaje

// Verificar si se recibió un ID de reserva válido
if (isset($_POST["id_reserva"])) {
    $idReserva = $_POST["id_reserva"];

    // Consulta para obtener los datos actuales de la reserva
    $consultaReserva = $conex->query("SELECT HORA_INICIO, HORA_FIN, FECHA, ID_PISTA FROM reservas WHERE ID_RESERVA = '$idReserva';");

    // Verificar si se encontraron datos
    if ($consultaReserva->num_rows > 0) {
        $row = $consultaReserva->fetch_assoc();

        // Asignar los datos de la reserva a las variables
        $horaInicio = $row["HORA_INICIO"];
        $horaFin = $row["HORA_FIN"];
        $fecha = $row["FECHA"];
        $idPista = $row["ID_PISTA"];
    } else {
        // Si no se encontraron datos, redirigir a la página de mis_reservas.php
        header("Location: mis_reservas.php");
        exit(); // Asegura que el script se detenga después de redirigir
    }
}

// Verificar si se ha enviado el formulario para actualizar la reserva
if (isset($_POST["actualizar"])) {
    $horaInicio = $_POST["hora_inicio"];
    $fecha = $_POST["fecha"];
    $idPista = $_POST["id_pista"];

    // Actualizar reserva en la base de datos
    $query = "UPDATE reservas 
              SET hora_inicio = '$horaInicio', hora_fin = '$horaFin', fecha = '$fecha', id_pista = '$idPista'
              WHERE id_reserva = '$idReserva'";
    $result = $conex->query($query);

    if ($result) {
        // Modificación realizada
        $mensaje = "Modificación realizada";

        $query_nombre = "SELECT nombre_pista FROM pistas WHERE id_pista = '$idPista'";
        $consulta_nombre = $conex->query($query_nombre);
        $row_nombre = $consulta_nombre->fetch_row();
        $nombre_pista = $row_nombre[0];

        // Configuración del servidor SMTP
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host       = 'smtp-mail.outlook.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'basketbooker@outlook.com';
        $mail->Password   = '#Baloncesto23';
        $mail->Port       = 587;

        // Información para envío de correos
        $mail->setFrom('basketbooker@outlook.com', 'Admin');
        $mail->addAddress($_SESSION['usuario']); // Correo del usuario

        $mail->Subject = 'Reserva modificada';
        $mail->Body = "Se ha modificado la reserva existente. La nueva reserva es: \nFecha: $fecha \nHora: $horaInicio \nPista: $nombre_pista.";

        // Enviar correo electrónico de confirmación
        if (!$mail->send()) {
            // Manejar el error si falla el envío del correo
            echo "Error al enviar el correo: " . $mail->ErrorInfo;
        }
    } else {
        // Si ocurrió un error al actualizar, redirigir a la página de mis_reservas.php
        header("Location: mis_reservas.php");
        exit(); // Asegura que el script se detenga después de redirigir
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Reserva</title>
    <style>
        /* Agrega aquí tus estilos CSS */
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

        form {
            text-align: center;
            margin-top: 20px;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #dfcdc3;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        button:hover {
            background-color: #3c4245;
            color: #fff;
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

        /* Agrega aquí tus estilos CSS adicionales */
        .button {
            position: relative;
            overflow: hidden;
            font-weight: bold;
        }

        .button i {
            position: absolute;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            margin-left: 10px;
        }

        .button:hover i {
            opacity: 1;
        }

        .mensaje {
            color: green;
            margin-top: 10px;
        }

    </style>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">
</head>
<body>
    <header>
        <?php
            // Obtener el nombre de usuario desde la sesión
            $correo = $_SESSION["usuario"];

            // Consulta para obtener el nombre de usuario
            $consultaUsuario = $conex->query("SELECT usuario FROM usuarios WHERE correo = '$correo';");
            $usuario = $consultaUsuario->fetch_row()[0];

            echo "<h1>";
            echo "Bienvenido a BBooker $usuario";
            echo "</h1>";
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
    
    <div class="contenedor">
        <form method="post" action="">
            <input type="hidden" name="id_reserva" value="<?php echo $idReserva; ?>">
            
            <label for="hora_inicio">Hora de Inicio:</label>
            <input type="time" id="hora_inicio" name="hora_inicio" value="<?php echo $horaInicio; ?>" min="08:00" max="23:00" title="HORARIOS DE 08:00 a 23:00" required>

            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $fecha; ?>" min="<?php echo date('Y-m-d'); ?>" required>

            <label for="id_pista">Selecciona una pista:</label>
            <select id="id_pista" name="id_pista" required>
                <?php
                    // Obtener lista de pistas existentes
                    $sqlPistas = "SELECT ID_PISTA, NOMBRE_PISTA FROM pistas";
                    $resultPistas = $conex->query($sqlPistas);

                    // Mostrar opciones de pistas desde la base de datos usando fetch_row
                    while ($rowPistas = $resultPistas->fetch_row()) {
                        $selected = ($rowPistas[0] == $idPista) ? "selected" : "";
                        echo "<option value='" . $rowPistas[0] . "' " . $selected . ">" . $rowPistas[1] . "</option>";
                    }
                ?>
            </select>

            <!-- Botón para actualizar reserva con estilos e icono -->
            <button type="submit" name="actualizar" class="button update-button">
                <span>Actualizar Reserva</span>
                <i class="fas fa-sync-alt"></i>
            </button>

            <!-- Mensaje de modificación realizada -->
            <p class="mensaje"><?php echo isset($_POST["actualizar"]) ? $mensaje : ''; ?></p>

            <!-- Agregar botón para volver a mis_reservas.php con estilos e icono -->
            <div id="volver-mis-reservas">
                <a href="mis_reservas.php">
                    <button type="button" class="button return-button">
                        <span>Volver a Mis Reservas</span>
                        <i class="fas fa-arrow-left"></i>
                    </button>
                </a>
            </div>
        </form>
    </div>
</body>
</html>
