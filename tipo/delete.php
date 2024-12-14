<?php
    if($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        require_once('../conexion.php');

        $tipo_id_to_delete = $_GET["tipo_id"];

        $mysqli->select_db("pokedex");

        $exist_tipo = false;
        $query_get_tipo = "select tipo_id, nombre_tipo, color_fondo, color_letra from tipo where tipo_id = ?";
        $stmt = $mysqli->prepare($query_get_tipo);
        $stmt->bind_param('i', $tipo_id_to_delete);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_array(MYSQLI_NUM)) {
            if($row[0] == $tipo_id_to_delete) {
                $exist_tipo = true;
            }
        }

        if($exist_tipo) {

            $query_delete_tipo = "delete from tipo where tipo_id = ?";
            $stmt = $mysqli->prepare($query_delete_tipo);
            $stmt->bind_param('i', $tipo_id_to_delete);
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