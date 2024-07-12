<?php
$app->group('/usuarios', function($app){
    //Consulta todos los usuarios
    $app->get('/buscartodos', function($request, $response,$args){
        try {
            $sql = "SELECT * FROM usuarios";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $dbc = null;
            if ($data) {
                $json = json_encode(['status' => true, 'code' => 200, 'data' => $data ]);
            }else{
                $json = json_encode(['status' => false, 'code' => 401, 'data' => "No se encontraron usuarios"]);
            }
        } catch (PDOException $error) {
            $message = $error->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });
    //Consultar un usuario
    $app->get('/buscar/{idUsuario}', function($request, $response, $args){
        try {
            $idUsuario = $args['idUsuario'];
            $sql = "SELECT * FROM usuarios WHERE idUsuario = '$idUsuario'";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $dbc = null;
            if($data){
                $json = json_encode(['status' => true, 'code' => 200, 'data' => $data]);
            }else{
                $json = json_encode(['status' => false, 'code' => 401, 'data' => 'no se encontro al usuario']);
            }
        } catch (PDOException $error) {
            $message = $error->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });
    //Creacion del usuario
    $app->post('/crear', function($request, $response, $args){
        try {
            $data = $request->getParsedBody();
            $uuid = gene_id();
            $clave = $data["clave"];
            $clave = gene_encryp($clave);
            $estatus = 1;
            $sql = "INSERT INTO usuarios (idUsuario, nombreUsuario, correo, clave, nombres, apellidos, telefono, domicilio, estatus, idRol) VALUES (:idUsuario, :nombreUsuario, :correo, :clave, :nombres, :apellidos, :telefono, :domicilio, :estatus, :idRol)";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->prepare($sql);
            $stmt->bindParam("idUsuario", $uuid);
            $stmt->bindParam("nombreUsuario", $data["nombreUsuario"]);
            $stmt->bindParam("correo", $data["correo"]);
            $stmt->bindParam("clave", $clave);
            $stmt->bindParam("nombres", $data["nombres"]);
            $stmt->bindParam("apellidos", $data["apellidos"]);
            $stmt->bindParam("telefono", $data["telefono"]);
            $stmt->bindParam("domicilio", $data["domicilio"]);
            $stmt->bindParam("estatus", $estatus);
            $stmt->bindParam("idRol", $data["idRol"]);
            $stmt->execute();
            $dbc = null;
            if ($data) {
                $json = json_encode(['status' => true, 'code' => 200, 'data' => 'Fue generado el usuario']);
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
    //Actualización del usuario
    $app->put('/editar', function($request, $response, $args){
        try {
            $data = $request->getParsedBody();
            $clave = $data["clave"];
            $clave = gene_encryp($clave);
            $sql = "UPDATE usuarios SET nombreUsuario= :nombreUsuario, correo= :correo, clave= :clave, nombres= :nombres, apellidos= :apellidos, telefono= :telefono, domicilio= :domicilio, estatus= :estatus, idRol= :idRol WHERE idUsuario= :idUsuario";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->prepare($sql);
            $stmt->bindParam("idUsuario", $data["idUsuario"]);

            $stmt->bindParam("nombreUsuario", $data["nombreUsuario"]);
            $stmt->bindParam("correo", $data["correo"]);
            $stmt->bindParam("clave", $clave);
            $stmt->bindParam("nombres", $data["nombres"]);
            $stmt->bindParam("apellidos", $data["apellidos"]);
            $stmt->bindParam("telefono", $data["telefono"]);
            $stmt->bindParam("domicilio", $data["domicilio"]);
            $stmt->bindParam("estatus", $data["estatus"]);
            $stmt->bindParam("idRol", $data["idRol"]);
            $stmt->execute();
            $dbc = null;
            if($data){
                $json = json_encode(['status' => true, 'code' => 200, 'data' => $data]);
            }else{
                $json = json_encode(['status' => false, 'code' => 401, 'data' => 'No se encontro el usuario']);
            }
        } catch (PDOException $error) {
            $message = $error->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });
    //Eliminar usuario
    $app->delete('/eliminar/{idUsuario}', function($request, $response, $args){
        try {
            $idUsuario = $args['idUsuario'];
            $sql = "DELETE FROM usuarios WHERE idUsuario = '$idUsuario'";
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
    //login web
    $app->post('/loginweb', function ($request, $response, $args) {
        try {
            $usuario = $request->getParsedBody();
            $correo = $usuario["correo"];
            $nombreUsuario = $usuario["nombreUsuario"];
            $clave = gene_encryp($usuario["clave"]);
            $sql = "SELECT * FROM usuarios WHERE Estatus=1 AND (correo = '$correo' OR  nombreUsuario = '$nombreUsuario') AND clave = '$clave'";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->query($sql);
            $usuario = $stmt->fetchAll(PDO::FETCH_OBJ);
            $dbc = null;
            if ($usuario) {
                $json = json_encode(['status' => true, 'code' => 200, 'data' => $usuario[0]]);
            } else {
                $json = json_encode(['status' => false, 'code' => 400, 'data' => 'El correo o la contraseña son incorrectas']);
            }
        } catch (PDOException $err) {
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $err->getMessage()]);
        }
        $response->getBody()->write($json);
        return $response;
    });
    //Recuperacion de contraseña
    $app->get('/recuperar/{correo}', function($request, $response, $args){
        try {
            $correo = $args['correo'];
            $sql = "SELECT * FROM usuarios WHERE correo = '$correo'";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->query($sql);
            
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $clave = gene_decryp($data[0]["clave"]);
            $dbc = null;
            if($data){
                $json = json_encode(['status' => true, 'code' => 200, 'data' => $clave]);
            }else{
                $json = json_encode(['status' => false, 'code' => 401, 'data' => 'no se encontro al usuario']);
            }
        } catch (PDOException $error) {
            $message = $error->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });
    //Obtener usuarios activos
    //Consulta todos los usuarios
    $app->get('/buscarActivos', function($request, $response,$args){
        try {
            $sql = "SELECT usu.*, rol.nombre as idRol FROM usuarios as usu INNER JOIN Roles as rol WHERE usu.estatus = 1 AND usu.idRol = rol.idRol";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $dbc = null;
            if ($data) {
                $json = json_encode(['status' => true, 'code' => 200, 'data' => $data ]);
            }else{
                $json = json_encode(['status' => false, 'code' => 401, 'data' => "No se encontraron usuarios"]);
            }
        } catch (PDOException $error) {
            $message = $error->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });
});
?>