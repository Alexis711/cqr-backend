<?php
$app->group('/asistencias', function ($app) {
    //Consulta todas las asistencias
    $app->get('/buscartodos', function ($request, $response, $args) {
        try {
            $sql = "SELECT * FROM asistencias";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $dbc = null;
            if ($data) {
                $json = json_encode(['status' => true, 'code' => 200, 'data' => $data]);
            } else {
                $json = json_encode(['status' => false, 'code' => 401, 'data' => 'No se encontraron asistencias']);
            }
        } catch (PDOException $error) {
            $message = $error->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });

    //Consulta una asistencia
    $app->get('/buscar/{idAsistencia}', function ($request, $response, $args) {
        try {
            $idAsistencia = $args['idAsistencia'];
            $sql = "SELECT * FROM asistencias WHERE idAsistencia = '$idAsistencia'";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $dbc = null;
            if ($data) {
                $json = json_encode(['status' => true, 'code' => 200, 'data' => $data]);
            } else {
                $json = json_encode(['status' => false, 'code' => 401, 'data' => 'No se encontro la asistencia']);
            }
        } catch (PDOException $error) {
            $message = $error->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });

    //Solicitud de creacion de asistencia
    $app->post('/crear', function ($request, $response, $args) {
        try {
            $data = $request->getParsedBody();
            $uuid = gene_id();
            $date = gene_date();
            $time = gene_time();
            $dia = gene_week_day();
            $estatus = 0;
            $sql = "INSERT INTO asistencias (idAsistencia, fechaActual, horaEntrada, horaSalida, estatus, notas, dia, idEvento, idUsuario) 
            VALUES (:idAsistencia, :fechaActual, :horaEntrada, :horaSalida, :estatus, :notas, :dia, :idEvento, :idUsuario)";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->prepare($sql);
            $stmt->bindParam("idAsistencia", $uuid);
            $stmt->bindParam("fechaActual", $date);
            $stmt->bindParam("horaEntrada", $time);
            $stmt->bindParam("horaSalida", $data["horaSalida"]);
            $stmt->bindParam("estatus", $estatus);
            $stmt->bindParam("notas", $data["notas"]);
            $stmt->bindParam("dia", $dia);
            $stmt->bindParam("idEvento", $data["idEvento"]);
            $stmt->bindParam("idUsuario", $data["idUsuario"]);
            $stmt->execute();
            $dbc = null;
            if ($data) {
                $json = json_encode(['status' => true, 'code' => 200, 'data' => 'Fue generada la asistencia']);
            }
        } catch (PDOException $error) {
            $message = $error->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });
    $app->put('/editar', function ($request, $response, $args) {
        try {
            $asistencia = $request->getParsedBody();
            $sql = "UPDATE asistencias SET horaSalida= :horaSalida,estatus= :estatus,notas= :notas WHERE idAsistencia= :idAsistencia";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->prepare($sql);
            $stmt->bindParam("idAsistencia", $asistencia["idAsistencia"]);
            $stmt->bindParam("horaSalida", $asistencia["horaSalida"]);
            $stmt->bindParam("estatus", $asistencia["estatus"]);
            $stmt->bindParam("notas", $asistencia["notas"]);
            $stmt->execute();
            $dbc = null;
            if ($asistencia) {
                $json = json_encode(['status' => true, 'code' => 200, 'data' => "Asistencia actualizada"]);
            } else {
                $json = json_encode(['status' => false, 'code' => 401, 'data' => "No se encontro la asistencia"]);
            }
        } catch (PDOException $error) {
            $message = $error->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });

    $app->delete('/eliminar/{idAsistencia}', function ($request, $response, $args) {
        try {
            $idAsistencia = $args['idAsistencia'];
            $sql = "DELETE FROM asistencias WHERE idAsistencia = '$idAsistencia'";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->query($sql);
            $dbc =  null;
            $json = json_encode(['status' => true, 'code' => 200, 'data' => 'Elemento eliminado']);
        } catch (PDOException $ERROR) {
            $mensaje = $ERROR->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $mensaje]);
        }
        $response->getBody()->write($json);
        return $response;
    });
    //Asistencias mes actual
    $app->get('/buscartodosmesactual/{fechaInicio}/{fechaFin}', function ($request, $response, $args) {
        try {
            //$fechaInicio = "2024-07-01";
            //$fechaFin = "2024-07-31";
            $fechaInicio = $args['fechaInicio'];
            $fechaFin = $args['fechaFin'];
            //$sql = "SELECT asi.*, usu.nombres as nombresUsuario, usu.apellidos as apellidosUsuarios, eve.nombre as nombreEvento, eve.horaEntrada as horaEntradaEvento, eve.horaSalida as horaSalidaEvento FROM asistencias AS asi INNER JOIN usuarios AS usu INNER JOIN eventos AS eve WHERE asi.estatus != 0 AND asi.idUsuario = usu.idUsuario AND fechaActual >= DATE('$fechaInicio') AND fechaActual <= DATE('$fechaFin')";
            $sql = "SELECT asi.*, usu.nombres as nombresUsuario, usu.apellidos as apellidosUsuarios, eve.nombre as nombreEvento, eve.horaEntrada as horaEntradaEvento, eve.horaSalida as horaSalidaEvento, eve.grupo as grupo, ubi.nombre as salon FROM asistencias AS asi INNER JOIN usuarios AS usu ON asi.idUsuario = usu.idUsuario INNER JOIN eventos AS eve ON asi.idEvento = eve.idEvento INNER JOIN ubicaciones AS ubi ON eve.idUbicacion = ubi.idUbicacion INNER JOIN dias AS dia ON asi.idEvento = dia.idEvento WHERE asi.estatus != 0 AND asi.fechaActual >= DATE('$fechaInicio') AND asi.fechaActual <= DATE('$fechaFin')";

            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $dbc = null;
            $json = json_encode(['status' => true, 'code' => 200, 'data' => $data]);
            /*if ($data) {
                $json = json_encode(['status' => true, 'code' => 200, 'data' => $data]);
            } else {
                $json = json_encode(['status' => false, 'code' => 401, 'data' => 'No se encontro la asistencia']);
            }*/
        } catch (PDOException $ERROR) {
            $message = $ERROR->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });
    //Eliminacion logica
    $app->put('/eliminacionlogica/{idAsistencia}', function ($request, $response, $args) {
        try {
            //$data = $request->getParsedBody();
            $idAsistencia = $args["idAsistencia"];
            $estatus = 0;
            $sql = "UPDATE asistencias SET estatus= :estatus WHERE idAsistencia= :idAsistencia";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->prepare($sql);
            $stmt->bindParam("idAsistencia", $idAsistencia);
            $stmt->bindParam("estatus", $estatus);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $dbc = null;
            $json = json_encode(['status' => true, 'code' => 200, 'data' => "Se elimino correctamente"]);
            /*if($data){
                $json = json_encode(['status' => true, 'code' => 200, 'data' => $data]);
            }else{
                $json = json_encode(['status' => false, 'code' => 401, 'data' => 'No se encontro la asistencia']);
            }*/
        } catch (PDOException $error) {
            $message = $error->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });
    //Funcion de asistencia por dia y hora
    $app->get('/asistenciapordia/{horaActual}', function ($request, $response, $args) {
        try {
            $arrayDias = ["domingo", "lunes", "martes", "miercoles", "jueves", "viernes", "sabado"];
            $diaSemana = gene_week_day();
            $horaActual = $args['horaActual'];
            $fechaActual = gene_date();
            $sql = "SELECT asi.*, usu.nombres as nombresUsuario, usu.apellidos as apellidosUsuarios, eve.nombre as nombreEvento, eve.grupo as grupo, ubi.nombre as salon, eve.horaEntrada as horaEntradaEvento, eve.horaSalida as horaSalidaEvento FROM asistencias AS asi INNER JOIN usuarios AS usu ON asi.idUsuario = usu.idUsuario INNER JOIN eventos AS eve ON asi.idEvento = eve.idEvento INNER JOIN ubicaciones AS ubi ON eve.idUbicacion = ubi.idUbicacion INNER JOIN dias AS dia ON asi.idEvento = dia.idEvento WHERE asi.estatus != 0 AND (dia.$arrayDias[$diaSemana] = 1) AND asi.fechaActual = DATE('$fechaActual') AND ('$horaActual' >= eve.horaEntrada AND '$horaActual' <= eve.horaSalida )";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $dbc = null;
            if ($data) {
                $json = json_encode(['status' => true, 'code' => 200, 'data' => $data]);
            } else {
                $json = json_encode(['status' => false, 'code' => 401, 'data' => 'No hay asistencias']);
            }
        } catch (PDOException $ERROR) {
            $message = $ERROR->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });
    //Funcion de asistencia por dia, hora y grupo
    $app->get('/asistenciaporgrupo/{fechaInicio}/{fechaFin}/{grupo}', function ($request, $response, $args) {
        try {
            $arrayDias = ["domingo", "lunes", "martes", "miercoles", "jueves", "viernes", "sabado"];
            $diaSemana = gene_week_day();
            $grupo = $args['grupo'];
            $fechaInicio = $args['fechaInicio'];
            $fechaFin = $args['fechaFin'];
            //$sql = "SELECT asi.*, usu.nombres as nombresUsuario, usu.apellidos as apellidosUsuarios, eve.nombre as nombreEvento, eve.grupo as grupo, ubi.nombre as salon, eve.horaEntrada as horaEntradaEvento, eve.horaSalida as horaSalidaEvento FROM asistencias AS asi INNER JOIN usuarios AS usu ON asi.idUsuario = usu.idUsuario INNER JOIN eventos AS eve ON asi.idEvento = eve.idEvento INNER JOIN ubicaciones AS ubi ON eve.idUbicacion = ubi.idUbicacion INNER JOIN dias AS dia ON asi.idEvento = dia.idEvento WHERE asi.estatus != 0 AND (dia.$arrayDias[$diaSemana] = 1) AND asi.fechaActual = DATE('$fechaActual') AND ('$horaActual' >= eve.horaEntrada AND '$horaActual' <= eve.horaSalida ) AND eve.grupo = '$grupo'";
            $sql = "SELECT asi.*, usu.nombres as nombresUsuario, usu.apellidos as apellidosUsuarios, eve.nombre as nombreEvento, eve.grupo as grupo, ubi.nombre as salon, eve.horaEntrada as horaEntradaEvento, eve.horaSalida as horaSalidaEvento FROM asistencias AS asi INNER JOIN usuarios AS usu ON asi.idUsuario = usu.idUsuario INNER JOIN eventos AS eve ON asi.idEvento = eve.idEvento INNER JOIN ubicaciones AS ubi ON eve.idUbicacion = ubi.idUbicacion INNER JOIN dias AS dia ON asi.idEvento = dia.idEvento WHERE asi.estatus != 0 AND (dia.$arrayDias[$diaSemana] = 1) AND (asi.fechaActual >= DATE('$fechaInicio') AND asi.fechaActual <= DATE('$fechaFin')) AND eve.grupo = '$grupo'";
            
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $dbc = null;
            if ($data) {
                $json = json_encode(['status' => true, 'code' => 200, 'data' => $data]);
            } else {
                $json = json_encode(['status' => false, 'code' => 401, 'data' => 'No hay asistencias']);
            }
        } catch (PDOException $ERROR) {
            $message = $ERROR->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });
    //Funcion de asistencia por dia (puntual)
    $app->get('/asistenciapuntual/{horaActual}', function ($request, $response, $args) {
        try {
            $arrayDias = ["domingo", "lunes", "martes", "miercoles", "jueves", "viernes", "sabado"];
            $diaSemana = gene_week_day();
            $horaActual = $args['horaActual'];
            $fechaActual = gene_date();
            $sql = "SELECT asi.*, usu.nombres as nombresUsuario, usu.apellidos as apellidosUsuarios, eve.nombre as nombreEvento, eve.grupo as grupo, ubi.nombre as salon, eve.horaEntrada as horaEntradaEvento, eve.horaSalida as horaSalidaEvento FROM asistencias AS asi INNER JOIN usuarios AS usu ON asi.idUsuario = usu.idUsuario INNER JOIN eventos AS eve ON asi.idEvento = eve.idEvento INNER JOIN ubicaciones AS ubi ON eve.idUbicacion = ubi.idUbicacion INNER JOIN dias AS dia ON asi.idEvento = dia.idEvento WHERE asi.estatus != 0 AND (dia.$arrayDias[$diaSemana] = 1) AND asi.fechaActual = DATE('$fechaActual') AND ('$horaActual' >= eve.horaEntrada AND '$horaActual' <= eve.horaSalida ) AND (TIMEDIFF(asi.horaEntrada,eve.horaEntrada) <= '00:10:00') ";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $dbc = null;
            $json = json_encode(['status' => true, 'code' => 200, 'data' => $data]);
            /*if ($data) {
                $json = json_encode(['status' => true, 'code' => 200, 'data' => $data]);
            } else {
                $json = json_encode(['status' => false, 'code' => 401, 'data' => 'No hay asistencias']);
            }*/
        } catch (PDOException $ERROR) {
            $message = $ERROR->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });
    //Funcion busqueda por maestro
    $app->get('/asistenciapormaestros/{idUsuario}', function ($request, $response, $args) {
        try {
            $arrayDias = ["domingo", "lunes", "martes", "miercoles", "jueves", "viernes", "sabado"];
            $diaSemana = gene_week_day();
            $idUsuario = $args['idUsuario'];
            $fechaActual = gene_date();
            $sql = "SELECT asi.*, usu.nombres as nombresUsuario, usu.apellidos as apellidosUsuarios, eve.nombre as nombreEvento, eve.grupo as grupo, ubi.nombre as salon, eve.horaEntrada as horaEntradaEvento, eve.horaSalida as horaSalidaEvento FROM asistencias AS asi INNER JOIN usuarios AS usu ON asi.idUsuario = usu.idUsuario INNER JOIN eventos AS eve ON asi.idEvento = eve.idEvento INNER JOIN ubicaciones AS ubi ON eve.idUbicacion = ubi.idUbicacion INNER JOIN dias AS dia ON asi.idEvento = dia.idEvento WHERE asi.estatus != 0 AND (dia.$arrayDias[$diaSemana] = 1) AND asi.fechaActual = DATE('$fechaActual') AND asi.idUsuario = $idUsuario";
            $dbc = new db();
            $dbc = $dbc->connect();
            $stmt = $dbc->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $dbc = null;
            $json = json_encode(['status' => true, 'code' => 200, 'data' => $data]);
            /*if ($data) {
                $json = json_encode(['status' => true, 'code' => 200, 'data' => $data]);
            } else {
                $json = json_encode(['status' => false, 'code' => 401, 'data' => 'No hay asistencias']);
            }*/
        } catch (PDOException $ERROR) {
            $message = $ERROR->getMessage();
            $json = json_encode(['status' => false, 'code' => 400, 'data' => $message]);
        }
        $response->getBody()->write($json);
        return $response;
    });
});
