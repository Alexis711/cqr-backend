<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\UploadedFile;

require '../vendor/autoload.php';

include_once '../includes/connection.php';
include_once '../includes/settings.php';
include_once '../includes/functions.php';

$app = new \Slim\App();

$app->add(function ($request, $response, $next) {
    $contentType = $request->getContentType();
    if ((strpos($contentType, 'application/json') !== false) || (strpos($contentType, 'multipart/form-data') !== false)) {
        $response = $next($request, $response);
    } else {
        $json = json_encode([
            'status' => false,
            'cod_status' => 200,
            'msg' => 'bloqueo de comunicacion '
        ]);
        $response->getBody()->write($json);
    }

    // Response with CORS
    return $response->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type,Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->get('/Api', function (Request $request, Response $response) {
    $json = json_encode([
        'status' => true,
        'cod_status' => 200,
        'msg' => 'API CQR'
    ]);
    $response->getBody()->write($json);

    return $response;
});

// Rutas
include '../routes/asistencias.php';
include '../routes/usuarios.php';
include '../routes/roles.php';
include '../routes/eventos.php';
include '../routes/ubicaciones.php';


$app->run();
