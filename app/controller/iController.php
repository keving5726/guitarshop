<?php
declare(strict_types=1);

namespace App\Controller;

interface iController
{
    public function index();
    public function new();
    public function show(string $id);
    public function edit();
    public function delete();
}
