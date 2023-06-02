<?php
session_start();
header("Access-Control-Allow-Origin: *");

require '../vendor/autoload.php';

use Firebase\JWT\JWT;

$dotenv = Dotenv\Dotenv::createImmutable('C:/xampp/htdocs/scsp');
$dotenv->load();

$secretKey = $_ENV['KEY'];

if (!isset($_SESSION['token'])) {
    http_response_code(401);
    die('Token não encontrado');
}

$token = $_SESSION['token'];

try {
    // Decodifique e verifique o token utilizando a chave secreta
    $decoded = JWT::decode($token, $secretKey, ['HS256']);

    // Verifique se o token não está expirado
    if ($decoded->exp < time()) {
        http_response_code(401);
        die('Token expirado');
    }

    // O token é válido, permita que o usuário acesse a página protegida e execute as requisições no banco de dados

    // Exemplo: Buscar os dados do usuário no banco de dados
    $pdo = new PDO("mysql:host=localhost;dbname=dados", "root", "");
    $email = $decoded->email;
    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $statement->execute(["email" => $email]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    // Exemplo: Enviar os dados do usuário como resposta em formato JSON
    header("Content-Type: application/json");
    echo json_encode($user);

} catch (Throwable $e) {
    http_response_code(401);
    die('Token inválido');
}
?>
