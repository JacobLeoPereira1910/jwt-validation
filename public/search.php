<?php
header("Access-Control-Allow-Origin: http://localhost");
header("Content-Type: application/json");

require '../vendor/autoload.php';

use Firebase\JWT\JWT;

$dotenv = Dotenv\Dotenv::createImmutable('C:/xampp/htdocs/scsp');
$dotenv->load();

// Verifique a presença do token na URL (parâmetro "token")
if (isset($_GET['token'])) {
  $token = $_GET['token'];

  try {
    // Verifique e decodifique o token utilizando a chave secreta
    $decoded = JWT::decode($token, $_ENV['KEY'], array('HS256'));

    // Verifique se o token não está expirado
    if (time() > $decoded->exp) {
      // Redirecione o usuário de volta para a página de login
      header("Location: http://localhost/scsp/index.html");
      exit();
    }

    // O token é válido, permita que o usuário acesse a página protegida
    // Agora você pode executar as requisições no banco de dados ou realizar outras ações permitidas

  } catch (Exception $e) {
    // O token é inválido, redirecione o usuário de volta para a página de login
    header("Location: http://localhost/scsp/index.html");
    exit();
  }
} else {
  // O token não está presente na URL, redirecione o usuário de volta para a página de login
  header("Location: http://localhost/scsp/index.html");
  exit();
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Exemplo de Requisição com Bootstrap</title>
  <!-- Importando Bootstrap CSS -->
  <link rel="stylesheet" href="http://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
  <div class="container">
    <h1>Exemplo de Requisição com Bootstrap</h1>
    <button id="fetchButton" class="btn btn-primary">Realizar Requisição</button>
    <div id="responseContainer"></div>
  </div>

  <!-- Importando o Bootstrap JS e o código JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>