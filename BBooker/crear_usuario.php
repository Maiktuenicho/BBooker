<?php
    /* Ejecutamos solo una vez la conexion */
    require_once 'conexion.php';
   ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Crear</title>
        <meta charset="UTF-8">
        <meta name="author" content="Agustín Mercado Prieto" />
        <meta name="copyright" content="Agustín Mercado Prieto" />
        <meta name="description" content="Creación de productos para CRUD en PHP" />
        <meta name="keyword" content="PHP,CRUD,Crear,Productos,Cocina" />
        <link rel="stylesheet" href="../ESTILOS_PROYECTO/update.css">
        <style>
        body {
            font-family: 'Overpass', sans-serif;
            font-weight: normal;
            font-size: 100%;
            color: #1b262c;
            margin: 0;
            background-color: #5f6769;
        }

        #login h3 {
            text-align: center;
            margin-bottom: 5px; 
            margin-top: 0px;
        }

        #contenedor {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            /*background-image: url(fondo_difuminado_login.jpg);*/
            background-position: center;
            background-size: cover;
        }

        #contenedorcentrado {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            width: 90%;
            border-radius: 10px;
            padding: 30px;
            box-sizing: border-box;
            opacity: 1;
        }

        #login {
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

        #login label {
            display: block;
            font-size: 120%;
            color: #3c4245;
            margin-top: 5px;
        }


        #login input {
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

        #login input::placeholder {
            color: #E4E4E4;
        }

        #login button, #login input[type="submit"] {
            font-size: 110%;
            color: #1b262c;
            width: 100%;
            height: 40px;
            border: none;
            border-radius: 3px;
            background-color: #dfcdc3;
            margin-top: 5px;
        }

        #login button:hover, #login input[type="submit"]:hover {
            background-color: #3c4245;
            color: #dfcdc3;
        }
        </style>
    </head>
    <body>
        <div id="contenedor">
            <div id="contenedorcentrado">
                <div id="login">
                        <h3>CREAR</h3>
                        <form name="insertar" id="insertar" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <table>
                                    <tr>
                                        <td>
                                        <label for="tabla">Usuario: </label>
                                                <input type="text" name="usuario" id="usuario" size="15" title="Introduzca el nombre de usuario" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="tabla">Nombre: </label>
                                            <input type="text" name="nombre" id="nombre" size="15" title="Intrdouzca su nombre completo" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="email">Correo: </label>
                                            <input type="text" class="form__input" name="email" pattern="[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" size="25" maxlength="50" title="Introduce tu correo electónico." required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for ="numero">Teléfono:</label>
                                            <input type="text" class="form__input" name="numero" pattern="[6-9][0-9]{2}( ?[0-9]{3} ?[0-9]{3}| ?[0-9]{2} ?[0-9]{2} ?[0-9]{2})" size="7" maxlength="30" title="Introduce tu número. Ej: 656785935 | 600 123 456 | 910 12 34 56" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for ="pass">Contraseña:</label>
                                            <input type="password" name="pass"  class="form__input"  size="15" maxlength="70" title="Introduce una contraseña" required>
                                        </td>
                                    </tr>
                                </table>
                                <br>
                                <div class="center">
                                    <input type="submit" name="insertar" value="DARSE DE ALTA" class='login' style='vertical-align:middle'>
                                </div>
                        </form>
                        <?php

                        //pattern="(?=.*\d)(?=.*[$@#$!%*?&])(?=.*[a-z])(?=.*[A-Z]).{8,}"
                        if (isset($_POST['insertar'])) {
                            $usuario = trim($_POST['usuario']);
                            $nombre = trim($_POST['nombre']);
                            $email = trim($_POST['email']);
                            $numero = trim($_POST['numero']);
                            $password = trim($_POST['pass']);
                        
                            try {
                                // Insertar usuario
                                $insert = "INSERT INTO usuarios(usuario, contraseña, nombre_completo, correo, telefono)
                                            VALUES ('$usuario','$password','$nombre','$email', '$numero')";
                                $result = $conex->query($insert);
                        
                                echo "<h4>Usuario y contraseña guardados con éxito</h4>";
                            } catch (mysqli_sql_exception $error_insert) {
                                echo "Error al insertar los datos: " . $error_insert->getMessage();
                            }
                        }                    
                        ?>
                            <a href='login.php' class="center">
                                    <button class='button' style='vertical-align:middle'>
                                        <span>VOLVER</span>
                                    </button>
                            </a>
                </div>
            </div>
        </div>
    </body>
</html>