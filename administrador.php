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
            $sql = 'SELECT a.id, 
                            a.nome, 
                            a.cpf, 
                            a.senha_login, 
                            tf.tipo as cargo
                            from administrador a
                            inner join tipo_funcionario tf
                            on a.id_tipo_funcionario = tf.id';

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
                $nome = $value['nome'];
                $cpf = $value['cpf'];
                $senha_login = $value['senha_login'];
                $cargo = $value['cargo'];

                $json [] = [
                    "id" => $id,
                    "nome" => $nome,
                    "cpf" => $cpf,
                    "cargo" => $cargo
                ];
            }

            echo json_encode($json, JSON_PRETTY_PRINT);
        } catch (PDOException $e) {
            echo 'ERRO getRastreador';
        }
        break;

    case 'POST':

        $data = $_REQUEST['data'];
        $data = preg_replace('/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/', '', $data);
        $data = json_decode($data, true);

        $nome = $data['nome'];
        $nome = str_replace("'", "", $nome);

        $cpf = $data['cpf'];
        $cpf = str_replace("'", "", $cpf);

        $senha_login = $data['senha_login'];
        $senha_login = str_replace("'", "", $senha_login);

        $id_tipo_funcionario = $data['id_tipo_funcionario'];
        $id_tipo_funcionario = str_replace("'", "", $id_tipo_funcionario);

        try {

            $sql = 'INSERT INTO administrador values (null, :nome, :cpf, :senha_login, :id_tipo_funcionario)';

            $result = $conn->prepare($sql);
            $result->bindParam(':nome', $nome);
            $result->bindParam(':cpf', $cpf);
            $result->bindParam(':senha_login', $senha_login);
            $result->bindParam(':id_tipo_funcionario', $id_tipo_funcionario);
            $result->execute();

            $id = $conn->lastInsertId();
            $json_post = [
                "id" => $id,
                "nome" => $nome,
                "cpf" => $cpf,
                "id_tipo_funcionario" => $id_tipo_funcionario
            ];

            echo json_encode($json_post, JSON_PRETTY_PRINT);

        } catch (PDOException $e) {
            echo 'ERRO postRastreador';
        }

        break;

    case 'PUT':
        $data = $_REQUEST['data'];
        $data = preg_replace('/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/', '', $data);
        $data = json_decode($data, true);

        $id = $data['id'];
        $id = str_replace("'", "", $id);

        $nome = $data['nome'];
        $nome = str_replace("'", "", $nome);

        $cpf = $data['cpf'];
        $cpf = str_replace("'", "", $cpf);

        $senha_login = $data['senha_login'];
        $senha_login = str_replace("'", "", $senha_login);

        $id_tipo_funcionario = $data['id_tipo_funcionario'];
        $id_tipo_funcionario = str_replace("'", "", $id_tipo_funcionario);


        foreach ($data as $key => $value) {
            if ($key !== 'id') {
                try {
                    $sql = "UPDATE administrador set " . $key . " = :value where id = :id";
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
            $sql = 'DELETE from administrador where id = :id';

            $result = $conn->prepare($sql);
            $result->bindParam(':id', $id);
            $result->execute();

            if ($result) {
                echo $id;
            }
        }catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
            echo 'SQL deleteRastreador';
        }
        break;
}

?>