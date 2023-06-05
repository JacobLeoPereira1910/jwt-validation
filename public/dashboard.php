<?php
header("Access-Control-Allow-Origin: http://localhost");
header("Content-Type: application/json");
session_start();
$token = $_SESSION['token'];

require '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use app\database\Connection;


$pdo = Connection::connect();
$key = $_ENV['KEY'];



$dotenv = Dotenv\Dotenv::createImmutable('C:/xampp/htdocs/scsp');
$dotenv->load();


echo "<pre>";
print_r($dotenv);
echo "<pre>";


if (!empty($_SESSION['token'])) {
  $token = $_SESSION['token'];

  try {
    // Verifique e decodifique o token
    $decoded = JWT::decode($token, new Key($key, 'HS256'));
    $email = $decodedToken->email;

    // Faça algo com o email, como buscar informações do usuário no banco de dados
    // e retornar a resposta em JSON

    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $statement->execute(["email" => $email]);
    $user = $statement->fetch();

    echo "<pre>";
    print_r($user);
    echo "</pre>";

    // if ($user) {
    //   echo json_encode($user);
    // } else {
    //   http_response_code(404);
    //   die('Usuário não encontrado');
    // }
  } catch (Exception $e) {
  }
} else {
  echo "Token inexistente";
}
