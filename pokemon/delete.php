<?php
    if($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    
        require_once('../conexion.php');

        $pokemon_id_to_delete = $_GET["pokemon_id"];

        $mysqli->select_db("pokedex");

        $exist_pokemon = false;
        $query_get_pokemon = "select pokemon_id, nombre, numero, imagen from pokemon p where p.pokemon_id = ?";
        $stmt = $mysqli->prepare($query_get_pokemon);
        $stmt->bind_param('i', $pokemon_id_to_delete);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_array(MYSQLI_NUM)) {
            if($row[0] == $pokemon_id_to_delete) {
                $exist_pokemon = true;
            }
        }

        if($exist_pokemon) {

            //borrar relaciones
            $query_delete_rel = "delete from rel_pokemon_tipo where pokemon_id = ?";
            $stmt = $mysqli->prepare($query_delete_rel);
            $stmt->bind_param('i', $pokemon_id_to_delete);
            $stmt->execute();
            //borrar pokemon
            $query_delete_pokemon = "delete from pokemon where pokemon_id = ?";
            $stmt = $mysqli->prepare($query_delete_pokemon);
            $stmt->bind_param('i', $pokemon_id_to_delete);
            $stmt->execute();

            header('Content-type: application/json; charset=utf-8');
    		header("access-control-allow-origin: *");
            http_response_code(204);

        }else{

            header('Content-type: application/json; charset=utf-8');
    		header("access-control-allow-origin: *");
            http_response_code(404);

        }

        $mysqli->close();
    
    }
?>