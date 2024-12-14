<?php
    
    if($_SERVER['REQUEST_METHOD'] === 'PUT') {
        
        require_once('../conexion.php');
        $inputJSON = file_get_contents('php://input');
        $data = json_decode($inputJSON, true);

        $tipo_id = $data["tipo_id"];
        $nombre_tipo = $data["nombre_tipo"];
        $color_fondo = $data["color_fondo"];
        $color_letra = $data["color_letra"];

        $mysqli->select_db("pokedex");

        //consultar que exista
        $exist_tipo = false;
        $query_get_tipo = "select tipo_id, nombre_tipo, color_fondo, color_letra from tipo where tipo_id = ?";
        $stmt = $mysqli->prepare($query_get_tipo);
        $stmt->bind_param('i', $tipo_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_array(MYSQLI_NUM)) {
            if($row[0] == $tipo_id) {
                $exist_tipo = true;
            }
        }

        if($exist_tipo){
            //actualizar
            $query_insert_tipo = "update tipo set nombre_tipo = ?, color_fondo  = ?, color_letra  = ? where tipo_id  = ?";

            $stmt = $mysqli->prepare($query_insert_tipo);
            $stmt->bind_param('sssi', $nombre_tipo , $color_fondo, $color_letra, $tipo_id);

            $stmt->execute();

            header('Content-type: application/json; charset=utf-8');
		    header("access-control-allow-origin: *");

            $data_response["nombre_tipo"] = $nombre_tipo;
            $data_response["color_fondo"] = $color_fondo;
            $data_response["color_letra"] = $color_letra;
            $data_response["tipo_id"] = $tipo_id;
    
            print(json_encode($data_response));

        }else{
            //error not found
            header('Content-type: application/json; charset=utf-8');
    		header("access-control-allow-origin: *");
            http_response_code(404);
        }
    
        $mysqli->close();
    }

?>