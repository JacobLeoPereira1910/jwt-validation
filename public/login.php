<?php
header("Access-Control-Allow-Origin: http://localhost");
header("Content-Type: application/json");

require '../vendor/autoload.php';

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

  if (isset($requestData['email']) && isset($requestData['senha'])) {
    $email = $requestData['email'];
    $password = $requestData['senha'];

    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email AND password = :senha");
    $statement->execute([
      "email" => $email,
      "senha" => $password
    ]);
    $userFound = $statement->fetch();


    echo "<pre>";
    print_r($userFound->email);
    echo "</pre>";

    if (!$userFound) {
      http_response_code(401);
    }

    // if (!password_verify($password, $userFound['password'])) {
    //   http_response_code(401);
    // }
  }
}
