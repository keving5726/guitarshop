<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Core\Session;

class PurchaseController implements iController
{
    private ?int $balance;
    private ?array $purchases;
    private ?int $total;
    private ?array $alert;

    public function __construct()
    {
        $this->balance = &$_SESSION["balance"];
        $this->purchases = &$_SESSION["purchases"];
        $this->total = &$_SESSION["total_purchases"];
        $this->alert = &$_SESSION["alert"];
    }

    public function index()
    {
        return View::show("purchases", ['purchases' => $this->purchases, 'title' => 'Purchases']);
    }

    public function new()
    {
        if (empty($_POST["shipping"]))
        {
            $this->alert = [
                'message' => "Please select a shipping option",
                'type' => "warning",
            ];

            header('Location: /shoppingcart');
            return;
        }
        elseif($_POST["shipping"] === "option1")
        {
            $this->purchases(0);
            return;
        }
        elseif($_POST["shipping"] === "option2")
        {
            $this->purchases(5);
            return;
        }
        else
        {
            $this->alert = [
                'message' => "Invalid shipping option",
                'type' => "warning",
            ];

            header('Location: /shoppingcart');
            return;
        }
    }

    public function show(string $code)
    {
        foreach ($_SESSION["purchases"] as $value)
        {
            if ($value["code"] === $code)
            {
                foreach ($value["purchase"] as $value)
                {
                    $object = new \stdClass;
                    $object->code = $value->code;
                    $object->name = $value->name;
                    $object->price = $value->price;
                    $object->quantity = $value->quantity;
                    $object->total = $value->total;

                    $purchase[] = $object;
                }

                return View::show("purchases.show", ['purchase' => $purchase, 'title' => 'Purchase Details']);
               
            }
        }
    }

    public function edit()
    {
    }

    public function delete()
    {
    }

    public function purchases(int $shipping): void
    {
        if ($this->balance < ($_SESSION["total"] + $shipping))
        {
            $this->alert = [
                'message' => "Your balance is insufficient",
                'type' => "warning",
            ];

            header('Location: /shoppingcart');
            return;
        }

        foreach ($_SESSION["cart"] as $value)
        {
            $object = new \stdClass;
            $object->code = $value->code;
            $object->name = $value->name;
            $object->price = $value->price;
            $object->quantity = $value->quantity;
            $object->total = $value->total;

            $purchase[] = $object;
        }

        ($shipping === 0) ? $shipping_option = "Pick Up (USD 0)" : $shipping_option = "UPS (USD 5)";
        $code = rand();
        $this->total += ($_SESSION["total"] + $shipping);
        $this->purchases[] = ["code" => "$code", "purchase" => $purchase, "date" => (new \DateTime())->format('Y-m-d H:i:s'), "shipping" => $shipping_option, "total" => ($_SESSION["total"] + $shipping)];

        $this->balance -= ($_SESSION["total"] + $shipping);
        Session::clean();
        $this->alert = [
            'message' => "Your purchase have been added successfully",
            'type' => "success",
        ];
        header('Location: /shoppingcart');
        return;
    }
}
