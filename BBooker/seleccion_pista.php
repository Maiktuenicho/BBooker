<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Pista - BasketBooker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
        }

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

        input[type="radio"] {
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <h1>Seleccionar Pista - BasketBooker</h1>

    <form method="GET" action="horas.php">
        <table>
            <tr>
                <th>ID Pista</th>
                <th>Nombre Pista</th>
                <th>Seleccionar</th>
            </tr>
            <?php
            // Conectar a la base de datos
            require_once 'conexion.php';

            // Consulta para obtener todas las pistas
            $pistas_query = "SELECT id_pista, nombre_pista FROM pistas";
            $pistas_result = $conex->query($pistas_query);

            // Mostrar las pistas en la tabla
            while ($pista_row = $pistas_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$pista_row['id_pista']}</td>";
                echo "<td>{$pista_row['nombre_pista']}</td>";
                echo "<td><input type='radio' name='pista' value='{$pista_row['id_pista']}'></td>";
                echo "</tr>";
            }

            // Cerrar el resultado de la consulta de pistas
            $pistas_result->free();
            ?>
        </table>
        <input type="hidden" name="fecha" value="<?php echo isset($_GET['fecha']) ? $_GET['fecha'] : ''; ?>">
        <input type="submit" value="Seleccionar Pista">
    </form>

    <button onclick="window.history.back()">Volver Atrás</button>
</body>
</html>
