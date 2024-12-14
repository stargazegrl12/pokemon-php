<?php

    if($_SERVER['REQUEST_METHOD'] === 'PUT') {

        require_once('../conexion.php');
        $inputJSON = file_get_contents('php://input');
        $data = json_decode($inputJSON, true);

        //var_dump($data);

        $pokemon_id = $data["pokemon_id"];
        $nombre = $data["nombre"];
        $numero = $data["numero"];
        $imagen = $data["imagen"];
        $tipos = $data["tipos"];

        $mysqli->select_db("pokedex");

        //consultar que exista
        $exist_pokemon = false;
        $query_get_pokemon = "select pokemon_id, nombre, numero, imagen from pokemon p where p.pokemon_id = ?";
        $stmt = $mysqli->prepare($query_get_pokemon);
        $stmt->bind_param('i', $pokemon_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_array(MYSQLI_NUM)) {
            if($row[0] == $pokemon_id) {
                $exist_pokemon = true;
            }
        }

        if($exist_pokemon) {

            $query_update_pokemon = "update pokemon set nombre = ?, numero  = ?, imagen = ? where pokemon_id  = ?";
            $stmt = $mysqli->prepare($query_update_pokemon);
            $stmt->bind_param('sssi', $nombre , $numero, $imagen, $pokemon_id);
            $stmt->execute();

            $query_delete_rel = "delete from rel_pokemon_tipo where pokemon_id = ?";
            $stmt = $mysqli->prepare($query_delete_rel);
            $stmt->bind_param('i', $pokemon_id);
            $stmt->execute();

            //insertar los tipos
            foreach ($tipos as $tipo) {
                $query_insert_reltipopokemon = "insert into rel_pokemon_tipo (tipo_id, pokemon_id) values (?, ?)";
                $stmt = $mysqli->prepare($query_insert_reltipopokemon);
                $stmt->bind_param('ii', $tipo["tipo_id"] , $pokemon_id);
                $stmt->execute();
            }

            header('Content-type: application/json; charset=utf-8');
		    header("access-control-allow-origin: *");

            $data_response["pokemon_id"] = $pokemon_id;
            $data_response["nombre"] = $nombre;
            $data_response["numero"] = $numero;
            $data_response["imagen"] = $imagen;
            $data_response["tipos"] = $tipos;
    
            print(json_encode($data_response));

        } else {

            //error not found
            header('Content-type: application/json; charset=utf-8');
            header("access-control-allow-origin: *");
            http_response_code(404);

        }

        $mysqli->close();

    }

?>