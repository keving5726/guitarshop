<?php
declare(strict_types=1);

namespace App\Core;

class Session
{
    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE)
        {
            session_start();

            if (!isset($_SESSION["balance"]))
            {
                $_SESSION["balance"] = (float) getenv('BALANCE') ?: '5000';
            }

            if (!isset($_SESSION["items"]))
            {
                $_SESSION["items"] = "0";
            }

            if (!isset($_SESSION["subtotal"]))
            {
                $_SESSION["subtotal"] = "0";
            }

            if (!isset($_SESSION["discount"]))
            {
                $_SESSION["discount"] = "0";
            }

            if (!isset($_SESSION["totalBeforeTax"]))
            {
                $_SESSION["totalBeforeTax"] = "0";
            }

            if (!isset($_SESSION["tax"]))
            {
                $_SESSION["tax"] = "0";
            }

            if (!isset($_SESSION["total"]))
            {
                $_SESSION["total"] = "0";
            }
        }
    }

    public static function clear(): void
    {
        $_SESSION["cart"] = NULL;
        $_SESSION["items"] = "0";
        $_SESSION["subtotal"] = "0";
        $_SESSION["discount"] = "0";
        $_SESSION["shippingOption"] = NULL;
        $_SESSION["totalBeforeTax"] = "0";
        $_SESSION["tax"] = "0";
        $_SESSION["total"] = "0";
    }
}
