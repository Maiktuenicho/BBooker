<?php
// Iniciar la sesión
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario de Reservas</title>
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

        h1 {
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
            transition: background-color 0.3s ease; /* Transición para el cambio de color de fondo */
        }

        #volver-atras button:hover {
            background-color: #7B6B4C; /* Color de fondo al pasar el ratón */
            
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

        #calendario {
            display: flex;
            justify-content: center; /* Centra los elementos horizontalmente */
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            margin-top: 20px;
        }

        .diaCalendario {
            cursor: pointer;
            padding: 10px;
            width: 150px; /* Ajusta el ancho del botón */
            height: 20px; /* Ajusta la altura del botón */
            border: 1px solid #ccc;
            text-align: center;
            font-weight: bold;
            background-color: white;
            border-radius: 5px;
        }

        .diaCalendario:hover {
            background-color: #dfcdc3;
        }

        #horasReservas {
            text-align: center;
            margin-top: 20px;
        }

        .horaReserva {
            display: inline-block;
            margin: 5px;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #F4F4F4;
            border-radius: 5px;
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

    <h1>Calendario de Reservas</h1>

    <div id="calendario"></div>

    <div id="volver-atras">
        <button onclick="window.history.back()">
            <span>Volver Atrás</span>
            <i class="fa fa-solid fa-arrow-left"></i>
        </button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendario = document.getElementById('calendario');
    
            // Obtener la fecha actual
            const fechaActual = new Date();
    
            // Mostrar 7 días desde la fecha actual
            for (let i = 0; i < 7; i++) {
                const dia = new Date();
                dia.setDate(fechaActual.getDate() + i);
    
                const diaElement = document.createElement('div');
                diaElement.textContent = obtenerFechaFormatoCorto(dia);
                diaElement.classList.add('diaCalendario');
                diaElement.addEventListener('click', () => redirigirAPistas(dia));
                calendario.appendChild(diaElement);
            }
    
            // Función para redirigir a la selección de pista al hacer clic en un día
            function redirigirAPistas(dia) {
                // Construir la fecha en formato YYYY-MM-DD
                const fechaFormatoISO = dia.toISOString().split('T')[0];
    
                // Redirigir a la página de selección de pista con la fecha seleccionada
                window.location.href = `seleccion_pista.php?fecha=${fechaFormatoISO}`;
            }
    
            // Función para obtener la fecha en formato corto (DD/MM)
            function obtenerFechaFormatoCorto(fecha) {
                const dia = fecha.getDate().toString().padStart(2, '0');
                const mes = (fecha.getMonth() + 1).toString().padStart(2, '0');
                return `${dia}/${mes}`;
            }
        });
    </script>
</body>
</html>
