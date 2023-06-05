<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require '../vendor/autoload.php';

use Firebase\JWT\JWT;
use app\database\Connection;

$dotenv = Dotenv\Dotenv::createImmutable('C:/xampp/htdocs/scsp');
$dotenv->load();

$pdo = Connection::connect();
$key = $_ENV['KEY'];

// Obter o corpo da requisição
$requestBody = file_get_contents('php://input');
$data = json_decode($requestBody, true);

// Verificar se o email e senha estão presentes
if (isset($data['email']) && isset($data['password'])) {
  $email = $data['email'];
  $password = $data['password'];

  $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
  $statement->execute([
    "email" => $email,
    "password" => $password
  ]);
  $userFound = $statement->fetchAll();

  if (count($userFound) > 0) {
    $payload = [
      "exp" => time() + 2,
      "iat" => time(),
      "email" => $email
    ];

    $token = JWT::encode($payload, $key, 'HS256');

    echo json_encode($token);
  } else {
    http_response_code(401);
    die('Credenciais inválidas');
  }
} else {
  http_response_code(400);
  echo json_encode(["error" => "Dados inválidos"]);
}
