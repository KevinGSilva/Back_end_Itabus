 
<?php
    include "conexao.php";

    header('content-type:text/html;charset=utf-8');
    date_default_timezone_set('America/Sao_Paulo');

        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Max-Age: 1000');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        
    $method = $_SERVER['REQUEST_METHOD'];
    
    
    
    switch($method){
        case 'GET':
            $id = $_REQUEST['id'];
            $id = preg_replace('/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/', '', $id);
            $id = str_replace("'", "", $id);

            $cpf = $_REQUEST['cpf'];
            $cpf = preg_replace('/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/', '', $cpf);
            $cpf = str_replace("'", "", $cpf);

            try {
                $sql = 'SELECT f.id, 
                                f.nome,
                                f.cpf,
                                f.data_nascimento,
                                f.matricula,
                                f.senha_login
                                from funcionario f';



                #Aqui é se vc quiser procurar por id. Daí no front, ce vai mandar junto na url o id:
                #...arquivo.php?id=${variavel_id}
                if (isset($_REQUEST['id'])) {
                    $sql = $sql . ' where f.id = :id';
                    $result = $conn->prepare($sql);
                    $result->bindParam(':id', $id);
                    $result->execute();
                    $getFuncionario = $result->fetchAll();

                foreach ($getFuncionario as $value) {
                    $id = $value['id'];
                    $nome = $value['nome'];
                    $cpf = $value['cpf'];
                    $data_nascimento = $value['data_nascimento'];
                    $matricula = $value['matricula'];
                    $senha_login = $value['senha_login'];

                    $json [] = [
                        "id" => $id,
                        "nome" => $nome,
                        "cpf" => $cpf,
                        "data_nascimento" => $data_nascimento,
                        "matricula" => $matricula,
                        "senha_login" => $senha_login
                    ];
                }
                echo json_encode($json, JSON_PRETTY_PRINT);
                } 
                

                #Aqui é se vc quiser fazer a requisicao pelo cpf. Pode servir como o email tbm, só trocar tudo onde tem cpf, por email.
                #Daí no front, ce vai mandar junto na url o cpf:
                ##...arquivo.php?id=${variavel_cpf}
                else if(isset($_REQUEST['cpf'])) {
                    $sql = $sql . ' where f.cpf = :cpf';
                    $result = $conn->prepare($sql);
                    $result->bindParam(':cpf', $cpf);
                    $result->execute();
                    $getFuncionario = $result->fetchAll();

                foreach ($getFuncionario as $value) {
                    $id = $value['id'];
                    $nome = $value['nome'];
                    $cpf = $value['cpf'];
                    $data_nascimento = $value['data_nascimento'];
                    $matricula = $value['matricula'];
                    $senha_login = $value['senha_login'];

                    $json [] = [
                        "id" => $id,
                        "nome" => $nome,
                        "cpf" => $cpf,
                        "data_nascimento" => $data_nascimento,
                        "matricula" => $matricula,
                        "senha_login" => $senha_login
                    ];
                }
                echo json_encode($json, JSON_PRETTY_PRINT);
            } 

            #Aqui restorna todos os objetos da tabela. Sem mandar nenhum parametro pela url
            else {
                    $result = $conn->prepare($sql);
                    $result->execute();
                    $getFuncionario = $result->fetchAll();

                foreach ($getFuncionario as $value) {
                    $id = $value['id'];
                    $nome = $value['nome'];
                    $cpf = $value['cpf'];
                    $data_nascimento = $value['data_nascimento'];
                    $matricula = $value['matricula'];
                    $senha_login = $value['senha_login'];

                    $json [] = [
                        "id" => $id,
                        "nome" => $nome,
                        "cpf" => $cpf,
                        "data_nascimento" => $data_nascimento,
                        "matricula" => $matricula,
                        "senha_login" => $senha_login
                    ];
                }
                echo json_encode($json, JSON_PRETTY_PRINT);
            }

            } catch (PDOException  $e) {
                echo 'ERRO getFuncionario ' . $e->getMessage();
            }
            break;

        case 'POST':
            $data = file_get_contents('php://input');
            $data = preg_replace('/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/', '', $data);
            $data = json_decode($data, true);

            $nome = $data['nome'];
            $nome = str_replace("'", "", $nome);

            $cpf = $data['cpf'];
            $cpf = str_replace("'", "", $cpf);
            $cpf = str_replace("-", "", $cpf);
            $cpf = str_replace(".", "", $cpf);

            $data_nascimento = $data['data_nascimento'];
            $data_nascimento = str_replace("'", "", $data_nascimento);
            $data_nascimento = str_replace("/", "-", $data_nascimento);

            $matricula = $data['matricula'];
            $matricula = str_replace("'", "", $matricula);

            $senha_login = $data['senha_login'];
            $senha_login = str_replace("'", "", $senha_login);

            $id_tipo_funcionario = $data['id_tipo_funcionario'];
            $id_tipo_funcionario = str_replace("'", "", $id_tipo_funcionario);

            try {
                $sql = 'INSERT INTO funcionario values (null,
                                                        :nome,
                                                        :cpf,
                                                        :data_nascimento,
                                                        :matricula,
                                                        :senha_login,
                                                        :id_tipo_funcionario)';

                $result = $conn->prepare($sql);
                $result->bindParam(':nome', $nome);
                $result->bindParam(':cpf', $cpf);
                $result->bindParam(':data_nascimento', $data_nascimento);
                $result->bindParam(':matricula', $matricula);
                $result->bindParam(':senha_login', $senha_login);
                $result->bindParam(':id_tipo_funcionario', $id_tipo_funcionario);
                $result->execute();

                $id = $conn->lastInsertId();
                echo $id;
            } catch (PDOException $e) {
                echo 'ERRO postFuncionario ' . $e->getMessage();
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
            $cpf = str_replace("-", "", $cpf);
            $cpf = str_replace(".", "", $cpf);

            $data_nascimento = $data['data_nascimento'];
            $data_nascimento = str_replace("'", "", $data_nascimento);

            $matricula = $data['matricula'];
            $matricula = str_replace("'", "", $matricula);

            $senha_login = $data['senha_login'];
            $senha_login = str_replace("'", "", $senha_login);

            $id_tipo_funcionario = $data['id_tipo_funcionario'];
            $id_tipo_funcionario = str_replace("'", "", $id_tipo_funcionario);

            foreach ($data as $key => $value) {
                if ($key !== 'id') {
                    try {
                        $sql = 'UPDATE funcionario set ' . $key . ' = :value where id = :id';
                        $result = $conn->prepare($sql);
                        $result->bindParam(':value', $value);
                        $result->bindParam(':id', $id);
                        $result->execute();

                    } catch (PDOException $e) {
                        echo 'ERRO putFuncionario ' . $e->getMessage();
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
                $sql = 'DELETE from funcionario where id = :id';

                $result = $conn->prepare($sql);
                $result->bindParam(':id', $id);
                $result->execute();

                if ($result) {
                    echo $id;
                }
            }catch (PDOException $e) {
                echo 'ERROR: ' . $e->getMessage();
                echo 'SQL deleteFuncionario';
            }
            break;
    }
?>