<?php
declare(strict_types=1);

namespace App\Core;

use App\Core\ExceptionHandler;

class View
{
    public static function show(string $view, ?array $parameters = []): void
    {
        $file = VIEWS . DIRECTORY_SEPARATOR . $view . ".html";

        if (file_exists($file))
        {
            include $file;
        }
        else
        {
            ExceptionHandler::defaultRequestHandler("View \"$view\" is not found");
        }
    }
}
