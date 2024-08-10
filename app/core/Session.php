<?php
declare(strict_types=1);

namespace App\Core;

class Session
{
    public function __construct()
    {
        if (session_status() !== 2)
        {
            session_start();

            if (!isset($_SESSION["balance"]))
            {
                $_SESSION["balance"] = 5000;
            }
        }
    }

    public static function clean(): void
    {
        $_SESSION["cart"] = NULL;
        $_SESSION["quantity"] = NULL;
        $_SESSION["total"] = NULL;
    }
}
