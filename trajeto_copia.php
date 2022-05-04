<?php
    include "conexao.php";

    header('content-type:text/html;charset=utf-8');
    date_default_timezone_set('America/Sao_Paulo');
    
    $http_origin = $_SERVER['HTTP_ORIGIN'];
    
    if ($http_origin == "http://localhost:3000") {
        header("Access-Control-Allow-Origin: " . $http_origin);
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Max-Age: 1000');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    }
    
    $method = $_SERVER['REQUEST_METHOD'];
    
    
    
    switch($method){
        case 'GET':
            $id = $_REQUEST['id'];
            $id = preg_replace('/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/', '', $id);
            $id = str_replace("'", "", $id);

            $id_funcionario = $_REQUEST['id_funcionario'];
            $id_funcionario = preg_replace('/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/', '', $id_funcionario);
            $id_funcionario = str_replace("'", "", $id_funcionario);

            try {
                $sql = 'SELECT t.id,
                                t.id_localidade_inicio,
                                l.nome as local_inicio,
                                t.id_localidade_fim,
                                lf.nome as local_fim,
                                t.id_funcionario,
                                f.nome as funcionario,
                                t.id_veiculo,
                                v.placa as veiculo,
                                t.id_rastreador,
                                t.horario_partida,
                                t.horario_chegada,
                                r.latitude,
                                r.longitude
                                from trajeto t
                                inner join localidade l
                                on t.id_localidade_inicio = l.id
                                inner join localidade lf
                                on t.id_localidade_fim = lf.id
                                inner join funcionario f
                                on t.id_funcionario = f.id
                                inner join veiculo v
                                on t.id_veiculo = v.id
                                inner join rastreador r
                                on t.id_rastreador = r.id';

                if (isset($_REQUEST['id'])) {
                    $sql = $sql . ' where t.id = :id';
                    $result = $conn->prepare($sql);
                    $result->bindParam(':id', $id);

                    $result->execute();
                    $getFuncionario = $result->fetchAll();

                    foreach ($getFuncionario as $value) {
                        $id = $value['id'];
                        $id_localidade_inicio = $value['id_localidade_inicio'];
                        $local_inicio = $value['local_inicio'];
                        $id_localidade_fim = $value['id_localidade_inicio'];
                        $local_fim = $value['local_fim'];
                        $id_funcionario = $value['id_funcionario'];
                        $funcionario = $value['funcionario'];
                        $id_veiculo = $value['id_veiculo'];
                        $veiculo = $value['veiculo'];
                        $id_rastreador = $value['id_rastreador'];
                        $latitude = $value['latitude'];
                        $longitude = $value['longitude'];
                        $horario_partida = $value['horario_partida'];
                        $horario_chegada = $value['horario_chegada'];

                        $json [] = [
                            "id" => $id,
                            "rota" => $local_inicio . ' - ' . $local_fim,
                            "id_localidade_inicio" => $id_localidade_inicio,
                            "local_inicio" => $local_inicio,
                            "id_localidade_fim" => $id_localidade_fim,
                            "local_fim" => $local_fim,
                            "id_funcionario" => $id_funcionario,
                            "funcionario" => $funcionario,
                            "id_veiculo" => $id_veiculo,
                            "veiculo" => $veiculo,
                            "id_rastreador" => $id_rastreador,
                            "latitude" => $latitude,
                            "longitude" => $longitude,
                            "horario_partida" => $horario_partida,
                            "horario_chegada" => $horario_chegada
                        ];
                    }
                    echo json_encode($json, JSON_PRETTY_PRINT);
                }else if (isset($_REQUEST['id_funcionario'])) {
                    $sql = $sql . ' where t.id_funcionario = :id_funcionario';
                    $result = $conn->prepare($sql);
                    $result->bindParam(':id_funcionario', $id_funcionario);

                    $result->execute();
                    $getFuncionario = $result->fetchAll();

                    foreach ($getFuncionario as $value) {
                        $id = $value['id'];
                        $id_localidade_inicio = $value['id_localidade_inicio'];
                        $local_inicio = $value['local_inicio'];
                        $id_localidade_fim = $value['id_localidade_inicio'];
                        $local_fim = $value['local_fim'];
                        $id_funcionario = $value['id_funcionario'];
                        $funcionario = $value['funcionario'];
                        $id_veiculo = $value['id_veiculo'];
                        $veiculo = $value['veiculo'];
                        $id_rastreador = $value['id_rastreador'];
                        $latitude = $value['latitude'];
                        $longitude = $value['longitude'];
                        $horario_partida = $value['horario_partida'];
                        $horario_chegada = $value['horario_chegada'];

                        $json [] = [
                            "id" => $id,
                            "rota" => $local_inicio . ' - ' . $local_fim,
                            "id_localidade_inicio" => $id_localidade_inicio,
                            "local_inicio" => $local_inicio,
                            "id_localidade_fim" => $id_localidade_fim,
                            "local_fim" => $local_fim,
                            "id_funcionario" => $id_funcionario,
                            "funcionario" => $funcionario,
                            "id_veiculo" => $id_veiculo,
                            "veiculo" => $veiculo,
                            "id_rastreador" => $id_rastreador,
                            "latitude" => $latitude,
                            "longitude" => $longitude,
                            "horario_partida" => $horario_partida,
                            "horario_chegada" => $horario_chegada
                        ];
                    }
                    echo json_encode($json, JSON_PRETTY_PRINT);

                } else {
                    $result = $conn->prepare($sql);
                    $result->execute();
                $getFuncionario = $result->fetchAll();

                foreach ($getFuncionario as $value) {
                    $id = $value['id'];
                    $id_localidade_inicio = $value['id_localidade_inicio'];
                    $local_inicio = $value['local_inicio'];
                    $id_localidade_fim = $value['id_localidade_inicio'];
                    $local_fim = $value['local_fim'];
                    $id_funcionario = $value['id_funcionario'];
                    $funcionario = $value['funcionario'];
                    $id_veiculo = $value['id_veiculo'];
                    $veiculo = $value['veiculo'];
                    $id_rastreador = $value['id_rastreador'];
                    $latitude = $value['latitude'];
                    $longitude = $value['longitude'];
                    $horario_partida = $value['horario_partida'];
                    $horario_chegada = $value['horario_chegada'];

                    $json [] = [
                        "id" => $id,
                        "rota" => $local_inicio . ' - ' . $local_fim,
                        "id_localidade_inicio" => $id_localidade_inicio,
                        "local_inicio" => $local_inicio,
                        "id_localidade_fim" => $id_localidade_fim,
                        "local_fim" => $local_fim,
                        "id_funcionario" => $id_funcionario,
                        "funcionario" => $funcionario,
                        "id_veiculo" => $id_veiculo,
                        "veiculo" => $veiculo,
                        "id_rastreador" => $id_rastreador,
                        "latitude" => $latitude,
                        "longitude" => $longitude,
                        "horario_partida" => $horario_partida,
                        "horario_chegada" => $horario_chegada
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
            $data = json_decode($data, true);

            $id_local_inicio = $data['id_local_inicio'];
            $id_local_inicio = str_replace("'", "", $id_local_inicio);
            $id_local_inicio = str_replace("'\'", "", $id_local_inicio);

            $id_local_fim = $data['id_local_fim'];
            $id_local_fim = str_replace("'", "", $id_local_fim);

            $id_funcionario = $data['id_funcionario'];
            $id_funcionario = str_replace("'", "", $id_funcionario);

            $id_veiculo = $data['id_veiculo'];
            $id_veiculo = str_replace("'", "", $id_veiculo);

            $id_rastreador = $data['id_rastreador'];
            $id_rastreador = str_replace("'", "", $id_rastreador);

            $horario_partida = $data['horario_partida'];
            $horario_partida = str_replace("'", "", $horario_partida);

            $horario_chegada = $data['horario_chegada'];
            $horario_chegada = str_replace("'", "", $horario_chegada);

            try {
                $sql = 'INSERT INTO trajeto values (null,
                                                        :id_local_inicio,
                                                        :id_local_fim,
                                                        :id_funcionario,
                                                        :id_veiculo,
                                                        :id_rastreador,
                                                        :horario_partida,
                                                        :horario_chegada)';

                $result = $conn->prepare($sql);
                $result->bindParam(':id_local_inicio', $id_local_inicio);
                $result->bindParam(':id_local_fim', $id_local_fim);
                $result->bindParam(':id_funcionario', $id_funcionario);
                $result->bindParam(':id_veiculo', $id_veiculo);
                $result->bindParam(':id_rastreador', $id_rastreador);
                $result->bindParam(':horario_partida', $horario_partida);
                $result->bindParam(':horario_chegada', $horario_chegada);
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

            $id_local_inicio = $data['id_local_inicio'];
            $id_local_inicio = str_replace("'", "", $id_local_inicio);

            $id_local_fim = $data['id_local_fim'];
            $id_local_fim = str_replace("'", "", $id_local_fim);

            $id_funcionario = $data['id_funcionario'];
            $id_funcionario = str_replace("'", "", $id_funcionario);

            $id_veiculo = $data['id_veiculo'];
            $id_veiculo = str_replace("'", "", $id_veiculo);

            $id_rastreador = $data['id_rastreador'];
            $id_rastreador = str_replace("'", "", $id_rastreador);

            $horario_partida = $data['horario_partida'];
            $horario_partida = str_replace("'", "", $horario_partida);

            $horario_chegada = $data['horario_chegada'];
            $horario_chegada = str_replace("'", "", $horario_chegada);

            foreach ($data as $key => $value) {
                if ($key !== 'id') {
                    try {
                        $sql = 'UPDATE trajeto set ' . $key . ' = :value where id = :id';
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
                $sql = 'DELETE from trajeto where id = :id';

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