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
            $sql = 'SELECT id, latitude, longitude, horario_gps, observacao, situacao from rastreador';

            if(isset($_REQUEST['id'])) {
                $sql = $sql . ' where id = :id';
                $result = $conn->prepare($sql);
                $result->bindParam(':id', $id);
            }else {
                $result = $conn->prepare($sql);
            }
            $result->execute();

            $getLocal = $result->fetchAll();

            foreach ($getLocal as $value) {
                $id = $value['id'];
                $latitude = $value['latitude'];
                $longitude = $value['longitude'];
                $horario_gps = $value['horario_gps'];
                $observacao = $value['observacao'];
                $situacao = $value['situacao'];

                $json [] = [
                    "id" => $id,
                    "latitude" => $latitude,
                    "longitude" => $longitude,
                    "horario_gps" => $horario_gps,
                    "observacao" => $observacao,
                    "situacao" => $situacao
                ];
            }

            echo json_encode($json, JSON_PRETTY_PRINT);
        } catch (PDOException $e) {
            echo 'ERRO getRastreador';
        }
        break;

    case 'POST':

        $data = "php://input";
        $data = file_get_contents($data);
        $data = json_decode($data, true);

        $latitude = $data['latitude'];
        $latitude = str_replace("'", "", $latitude);

        $longitude = $data['longitude'];
        $longitude = str_replace("'", "", $longitude);

        $horario_gps = $data['horario_gps'];
        $horario_gps = str_replace("'", "", $horario_gps);

        $observacao = $data['observacao'];
        $observacao = str_replace("'", "", $observacao);

        $situacao = $data['situacao'];
        $situacao = str_replace("'", "", $situacao);


        try {

            $sql = "INSERT INTO rastreador values (null, :latitude, :longitude, :horario_gps, :observacao, :situacao)";

            $result = $conn->prepare($sql);
            $result->bindParam(':latitude', $latitude);
            $result->bindParam(':longitude', $longitude);
            $result->bindParam(':horario_gps', $horario_gps);
            $result->bindParam(':observacao', $observacao);
            $result->bindParam(':situacao', $situacao);
            $result->execute();

            $id = $conn->lastInsertId();
            $json_post = [
                "id" => $id,
                "latitude" => $latitude,
                "longitude" => $longitude,
                "horario_gps" => $horario_gps,
                "observacao" => $observacao,
                "situacao" => $situacao
            ];

            echo json_encode($json_post, JSON_PRETTY_PRINT);

        } catch (PDOException $e) {
            echo 'ERRO postRastreador';
        }

        break;

    case 'PUT':
       $data = "php://input";
       $data = file_get_contents($data);
       $data = json_decode($data, true);

        $id = $data['id'];
        $id = str_replace("'", "", $id);

        $latitude = $data['latitude'];
        $latitude = str_replace("'", "", $latitude);

        $longitude = $data['longitude'];
        $longitude = str_replace("'", "", $longitude);

        $horario_gps = $data['horario_gps'];
        $horario_gps = str_replace("'", "", $horario_gps);

        $observacao = $data['observacao'];
        $observacao = str_replace("'", "", $observacao);

        $situacao = $data['situacao'];
        $situacao = str_replace("'", "", $situacao);

        $data = [
            "id" => $id,
            "latitude" => $latitude,
            "longitude" => $longitude,
            "horario_gps" =>$horario_gps,
            "observacao" => $observacao,
            "situacao" => $situacao
        ];


        foreach ($data as $key => $value) {
            if ($key !== 'id') {
                try {
                    $sql = "UPDATE rastreador set " . $key . " = :value where id = :id";
                    $result = $conn->prepare($sql);
                    $result->bindParam(':id', $id);
                    $result->bindParam(':value', $value);

                    $result->execute();

                } catch (PDOException $e) {
                    echo 'ERRO putRastreador';
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
            $sql = 'DELETE from rastreador where id = :id';

            $result = $conn->prepare($sql);
            $result->bindParam(':id', $id);
            $result->execute();

            if ($result) {
                echo $id;
            }
        }catch (PDOException $e) {
            echo false;
        }
        break;
}

?>