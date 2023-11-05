<?php
    // Inicia la sesión si no está iniciada
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Verifica si hay un usuario en la sesión
    if(isset($_SESSION["usuario"])) {
        // Obtén el usuario de la sesión
        $user = $_SESSION["usuario"];

        // Ejecuta la consulta para obtener la información del usuario
        require_once 'conexion.php';
        try{
            $result = $conex->query("SELECT usuario, nombre_completo, correo, telefono FROM usuarios WHERE correo='$user'");
        } catch(mysqli_sql_exception $error_select){
            echo "Error al recuperar los productos" . $error_select->__toString();
        }
    } else {
        // Si no hay usuario en la sesión, realiza alguna acción (redireccionar, mostrar un mensaje, etc.)
        echo "No hay usuario en la sesión.";
    }
?>
<!DOCTYPE html>
<html>
    <head>
            <title>Listado</title>
            <meta charset="UTF-8">
            <meta name="author" content="Agustín Mercado Prieto" />
            <meta name="copyright" content="Agustín Mercado Prieto" />
            <meta name="description" content="Listado de CRUD en PHP" />
            <meta name="keyword" content="PHP,CRUD,Productos,Cocina" />
            <style>
                body {
    font-family: 'Overpass', sans-serif;
    font-weight: normal;
    font-size: 100%;
    color: #1b262c;
    margin: 0;
    background-color: #5f6769;
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
    background-image: url(../imagenes/fondo_difuminado_login.jpg);
    background-position: center;
    background-size: cover;
}

.contenedorcentrado {
    display: auto;
    align-items: center;
    justify-content: center;
    flex-direction: row;
    min-width: 380px;
    max-width: 900px;
    width: 90%;
    background-color: #5f6769;
    border-radius: 10px;
    box-shadow: 0px 0px 5px 5px rgba(0, 0, 0, 0.15);
    padding: 30px;
    box-sizing: border-box;
    margin: auto;
    text-align: center;
}

table {
    border-collapse: collapse;
    border-radius: 12px;
    overflow: hidden;
    width: 700px;
    margin: auto;
    margin-top: 20px;
}

.th {
    text-align: center;
    font-family: 'Lucida Handwriting';
    font-size: 15px;background-color: #9A8A62; /* Color de fondo similar al encabezado */
    color: white;
    border-bottom-width: 2px;
    border-bottom-style: solid;
    color: aliceblue;
    padding: 5px;
}

.td {
    font-family: 'Gabriola';
    font-weight: bolder;
    font-size: 22px;
    background-color: #FEFDF8;
    border-bottom-width: 2px;
    border-bottom-style: solid;
    border-bottom-color: black;
}

.center {
    text-align: center;
    padding-left: 8px;
    padding-right: 8px;
}


                .button-link {
                    text-decoration: none;
                }

                .button-link button {
                    font-family: 'Overpass', sans-serif;
                    font-size: 110%;
                    color: #1b262c;
                    width: 20%;
                    height: 40px;
                    border: none;
                    
                    border-radius: 3px 3px 3px 3px;
                    -moz-border-radius: 3px 3px 3px 3px;
                    -webkit-border-radius: 3px 3px 3px 3px;
                    
                    background-color: #dfcdc3;
                    
                    margin-top: 10px;
                }

                .button-link button:hover {
                    background-color: #3c4245;
                }
                .contenedorcentrado h3 {

                    border-radius: 12px;
    background-color: #9A8A62; /* Color de fondo similar al encabezado */
    color: white;
    padding: 10px; /* Ajusta el relleno del título */
    text-align: center;
    margin-bottom: 20px; /* Añade espacio inferior al título */
}

/* Estilo para el botón de actualizar */
.button.update-button {
    position: relative;
    overflow: hidden;
}

.button.update-button span {
    z-index: 1;
    color: #1b262c; /* Color inicial de las letras */
    transition: color 0.3s ease; /* Transición para el cambio de color */
}

.button.update-button i {
    margin-left: 10px;
    color: #fff;
    opacity: 0; /* Inicia con opacidad 0 */
    transition: opacity 0.3s ease;
}

/* Cambiar el color del texto al pasar el ratón sobre el botón */
.button.update-button:hover span {
    color: #fff;
}

/* Mostrar el icono al pasar el ratón sobre el botón */
.button.update-button:hover i {
    opacity: 1;
}

/* Estilo para el botón de volver */
.button.back-button {
    position: relative;
    overflow: hidden;
}

.button.back-button span {
    z-index: 1;
    color: #1b262c; /* Color inicial de las letras */
    transition: color 0.3s ease; /* Transición para el cambio de color */
}

.button.back-button i {
    margin-left: 10px;
    color: #fff;
    opacity: 0; /* Inicia con opacidad 0 */
    transition: opacity 0.3s ease;
}

/* Cambiar el color del texto al pasar el ratón sobre el botón */
.button.back-button:hover span {
    color: #fff;
}

/* Mostrar el icono al pasar el ratón sobre el botón */
.button.back-button:hover i {
    opacity: 1;
}

            </style>

            
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">
    </head>
    <body>
        
        <br><br>

        <div class="contenedor">
            <div class="contenedorcentrado">
                <h3>MI USUARIO</h3>
                <table border="0">
                <?php
                    //Vamos a recorrer las lineas que nos devuelve la query con la condicion de "Mientras recorrer las filas de la consulta de datos" mostralos
                    while ($filas = $result->fetch_row()){
                        echo "<tr>";
                        echo "  <th class='center th'>USUARIO</td>";
                        echo "  <td class='center td'>".$filas[0]."</td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "  <th class='center th'>Nombre completo</td>";
                        echo "  <td class='center td'>".$filas[1]."</td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "  <th class='center th'>Correo</td>";
                        echo "  <td class='center td'>".$filas[2]."</td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "  <th class='center th'>Teléfono</td>";
                        echo "  <td class='center td'>".$filas[3]."</td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "  <td colspan='2' class='center'>
                                    <a href='actualizar.php' class='button-link'>
                                    <button class='button update-button'>
                                        <span>Actualizar</span>
                                        <i class='fas fa-sync-alt'></i> 
                                    </button>
                                    </a>
                                </td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "  <td colspan='2'>
                                    <a href='inicio.php' class='button-link'>
                                        <button class='button back-button'>
                                            <span>Volver</span>
                                            <i class='fas fa-arrow-left'></i> 
                                        </button>
                                    </a>
                                </td>";
                        echo "</tr>";
                    } 
                ?>
                </table>
            </div>
        </div>
    </body>
</html>