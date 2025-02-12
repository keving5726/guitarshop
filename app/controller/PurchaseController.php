<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Core\Session;

class PurchaseController implements iController
{
    private ?float $balance;
    private ?array $purchases;
    private ?float $total;
    private ?array $alert;

    public function __construct()
    {
        $this->balance = &$_SESSION["balance"];
        $this->purchases = &$_SESSION["purchases"];
        $this->total = &$_SESSION["total_purchases"];
        $this->alert = &$_SESSION["alert"];
    }

    public function index(): ?View
    {
        return View::show("purchases", ['purchases' => $this->purchases, 'title' => 'Purchases']);
    }

    public function create(): void
    {
        if ($this->balance < $_SESSION["total"])
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
            $object->items = $value->items;
            $object->subtotal = $value->subtotal;

            $purchase[] = $object;
        }

        $code = rand();
        $this->total += $_SESSION["total"];
        $this->purchases[] = [
            "code" => "$code",
            "date" => (new \DateTime())->format('Y-m-d H:i:s'),
            "items" => $_SESSION["items"],
            "subtotal" => $_SESSION["subtotal"],
            "discount" => $_SESSION["discount"],
            "shippingOption" => $_SESSION["shippingOption"],
            "totalBeforeTax" => $_SESSION["totalBeforeTax"],
            "tax" => $_SESSION["tax"],
            "total" => $_SESSION["total"],
            "purchase" => $purchase
        ];

        $this->balance -= $_SESSION["total"];
        Session::clear();
        $this->alert = [
            'message' => "Your purchase have been added successfully",
            'type' => "success",
        ];
        header('Location: /purchases');
        return;
    }

    public function show(string $code): ?View
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
                    $object->items = $value->items;
                    $object->subtotal = $value->subtotal;

                    $purchase[] = $object;
                }

                return View::show("purchases.show", ['purchase' => $purchase, 'title' => 'Purchase Details']);
               
            }
        }
    }

    public function edit(): void
    {
    }

    public function destroy(): void
    {
    }
}
