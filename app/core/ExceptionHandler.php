<?php
declare(strict_types=1);

namespace App\Core;

class ExceptionHandler
{
    public static function defaultRequestHandler(string $message, string $code = "404 Not Found"): void
    {
        try
        {
            throw new \Exception($message);
        }
        catch (\Exception $e)
        {
            header("{$_SERVER['SERVER_PROTOCOL']} $code");
            echo $e->getMessage();
            exit;
        }
    }
}
