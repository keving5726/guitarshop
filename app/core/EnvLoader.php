<?php
declare(strict_types=1);

namespace App\Core;

use App\Core\ExceptionHandler;

class EnvLoader
{
    private string $filePath;

    public function __construct()
    {
        $this->filePath = __DIR__ . '/../../.env';
    }

    private function load(): void
    {
        if (!file_exists($this->filePath))
        {
            ExceptionHandler::defaultRequestHandler("Please create the .env file and configure the environment variables, check the .env.dist file for more details");
        }

        $lines = file($this->filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line)
        {
            // Ignore comments
            if (strpos(trim($line), '#') === 0)
            {
                continue;
            }

            // Split the line into key and value
            [$key, $value] = explode('=', $line, 2);

            // Remove whitespace and quotes
            $key = trim($key);
            $value = trim($value, " \t\n\r\0\x0B\"'");

            // Set the environment variable
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }

    public function initialize(): void
    {
        $this->load();
    }
}
