<?php
    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        
        require_once('../conexion.php');

        $mysqli->select_db("pokedex");

        $pokemon_id_get = $_GET["pokemon_id"];

        $consulta_pokemon = "select pokemon_id, nombre, numero, imagen from pokemon p where p.pokemon_id = ?";

        $stmt = $mysqli->prepare($consulta_pokemon);
        $stmt->bind_param('i', $pokemon_id_get);
        $stmt->execute();
        $result = $stmt->get_result();

        $exist_tipo = false;

        while ($row = $result->fetch_array(MYSQLI_NUM)) {
            if($row[0] == $pokemon_id_get) {
                $exist_tipo = true;

                $data_response["pokemon_id"] = $row[0];
                $data_response["nombre"] = $row[1];
                $data_response["numero"] = $row[2];
                $data_response["imagen"] = $row[3];
                
                $pokemon_id = $row[0];
                $consulta_tipos = "select t.tipo_id, t.nombre_tipo, t.color_fondo, t.color_letra from tipo t 
                                join rel_pokemon_tipo rpt on rpt.tipo_id  = t.tipo_id 
                                join pokemon p on p.pokemon_id = rpt.pokemon_id 
                                where rpt.pokemon_id = ". $pokemon_id ."
                                order by t.tipo_id ";
                $result_tipos = $mysqli->query($consulta_tipos);
                foreach ($result_tipos as $row_tipo) {
                    $tipos[] = $row_tipo;
                }
                $data_response["tipos"] = $tipos;
                
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

        $mysqli->close();

    }
?>