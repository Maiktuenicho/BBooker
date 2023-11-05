<?php

// carga manual de los path de configuración
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../../PHPMailer/Exception.php';
require '../../PHPMailer/PHPMailer.php';
require '../../PHPMailer/SMTP.php';

require_once 'conexion.php';

// Inicia la sesión
session_start();

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

        // Resto del código...
    } else {
        echo "No se encontró la pista con el ID proporcionado.";
    }
} else {
    // Manejar errores de consulta para obtener el nombre_pista
    echo "Error de consulta para obtener el nombre_pista: " . $conex->error;
}

// Obtener el ID_USUARIO de la sesión
if (isset($_SESSION['usuario'])) {
    $correo_usuario = $_SESSION['usuario'];

    // Consulta para obtener el ID_USUARIO
    $query_id_usuario = "SELECT id_usuario FROM usuarios WHERE correo = '$correo_usuario'";
    $result_id_usuario = $conex->query($query_id_usuario);

    if ($result_id_usuario) {
        $row_id_usuario = $result_id_usuario->fetch_assoc();
        $id_usuario = $row_id_usuario['id_usuario'];

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
            $mail->addAddress('agustinmp23@hotmail.com', 'Agustin'); //Add a recipient

            $mail->Subject = 'Reserva realizada';
            $mail->Body = "Se ha realizado una reserva para la fecha $fecha_reserva y hora $hora_reserva en la pista $nombre_pista.";

            if ($mail->send()) {
                echo "Reserva realizada con éxito.";
                echo "<br>";
                echo "Se ha enviado un correo de confirmación.";
                echo "<br>";
                echo "<a href='inicio.php'>Volver a la página de inicio</a>"; // Agregado el enlace para volver a inicio.php
            } else {
                echo "Error al enviar el correo: " . $mail->ErrorInfo;
            }
        } else {
            echo "Error al procesar la reserva: " . $conex->error;
        }
    } else {
        echo "Error al obtener el ID_USUARIO: " . $conex->error;
    }
} else {
    echo "Error: No se ha iniciado sesión.";
}

// Cerrar la conexión
$conex->close();
?>
