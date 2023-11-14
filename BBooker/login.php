
<?php
// Definir variables para mensajes
$mensaje = '';

// Verificar si se enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Conectar a la base de datos
    require_once 'conexion.php';

    // Consulta SQL para verificar el correo y obtener el ID de usuario
    $consulta = "SELECT id_usuario, contraseña FROM usuarios WHERE correo = '$email'";
    $resultado = $conex->query($consulta);

    if ($resultado->num_rows > 0) {
        // Usuario encontrado, obtener los datos
        $fila = $resultado->fetch_assoc();
        $id_usuario = $fila["id_usuario"];
        $contrasena_db = $fila["contraseña"];

        // Comparar la contraseña en texto plano (Cambiar cuando pueda para que compare con md5)
        if ($password == $contrasena_db) {
            // Contraseña válida, iniciar sesión y redirigir
            session_start();
            $_SESSION["usuario"] = $email;
            session_write_close(); // Cerrar la sesión para evitar problemas
            header("Location: inicio.php");
            exit(); // Asegurarse de que no haya salida adicional después de la redirección
        } else {
            // En caso que la contraseña no coincida con la guardada en la base de datos
            $mensaje = "Credenciales incorrectas. Intente nuevamente.";
        }
    } else {
        // En caso que el usuario no esté guardado en la base de datos
        $mensaje = "Usuario no encontrado. Intente nuevamente.";
    }

    // Cerrar la conexión a la base de datos
    $conex->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Iniciar Sesión</title>
    <style>
        /* efectos por defecto */
        body {
            font-family: 'Overpass', sans-serif;
            font-weight: normal;
            font-size: 100%;
            color: #1b262c;
            margin: 0;
            background-color: #F4F4F4;
        }

        /* efectos para el contenedor de pantalla completa */
        #contenedor {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 0;
            min-width: 100vw;
            min-height: 100vh;
            width: 100%;
            height: 100%;
            background-image: url(../imagenes/fondo_difuminado_login.jpg);
            background-position: center;
            background-size: cover;
        }

        /* contenedor del login a la izquierda */
        #contenedorcentrado {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: row;
            min-width: 380px;
            max-width: 900px;
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

        /* formulario de login */
        #login {
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

        #login label {
            display: block;
            font-family: 'Overpass', sans-serif;
            font-size: 120%;
            color: #F4F4F4;
            margin-top: 15px;
        }

        #login input {
            font-family: 'Overpass', sans-serif;
            font-size: 110%;
            color: #464645; /* Cambiado a #464645 */
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

        #login input::placeholder {
            font-family: 'Overpass', sans-serif;
            color: #E4E4E4;
        }

        #login button {
            font-family: 'Overpass', sans-serif;
            font-size: 110%;
            color: #1b262c;
            width: 100%;
            height: 40px;
            border: none;
            border-radius: 3px 3px 3px 3px;
            -moz-border-radius: 3px 3px 3px 3px;
            -webkit-border-radius: 3px 3px 3px 3px;
            background-color: #dfcdc3;
            margin-top: 10px;
        }

        #login button:hover {
            background-color: #3c4245;
            color: #dfcdc3;
        }

        /* seccion de la derecha */
        #derecho {
            text-align: center;
            width: 100%;
            opacity: 0.70;
            filter: alpha(opacity=70);
            padding: 20px 20px 20px 50px;
            box-sizing: border-box;
        }

        .titulo {
            font-size: 300%;
            color: #464645; /* Cambiado a #464645 */
        }

        hr {
            border-top: 1px solid #8c8b8b;
            border-bottom: 1px solid #dfcdc3;
        }

        .pie-form {
            font-size: 90%;
            text-align: center;
            margin-top: 15px;
        }

        .pie-form a {
            display: block;
            text-decoration: none;
            color: #464645; /* Cambiado a #464645 */
            margin-bottom: 3px;
        }

        .pie-form a:hover {
            color: #263133;
            font-weight: bolder;
        }

        /* ajustar a pantallas con ancho menor o igual a 775px; */
        @media all and (max-width: 775px) {
            #contenedorcentrado {
                flex-direction: column-reverse;
                min-width: 380px;
                max-width: 900px;
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

            #login {
                margin: 0 auto;
            }

            #derecho {
                padding: 20px 20px 20px 20px;
            }

            #login label {
                text-align: left;
            }
        }

        #mensaje-error {
            font-weight: bold;
        }

        #mensaje-error p {
            text-align: center;
        }


    </style>
</head>
<body>
    <div id="contenedor">
        <div id="contenedorcentrado">
            <div id="login">
                <form id="loginform" method="post">
                    <label for="email">Correo</label>
                    <input id="email" type="email" name="email" placeholder="Correo" required>
                    
                    <label for="password">Contraseña</label>
                    <input id="password" type="password" placeholder="Contraseña" name="password" required>
                    
                    <button type="submit" title="Ingresar" name="Ingresar">Login</button>
                </form>
                <!-- Sección para mostrar mensajes de error solo después de enviar el formulario -->
                <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                    <div id="mensaje-error">
                        <p><?php echo $mensaje; ?></p>
                    </div>
                <?php endif; ?>
            </div>
            <div id="derecho">
                <div class="titulo">
                    Bienvenido
                </div>
                <hr>
                <div class="pie-form">
                    <a href="recuperar.php">¿Perdiste tu contraseña?</a>
                    <a href="crear_usuario.php">¿No tienes Cuenta? Registrate</a>
                    <hr>
                </div>
            </div>
        </div>
    </div>
</body>
</html>