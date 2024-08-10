<?php
declare(strict_types=1);

namespace App\Core;

use App\Core\ExceptionHandler;

class Route
{
    public string $uri;
    public string $method;
    public array $supportedMethods;
    public array $routes;

    public function __construct()
    {
        $this->uri = $this->formatRoute($_SERVER["REQUEST_URI"]);
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->supportedMethods = [ "GET", "POST" ];
        $this->routes = [];
    }

    public function __call(string $name, array $arguments): void
    {
        list($route, $closure) = $arguments;

        if(!in_array(strtoupper($name), $this->supportedMethods))
        {
            ExceptionHandler::defaultRequestHandler("Method \"$name\" is not allowed", "405 Method Not Allowed");
        }

        array_push(
            $this->routes, [
                "method" => strtoupper($name),
                "uri" => $this->formatRoute($route),
                "action" => $closure
            ]
        );
    }

    private function formatRoute(string $route): string
    {
        if ($route === '' || $route === '/')
        {
            return '/';
        }

        return strtolower(rtrim($route, '/'));
    }

    public function resolve(): void
    {
        $this->uri = strpos($this->uri, '?') ? strstr($this->uri, '?', true) : $this->uri;

        $match = false;
        foreach ($this->routes as $value)
        {
            $uri = preg_replace('/\{([^\/]+)\}/', '(?<\1>[^/]+)', $value["uri"]);
            preg_match_all('/\{([^\/]+)\}/', $value["uri"], $parameters);

            if (preg_match('#^' . $uri . '/*$#', $this->uri, $matches))
            {
                if ($this->method === $value["method"])
                {
                    $parameters = array_intersect_key($matches, array_flip($parameters[1]));
                    call_user_func_array($value["action"], $parameters);
                    return;
                }
                $match = true;
            }
        }

        $match ? ExceptionHandler::defaultRequestHandler("Method \"$this->method\" is not configured with \"$this->uri\"", "405") : ExceptionHandler::defaultRequestHandler("Route \"$this->uri\" is not found");
    }
}
