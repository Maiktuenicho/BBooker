<?php
// Incluir archivo de conexión
require_once 'conexion.php';

// Variable para mensaje de éxito
$mensajeExito = "";

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["modificar"])) {
    // Recuperar datos de la reserva
    $idReserva = $_POST["id_reserva"];
    $horaInicio = $_POST["hora_inicio"];
    $horaFin = $_POST["hora_fin"];
    $fecha = $_POST["fecha"];
    $idPista = $_POST["id_pista"];

    // Mostrar formulario de modificación
    echo "<h1>Modificar Reserva</h1>";
    echo "<form method='post'>"; 
    echo "<input type='hidden' name='id_reserva' value='" . $idReserva . "'>";
    echo "<label for='hora_inicio'>Hora de Inicio:</label>";
    echo "<input type='time' id='hora_inicio' name='hora_inicio' value='" . $horaInicio . "' min='08:00' max='23:00' title='HORARIOS DE 08:00 a 23:00' required>";

    echo "<label for='hora_fin'>Hora de Fin:</label>";
    echo "<input type='time' id='hora_fin' name='hora_fin' value='" . $horaFin . "' required>";

    echo "<label for='fecha'>Fecha:</label>";
    echo "<input type='date' id='fecha' name='fecha' value='" . $fecha . "' min='" . date('Y-m-d') . "' required>";

    echo "<label for='id_pista'>Selecciona una pista:</label>";
    echo "<select id='id_pista' name='id_pista' required>";

    // Obtener lista de pistas existentes
    $sqlPistas = "SELECT ID_PISTA, NOMBRE_PISTA FROM pistas";
    $resultPistas = $conex->query($sqlPistas);

    // Mostrar opciones de pistas desde la base de datos usando fetch_row
    while ($row = $resultPistas->fetch_row()) {
        $selected = ($row[0] == $idPista) ? "selected" : "";
        echo "<option value='" . $row[0] . "' " . $selected . ">" . $row[1] . "</option>";
    }

    echo "</select>";

    echo "<br><br><br>";
    echo "<button type='submit' name='actualizar'>Actualizar Reserva</button>";
    echo "</form>";
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["actualizar"])) {
    // Realizar la actualización en la base de datos
    try {
        // Recuperar datos de la reserva actualizada
        $idReserva = $_POST["id_reserva"];
        $horaInicio = $_POST["hora_inicio"];
        $horaFin = $_POST["hora_fin"];
        $fecha = $_POST["fecha"];
        $idPista = $_POST["id_pista"];

        // Actualizar la reserva en la base de datos
        $sqlUpdateReserva = "UPDATE reservas 
                             SET hora_inicio = '$horaInicio', hora_fin = '$horaFin', fecha = '$fecha', ID_PISTA = $idPista 
                             WHERE ID_RESERVA = $idReserva";

        if ($conex->query($sqlUpdateReserva)) {
            // Mostrar mensaje de éxito
            $mensajeExito = "La reserva se ha actualizado correctamente.";
        } else {
            echo "Error al actualizar la reserva: " . $conex->error;
        }
    } catch (mysqli_sql_exception $error_actualizar) {
        // Mostrar mensaje de error en caso de falla
        echo "Error al actualizar la reserva" . $error_actualizar->__toString();
    }
    
    // Redirigir a mis_reservas.php después de actualizar
    header("Location: mis_reservas.php");
    exit();
} else {
    // Redirigir si no se proporcionaron datos válidos
    header("Location: mis_reservas.php");
    exit();
}

// Cerrar la conexión
$conex->close();
?>
