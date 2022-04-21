 
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

            try {
                $sql = 'SELECT t.id,
                                t.id_localidade_inicio,
                                l.nome as local_inicio,
                                t.id_localidade_fim,
                                lf.nome as local_fim,
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
                                on t.id_rastreador = r.id
                                where r.situacao = 1';

                if (isset($_REQUEST['id'])) {
                    $sql = $sql . ' and f.id = :id';
                    $result = $conn->prepare($sql);
                    $result->bindParam(':id', $id);
                } else {
                    $result = $conn->prepare($sql);
                }

                $result->execute();
                $getFuncionario = $result->fetchAll();

                foreach ($getFuncionario as $value) {
                    $id = $value['id'];
                    $id_localidade_inicio = $value['id_localidade_inicio'];
                    $local_inicio = $value['local_inicio'];
                    $id_localidade_fim = $value['id_localidade_inicio'];
                    $local_fim = $value['local_fim'];
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
            } catch (PDOException  $e) {
                echo 'ERRO getFuncionario ' . $e->getMessage();
            }
            break;
    }
?>