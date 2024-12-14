<?php
    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        
        require_once('../conexion.php');

        $mysqli->select_db("pokedex");

        $consulta_tipos = "select tipo_id, nombre_tipo, color_fondo, color_letra from tipo";

        $result = $mysqli->query($consulta_tipos);

        foreach ($result as $row) {
            /* Processing of the data retrieved from the database */
            $tipos[] = $row;
        }

        header('Content-type: application/json; charset=utf-8');
		header("access-control-allow-origin: *");

        //la codificamos en un json y la imprimimos
        print(json_encode($tipos));

        $mysqli->close();
    }
?>