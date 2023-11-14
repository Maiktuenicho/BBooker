<?php
// carga manual de los path de configuración
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

// Verificar si se enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["recuperar"])) {
    $email_recuperar = $_POST["email_recuperar"];

    // Conectar a la base de datos
    require_once 'conexion.php';

    // Verificar si el correo existe en la base de datos
    $consulta = "SELECT id_usuario FROM usuarios WHERE correo = '$email_recuperar'";
    $resultado = $conex->query($consulta);
    $row = $resultado->fetch_assoc();

    if ($resultado->num_rows > 0) {
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor
            $mail->isSMTP();                        //Send using SMTP
            $mail->Host       = 'smtp-mail.outlook.com'; //Set the SMTP server to send through
            $mail->SMTPAuth   = true;               //Enable SMTP authentication
            $mail->Username   = 'basketbooker@outlook.com'; //SMTP username
            $mail->Password   = '#Baloncesto23';    //SMTP password
            $mail->Port       = 587;                //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            // Información para envío de correos con outlook (gmail da muchos problemas)
            $mail->setFrom('basketbooker@outlook.com', 'Admin');
            $mail->addAddress($email_recuperar); ; //Add a recipient

            // Content
            $mail->isHTML(true);                    //Set email format to HTML
            $mail->Subject = 'Recuperacion de password';
            $mail->Body    = 'Buenas, este es un correo generado automaticamente para la recuperacion de contrase&ntildea. 
                                Por favor ingrese en el siguiente enlace 
                                <a href="http://iaweb.local.com/BBoker/cambiar_pass.php?id='.$row['id_usuario'].'">Recuperaci&oacuten de contrase&ntildea</a> ';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            header("Location: recuperar.php?message=OK");
            exit(); // Agregado para terminar la ejecución después de la redirección exitosa
        } 
        catch (Exception $e) {
            header("Location: recuperar.php?message=ERROR");
            exit(); // Agregado para terminar la ejecución después de la redirección de error
        }

    } else {
        // El correo no existe en la base de datos
        $mensaje_recuperar = "Correo no encontrado. Verifica la dirección de correo e intenta nuevamente.";
    }

    // Cerrar la conexión a la base de datos
    $conex->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <!-- Agregamos el enlace a Font Awesome para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Estilos generales */
        body {
            font-family: 'Overpass', sans-serif;
            font-weight: normal;
            font-size: 100%;
            color: #464645;
            margin: 0;
            background-color: #F4F4F4;
        }

        /* Estilos del contenedor principal */
        #contenedor {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        /* Estilos del contenedor central */
        #contenedorcentrado {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            min-width: 380px;
            max-width: 380px;
            width: 90%;
            background-color: #F4F4F4;
            border-radius: 10px 10px 10px 10px;
            -moz-border-radius: 10px 10px 10px 10px;
            -webkit-border-radius: 10px 10px 10px 10px;
            -webkit-box-shadow: 0px 0px 5px 5px rgba(0, 0, 0, 0.15);
            -moz-box-shadow: 0px 0px 5px 5px rgba(0, 0, 0, 0.15);
            box-shadow: 0px 0px 5px 5px rgba(0, 0, 0, 0.15);
            padding: 30px;
            box-sizing: border-box;
        }

        /* Estilos del formulario de recuperar */
        #formulario-recuperar {
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
        }

        /* Estilos de etiquetas en el formulario */
        #formulario-recuperar label {
            display: block;
            font-size: 120%;
            color: #F4F4F4;
            margin-top: 15px;
        }

        /* Estilos de campos de entrada en el formulario */
        #formulario-recuperar input {
            font-size: 110%;
            color: #464645;
            display: block;
            width: 100%;
            height: 40px;
            margin-bottom: 10px;
            padding: 5px 5px 5px 10px;
            box-sizing: border-box;
            border: none;
            border-radius: 3px 3px 3px 3px;
            -moz-border-radius: 3px 3px 3px 3px;
            -webkit-border-radius: 3px 3px 3px 3px;
        }

        /* Estilos del texto de marcador de posición en el formulario */
        #formulario-recuperar input::placeholder {
            color: #E4E4E4;
        }

        /* Estilos del botón de recuperar en el formulario */
        #formulario-recuperar button, #formulario-recuperar a.volver {
            font-size: 110%;
            color: #1b262c;
            background-color: #dfcdc3;
            width: 100%;
            height: 40px;
            border: none;
            border-radius: 3px;
            margin-top: 10px;
            position: relative;
            display: flex;
            align-items: center; /* Centramos verticalmente */
            justify-content: center; /* Centramos horizontalmente */
            overflow: hidden;
            text-decoration: none; /* Quita el subrayado del enlace */
            transition: background-color 0.3s, color 0.3s;
        }

        /* Estilos del botón de recuperar en estado hover */
        #formulario-recuperar button:hover, #formulario-recuperar a.volver:hover {
            background-color: #3c4245;
            color: #dfcdc3;
        }

        /* Estilos del icono dentro del botón */
        #formulario-recuperar button i, #formulario-recuperar a.volver i {
            margin-right: 5px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        /* Estilos del icono dentro del botón de recuperar en estado hover */
        #formulario-recuperar button:hover i.recuperar-icon, #formulario-recuperar a.volver:hover i.volver-icon {
            opacity: 1;
        }

        /* Estilos del mensaje de resultado */
        .alert-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50px;
        }

        .alert {
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Contenedor principal -->
    <div id="contenedor">
        <!-- Contenedor central -->
        <div id="contenedorcentrado">
            <!-- Formulario de recuperar contraseña -->
            <div id="formulario-recuperar">
                <form method="post">
                    <!-- Campo de entrada para el correo -->
                    <label for="email_recuperar">Correo</label>
                    <input id="email_recuperar" type="email" name="email_recuperar" placeholder="Tu correo electrónico" required>
                    <!-- Botón para recuperar contraseña -->
                    <button type="submit" name="recuperar">
                        <i class="fas fa-sync-alt recuperar-icon"></i> <span>Recuperar Contraseña</span>
                    </button>
                    <!-- Enlace para volver -->
                    <a href="login.php" class="volver">
                        <i class="fas fa-arrow-left volver-icon"></i> <span>Volver</span>
                    </a>
                    <!-- Mensaje de resultado -->
                    <?php
                        if(isset($_GET['message'])){
                            $mensaje = '';
                            switch ($_GET['message']) {
                                case 'OK':
                                    $mensaje = 'Revisa tu correo por favor';
                                    $class = 'alert-primary';
                                    break;
                                default:
                                    $mensaje = 'Algo salió mal, inténtelo de nuevo';
                                    $class = 'alert-danger';
                                    break;
                            }

                            echo '<div class="alert-container">';
                            echo '<div class="alert ' . $class . '" role="alert">';
                            echo '<br>';
                            echo $mensaje;
                            echo '</div>';
                            echo '</div>';
                        } elseif(isset($mensaje_recuperar)) {
                            echo '<div class="alert-container">';
                            echo '<div class="alert alert-danger" role="alert">';
                            echo '<br>';
                            echo $mensaje_recuperar;
                            echo '</div>';
                            echo '</div>';
                        }
                    ?>
                </form>
            </div>
        </div>
    </div>
</body>
</html>