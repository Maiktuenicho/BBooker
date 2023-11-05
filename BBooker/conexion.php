<?php

    // generacion de variantes con los datos que se necesita para acceder a la base de datos
    $servidor="localhost";
    $usuario="Agustin";
    $pass="ASIR123";
    $bases="basketbooker";

    try{
        //Si la variante conex da positivo al realizar una nueva conexion estas dentro de la base de datos
        if($conex = new mysqli($servidor,$usuario,$pass,$bases)){
           //echo "Te has conectado a la base de datos";
        }
    }
    // si alguno de los datos de las variantes es erroneo nos mostrará el mensaje y no accederemos a la base de datos
    catch(mysqli_sqlexception $error_conex){
        echo "Ha ocurrido un error de conexión.";
    }
?>