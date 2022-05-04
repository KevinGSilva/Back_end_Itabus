<?php
include "conexao.php";

header('content-type:application/json;charset=utf-8');
date_default_timezone_set('America/Sao_Paulo');

$http_origin = $_SERVER['HTTP_ORIGIN'];

if ($http_origin == "http://localhost:9100" || $http_origin == "http://localhost:5037" || $http_origin == "http://localhost:46549" || $http_origin == "http://localhost:43549") {
    header("Access-Control-Allow-Origin: " . $http_origin);
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET': 

        $id = $_REQUEST['id'];
        $id = str_replace("'", "", $id);

        try {
            $sql = 'SELECT id, nome from localidade';

            if(isset($_REQUEST['id'])) {
                $sql = $sql . ' where id = :id';
                $result = $conn->prepare($sql);
                $result->bindParam(':id', $id);

                $result->execute();

            $getLocal = $result->fetchAll();

            foreach ($getLocal as $value) {
                $id = $value['id'];
                $nome = $value['nome'];

                $json = [
                    "id" => $id,
                    "nome" => $nome
                ];
            }

            echo json_encode($json, JSON_PRETTY_PRINT);
            }else {
                $result = $conn->prepare($sql);
                $result->execute();

            $getLocal = $result->fetchAll();

            foreach ($getLocal as $value) {
                $id = $value['id'];
                $nome = $value['nome'];

                $json [] = [
                    "id" => $id,
                    "nome" => $nome
                ];
            }

            echo json_encode($json, JSON_PRETTY_PRINT);
            }
            
        } catch (PDOException $e) {
            echo 'ERRO getLocalidade';
        }
        break;

    case 'POST':
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);

        $nome = $data['nome'];
        $nome = str_replace("'", "", $nome);

        try {

            $sql = "INSERT INTO localidade values (null, :nome)";

            $result = $conn->prepare($sql);
            $result->bindParam(':nome', $nome);
            $result->execute();

            $id = $conn->lastInsertId();
            $json_post = [
                "id" => $id,
                "nome"=> $nome
            ];

            echo json_encode($json_post, JSON_PRETTY_PRINT);

        } catch (PDOException $e) {
            echo 'ERRO postLocalidade';
        }

        break;

    case 'PUT':
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);

        $id = $data['id'];
        $id = str_replace("'", "", $id);

        $nome = $data['nome'];
        $nome = str_replace("'", "", $nome);


        foreach ($data as $key => $value) {
            if ($key !== 'id') {
                try {
                    $sql = "UPDATE localidade set " . $key . " = :value where id = :id";
                    $result = $conn->prepare($sql);
                    $result->bindParam(':id', $id);
                    $result->bindParam(':value', $value);

                    $result->execute();

                } catch (PDOException $e) {
                    echo 'ERRO putLocalidade' . $e;
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
            $sql = 'DELETE from localidade where id = :id';

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