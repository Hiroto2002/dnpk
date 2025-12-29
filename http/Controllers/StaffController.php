<?php
declare(strict_types=1);

use Domain\Repositories\StaffRepository;
use Domain\Services\StaffService;

require_once dirname(__DIR__, 2) . '/lib/autoload.php';
require_once dirname(__DIR__, 2) . '/DbManager.php';
require_once dirname(__DIR__) . '/cors.php';

http_apply_cors();

session_start();

function json($data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

    $pdo = getDb();
    $repo = new StaffRepository($pdo);
    $svc = new StaffService($repo);

    $action = $_GET['action'] ?? null;
    switch ($action) {
        case 'getAll':
            $result = $svc->findAll();
            if ($result->isLeft()) {
                $error = $result->getLeft();
                error_log($error->getMessage());
                json(['ok' => false, 'error' => $error->getMessage()], 500);
            } else{
                $staffs = $result->get();
                json(['ok' => true, 'staff' => $staffs]);
            }
            break;
        default:
            json(['ok' => false, 'error' => 'unknown action'], 400);
            break;
    }