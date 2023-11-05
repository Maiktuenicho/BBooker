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
            $mail->addAddress('agustinmp23@hotmail.com', 'Agustin'); //Add a recipient

            // Content
            $mail->isHTML(true);                    //Set email format to HTML
            $mail->Subject = 'Recuperacion de contrase&ntilde;a';
            $mail->Body    = 'Buenas, este es un correo generado automaticamente para la recuperacion de contrase&ntildea. 
                                Por favor ingrese en el siguiente enlace 
                                <a href="http://iaweb.local.com/PRUEBAS/PRUEBAS_2_PROYECTO/cambiar_pass.php?id='.$row['id_usuario'].'">Recuperaci&oacuten de contrase&ntildea</a> ';
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
        echo $mensaje_recuperar = "Correo no encontrado. Verifica la dirección de correo e intenta nuevamente.";
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
    <style>
        body {
            font-family: 'Overpass', sans-serif;
            font-weight: normal;
            font-size: 100%;
            color: #1b262c;
            margin: 0;
            background-color: #5f6769;
        }

        #recuperar {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        #formulario-recuperar {
            width: 100%;
            max-width: 345px;
            min-width: 345px;
            padding: 30px;
            background-color: #719192;
            box-shadow: 0px 0px 5px 5px rgba(0,0,0,0.15);
            border-radius: 3px;
            box-sizing: border-box;
            opacity: 1;
        }

        #formulario-recuperar label {
            display: block;
            font-size: 120%;
            color: #3c4245;
            margin-top: 5px;
        }

        #formulario-recuperar input {
            font-size: 110%;
            color: #1b262c;
            display: block;
            width: 100%;
            height: 40px;
            margin-bottom: 10px;
            padding: 5px 10px;
            box-sizing: border-box;
            border: none;
            border-radius: 3px;
        }

        #formulario-recuperar input::placeholder {
            color: #E4E4E4;
        }

        #formulario-recuperar button {
            font-size: 110%;
            color: #1b262c;
            width: 100%;
            height: 40px;
            border: none;
            border-radius: 3px;
            background-color: #dfcdc3;
            margin-top: 5px;
        }

        #formulario-recuperar button:hover {
            background-color: #3c4245;
            color: #dfcdc3;
        }
    </style>
</head>
<body>
    <div id="recuperar">
        <div id="formulario-recuperar">
            <form method="post">
                <label for="email_recuperar">Ingresa tu correo para recuperar la contraseña</label>
                <input id="email_recuperar" type="email" name="email_recuperar" placeholder="Correo" required>

                <button type="submit" title="Recuperar" name="recuperar">Recuperar Contraseña</button>

                <?php
                    if(isset($_GET['message'])){
                    
                    ?>
                        <div class="alert alert-primary" role="alert">
                            <?php
                                // 
                                switch ($_GET['message']) {
                                    case 'OK':
                                        echo '<br>';
                                        echo 'Revisa tu correo porfi';
                                        break;
                                    
                                    default:
                                        echo '<br>';
                                        echo 'Algo salió mal, intentelo de nuevo';
                                        break;
                                }
                            ?>
                        </div>
                <?php
                    }
                ?>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
