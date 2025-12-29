<?php

declare(strict_types=1);

use Domain\Repositories\UserSessionRepository;
use Domain\Services\UserService;

require_once dirname(__DIR__, 2) . '/lib/autoload.php';
require_once dirname(__DIR__) . '/cors.php';

http_apply_cors();

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$service = new UserService(new UserSessionRepository($_SESSION));

function json_response(array $payload, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function read_request_payload(): array
{
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

    if (stripos($contentType, 'application/json') !== false) {
        $raw = file_get_contents('php://input');
        if ($raw !== false && $raw !== '') {
            $decoded = json_decode($raw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }
    }

    if (!empty($_POST)) {
        return $_POST;
    }

    return [];
}

$action = $_GET['action'] ?? ($_POST['action'] ?? null);

switch ($action) {
    case 'me':
        $user = $service->current();
        json_response([
            'ok' => true,
            'userId' => $user ? $user->id() : null,
            'userName' => $user ? $user->name() : null,
        ]);
        break;

    case 'login':
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            json_response(['ok' => false, 'error' => 'method not allowed'], 405);
        }

        $payload = read_request_payload();
        $user = $payload['user'] ?? null;
        $userName = $payload['userName'] ?? null;

        if ($user === null || $user === '') {
            json_response(['ok' => false, 'error' => 'user is required'], 400);
        }

        $loggedIn = $service->login((string)$user, $userName !== null ? (string)$userName : null);

        json_response([
            'ok' => true,
            'userId' => $loggedIn->id(),
            'userName' => $loggedIn->name(),
        ]);
        break;

    case 'logout':
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            json_response(['ok' => false, 'error' => 'method not allowed'], 405);
        }

        $service->logout();

        json_response(['ok' => true]);
        break;

    default:
        json_response(['ok' => false, 'error' => 'unknown action'], 400);
        break;
}
