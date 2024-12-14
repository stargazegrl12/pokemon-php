<?php
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
        require_once('../conexion.php');

        $inputJSON = file_get_contents('php://input');
        $data = json_decode($inputJSON, true);

        //var_dump($data);

        $mysqli->select_db("pokedex");

        $nombre = $data["nombre"];
        $numero = $data["numero"];
        $imagen = $data["imagen"];
        $tipos = $data["tipos"];

        //var_dump($tipos);

        $query_insert_pokemon = "insert into pokemon (nombre, numero, imagen) values (?, ? , ?)";
        $stmt = $mysqli->prepare($query_insert_pokemon);
        $stmt->bind_param('sss', $nombre , $numero, $imagen);
        $stmt->execute();

        $result_insert_id = $mysqli->query("select LAST_INSERT_ID() as id");
        $row = $result_insert_id->fetch_row();
        $pokemon_id_insert = $row[0];

        //insertar los tipos
        foreach ($tipos as $tipo) {
            $query_insert_reltipopokemon = "insert into rel_pokemon_tipo (tipo_id, pokemon_id) values (?, ?)";
            $stmt = $mysqli->prepare($query_insert_reltipopokemon);
            $stmt->bind_param('ii', $tipo["tipo_id"] , $pokemon_id_insert);
            $stmt->execute();
        }

        header('Content-type: application/json; charset=utf-8');
		header("access-control-allow-origin: *");

        $data_response["nombre"] = $nombre;
        $data_response["numero"] = $numero;
        $data_response["imagen"] = $imagen;
        $data_response["pokemon_id"] = (int)$pokemon_id_insert;
        $data_response["tipos"] = $tipos;

        print(json_encode($data_response));

        $mysqli->close();

    }
?>