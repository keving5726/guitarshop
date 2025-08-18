<?php
declare(strict_types=1);

namespace App\Controller;

interface iShoppingCart
{
    public function index();
    public function add();
    public function remove();
    public function clear();
    public function checkout();
}
