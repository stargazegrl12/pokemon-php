<?php
    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        
        require_once('../conexion.php');

        $mysqli->select_db("pokedex");

        $consulta_pokemos = "select pokemon_id, nombre, numero, imagen from pokemon";

        $result = $mysqli->query($consulta_pokemos);

        foreach ($result as $row) {
            /* Processing of the data retrieved from the database */
            $pokemon_id = $row["pokemon_id"];
            $consulta_tipos = "select t.tipo_id, t.nombre_tipo, t.color_fondo, t.color_letra from tipo t 
                                join rel_pokemon_tipo rpt on rpt.tipo_id  = t.tipo_id 
                                join pokemon p on p.pokemon_id = rpt.pokemon_id 
                                where rpt.pokemon_id = ". $pokemon_id ."
                                order by t.tipo_id ";
            $result_tipos = $mysqli->query($consulta_tipos);
            foreach ($result_tipos as $row_tipo) {
                $tipos[] = $row_tipo;
            }
            $row["tipos"] = $tipos;
            $pokemones[] = $row;
        }

        header('Content-type: application/json; charset=utf-8');
		header("access-control-allow-origin: *");

        //la codificamos en un json y la imprimimos
        print(json_encode($pokemones));

        $mysqli->close();

    }
?>