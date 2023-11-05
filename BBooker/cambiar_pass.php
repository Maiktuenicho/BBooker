<?php
// Inicializar variables
$mensaje_exito = $mensaje_error = $mensaje_contraseña_repetida = '';

// Verifica si se recibió el parámetro 'id' en la URL
if (isset($_GET['id'])) {
    $usuario_id = $_GET['id'];

    // Lógica para cambiar la contraseña del usuario con ID $usuario_id

    require_once 'conexion.php';

    // Verificar si se enviaron los datos del formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cambiar_contraseña"])) {
        $nueva_contraseña = $_POST["nueva_contraseña"];
        $confirmar_contraseña = $_POST["confirmar_contraseña"];

        // Verificar si las contraseñas coinciden
        if ($nueva_contraseña == $confirmar_contraseña) {
            // Verificar si la nueva contraseña es diferente a la guardada en la base de datos
            $consulta_verificar = "SELECT contraseña FROM usuarios WHERE id_usuario = $usuario_id";
            $resultado_verificar = $conex->query($consulta_verificar);
            $fila_verificar = $resultado_verificar->fetch_assoc();
            $contraseña_guardada = $fila_verificar['contraseña'];

            if ($nueva_contraseña != $contraseña_guardada) {
                $consulta_update = "UPDATE usuarios SET contraseña = '$nueva_contraseña' WHERE id_usuario = $usuario_id";
                $conex->query($consulta_update);

                // Cierra la conexión a la base de datos 
                $conex->close();

                $mensaje_exito = "Contraseña cambiada con éxito. Ahora puedes iniciar sesión con tu nueva contraseña.";
            } else {
                $mensaje_contraseña_repetida = "No se puede utilizar una contraseña utilizada anteriormente.";
            }
        } else {
            $mensaje_error = "Las contraseñas no coinciden. Por favor, inténtalo de nuevo.";
        }
    }
} else {
    // Si no se proporciona el parámetro 'id', muestra un mensaje de error
    $mensaje_error = "No se proporcionó un ID de usuario válido.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña</title>
    <style>
        body {
            font-family: 'Overpass', sans-serif;
            font-weight: normal;
            font-size: 100%;
            color: #1b262c;
            margin: 0;
            background-color: #5f6769;
        }

        #cambiar-contraseña {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        #formulario-cambiar-contraseña {
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

        #formulario-cambiar-contraseña label {
            display: block;
            font-size: 120%;
            color: #3c4245;
            margin-top: 5px;
        }

        #formulario-cambiar-contraseña input {
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

        #formulario-cambiar-contraseña input::placeholder {
            color: #E4E4E4;
        }

        #formulario-cambiar-contraseña button {
            font-size: 110%;
            color: #1b262c;
            width: 100%;
            height: 40px;
            border: none;
            border-radius: 3px;
            background-color: #dfcdc3;
            margin-top: 5px;
        }

        #formulario-cambiar-contraseña button:hover {
            background-color: #3c4245;
            color: #dfcdc3;
        }

        #mensaje-exito {
            font-family: 'Overpass', sans-serif;
            font-weight: normal;
            font-size: 100%;
            color: #1b262c;
            margin: 0;
            text-align: center;
        }

        #ir-a-login a {
            display: inline-block;
            font-size: 110%;
            color: #1b262c;
            width: 100%;
            height: 40px;
            border: none;
            border-radius: 3px;
            background-color: #dfcdc3;
            margin-top: 5px;
            text-align: center;
            line-height: 40px;
            text-decoration: none;
        }

        #ir-a-login a:hover {
            background-color: #3c4245;
            color: #dfcdc3;
        }
    </style>
</head>
<body>
    <div id="cambiar-contraseña">
        <div id="formulario-cambiar-contraseña">
            
            <form method="post" action="cambiar_pass.php?id=<?php echo $usuario_id; ?>">
                <label for="nueva_contraseña">Nueva Contraseña</label>
                <input id="nueva_contraseña" type="password" name="nueva_contraseña" placeholder="Nueva Contraseña" required>

                <label for="confirmar_contraseña">Confirmar Contraseña</label>
                <input id="confirmar_contraseña" type="password" name="confirmar_contraseña" placeholder="Confirmar Contraseña" required>

                <button type="submit" title="Cambiar Contraseña" name="cambiar_contraseña">Cambiar Contraseña</button>
            </form>

            <?php
                // Mostrar mensajes según la situación
                if ($mensaje_exito != '') {
                    echo '<div id="ir-a-login"><a href="login.php">Ir a la página de inicio de sesión</a></div>';
                    echo '<br><br>';
                    echo '<div id="mensaje-exito">' . $mensaje_exito . '</div>';
                } elseif ($mensaje_contraseña_repetida != '') {
                    echo '<div id="mensaje-error">' . $mensaje_contraseña_repetida . '</div>';
                } elseif ($mensaje_error != '') {
                    echo '<div id="mensaje-error">' . $mensaje_error . '</div>';
                }
            ?>

        </div>
    </div>
</body>
</html>
