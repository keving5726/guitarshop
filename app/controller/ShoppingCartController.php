<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Product;
use App\Core\View;
use App\Core\Session;
use App\Core\ExceptionHandler;

class ShoppingCartController extends Product implements iController
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

    public function new()
    {
        $_POST["quantity"] = empty($_POST["quantity"]) ? 1 : $_POST["quantity"];

        if (!is_numeric($_POST["quantity"]))
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
            if ($_POST["quantity"] < 1)
            {
                $_POST["quantity"] = 1;
            }
        }

        $product = (new Product())->getByCode($_POST["code"]);
        $object = new \stdClass;
        $object->code = $product->code;
        $object->name = $product->name;
        $object->price = $product->price;
        $object->quantity = empty($_POST["quantity"]) ? 1 : $_POST["quantity"];
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

                    header('Location: /products');
                    return;
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

    public function show(string $code)
    {
    }

    public function edit()
    {
        $code = $_POST["code"];
        foreach ($this->cart as $key => $value)
        {
            if ($value->code === $code)
            {
                $this->quantity -= $value->quantity;
                $this->total -= $value->total;
                unset($this->cart[$key]);
                break;
            }
        }

        if (empty($this->cart))
        {
            Session::clean();
        }

        $this->alert = [
            'message' => "Removed from your shopping cart successfully",
            'type' => "success",
        ];

        header('Location: /shoppingcart');
    }

    public function delete()
    {
        Session::clean();
        header('Location: /shoppingcart');
    }

    public function logout()
    {
        session_destroy();
        header('Location: /');
    }

    public function post()
    {
        switch ($_POST["_method"])
        {
            case 'POST':
                $this->new();
                break;
            case 'PUT':
                $this->edit();
                break;
            case 'DELETE':
                $this->delete();
                break;
            default:
                ExceptionHandler::defaultRequestHandler("Method \"{$_POST["_method"]}\" is not allowed", "405 Method Not Allowed");
                break;
        }
    }
}
