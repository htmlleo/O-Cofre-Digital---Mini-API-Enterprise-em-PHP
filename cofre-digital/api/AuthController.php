<?php
/**
 * Controller de Autenticação
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../core/Security.php';
require_once __DIR__ . '/../models/User.php';

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    $userModel = new User();

    switch ($action) {
        case 'register':
            if (!Security::validateRequired($input, ['name', 'email', 'password'])) {
                Response::error('Todos os campos são obrigatórios.');
            }

            if (!Security::validateEmail($input['email'])) {
                Response::error('E-mail inválido.');
            }

            if ($userModel->findByEmail($input['email'])) {
                Response::error('Este e-mail já está cadastrado.');
            }

            if ($userModel->create($input['name'], $input['email'], $input['password'])) {
                Response::success([], 'Usuário registrado com sucesso!', 201);
            } else {
                Response::error('Erro ao registrar usuário.', 500);
            }
            break;

        case 'login':
            if (!Security::validateRequired($input, ['email', 'password'])) {
                Response::error('E-mail e senha são obrigatórios.');
            }

            $user = $userModel->findByEmail($input['email']);

            if ($user && $userModel->verifyPassword($input['password'], $user['password'])) {
                session_name(SESSION_NAME);
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                
                Response::success([
                    'name' => $user['name'],
                    'email' => $user['email']
                ], 'Login realizado com sucesso!');
            } else {
                Response::error('Credenciais inválidas.', 401);
            }
            break;

        case 'logout':
            session_name(SESSION_NAME);
            session_start();
            session_destroy();
            Response::success([], 'Logout realizado com sucesso.');
            break;

        default:
            Response::error('Ação inválida.', 400);
    }
} else {
    Response::error('Método não permitido.', 405);
}
