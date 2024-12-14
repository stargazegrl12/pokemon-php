<?php
    if($_SERVER['REQUEST_METHOD'] === 'GET'){
    
        require_once('../conexion.php');

        $mysqli->select_db("pokedex");

        $tipo_id_to_get = $_GET["tipo_id"];

        $consulta_tipos = "select tipo_id, nombre_tipo, color_fondo, color_letra from tipo where tipo_id = ?";

        $stmt = $mysqli->prepare($consulta_tipos);
        $stmt->bind_param('i', $tipo_id_to_get);
        $stmt->execute();
        $result = $stmt->get_result();

        $exist_tipo = false;

        while ($row = $result->fetch_array(MYSQLI_NUM)) {
            if($row[0] == $tipo_id_to_get) {
                $exist_tipo = true;
                $data_response["tipo_id"] = $row[0];
                $data_response["nombre_tipo"] = $row[1];
                $data_response["color_fondo"] = $row[2];
                $data_response["color_letra"] = $row[3];
                
            }
        }

        if($exist_tipo) {

            header('Content-type: application/json; charset=utf-8');
		    header("access-control-allow-origin: *");
            print(json_encode($data_response));
            
        }else{

            header('Content-type: application/json; charset=utf-8');
    		header("access-control-allow-origin: *");
            http_response_code(404);
            
        }

    }
?>