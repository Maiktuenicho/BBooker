<?php
    /* Ejecutamos solo una vez la conexion */
    require_once 'conexion.php';
   ?>
<!DOCTYPE html>
<html lang="es">
    <head>
         <!-- Metadatos del documento -->
         <title>Crear</title>
        <meta charset="UTF-8">
        <meta name="author" content="Agustín Mercado Prieto" />
        <!-- Descripción del contenido del documento -->
        <meta name="description" content="Creación de productos para CRUD en PHP" />
        <!-- Palabras clave relacionadas con el contenido del documento -->
        <meta name="keyword" content="PHP, CRUD, Crear, Productos, Cocina" />
        <!-- Estilos CSS -->
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
        #crear {
            width: 100%;
            max-width: 200px;
            min-width: 320px;
            padding: 10px 30px 50px 30px;
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
        #crear label {
            display: block;
            font-size: 120%;
            color: #F4F4F4;
            margin-top: 10px;
        }

        /* Estilos de campos de entrada en el formulario */
        #crear input {
            font-size: 110%;
            color: #464645;
            display: block;
            width: 100%;
            height: 30px;
            margin-bottom: 5px;
            padding: 5px 5px 5px 10px;
            box-sizing: border-box;
            border: none;
            border-radius: 3px 3px 3px 3px;
            -moz-border-radius: 3px 3px 3px 3px;
            -webkit-border-radius: 3px 3px 3px 3px;
        }

        /* Estilos del texto de marcador de posición en el formulario */
        #crear input::placeholder {
            color: #E4E4E4;
        }

        /* Estilos del botón en el formulario */
        #crear button, #crear a.volver {
            font-size: 110%;
            color: #1b262c;
            background-color: #dfcdc3;
            width: 100%;
            height: 40px;
            border: none;
            border-radius: 3px;
            margin-top: 5px;
            position: relative;
            display: flex;
            align-items: center; /* Centramos verticalmente */
            justify-content: center; /* Centramos horizontalmente */
            overflow: hidden;
            text-decoration: none; /* Quita el subrayado del enlace */
            transition: background-color 0.3s, color 0.3s;
        }

        /* Estilos del botón en estado hover */
        #crear button:hover, #crear a.volver:hover {
            background-color: #3c4245;
            color: #dfcdc3;
        }

        /* Estilos del icono dentro del botón */
        #crear button i, #crear a.volver i {
            margin-right: 5px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        /* Estilos del icono dentro del botón en estado hover */
        #crear button:hover i.crear-icon, #crear a.volver:hover i.volver-icon {
            opacity: 1;
        }

        #crear h3 {
            text-align: center; 
            color: #464645;
        }
    </style>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>
    <body>
        <div id="contenedor">
            <div id="contenedorcentrado">
                <div id="crear">
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
                                <button type="submit" name="insertar" class='crear' style='vertical-align:middle'>
                                    <i class="fas fa-pencil-alt crear-icon"></i> <span>DARSE DE ALTA</span>
                                </button>
                        </form>

                        <!-- Procesamiento de datos del formulario -->
                        <?php

                        //pattern="(?=.*\d)(?=.*[$@#$!%*?&])(?=.*[a-z])(?=.*[A-Z]).{8,}"
                        /* Se verifica si el formulario ha sido enviado */
                        if (isset($_POST['insertar'])) {
                            // Recolección de datos del formulario
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

                                // Mensaje de éxito si la inserción es exitosa
                                echo "<h4>Usuario y contraseña guardados con éxito</h4>";
                            } catch (mysqli_sql_exception $error_insert) {
                                // Manejo de errores en caso de fallo en la inserció
                                echo "Error al insertar los datos: " . $error_insert->getMessage();
                            }
                        }                    
                        ?>
                            <a href='login.php' class="volver">
                                <i class="fas fa-arrow-left volver-icon"></i> <span>VOLVER</span>
                            </a>
                </div>
            </div>
        </div>
    </body>
</html>