<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Product;
use App\Core\View;
use App\Core\Session;

class ShoppingCartController extends Product implements iShoppingCart
{
    private ?array $cart;
    private ?int $items;
    private ?int $subtotal;
    private ?float $discount;
    private ?string $shippingOption;
    private ?float $totalBeforeTax;
    private ?float $tax;
    private ?float $total;
    private ?array $alert;

    public function __construct()
    {
        $this->cart = &$_SESSION["cart"];
        $this->items = &$_SESSION["items"];
        $this->subtotal = &$_SESSION["subtotal"];
        $this->discount = &$_SESSION["discount"];
        $this->shippingOption = &$_SESSION["shippingOption"];
        $this->totalBeforeTax = &$_SESSION["totalBeforeTax"];
        $this->tax = &$_SESSION["tax"];
        $this->total = &$_SESSION["total"];
        $this->alert = &$_SESSION["alert"];
    }

    public function index()
    {
        return View::show("shoppingcart", ['cart' => $this->cart, 'title' => 'Shopping Cart']);
    }

    public function add()
    {
        $items = empty($_POST["items"]) ? 1 : $_POST["items"];
        $code = $_POST["code"];

        if (!is_numeric($items))
        {
            $this->alert = [
                'message' => "Invalid format: Only numbers",
                'type' => "warning",
            ];

            header('Location: /products');
            return;
        }
        else
        {
            if ($items < 1)
            {
                $items = 1;
            }
        }

        $product = (new Product())->getByCode($code);
        $object = new \stdClass;
        $object->code = $product->code;
        $object->name = $product->name;
        $object->price = $product->price;
        $object->items = $items;
        $object->subtotal = $product->price * $object->items;

        $this->items += $object->items;
        $this->subtotal += $object->subtotal;
        $this->shippingOption = empty($this->shippingOption) ? "pickup" : $this->shippingOption;
        $this->discount = ($this->subtotal * 5) / 100;

        if ($this->shippingOption === "ups")
        {
            $this->totalBeforeTax = $this->subtotal - $this->discount + 5;
        } else {
            $this->totalBeforeTax = $this->subtotal - $this->discount;
        }

        $this->tax = ($this->totalBeforeTax * 3) / 100;
        $this->total = $this->totalBeforeTax + $this->tax;

        if ($this->cart !== NULL)
        {
            foreach ($this->cart as $value)
            {
                if ($value->code === $product->code)
                {
                    $value->items += $object->items;
                    $value->subtotal += $object->subtotal;
                    $this->alert = [
                        'message' => "Added to your shopping cart successfully",
                        'type' => "success",
                    ];

                    if (isset($_POST["shoppingcart"])) {
                        header('Location: /shoppingcart');
                        return;
                    } else {
                        header('Location: /products');
                        return;
                    }
                }
            }
        }

        $this->cart[] = $object;
        $this->alert = [
            'message' => "Added to your shopping cart successfully",
            'type' => "success",
        ];

        header('Location: /products');
    }

    public function remove()
    {
        $code = $_POST["code"];

        foreach ($this->cart as $key => $value)
        {
            if ($value->code === $code)
            {
                if (isset($_POST["shoppingcart"]))
                {
                    if ($value->items === 1)
                    {
                        $this->items -= 1;
                        $this->subtotal -= $value->price;

                        unset($this->cart[$key]);
                        break;
                    } else {
                        $value->items -= 1;
                        $value->subtotal -= $value->price;

                        $this->items -= 1;
                        $this->subtotal -= $value->price;
                        break;
                    }
                } else {
                    $this->items -= $value->items;
                    $this->subtotal -= $value->subtotal;

                    unset($this->cart[$key]);
                    break;
                }
            }
        }

        if (empty($this->cart))
        {
            Session::clear();
        }

        $this->discount = ($this->subtotal * 5) / 100;

        if ($this->shippingOption === "ups")
        {
            $this->totalBeforeTax = $this->subtotal - $this->discount + 5;
        } else {
            $this->totalBeforeTax = $this->subtotal - $this->discount;
        }

        $this->tax = ($this->totalBeforeTax * 3) / 100;
        $this->total = $this->totalBeforeTax + $this->tax;

        $this->alert = [
            'message' => "Removed from your shopping cart successfully",
            'type' => "success",
        ];

        header('Location: /shoppingcart');
    }

    public function clear()
    {
        Session::clear();
        header('Location: /shoppingcart');
    }

    public function checkout()
    {
        return View::show("checkout", ['cart' => $this->cart, 'title' => 'Checkout']);
    }

    public function shippingOption()
    {
        $option = $_POST["shippingOption"];

        switch ($option)
        {
        case "pickup":
            $this->shippingOption = $option;
            $this->totalBeforeTax = $this->subtotal - $this->discount;
            $this->tax = ($this->totalBeforeTax * 3) / 100;
            $this->total = $this->totalBeforeTax + $this->tax;
            break;
        case "ups":
            $this->shippingOption = $option;
            $this->totalBeforeTax = $this->subtotal - $this->discount + 5;
            $this->tax = ($this->totalBeforeTax * 3) / 100;
            $this->total = $this->totalBeforeTax + $this->tax;
            break;
        default:
            ExceptionHandler::defaultRequestHandler("Shipping option \"$option\" is not allowed", "405 Shipping Option Not Allowed");
            break;
        }

        $data = [
            'shippingOption' => "$this->shippingOption",
            'totalBeforeTax' => $this->totalBeforeTax,
            'tax' => $this->tax,
            'total' => $this->total
        ];

        echo json_encode($data);
        return;
    }

    public function logout()
    {
        session_destroy();
        header('Location: /');
    }
}
