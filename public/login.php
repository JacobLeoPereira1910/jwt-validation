<?php
header("Access-Control-Allow-Origin: http://localhost");
header("Content-Type: application/json");

require '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Nowakowskir\JWT\JWT as JWTJWT;

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $requestData = json_decode(file_get_contents('php://input'), true); // Recebe os dados como JSON

  if (isset($requestData['email']) && isset($requestData['password'])) {
    $email = $requestData['email'];
    $password = $requestData['password'];

    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
    $statement->execute([
      "email" => $email,
      "password" => $password
    ]);
    $userFound = $statement->fetchAll();

    // ...

    if (count($userFound) > 0) {
      $payload = [
        "exp" => time() + 600,
        "iat" => time(),
        "email" => $email
      ];

      $encode = JWT::encode($payload, $_ENV['KEY'], 'HS256');

      // Armazene o token na sessão
      session_start();
      $_SESSION['token'] = 'Bearer ' . $token;

      // Redirecione o usuário para a página protegida
      header("Location: http://localhost/scsp/public/dashboard.php");
      exit();
    } else {
      http_response_code(401);
      die('Credenciais inválidas');
    }

    // ...

  }
}
