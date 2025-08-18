<?php
declare(strict_types=1);

use App\Core\Route;
use App\Core\EnvLoader;
use App\Core\Session;

require __DIR__.'/../app/autoload.php';

$route = new Route();
$envLoader = new EnvLoader();

require __DIR__.'/../config/global.php';
require __DIR__.'/../routes/web.php';

$envLoader->initialize();
$session = new Session();
$route->resolve();
