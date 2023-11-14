<?php

// carga manual de los path de configuración
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

// Incluir archivo de conexión
require_once 'conexion.php';

// Iniciar la sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

// Definir variable para el mensaje de éxito
$mensajeExito = "";

// Definir variable para el mensaje de duración mínima
$mensajeDuracionMinima = "";

// Antes de verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $horaInicio = isset($_POST['hora_inicio']) ? $_POST['hora_inicio'] : '';
    $horaFin = isset($_POST['hora_fin']) ? $_POST['hora_fin'] : ''; // poner + 3600 para que sea una hora justo despues del inicip
    $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
    $idPistaSeleccionada = isset($_POST['id_pista']) ? intval($_POST['id_pista']) : 0;
    $nombrePista = isset($_POST['nombre_pista']) ? $_POST['nombre_pista'] : '';

    // Validar duración mínima de 1 hora
    $duracionMinima = 3600; // 3600 segundos para 1 hora

    // Declarar las variables de tipo fecha
    $horaInicioTimestamp = strtotime($horaInicio);
    $horaFinTimestamp = strtotime($horaFin);

    if ($horaInicio !== false && $horaFin !== false && $horaFinTimestamp - $horaInicioTimestamp < $duracionMinima) {
        $mensajeDuracionMinima = "La reserva debe ser de al menos 1 hora.";
    } else {
        // Obtener el ID del usuario
        $correo = $_SESSION["usuario"];
        $consultaUsuario = $conex->query("SELECT id_usuario FROM usuarios WHERE correo = '$correo';");
        $idUsuario = $consultaUsuario->fetch_row()[0];
        
        // Insertar la reserva en la base de datos
        $sqlInsertReserva = "INSERT INTO reservas (id_usuario, hora_inicio, hora_fin, fecha, ID_PISTA) 
        VALUES ($idUsuario, '$horaInicio', '$horaFin', '$fecha', $idPistaSeleccionada)";

        if ($conex->query($sqlInsertReserva)) {
            $mensajeExito = "Su reserva se ha realizado con éxito.";

            // Configuración del servidor
            $mail = new PHPMailer(true);
            $mail->isSMTP();                        //Send using SMTP
            $mail->Host       = 'smtp-mail.outlook.com'; //Set the SMTP server to send through
            $mail->SMTPAuth   = true;               //Enable SMTP authentication
            $mail->Username   = 'basketbooker@outlook.com'; //SMTP username
            $mail->Password   = '#Baloncesto23';    //SMTP password
            $mail->Port       = 587;                //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            // Información para envío de correos con outlook (gmail da muchos problemas)
            $mail->setFrom('basketbooker@outlook.com', 'Admin');
            $mail->addAddress('agustinmp23@hotmail.com', 'Tito'); //Add a recipient
            
            //Contenido del mensaje
            $mail->Subject = 'Nueva reserva realizada';
            $mail->Body = 'Se ha realizado una nueva reserva. 
            Detalles: 
               Inicio -> ' . $horaInicio . 
             ' Fin -> ' . $horaFin . '
               Fecha -> ' . $fecha .'
               Pista -> ' .$nombrePista;

            // Enviar el correo electrónico
            if (!$mail->send()) {
                echo "Error al enviar el correo: " . $mail->ErrorInfo;
            }
        } else {
            echo "Error al crear la reserva: " . $conex->error;
        }
    }
}

$sqlPistas = "SELECT ID_PISTA, NOMBRE_PISTA FROM pistas";
$resultPistas = $conex->query($sqlPistas);

$conex->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Reserva</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #5f6769;
            color: white;
            padding: 10px;
            text-align: center;
        }

        nav {
            background-color: #444;
            color: white;
            padding: 10px;
            text-align: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }

        main {
            padding: 20px;
        }

        #cerrar-sesion {
            color: #fff;
            text-decoration: none;
            margin-left: 20px; 
            padding: 8px 16px; 
            border-radius: 4px; 
            background-color: #555; 
            display: inline-block; 
        }

        #cerrar-sesion i {
            font-size: 20px;
            margin-right: 2px;
        }

        h1 {
            text-align: center;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
        }

        button {
            background-color: #555;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #333;
        }

        button[type="submit"] {
            background-color: #555;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: auto; /* Centrar horizontalmente */
            display: block; /* Asegurar que ocupa el ancho completo disponible */
        }

        .boton {
            display: inline-block;
            padding: 10px 20px;
            background-color: #555;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }

        .boton:hover {
            background-color: #333;
        }

    </style>
</head>
<body>
    <header>
        <h1>Reservas de Pistas de Baloncesto</h1>
    </header>

    <nav>
        <a href="calendario.html">Disponibilidad</a>
        <a href="mis_reservas.php">Mis Reservas</a>
        <a href="crear_reserva.php">Crear Reserva</a>
        <a id="cerrar-sesion" href="login.php">
            <i class="cerrar-sesion"></i>Cerrar Sesión
        </a>
    </nav>

    <main>
        <h1>Crear Reserva</h1>

        <!-- Agregar mensaje de éxito -->
        <?php if (!empty($mensajeExito)): ?>
            <p style="color: green;"><?php echo $mensajeExito; ?></p>
        <?php endif; ?>

        <!-- Agregar mensaje de duración mínima -->
        <?php if (!empty($mensajeDuracionMinima)): ?>
            <p style="color: red;"><?php echo $mensajeDuracionMinima; ?></p>
        <?php endif; ?>
        
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="hora_inicio">Hora de Inicio:</label>
            <input type="time" id="hora_inicio" name="hora_inicio" min="08:00" max="23:00" title="HORARIOS DE 08:00 a 23:00" required>

            <label for="hora_fin">Hora de Fin:</label>
            <input type="time" id="hora_fin" name="hora_fin"  required>

            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" min="<?php echo date('Y-m-d'); ?>" required>

            <label for="id_pista">Selecciona una pista:</label>

            <select id="id_pista" name="id_pista" onchange="updateNombrePista(this)" onfocus="updateNombrePista(this)" required>
                <?php
                // Mostrar opciones de pistas desde la base de datos usando fetch_row
                while ($row = $resultPistas->fetch_row()) {
                    echo "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
                }
                ?>
            </select>

            <br><br><br>
            
            <!-- Enviar el dato del nombre de la pista -->
            <input type="hidden" id="nombre_pista" name="nombre_pista" value="">
            
            <button type="submit">Crear Reserva</button> <a href="inicio.php" class="boton">Volver Atrás</a>


        </form>
    </main>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Selecciona el elemento del select
            var select = document.getElementById("id_pista");

            // Actualiza el nombre de la pista al cargar la página
            updateNombrePista(select);

            // Agregar un evento onchange para actualizar el nombre de la pista
            select.addEventListener("change", function() {
                updateNombrePista(select);
            });
        });

        function updateNombrePista(select) {
            var nombrePista = select.options[select.selectedIndex].text;
            document.getElementById("nombre_pista").value = nombrePista;
        }
    </script>
</body>
</html>
