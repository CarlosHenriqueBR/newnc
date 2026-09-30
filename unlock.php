<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Resposta exata que o seu script shield.js espera para "desbloquear" o HTML
echo json_encode([
    "success" => true,
    "k" => "access_granted_correios_2026_secure"
]);
exit;