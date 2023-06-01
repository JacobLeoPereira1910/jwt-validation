<?php

require '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Nowakowskir\JWT\TokenDecoded;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type, x-xsrf-token, x_csrftoken, Cache-Control, X-Requested-With');

$dotenv = Dotenv\Dotenv::createImmutable('C:/xampp/htdocs/scsp');
$dotenv->load();

$headers = apache_request_headers();

$authorizationHeader = $headers['Authorization'];

// Verifique se o cabeçalho de autorização está presente e começa com "Bearer "
//if (isset($authorizationHeader) && strpos($authorizationHeader, 'Bearer ') === 0) {

// removendo a string 'Bearer ' do token JWT 
$token = str_replace('Bearer ', '', $authorizationHeader);
$secretKey = $_SERVER['KEY'];

try {
    $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
    echo json_encode($decoded);
} catch (Throwable $e) {
    if ($e->getMessage() === "Expired token") {
        http_response_code(401);
        die($e->getMessage());
    }
}
//} //else {
    // O cabeçalho de autorização está ausente ou não está no formato correto
    // Lide com o erro adequadamente
  //  echo "<script>alert('Sem o token')</script>";
//}
