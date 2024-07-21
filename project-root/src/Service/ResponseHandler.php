<?php

namespace App\Service;

class ResponseHandler {
    public function sendJsonResponse(array $data, int $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    public function sendResponse(int $statusCode = 200) {
        http_response_code($statusCode);
        exit;
    }
}
?>