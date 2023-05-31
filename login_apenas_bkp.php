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

  echo "<pre>";
  print_r($requestData);
  echo "</pre>";

  if (isset($requestData['email']) && isset($requestData['senha'])) {
    $email = $requestData['email'];
    $senha = $requestData['senha'];

    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email AND password = :senha");
    $statement->execute([
      "email" => $email,
      "senha" => $senha
    ]);
    $users = $statement->fetchAll();

    if (!empty($users)) {
      echo "Login realizado com sucesso!";
      echo json_encode(array('message' => 'Login realizado com sucesso.'));
    } else {
      echo "Credenciais inválidas";
    }
  }
}
