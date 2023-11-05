<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION["usuario"])) {
    // Obtener el nombre de usuario desde la sesión
    $correo = $_SESSION["usuario"];

    // Conexion con la base de datos
    require_once 'conexion.php';

    // Obtener el ID del usuario
    $consultaUsuario = $conex->query("SELECT id_usuario FROM usuarios WHERE correo = '$correo';");
    $idUsuario = $consultaUsuario->fetch_row()[0];

    // Manejar el borrado si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_reserva"])) {
        $idReserva = $_POST["id_reserva"];
        
        try {
            // Realizar la eliminación de la reserva
            $conex->query("DELETE FROM reservas WHERE ID_RESERVA = '$idReserva';");

            // Mostrar mensaje de eliminación exitosa
            $mensajeExito = "La reserva se ha eliminado correctamente.";
        } catch (mysqli_sql_exception $error_borrar) {
            // Mostrar mensaje de error en caso de falla
            echo "Error al borrar la reserva" . $error_borrar->__toString();
        }
    }

    // Consulta para obtener las reservas del usuario
    $consultaReservas = $conex->query("SELECT ID_RESERVA, HORA_INICIO, HORA_FIN, FECHA, ID_PISTA FROM reservas WHERE id_usuario = '$idUsuario';");

    echo "<h1>Estas son tus reservas</h1>";

    // Mostrar las reservas
    if ($consultaReservas->num_rows > 0) {
        
        echo "<table border='1'>";
        echo "<tr><th>HORA_INICIO</th><th>HORA_FIN</th><th>FECHA</th><th>PISTA</th><th>Acciones</th></tr>";

        while ($row = $consultaReservas->fetch_assoc()) {
            echo "<tr><td>" . $row["HORA_INICIO"] . "</td><td>" . $row["HORA_FIN"] . "</td><td>" . $row["FECHA"] . "</td><td>" . $row["ID_PISTA"] . "</td>";
            
                // Agregar formulario y botones para cada reserva
                echo "<td>";
                echo "<form method='post' action=''>";
                echo "<input type='hidden' name='id_reserva' value='" . $row["ID_RESERVA"] . "'>";
                echo "<input type='submit' name='eliminar' value='Eliminar'>";
                echo "</form>";

                // Agregar botón para modificar
                echo "<form method='post' action='modificar_reserva.php'>";
                echo "<input type='hidden' name='id_reserva' value='" . $row["ID_RESERVA"] . "'>";
                echo "<input type='hidden' name='hora_inicio' value='" . $row["HORA_INICIO"] . "'>";
                echo "<input type='hidden' name='hora_fin' value='" . $row["HORA_FIN"] . "'>";
                echo "<input type='hidden' name='fecha' value='" . $row["FECHA"] . "'>";
                echo "<input type='hidden' name='id_pista' value='" . $row["ID_PISTA"] . "'>";
                echo "<input type='submit' name='modificar' value='Modificar'>";
                echo "</form>";
                echo "</td></tr>";
        }

        echo "</table>";

        // Mostrar mensaje de éxito si existe
        if (isset($mensajeExito)) {
            echo "<p style='color: green;'>$mensajeExito</p>";
        }
    } else {
        echo "No tienes reservas.";
    }

    // botón para volver al inicio
    echo "<br><a href='inicio.php'><button>Volver al Inicio</button></a>";

} else {
    // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location: login.php");
    exit(); // Asegura que el script se detenga después de redirigir
}
?>
