<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Product;
use App\Core\View;
use App\Core\Session;

class ShoppingCartController extends Product implements iShoppingCart
{
    private ?array $cart;
    private ?int $quantity;
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
        $this->quantity = &$_SESSION["quantity"];
        $this->subtotal = &$_SESSION["subtotal"];
        $this->discount = &$_SESSION["discount"];
        $this->shippingOption = &$_SESSION["shipppingOption"];
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
        $quantity = empty($_POST["quantity"]) ? 1 : $_POST["quantity"];
        $code = $_POST["code"];

        if (!is_numeric($quantity))
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
            if ($quantity < 1)
            {
                $quantity = 1;
            }
        }

        $product = (new Product())->getByCode($code);
        $object = new \stdClass;
        $object->code = $product->code;
        $object->name = $product->name;
        $object->price = $product->price;
        $object->quantity = $quantity;
        $object->subtotal = $product->price * $object->quantity;

        $this->quantity += $object->quantity;
        $this->subtotal += $object->subtotal;
        $this->shippingOption = "pickup";
        $this->discount = ($this->subtotal * 5) / 100;
        $this->totalBeforeTax = $this->subtotal - $this->discount;
        $this->tax = ($this->totalBeforeTax * 3) / 100;
        $this->total = $this->totalBeforeTax + $this->tax;

        if ($this->cart !== NULL)
        {
            foreach ($this->cart as $value)
            {
                if ($value->code === $product->code)
                {
                    $value->quantity += $object->quantity;
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
                    if ($value->quantity === 1)
                    {
                        $this->quantity -= 1;
                        $this->subtotal -= $value->price;

                        unset($this->cart[$key]);
                        break;
                    } else {
                        $value->quantity -= 1;
                        $value->subtotal -= $value->price;

                        $this->quantity -= 1;
                        $this->subtotal -= $value->price;
                        break;
                    }
                } else {
                    $this->quantity -= $value->quantity;
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
