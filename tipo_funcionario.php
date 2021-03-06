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
            $sql = 'SELECT id, tipo from tipo_funcionario';

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
                $tipo = $value['tipo'];

                $json [] = [
                    "id" => $id,
                    "tipo" => $tipo
                ];
            }

            echo json_encode($json, JSON_PRETTY_PRINT);
        } catch (PDOException $e) {
            echo 'ERRO getTipo_funcionario';
        }
        break;

    case 'POST':

        $data = $_REQUEST['data'];
        $data = preg_replace('/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/', '', $data);
        $data = json_decode($data, true);

        $tipo = $data['tipo'];
        $tipo = str_replace("'", "", $tipo);

        try {

            $sql = "INSERT INTO tipo_funcionario values (null, :tipo)";

            $result = $conn->prepare($sql);
            $result->bindParam(':tipo', $tipo);
            $result->execute();

            $id = $conn->lastInsertId();
            $json_post = [
                "id" => $id,
                "tipo"=> $tipo
            ];

            echo json_encode($json_post, JSON_PRETTY_PRINT);

        } catch (PDOException $e) {
            echo 'ERRO postTipo_funcionario';
        }

        break;

    case 'PUT':
        $data = $_REQUEST['data'];
        $data = preg_replace('/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/', '', $data);
        $data = json_decode($data, true);

        $id = $data['id'];
        $id = str_replace("'", "", $id);

        $tipo = $data['tipo'];
        $tipo = str_replace("'", "", $tipo);


        foreach ($data as $key => $value) {
            if ($key !== 'id') {
                try {
                    $sql = "UPDATE tipo_funcionario set " . $key . " = :value where id = :id";
                    $result = $conn->prepare($sql);
                    $result->bindParam(':id', $id);
                    $result->bindParam(':value', $value);

                    $result->execute();

                } catch (PDOException $e) {
                    echo 'ERRO putTipo_funcionario';
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
            $sql = 'DELETE from tipo_funcionario where id = :id';

            $result = $conn->prepare($sql);
            $result->bindParam(':id', $id);
            $result->execute();

            if ($result) {
                echo $id;
            }
        }catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
            echo 'SQL deleteTipo_funcionario';
        }
        break;
}

?>