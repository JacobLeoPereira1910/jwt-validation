<?php

require '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type, x-xsrf-token, x_csrftoken, Cache-Control, X-Requested-With');

$dotenv = Dotenv\Dotenv::createImmutable('C:/xampp/htdocs/scsp');
$dotenv->load();

$headers = apache_request_headers();
$authorizationHeader = $headers['Authorization'];

// Verifique se o cabeçalho de autorização está presente e começa com "Bearer "
if (isset($authorizationHeader) && strpos($authorizationHeader, 'Bearer ') === 0) {
    // Extraia o token JWT removendo a parte "Bearer " do cabeçalho
    $token = substr($authorizationHeader, 7);

    echo "<pre>";
    print_r(json_encode($token));
    echo "</pre>";    // Faça algo com o token JWT
    // ...
} else {
    // O cabeçalho de autorização está ausente ou não está no formato correto
    // Lide com o erro adequadamente
    echo "<script>alert('Sem o token')</script>";
}
