<?php

declare(strict_types=1);

if (!function_exists('http_apply_cors')) {
    /**
     * 共通のCORSレスポンスヘッダーを送出する。
     * 必要に応じて許可ドメインやメソッドを引数で上書きする。
     */
    function http_apply_cors(?array $allowedOrigins = null, ?array $allowedMethods = null): void
    {
        if ($allowedOrigins === null) {
            $allowedOrigins = [
            'http://localhost:5173',
            'http://127.0.0.1:5173',
            'http://localhost:8081',
            'http://127.0.0.1:8081',
        ];
        }

        if ($allowedMethods === null) {
            $allowedMethods = ['GET', 'OPTIONS'];
        }

        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        if ($origin && in_array($origin, $allowedOrigins, true)) {
            header('Access-Control-Allow-Origin: ' . $origin);
            header('Vary: Origin');
        }

        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Access-Control-Allow-Methods: ' . implode(', ', $allowedMethods));

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }
}
