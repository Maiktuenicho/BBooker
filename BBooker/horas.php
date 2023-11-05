<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horas de Reserva</title>
    <style>
        table {
            border-collapse: collapse;
            width: 50%;
            margin: 20px auto;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .reserved {
            background-color: purple;
            color: white;
        }
    </style>
</head>
<body>

<h2>Horas de Reserva</h2>

<table>
    <tr>
        <th>Hora</th>
        <th>Estado</th>
        <th>Usuario</th>
        <th>Acciones</th>
    </tr>

    <?php
    // Conectar a la base de datos
    require_once 'conexion.php';

    // Obtener la fecha y pista seleccionadas
    $fecha_elegida = isset($_GET['fecha']) ? $_GET['fecha'] : null;
    $pista_elegida = isset($_GET['pista']) ? $_GET['pista'] : null;

    // Obtener el ID de la pista
    $id_pista = isset($_GET['id_pista']) ? $_GET['id_pista'] : null;

    // Obtener el nombre de la pista
    $query_nombre_pista = "SELECT nombre_pista FROM pistas WHERE id_pista = '$pista_elegida'";
    $result_nombre_pista = $conex->query($query_nombre_pista);

    // Verificar si la consulta fue exitosa
    if ($result_nombre_pista) {
        $row_nombre_pista = $result_nombre_pista->fetch_assoc();
        $nombre_pista = $row_nombre_pista['nombre_pista'];

        // Consulta para obtener todas las reservas con información del usuario para la pista y fecha seleccionadas
        $query = "SELECT r.hora_inicio, r.hora_fin, r.fecha, u.nombre_completo 
                  FROM reservas r 
                  RIGHT JOIN usuarios u ON r.id_usuario = u.id_usuario
                  WHERE r.id_pista = '$pista_elegida' AND r.fecha = '$fecha_elegida'";

        // Ejecutar la consulta
        $result = $conex->query($query);

        // Verificar si la consulta fue exitosa
        if ($result) {
            // Crear un array asociativo para almacenar todas las horas por fecha
            $horas_por_fecha = [];

            // Obtener las horas reservadas desde el resultado de la consulta
            while ($row = $result->fetch_assoc()) {
                // Obtener la información de la reserva
                $reserva_fecha = $row['fecha'];
                $hora_inicio = $row['hora_inicio'];
                $hora_fin = $row['hora_fin'];
                $nombre_usuario = $row['nombre_completo'];

                // Crear un rango de horas reservadas
                $hora_actual = $hora_inicio;
                while ($hora_actual <= $hora_fin) {
                    $horas_por_fecha[$reserva_fecha][] = ['hora' => $hora_actual, 'usuario' => $nombre_usuario];
                    $hora_actual = date('H:00', strtotime($hora_actual) + 3600); // Agregar 1 hora
                }
            }

            // Mostrar todas las horas con saltos de 1 hora para cada fecha
            for ($hour = 8; $hour <= 23; $hour++) {
                $current_time = sprintf("%02d:00", $hour);
                $status = 'Disponible';
                $class = '';
                $usuario_reserva = ''; // Nueva variable para almacenar el nombre del usuario

                // Verificar si la hora está reservada para alguna fecha
                foreach ($horas_por_fecha as $fecha => $horas_reservadas) {
                    foreach ($horas_reservadas as $reserva) {
                        $reserva_hora = $reserva['hora'];

                        // Verificar si la hora actual está dentro del rango de horas de la reserva
                        if (strtotime($reserva_hora) <= strtotime($current_time) && strtotime($current_time) <= strtotime($reserva_hora) + 3600) {
                            $status = 'Reservada';
                            $class = 'reserved';
                            $usuario_reserva = $reserva['usuario']; // Almacenar el nombre del usuario
                            break 2; // Salir de ambos bucles
                        }
                    }
                }

                echo "<tr class=\"$class\">
                        <td>$current_time</td>
                        <td>$status</td>";

                // Si está reservada, mostrar el nombre del usuario
                if ($status === 'Reservada') {
                    echo "<td>$usuario_reserva</td>";
                } else {
                    echo "<td></td>";
                }

                // En la columna de "Acciones", mostrar el botón solo si la hora está disponible
                echo "<td>";
                if ($status === 'Disponible') {
                    echo "<a href='procesar_reserva.php?fecha=$fecha_elegida&hora=$current_time&pista=$pista_elegida&nombre_pista=$nombre_pista&id_pista=$id_pista'>Reservar</a>";
                }
                echo "</td>";

                echo "</tr>";
            }

            // Liberar el resultado de la consulta
            $result->free();
        } else {
            // Manejar errores de consulta si es necesario
            echo "Error de consulta: " . $conex->error;
        }
    } else {
        // Manejar errores de consulta para obtener el nombre de la pista
        echo "Error de consulta para obtener el nombre de la pista: " . $conex->error;
    }

    // Cerrar la conexión
    $conex->close();
    ?>
</table>
<button onclick="window.history.back()">Volver Atrás</button>
</body>
</html>
