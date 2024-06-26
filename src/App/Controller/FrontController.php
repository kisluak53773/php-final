<?php

declare(strict_types=1);

namespace App\Controller;

use Exception;

class FrontController
{
    public function home(string $id = 'sassa'): void
    {
        try {
            $response = ['data' => $id];
        } catch (Exception $e) {
            $response = [ 'message' => 'Ошибка: ' . $e->getMessage()];
            http_response_code(500);
        }

        echo json_encode($response);
    }
}