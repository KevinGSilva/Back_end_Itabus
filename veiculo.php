<?php
include "conexao.php";

header('content-type:application/json;charset=utf-8');
date_default_timezone_set('America/Sao_Paulo');

    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET': 

        $id = $_REQUEST['id'];
        $id = str_replace("'", "", $id);

        try {
            $sql = 'SELECT id, placa from veiculo';

            if(isset($_REQUEST['id'])) {
                $sql = $sql . ' where id = :id';
                $result = $conn->prepare($sql);
                $result->bindParam(':id', $id);
                $result->execute();

                $getLocal = $result->fetchAll();

                foreach ($getLocal as $value) {
                    $id_veiculo = $value['id'];
                    $placa = $value['placa'];

                    $sql2 = 'select count(id_veiculo) as rotas from trajeto where id_veiculo = :id_veiculo';
                    $result2 = $conn->prepare($sql2);
                    $result2->bindParam(':id_veiculo', $id_veiculo);
                    $result2->execute();

                    $getTrajetoVeiculo = $result2->fetchAll();

                    foreach($getTrajetoVeiculo as $value){
                        $rotas_usadas = $value['rotas'];
                    }

                    if($rotas_usadas == 0 || $rotas_usadas == ''){ 
                        $json [] = [
                            "id" => $id,
                            "placa" => $placa,
                            "rotas" => '0'
                        ];
                    } else{
                        $json [] = [
                            "id" => $id,
                            "placa" => $placa,
                            "rotas" =>  $rotas_usadas
                        ];
                    }
                    
                }

                echo json_encode($json, JSON_PRETTY_PRINT);
            }else {
                $result = $conn->prepare($sql);
                $result->execute();

                $getLocal = $result->fetchAll();

                foreach ($getLocal as $value) {
                    $id = $value['id'];
                    $placa = $value['placa'];

                    $json [] = [
                        "id" => $id,
                        "placa" => $placa
                    ];
                }

                echo json_encode($json, JSON_PRETTY_PRINT);
            }
            
        } catch (PDOException $e) {
            echo 'ERRO getVeiculo';
        }
        break;

    case 'POST':
        $data = file_get_contents("php://input");
        $data = json_decode($data, true);

        $placa = $data['placa'];

        try {

            $sql = "INSERT INTO veiculo values (null, :placa)";

            $result = $conn->prepare($sql);
            $result->bindParam(':placa', $placa);
            $result->execute();

            $id = $conn->lastInsertId();
            $json_post = [
                "id" => $id,
                "placa"=> $placa
            ];

            echo json_encode($json_post, JSON_PRETTY_PRINT);

        } catch (PDOException $e) {
            echo 'ERRO postVeiculo';
        }

        break;

    case 'PUT':
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);

        $id = $data['id'];
        $id = str_replace("'", "", $id);

        $placa = $data['placa'];
        $placa = str_replace("'", "", $placa);


        foreach ($data as $key => $value) {
            if ($key !== 'id') {
                try {
                    $sql = "UPDATE veiculo set " . $key . " = :value where id = :id";
                    $result = $conn->prepare($sql);
                    $result->bindParam(':id', $id);
                    $result->bindParam(':value', $value);

                    $result->execute();

                } catch (PDOException $e) {
                    echo 'ERRO putVeiculo';
                }
            }
        }
        echo $id;
        break;

    case 'DELETE':

        $id = $_REQUEST['id'];
        $id = preg_replace('/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/', '', $id);
        $id = str_replace("'", "", $id);

        try {
            $sql = 'DELETE from veiculo where id = :id';

            $result = $conn->prepare($sql);
            $result->bindParam(':id', $id);
            $result->execute();

            if ($result) {
                echo $id;
            }
        }catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
            echo 'SQL deleteVeiculo';
        }
        break;
}

?>