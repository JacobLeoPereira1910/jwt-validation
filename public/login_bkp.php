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
    $senha = $requestData['senha'];

    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email AND password = :senha");
    $statement->execute([
      "email" => $email,
      "senha" => $senha
    ]);
    $users = $statement->fetchAll();


    $items = json_decode(json_encode($users), false);

    foreach ($items as $key => $item) {
      $userEmail = $item->email;
      $userPassword = $item->password;

      if ($userEmail === $email && $userPassword === $senha) {
        echo "Login realizado com sucesso!";
        echo json_encode(array('message' => 'Login realizado com sucesso.'));
      } else {
        // Usuário não encontrado, retorne uma resposta JSON com a mensagem de erro
        http_response_code(401);
        echo json_encode(array('message' => 'Credenciais inválidas.'));
      }
    }
  } else {
    // Trate o caso em que as chaves não estão presentes
    http_response_code(400);
    echo json_encode(array('message' => 'Campos de email e senha não foram enviados corretamente.'));
  }
} else {
  // Trate outros métodos de requisição, como GET, PUT, DELETE, etc.
  http_response_code(405);
  echo json_encode(array('message' => 'Método de requisição não permitido.'));
}
