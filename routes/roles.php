<?php
$app->group('/roles', function($app){
    //Consulta todos los roles
    $app->get('/buscartodos', function($request, $response,$args){
        try {
            $sql = "SELECT * FROM roles";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $dbc = null;
            if ($data) {
                $json = json_encode(['status' => true, 'code' => 200, 'data' => $data ]);
            }else{
                $json = json_encode(['status' => false, 'code' => 401, 'data' => "No se encontraron roles"]);
            }
        } catch (PDOException $error) {
            $message = $error->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });
    //Consultar un rol
    $app->get('/buscar/{idRol}', function($request, $response, $args){
        try {
            $idRol = $args['idRol'];
            $sql = "SELECT * FROM roles WHERE idRol = '$idRol'";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $dbc = null;
            if($data){
                $json = json_encode(['status' => true, 'code' => 200, 'data' => $data]);
            }else{
                $json = json_encode(['status' => false, 'code' => 401, 'data' => 'no se encontro al rol']);
            }
        } catch (PDOException $error) {
            $message = $error->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });
    //Creacion del roles
    $app->post('/crear', function($request, $response, $args){
        try {
            $data = $request->getParsedBody();
            $sql = "INSERT INTO roles (nombre, descripcion, tipo) VALUES (:nombre, :descripcion, :tipo)";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->prepare($sql);
            $stmt->bindParam("nombre", $data["nombre"]);
            $stmt->bindParam("descripcion", $data["descripcion"]);
            $stmt->bindParam("tipo", $data["tipo"]);
            $stmt->execute();
            $dbc = null;
            if ($data) {
                $json = json_encode(['status' => true, 'code' => 200, 'data' => 'Fue generado el roles']);
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
    //Actualización del roles
    $app->put('/editar', function($request, $response, $args){
        try {
            $data = $request->getParsedBody();
            $sql = "UPDATE roles SET nombre= :nombre, descripcion= :descripcion, tipo= :tipo WHERE idRol= :idRol";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->prepare($sql);
            $stmt->bindParam("idRol", $data["idRol"]);

            $stmt->bindParam("nombre", $data["nombre"]);
            $stmt->bindParam("descripcion", $data["descripcion"]);
            $stmt->bindParam("tipo", $data["tipo"]);
            $stmt->execute();
            $dbc = null;
            if($data){
                $json = json_encode(['status' => true, 'code' => 200, 'data' => $data]);
            }else{
                $json = json_encode(['status' => false, 'code' => 401, 'data' => 'No se encontro el rol']);
            }
        } catch (PDOException $error) {
            $message = $error->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });
    //Eliminar rol
    $app->delete('/eliminar/{idRol}', function($request, $response, $args){
        try {
            $idRol = $args['idRol'];
            $sql = "DELETE FROM roles WHERE idRol = '$idRol'";
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