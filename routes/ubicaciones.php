<?php
$app->group('/ubicaciones', function($app){
    //Consulta todos los ubicaciones
    $app->get('/buscartodos', function($request, $response,$args){
        try {
            $sql = "SELECT * FROM ubicaciones";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $dbc = null;
            if ($data) {
                $json = json_encode(['status' => true, 'code' => 200, 'data' => $data ]);
            }else{
                $json = json_encode(['status' => false, 'code' => 401, 'data' => "No se encontraron ubicaciones"]);
            }
        } catch (PDOException $error) {
            $message = $error->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });
    //Consultar un Ubicacion
    $app->get('/buscar/{idUbicacion}', function($request, $response, $args){
        try {
            $idUbicacion = $args['idUbicacion'];
            $sql = "SELECT * FROM ubicaciones WHERE idUbicacion = '$idUbicacion'";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $dbc = null;
            if($data){
                $json = json_encode(['status' => true, 'code' => 200, 'data' => $data]);
            }else{
                $json = json_encode(['status' => false, 'code' => 401, 'data' => 'no se encontro la ubicacion']);
            }
        } catch (PDOException $error) {
            $message = $error->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });
    //Creacion del ubicaciones
    $app->post('/crear', function($request, $response, $args){
        try {
            $data = $request->getParsedBody();
            $sql = "INSERT INTO ubicaciones (nombre, figura, descripcion, planta) VALUES (:nombre, :figura, :descripcion, :planta)";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->prepare($sql);
            $stmt->bindParam("nombre", $data["nombre"]);
            $stmt->bindParam("figura", $data["figura"]);
            $stmt->bindParam("descripcion", $data["descripcion"]);
            $stmt->bindParam("planta", $data["planta"]);
            $stmt->execute();
            $dbc = null;
            if ($data) {
                $json = json_encode(['status' => true, 'code' => 200, 'data' => 'Fue generado el ubicaciones']);
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
    //Actualización del ubicaciones
    $app->put('/editar', function($request, $response, $args){
        try {
            $data = $request->getParsedBody();
            $sql = "UPDATE ubicaciones SET nombre= :nombre, figura= :figura, descripcion= :descripcion, planta= :planta WHERE idUbicacion= :idUbicacion";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->prepare($sql);
            $stmt->bindParam("idUbicacion", $data["idUbicacion"]);

            $stmt->bindParam("nombre", $data["nombre"]);
            $stmt->bindParam("figura", $data["figura"]);
            $stmt->bindParam("descripcion", $data["descripcion"]);
            $stmt->bindParam("planta", $data["planta"]);
            $stmt->execute();
            $dbc = null;
            if($data){
                $json = json_encode(['status' => true, 'code' => 200, 'data' => $data]);
            }else{
                $json = json_encode(['status' => false, 'code' => 401, 'data' => 'No se encontro la ubicacion']);
            }
        } catch (PDOException $error) {
            $message = $error->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });
    //Eliminar Ubicacion
    $app->delete('/eliminar/{idUbicacion}', function($request, $response, $args){
        try {
            $idUbicacion = $args['idUbicacion'];
            $sql = "DELETE FROM ubicaciones WHERE idUbicacion = '$idUbicacion'";
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