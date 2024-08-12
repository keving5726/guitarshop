<?php
declare(strict_types=1);

use App\Core\Route;
use App\Core\Session;

require __DIR__.'/../app/autoload.php';

$route = new Route();

require __DIR__.'/../config/global.php';
require __DIR__.'/../routes/web.php';

$session = new Session();

$route->resolve();
