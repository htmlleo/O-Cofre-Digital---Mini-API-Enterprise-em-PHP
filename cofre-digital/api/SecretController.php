<?php
/**
 * Controller de Segredos
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../core/Security.php';
require_once __DIR__ . '/../core/AuthMiddleware.php';
require_once __DIR__ . '/../models/Secret.php';

// Protege todos os endpoints deste controller
$userId = AuthMiddleware::getUserId();
$secretModel = new Secret();
$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'create') {
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

    if (!Security::validateRequired($input, ['title', 'content'])) {
        Response::error('Título e conteúdo são obrigatórios.');
    }

    $title = Security::sanitize($input['title']);
    $content = Security::sanitize($input['content']);

    if ($secretModel->create($userId, $title, $content)) {
        Response::success([], 'Segredo guardado com sucesso!', 201);
    } else {
        Response::error('Erro ao guardar segredo.', 500);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    switch ($action) {
        case 'list':
            $secrets = $secretModel->listByUser($userId);
            Response::success($secrets);
            break;

        case 'show':
            $id = $_GET['id'] ?? 0;
            $secret = $secretModel->findById($id, $userId);
            
            if ($secret) {
                Response::success($secret);
            } else {
                Response::error('Segredo não encontrado ou acesso negado.', 404);
            }
            break;

        default:
            Response::error('Ação inválida.', 400);
    }
} else {
    Response::error('Método não permitido.', 405);
}
