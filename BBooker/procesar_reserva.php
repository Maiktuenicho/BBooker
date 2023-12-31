<?php
// carga manual de los path de configuración
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

require_once 'conexion.php';

// Inicia la sesión
session_start();

// Verifica si el usuario ha iniciado sesión
if (isset($_SESSION['usuario'])) {
    $correo_usuario = $_SESSION['usuario'];

    // Consulta para obtener el ID_USUARIO
    $query_id_usuario = "SELECT id_usuario FROM usuarios WHERE correo = '$correo_usuario'";
    $result_id_usuario = $conex->query($query_id_usuario);

    if ($result_id_usuario) {
        $row_id_usuario = $result_id_usuario->fetch_assoc();
        $id_usuario = $row_id_usuario['id_usuario'];

        // Resto del código...
    } else {
        echo "Error al obtener el ID_USUARIO: " . $conex->error;
    }
} else {
    echo "Error: No se ha iniciado sesión.";
    exit(); // Detener el script si no se ha iniciado sesión
}

// Obtener la fecha y hora de la reserva
$fecha_reserva = $_GET['fecha'];
$hora_reserva = $_GET['hora'];
$pista_reserva = $_GET['pista'];

// Obtener el nombre de la pista
$query_nombre_pista = "SELECT nombre_pista FROM pistas WHERE id_pista = '$pista_reserva'";
$result_nombre_pista = $conex->query($query_nombre_pista);

// Verificar si la consulta fue exitosa
if ($result_nombre_pista) {
    // Obtener el resultado como un array asociativo
    $row_nombre_pista = $result_nombre_pista->fetch_assoc();

    // Verificar si se encontró un resultado y si el índice 'nombre_pista' existe
    if ($row_nombre_pista && isset($row_nombre_pista['nombre_pista'])) {
        // Almacenar el valor de 'nombre_pista' en una variable
        $nombre_pista = $row_nombre_pista['nombre_pista'];

        // Insertar reserva en la base de datos
        $query = "INSERT INTO reservas (id_usuario, id_pista, fecha, hora_inicio, hora_fin) 
                  VALUES ('$id_usuario', '$pista_reserva', '$fecha_reserva', '$hora_reserva', '$hora_reserva')";
        $result = $conex->query($query);

        if ($result) {
            // Enviar correo electrónico de confirmación
            $mail = new PHPMailer;

            // Configuración del servidor
            $mail->isSMTP();                        //Send using SMTP
            $mail->Host       = 'smtp-mail.outlook.com'; //Set the SMTP server to send through
            $mail->SMTPAuth   = true;               //Enable SMTP authentication
            $mail->Username   = 'basketbooker@outlook.com'; //SMTP username
            $mail->Password   = '#Baloncesto23';    //SMTP password
            $mail->Port       = 587;                //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            // Información para envío de correos con outlook (gmail da muchos problemas)
            $mail->setFrom('basketbooker@outlook.com', 'Admin');
            $mail->addAddress($correo_usuario); // Correo del usuario

            $mail->Subject = 'Reserva realizada';
            $mail->Body = "Se ha realizado una reserva para la fecha $fecha_reserva y hora $hora_reserva en la pista $nombre_pista.";

            if ($mail->send()) {
                echo "<!DOCTYPE html>";
                echo "<html lang='es'>";
                echo "<head>";
                echo "<meta charset='UTF-8'>";
                echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
                echo "<title>Reserva realizada</title>";
                echo "<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.12.1/css/all.css' crossorigin='anonymous'>";
                echo "<link rel='stylesheet' href='http://iaweb.local.com/BBooker/Estilos/procesar_reserva.css' >";
               
                echo "</head>";
                echo "<body>";
                echo "<header>";
                echo "<h1>";
                echo "Reserva realizada con éxito";
                echo "</h1>";
                echo "</header>";
                echo "<nav>";
                echo "<a id='home-link' href='inicio.php'>";
                echo "<i class='fa fa-solid fa-home'></i>";
                echo "Inicio";
                echo "</a>";
                echo "<a id='perfil-link' href='perfil.php'>";
                echo "<i class='perfil-link fa fa-solid fa-user'></i>";
                echo "Perfil";
                echo "</a>";
                echo "<a id='cerrar-sesion' href='login.php'>";
                echo "<i class='cerrar-sesion fa fa-solid fa-arrow-right'></i>";
                echo "Salir";
                echo "</a>";
                echo "</nav>";
                echo "<h2>Reserva realizada con éxito</h2>";
                echo "<div id='otra-reserva'>";
                echo "<a href='calendario.php' class='button-link'>";
                echo "<button class='button'>";
                echo "<span>¿Quieres hacer otra reserva?</span>";
                echo "<i class='fas fa-arrow-right'></i>";
                echo "</button>";
                echo "</a>";
                echo "</div>";
                echo "<div id='volver-atras'>";
                echo "<a href='inicio.php' class='button-link'>";
                echo "<button class='button back-button'>";
                echo "<span>Volver</span>";
                echo "<i class='fas fa-arrow-left'></i>";
                echo "</button>";
                echo "</a>";
                echo "</div>";
                echo "</body>";
                echo "</html>";
            } else {
                echo "Error al enviar el correo: " . $mail->ErrorInfo;
            }
        } else {
            echo "Error al procesar la reserva: " . $conex->error;
        }
    } else {
        echo "No se encontró la pista con el ID proporcionado.";
    }
} else {
    // Manejar errores de consulta para obtener el nombre_pista
    echo "Error de consulta para obtener el nombre_pista: " . $conex->error;
}

// Cerrar la conexión
$conex->close();
?>
