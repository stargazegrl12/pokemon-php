<?php 
//Datos para la conexion sql
$host = 'localhost:3307';// En esta seccion va la dirección en donde esta alojada la base de datos
$user = 'root';//nombre de usuario de la base de datos
$bd = 'pokedex';//nombre de la base de datos
$pass= '';//Password de la base de datos

$mysqli = mysqli_connect($host, $user, $pass, $bd);
//mysql_connect($host, $user, $pass) or trigger_error(mysql_error(), E_USER_ERROR);

?>