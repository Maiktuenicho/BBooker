<?php
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los valores del formulario
    $user = $_POST["usuario"];
    $usuario = $_POST["usuario"];
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $telefono = $_POST["telefono"];

    // Actualizar los datos en la base de datos
    $updateQuery = "UPDATE usuarios SET usuario='$usuario', nombre_completo='$nombre', correo='$correo', telefono='$telefono' WHERE usuario='$user'";

    if ($conex->query($updateQuery) === TRUE) {
        echo "Datos actualizados correctamente.";
        echo "$user";
    } else {
        echo "Error al actualizar los datos: " . $conex->error;
    }
}

$conex->close();
?>
