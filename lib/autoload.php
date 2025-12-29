<?php

declare(strict_types=1);

// Lightweight project autoloader (Composer autoloader is not available).
// Controllers / CLI scripts should include this file once.
if (defined('APP_AUTOLOADER_REGISTERED')) {
    return;
}

define('APP_AUTOLOADER_REGISTERED', true);

$__AUTLOAD_ROOT__ = dirname(__DIR__);

// Functions under Utils\ namespace (not autoloadable as classes)
require_once $__AUTLOAD_ROOT__ . '/utils/helper.php';

spl_autoload_register(function (string $class) use ($__AUTLOAD_ROOT__) {
    $prefixes = [
        'Domain\\Models\\' => $__AUTLOAD_ROOT__ . '/Domain/Models/',
        'Domain\\Repositories\\' => $__AUTLOAD_ROOT__ . '/Domain/Repositories/',
        'Domain\\Services\\' => $__AUTLOAD_ROOT__ . '/Domain/Services/',
        'Utils\\' => $__AUTLOAD_ROOT__ . '/utils/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($class, $prefix, $len) !== 0) {
            continue;
        }

        $relative = substr($class, $len);
        $file = $baseDir . str_replace('\\', '/', $relative) . '.php';
        if (is_file($file)) {
            require $file;
            return;
        }
    }
});
