<?php
session_start();
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Authorization, Content-Type, x-xsrf-token, x_csrftoken, Cache-Control, X-Requested-With');

require '../vendor/autoload.php';

use Firebase\JWT\JWT;
use app\database\Connection;
use Firebase\JWT\Key;

$dotenv = Dotenv\Dotenv::createImmutable('C:/xampp/htdocs/scsp');
$dotenv->load();

$pdo = Connection::connect();
$key = $_ENV['KEY'];

// Verifica se o cabeçalho JWT_TOKEN está presente na requisição
if (!isset($_SERVER['HTTP_JWT_TOKEN'])) {
    http_response_code(401);
    die('Token não fornecido');
}

$JwtToken = $_SERVER['HTTP_JWT_TOKEN'];

$token = str_replace('Bearer ', '', $JwtToken);

try {
    // Decodifica o token JWT
    $decoded = JWT::decode($token, new Key($key, 'HS256'));
    $email = $decoded->email;
    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $statement->execute(["email" => $email]);
    $verifiedUser = $statement->fetch();

    if (isset($verifiedUser)) {
        echo json_encode($decoded);
    }
} catch (Throwable $e) {
    echo strval($e->getMessage());
    http_response_code(401);
}
