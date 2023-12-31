<?php
// Iniciar la sesión
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Pista - BasketBooker</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F4F4F4;
        }

        header {
            background-color: #9A8A62;
            color: white;
            padding: 5px;
            text-align: center;
        }

        nav {
            background-color: #444;
            color: white;
            padding: 5px;
            text-align: right;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 5px;
            font-weight: bold;
            transition: color 0.3s; /* Agregamos la transición al color */
        }

        nav a:hover {
            color: #9A8A62; /* Cambiamos el color al pasar el ratón */
        }

        h1 {
            text-align: center;
        }

        .pista-btn {
            cursor: pointer;
            background-color: white;
            color: #1b262c;
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
            font-weight: bold;
            border-radius: 5px;
            margin: 5px;
            width: 150px;
            transition: background-color 0.3s ease; /* Efecto de transición para el cambio de color de fondo */
        }

        .pista-btn:hover {
            background-color: #dfcdc3; /* Color de fondo al pasar el ratón */
        }

        #volver-atras {
            text-align: center;
            margin-top: 20px;
            position: relative;
            overflow: hidden;
        }

        #volver-atras button {
            cursor: pointer;
            background-color: #9A8A62;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            position: relative;
            overflow: hidden;
            transition: background-color 0.3s ease;
        }

        #volver-atras button:hover {
            background-color: #7B6B4C;
        }

        #volver-atras button span {
            z-index: 1;
            color: #1b262c;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        #volver-atras button i {
            margin-left: 10px;
            color: #fff;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        #volver-atras button:hover span {
            color: #fff;
        }

        #volver-atras button:hover i {
            opacity: 1;
        }

        .pista-fila {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

    </style>
</head>
<body>
    <header>
        <?php
            // Verificar si el usuario ha iniciado sesión
            if (isset($_SESSION["usuario"])) {
                // Obtener el nombre de usuario desde la sesión
                $correo = $_SESSION["usuario"];

                // Conexion con la base de datos
                require_once 'conexion.php';

                $consulta_usuario =$conex->query("SELECT usuario FROM usuarios WHERE correo = '$correo';");
                $usuario=$consulta_usuario->fetch_row();
                echo "<h1>";
                echo "Bienvenido a BBooker $usuario[0]";
                echo "</h1>";

            } else {
                // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
                header("Location: login.php");
                exit(); // Asegura que el script se detenga después de redirigir
            }
        ?>
    </header>
    <nav>
        <a id="home-link" href="inicio.php">
            <i class="fa fa-solid fa-home"></i>
                Inicio
        </a>
        <a id="perfil-link" href="perfil.php">
            <i class="perfil-link fa fa-solid fa-user"></i>
                Perfil
        </a>
        <a id="cerrar-sesion" href="login.php">
            <i class="cerrar-sesion fa fa-solid fa-arrow-right"></i>
                Salir
        </a>
    </nav>

    <h1>Seleccionar Pista - BasketBooker</h1>

    <?php
    // Conectar a la base de datos
    require_once 'conexion.php';

    // Consulta para obtener todas las pistas
    $pistas_query = "SELECT id_pista, nombre_pista FROM pistas";
    $pistas_result = $conex->query($pistas_query);

    // Mostrar las pistas como botones en filas de 10
    $count = 0;
    while ($pista_row = $pistas_result->fetch_assoc()) {
        if ($count % 10 == 0) {
            // Inicia una nueva fila
            echo "<div class='pista-fila'>";
        }

        echo "<div class='pista-btn' onclick='seleccionarPista({$pista_row['id_pista']})'>";
        echo $pista_row['nombre_pista'];
        echo "</div>";

        if (($count + 1) % 10 == 0 || $pistas_result->num_rows == $count + 1) {
            // Cierra la fila
            echo "</div>";
        }

        $count++;
    }

    // Cerrar el resultado de la consulta de pistas
    $pistas_result->free();
    ?>

    <div id="volver-atras">
        <button onclick="window.history.back()">
            <span>Volver Atrás</span>
            <i class="fa fa-solid fa-arrow-left"></i>
        </button>
    </div>

    <script>
        function seleccionarPista(idPista) {
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = 'horas.php';

            const inputPista = document.createElement('input');
            inputPista.type = 'hidden';
            inputPista.name = 'pista';
            inputPista.value = idPista;

            const inputFecha = document.createElement('input');
            inputFecha.type = 'hidden';
            inputFecha.name = 'fecha';
            inputFecha.value = '<?php echo isset($_GET['fecha']) ? $_GET['fecha'] : ''; ?>';

            form.appendChild(inputPista);
            form.appendChild(inputFecha);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>
