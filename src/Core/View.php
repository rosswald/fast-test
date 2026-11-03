<?php

namespace Core;

class View
{
    private static string $viewsDirectory = __DIR__ . '/../views';
    private static string $downloadDirectory = __DIR__ . '/../../download';

    public static function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        ob_start();

        require self::$viewsDirectory . '/' . $view . '.php';

        $content = ob_get_clean();

        require self::$viewsDirectory . '/layout.php';
    }

    public static function renderError(int $errorCode, string $errorMessage): void
    {
        http_response_code($errorCode);

        require self::$viewsDirectory . '/error.php';
    }

    public static function download(string $filename): void
    {
        $filePath = self::$downloadDirectory . '/' . $filename;

        if (!is_file($filePath) || !is_readable($filePath)) {
            self::renderError(404, 'File not found.');
            return;
        }

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: no-cache');

        readfile($filePath);
        exit;
    }

    public static function renderImage(\GdImage $image): void
    {
        header('Content-Type: image/png');
        imagepng($image);
    }
}