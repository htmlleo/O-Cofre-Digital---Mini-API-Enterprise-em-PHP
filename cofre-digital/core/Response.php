<?php
/**
 * Classe utilitária para respostas JSON padronizadas
 */

class Response {
    public static function json($status, $message = '', $data = [], $code = 200) {
        header('Content-Type: application/json');
        http_response_code($code);
        
        $response = [
            "status" => $status
        ];

        if (!empty($message)) {
            $response["message"] = $message;
        }

        if (!empty($data)) {
            $response["data"] = $data;
        }

        echo json_encode($response);
        exit;
    }

    public static function success($data = [], $message = 'Operação realizada com sucesso.', $code = 200) {
        self::json('success', $message, $data, $code);
    }

    public static function error($message = 'Ocorreu um erro.', $code = 400, $data = []) {
        self::json('error', $message, $data, $code);
    }
}
