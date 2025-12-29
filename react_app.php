<?php

declare(strict_types=1);

$distDirectory = __DIR__ . '/frontend/my-react-app/dist';
$entryFile = $distDirectory . '/index.html';

if (!is_readable($entryFile)) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'React build assets were not found. Run `pnpm run build` (or `make build/front`) inside frontend/my-react-app.';
    exit;
}

header('Content-Type: text/html; charset=UTF-8');
$handle = fopen($entryFile, 'rb');

if ($handle === false) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'Failed to read React build entry file.';
    exit;
}

fpassthru($handle);
fclose($handle);
