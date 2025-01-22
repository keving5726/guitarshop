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
    private ?int $total;
    private ?array $alert;

    public function __construct()
    {
        $this->cart = &$_SESSION["cart"];
        $this->quantity = &$_SESSION["quantity"];
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
        $object->total = $product->price * $object->quantity;

        $this->quantity += $object->quantity;
        $this->total += $object->total;

        if ($this->cart !== NULL)
        {
            foreach ($this->cart as $value)
            {
                if ($value->code === $product->code)
                {
                    $value->quantity += $object->quantity;
                    $value->total += $object->total;
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
                        $this->total -= $value->price;

                        unset($this->cart[$key]);
                        break;
                    } else {
                        $value->quantity -= 1;
                        $value->total -= $value->price;

                        $this->quantity -= 1;
                        $this->total -= $value->price;
                        break;
                    }
                } else {
                    $this->quantity -= $value->quantity;
                    $this->total -= $value->total;

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

    public function logout()
    {
        session_destroy();
        header('Location: /');
    }
}
