<?php

declare(strict_types=1);

spl_autoload_register(function (string $class): void {
    $classPath = str_replace('\\', '/', $class);

    $file =  __DIR__ . '/../src/' . $classPath . '.php';

    if (file_exists($file)) {
        require $file;
    } else {
        die("Class $classPath not found");
    }
});

require dirname(__DIR__) . '/src/bootstrap.php';

