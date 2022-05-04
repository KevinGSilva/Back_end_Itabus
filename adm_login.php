<?php
include "conexao.php";

header('content-type:application/json;charset=utf-8');
date_default_timezone_set('America/Sao_Paulo');

$http_origin = $_SERVER['HTTP_ORIGIN'];

if ($http_origin == "http://localhost") {
    header("Access-Control-Allow-Origin: " . $http_origin);
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET': 

        $cpf = $_REQUEST['cpf'];
        $cpf = str_replace("'", "", $cpf);

        $senha_login = $_REQUEST['senha'];
        $senha_login = str_replace("'", "", $senha_login);

        try {
            $sql = 'SELECT id, nome, cpf, senha_login, id_tipo_funcionario from administrador';

            if(isset($_REQUEST['cpf'])) {
                $sql = $sql . ' where cpf = :cpf';
                $result = $conn->prepare($sql);
                $result->bindParam(':cpf', $cpf);
            }else {
                $result = $conn->prepare($sql);
            }
            $result->execute();

            $getLocal = $result->fetchAll();

            foreach ($getLocal as $value) {
                $id = $value['id'];
                $nome = $value['nome'];
                $cpf = $value['cpf'];
                $senha_login = $value['senha_login'];

                $json [] = [
                    "id" => $id,
                    "nome" => $nome,
                    "cpf" => $cpf,
                    "senha_login" => $senha_login
                ];
            }

            echo json_encode($json, JSON_PRETTY_PRINT);
        } catch (PDOException $e) {
            echo 'ERRO getVeiculo' . $e;
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
