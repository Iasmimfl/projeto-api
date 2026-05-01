<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

function erro($msg){
    echo json_encode([
        'success' => false,
        'message' => $msg
    ]);
    exit;
}

$action = $_GET['action'] ?? 'random';

if ($action == 'random') {

    $url = "https://dog.ceo/api/breeds/image/random";
    $response = @file_get_contents($url);

    if (!$response) {
        erro("Erro de conexão com a API");
    }

    $data = json_decode($response, true);

    if (!$data || $data['status'] != 'success') {
        erro("Erro na resposta da API");
    }

    $image = $data['message'];

    $parts = explode('/', $image);
    $breedInfo = $parts[4] ?? 'unknown';

    $breedParts = explode('-', $breedInfo);

    echo json_encode([
        'success' => true,
        'image' => $image,
        'breed' => $breedParts[0] ?? 'desconhecida',
        'subbreed' => $breedParts[1] ?? 'nenhuma'
    ]);
    exit;
}

if ($action == 'breed') {

    $breed = $_GET['breed'] ?? '';

    if ($breed == '') {
        erro("Informe uma raça");
    }

    $url = "https://dog.ceo/api/breed/$breed/images";
    $response = @file_get_contents($url);

    if (!$response) {
        erro("Raça não encontrada.");
    }

    $data = json_decode($response, true);

    if (!$data || $data['status'] != 'success') {
        erro("Raça não encontrada");
    }

    // 🔥 CORREÇÃO PRINCIPAL: remove o limite de 9
    echo json_encode([
        'success' => true,
        'images' => $data['message']
    ]);
}
?>