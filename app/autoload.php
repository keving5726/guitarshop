<?php
declare(strict_types=1);

spl_autoload_register(function (string $namespace)
{
    $namespace = str_replace("\\", DIRECTORY_SEPARATOR, $namespace);
    $namespace = explode(DIRECTORY_SEPARATOR, $namespace);
    $class = array_pop($namespace);
    $namespace = implode(DIRECTORY_SEPARATOR, $namespace);
    $filePath = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . strtolower($namespace) . DIRECTORY_SEPARATOR . $class . '.php';
    if (file_exists($filePath))
    {
        require_once("$filePath");
    }
});
