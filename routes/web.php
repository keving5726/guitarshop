<?php
declare(strict_types=1);

use App\Core\Controller;
use App\Core\View;

$route->get('/', fn() => View::show("home", ['title' => 'Home']));

$route->get('/home', fn() => View::show("home", ['title' => 'Home']));

$route->get('/products', fn() => Controller::run("ProductController", "index"));

$route->get('/products/{code}', fn($code) => Controller::run("ProductController", "show", [$code]));

$route->post('/products/{code}', fn($code) => Controller::run("ProductController", "rating", [$code]));

$route->get('/faq/', fn() => View::show("faq", ['title' => 'FAQ']));

$route->get('/about/', fn() => View::show("about", ['title' => 'About']));

$route->get('/shoppingcart', fn() => Controller::run("ShoppingCartController"));

$route->post('/shoppingcart', fn() => Controller::run("ShoppingCartController", "post"));

$route->get('/purchases', fn() => Controller::run("PurchaseController"));

$route->get('/purchases/{purchase}', fn($purchase) => Controller::run("PurchaseController", "show", ["$purchase"]));

$route->post('/purchases', fn() => Controller::run("PurchaseController", "new"));

$route->get('/logout', fn() => Controller::run("ShoppingCartController", "logout"));
