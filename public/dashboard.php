<?php
header("Access-Control-Allow-Origin: http://localhost");
header("Content-Type: application/json");

require '../vendor/autoload.php';

use Firebase\JWT\JWT;

$dotenv = Dotenv\Dotenv::createImmutable('C:/xampp/htdocs/scsp');
$dotenv->load();

class Connection
{
  public static function connect()
  {
    return new PDO("mysql:host=localhost;dbname=dados", "root", "", [
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
  }
}

$pdo = Connection::connect();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // Verifique se o token está presente no cabeçalho da requisição
  $headers = apache_request_headers();
  $authorizationHeader = $headers['Authorization'] ?? '';

  echo " _ENV <pre>";
  print_r($_ENV);
  echo "</pre>";


  echo " auth <pre>";
  print_r($authorizationHeader);
  echo "</pre>";

  if (!empty($authorizationHeader)) {
    $token = str_replace('Bearer ', '', $authorizationHeader);

    try {
      // Verifique e decodifique o token
      $decodedToken = JWT::decode($token, $_ENV['KEYFORSCSP123456789'], array('HS256'));
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
  }
}
