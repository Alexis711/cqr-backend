<?php
$app->group('/eventos', function($app){
    //Consulta todos los eventos
    $app->get('/buscartodos', function($request, $response,$args){
        try {
            $sql = "SELECT * FROM eventos";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $dbc = null;
            if ($data) {
                $json = json_encode(['status' => true, 'code' => 200, 'data' => $data ]);
            }else{
                $json = json_encode(['status' => false, 'code' => 401, 'data' => "No se encontraron eventos"]);
            }
        } catch (PDOException $error) {
            $message = $error->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });
    //Consultar el evento
    $app->get('/buscar/{idEvento}', function($request, $response, $args){
        try {
            $idEvento = $args['idEvento'];
            $sql = "SELECT * FROM eventos WHERE idEvento = '$idEvento'";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $dbc = null;
            if($data){
                $json = json_encode(['status' => true, 'code' => 200, 'data' => $data]);
            }else{
                $json = json_encode(['status' => false, 'code' => 401, 'data' => 'no se encontro el evento']);
            }
        } catch (PDOException $error) {
            $message = $error->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });
    //Creacion del eventos
    $app->post('/crear', function($request, $response, $args){
        try {
            $data = $request->getParsedBody();
            $sql = "INSERT INTO eventos (nombre, grupo, fechaInicio, fechaFinal, horaEntrada, horaSalida, tipoEvento, repeticion, diaSemana, idUbicacion, idUsuario) VALUES (:nombre, :grupo, :fechaInicio, :fechaFinal, :horaEntrada, :horaSalida, :tipoEvento, :repeticion, :diaSemana, :idUbicacion, :idUsuario)";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->prepare($sql);
            $stmt->bindParam("nombre", $data["nombre"]);
            $stmt->bindParam("grupo", $data["grupo"]);
            $stmt->bindParam("fechaInicio", $data["fechaInicio"]);
            $stmt->bindParam("fechaFinal", $data["fechaFinal"]);
            $stmt->bindParam("horaEntrada", $data["horaEntrada"]);
            $stmt->bindParam("horaSalida", $data["horaSalida"]);
            $stmt->bindParam("tipoEvento", $data["tipoEvento"]);
            $stmt->bindParam("repeticion", $data["repeticion"]);
            $stmt->bindParam("diaSemana", $data["diaSemana"]);
            $stmt->bindParam("idUbicacion", $data["idUbicacion"]);
            $stmt->bindParam("idUsuario", $data["idUsuario"]);
            $stmt->execute();
            $dbc = null;
            if ($data) {
                $json = json_encode(['status' => true, 'code' => 200, 'data' => 'Fue generado el evento']);
            }else{
                $json = json_encode(['status' => false, 'code' => 401, 'data' => 'Ocurrio un error en la generación']);
            }
        } catch (PDOException $error) {
            $message = $error->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });
    //Actualización del eventos
    $app->put('/editar', function($request, $response, $args){
        try {
            $data = $request->getParsedBody();
            $sql = "UPDATE eventos SET nombre= :nombre, grupo= :grupo, fechaInicio= :fechaInicio, fechaFinal= :fechaFinal, horaEntrada= :horaEntrada, horaSalida= :horaSalida, tipoEvento= :tipoEvento, repeticion= :repeticion, diaSemana= :diaSemana, idUbicacion= :idUbicacion, idUsuario= :idUsuario WHERE idEvento= :idEvento";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->prepare($sql);
            $stmt->bindParam("idEvento", $data["idEvento"]);
            $stmt->bindParam("nombre", $data["nombre"]);
            $stmt->bindParam("grupo", $data["grupo"]);
            $stmt->bindParam("fechaInicio", $data["fechaInicio"]);
            $stmt->bindParam("fechaFinal", $data["fechaFinal"]);
            $stmt->bindParam("horaEntrada", $data["horaEntrada"]);
            $stmt->bindParam("horaSalida", $data["horaSalida"]);
            $stmt->bindParam("tipoEvento", $data["tipoEvento"]);
            $stmt->bindParam("repeticion", $data["repeticion"]);
            $stmt->bindParam("diaSemana", $data["diaSemana"]);
            $stmt->bindParam("idUbicacion", $data["idUbicacion"]);
            $stmt->bindParam("idUsuario", $data["idUsuario"]);
            $stmt->execute();
            $dbc = null;
            if($data){
                $json = json_encode(['status' => true, 'code' => 200, 'data' => $data]);
            }else{
                $json = json_encode(['status' => false, 'code' => 401, 'data' => 'No se encontro el evento']);
            }
        } catch (PDOException $error) {
            $message = $error->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });
    //Eliminar Evento
    $app->delete('/eliminar/{idEvento}', function($request, $response, $args){
        try {
            $idEvento = $args['idEvento'];
            $sql = "DELETE FROM eventos WHERE idEvento = '$idEvento'";
            $dbc = new db();
            $dbc = $dbc-> connect();
            $stmt = $dbc->query($sql);
            $data = $stmt->fetchAll(pdo::FETCH_OBJ);
            $dbc = null;
            $json = json_encode(['status' => true, 'code' => 200, 'data' => 'Elemento eliminado']);

        } catch (PDOException $error) {
            $message = $error->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });
});
?>