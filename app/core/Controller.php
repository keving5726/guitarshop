<?php
declare(strict_types=1);

namespace App\Core;

use App\Core\ExceptionHandler;

class Controller
{
    public static function run(string $controller, string $action = "index", array $parameters = []): void
    {
        $file = CONTROLLERS . DIRECTORY_SEPARATOR . $controller . ".php";

        if (file_exists($file))
        {
            $controller = 'App\Controller\\' . $controller;
            
            if(method_exists($controller, $action))
            {
                call_user_func_array([new $controller, $action], $parameters);
            }
            else
            {
                ExceptionHandler::defaultRequestHandler("Call to undefined function \"$action()\"");
            }
        }
        else
        {
            ExceptionHandler::defaultRequestHandler("Controller \"$controller\" is not found");
        }
    }
}
