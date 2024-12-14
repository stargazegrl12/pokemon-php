<?php
    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        require_once('../conexion.php');

        $inputJSON = file_get_contents('php://input');
        $data = json_decode($inputJSON, true);

        $nombre_tipo = $data["nombre_tipo"];
        $color_fondo = $data["color_fondo"];
        $color_letra = $data["color_letra"];

        $mysqli->select_db("pokedex");

        $query_insert_tipo = "insert into tipo (nombre_tipo, color_fondo, color_letra) values (?, ?, ?)";

        $stmt = $mysqli->prepare($query_insert_tipo);
        $stmt->bind_param('sss', $nombre_tipo , $color_fondo, $color_letra);

        $stmt->execute();

        //printf("%d row inserted.\n", $stmt->affected_rows);
        $result_insert_id = $mysqli->query("select LAST_INSERT_ID() as id");
        $row = $result_insert_id->fetch_row();
        $id_inserted = $row[0];

        header('Content-type: application/json; charset=utf-8');
		header("access-control-allow-origin: *");

        $data_response["nombre_tipo"] = $nombre_tipo;
        $data_response["color_fondo"] = $color_fondo;
        $data_response["color_letra"] = $color_letra;
        $data_response["tipo_id"] = (int)$id_inserted;

        print(json_encode($data_response));

        $mysqli->close();
    }

?>