<?php

use Dotenv\Dotenv;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Tuupola\Middleware\CorsMiddleware;

require_once __DIR__ . '/vendor/autoload.php';
require './database/Database.php';

$envfile = Dotenv::createImmutable(__DIR__);
$envfile->load();

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);

$app->pipe(CorsMiddleware::class);

$app->add(new Tuupola\Middleware\CorsMiddleware([
    "origin" => ["*"],
    "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE", "OPTIONS"],
    "headers.allow" => ["Authorization", "Token", "X-Requested-With", "Content-Type", "Accept", "Origin", "Access-Control-Allow-Origin", "Accept-Encoding"],
    "headers.expose" => [],
    "credentials" => true
]));

$app->any('/', function (Request $request, Response $response) {
    http_response_code(404);
});

$app->post("/auth/register", function ($request, $response, $args) {
    $db = new MYSQL_DB();
    $body = json_decode($request->getBody(), true);
    $user = $db->register($body);
    return $response->withJson(["message" => $user], 201);
});

$app->post("/auth/login", function ($request, $response) {
    $db = new MYSQL_DB();
    $body = json_decode($request->getBody(), true);
    $user = $db->login($body['email']);
    if (!$user || !password_verify($body["password"], $user['password'])) {
        return $response->withJson(["message" => ["Login failed"]], 401);
    } else {
        unset($user['password']);
        $token = $db->generateToken($user);
        return $response->withJson(["message" => [$token]], 201);
    }
});


$app->get("/posts", function ($request, $response) {
    $db = new MYSQL_DB();
    $jwt = $request->getHeader('Authorization')[0];
    if (!$jwt) {
        return $response->withJson(['message' => array("Unauthorized")], 401);
    }
    $user = $db->decode($jwt);
    if ($user) {
        return $response->withJson($db->findAll(), 200);
    }
    else {
        return $response->withJson(['message' => array("Unauthorized")], 401);
    }
});

$app->post("/posts", function ($request, $response) {
    $db = new MYSQL_DB();
    $body = json_decode($request->getBody(), true);
    $jwt = $request->getHeader('Authorization')[0];
    if (!$jwt) {
        return $response->withJson(['message' => array("Unauthorized")], 401);
    }
    $user = $db->decode($jwt);
    if ($user) {
        $body['userId'] = $user->data->id;
         $post = $db->create($body);
         if ($post) {
            return $response->withJson($post, 201);
         }
         return $response->withJson(['message' => array("Bad Request")], 400);
    }
    else {
        return $response->withJson(['message' => array("Unauthorized")], 401);
    }
});

$app->post("/comment", function ($request, $response) {
    $db = new MYSQL_DB();
    $body = json_decode($request->getBody(), true);
    $jwt = $request->getHeader('Authorization')[0];
    if (!$jwt) {
        return $response->withJson(['message' => array("Unauthorized")], 401);
    }
    $user = $db->decode($jwt);
    if ($user) {
        $body['userId'] = $user->data->id;
         $comment = $db->addComments($body);
         if ($comment) {
            return $response->withJson($comment, 201);
         }
         return $response->withJson(['message' => array("Bad Request")], 400);
    }
    else {
        return $response->withJson(['message' => array("Unauthorized")], 401);
    }
});


$app->run();
