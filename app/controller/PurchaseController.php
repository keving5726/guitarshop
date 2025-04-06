<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Core\Session;
use App\core\ExceptionHandler;

class PurchaseController implements iController
{
    private string $balance;
    private ?array $purchases;
    private string $total;
    private ?array $alert;

    public function __construct()
    {
        $this->balance = &$_SESSION["balance"];
        $this->purchases = &$_SESSION["purchases"];
        $this->totalPurchases = &$_SESSION["totalPurchases"];
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
        $this->totalPurchases = bcadd($this->totalPurchases, $_SESSION["total"], 2);
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

        $this->balance = bcsub($this->balance, $_SESSION["total"], 2);
        Session::clear();
        $this->alert = [
            'message' => "Your purchase have been added successfully",
            'type' => "success",
        ];
        header('Location: /purchases');
        return;
    }

    public function show(string $code): ?ExceptionHandler
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
        ExceptionHandler::defaultRequestHandler("The purchase does not exist");
    }

    public function edit(): void
    {
    }

    public function destroy(): void
    {
    }
}
