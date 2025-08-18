<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Product;
use App\Core\View;
use App\Core\Session;
use App\Core\ExceptionHandler;

class ShoppingCartController extends Product implements iShoppingCart
{
    private ?array $cart;
    private string $items;
    private string $subtotal;
    private string $discount;
    private ?string $shippingOption;
    private string $totalBeforeTax;
    private string $tax;
    private string $total;
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

    public function index(): ?View
    {
        return View::show("shoppingcart", ['cart' => $this->cart, 'title' => 'Shopping Cart']);
    }

    public function add(): void
    {
        $items = empty($_POST["items"]) ? "1" : $_POST["items"];
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

        $product = (new Product())->getByCode($code);
        $object = new \stdClass;
        $object->code = $product->code;
        $object->name = $product->name;
        $object->price = $product->price;
        $object->items = $items;
        $object->subtotal = bcmul($product->price, $object->items, 2);

        $this->items = bcadd($this->items, $object->items);
        $this->subtotal = bcadd($this->subtotal, $object->subtotal, 2);
        $this->shippingOption = empty($this->shippingOption) ? "Pick up" : $this->shippingOption;
        $this->discount = bcmul($this->subtotal, "5", 2);
        $this->discount = bcdiv($this->discount, "100", 2);
        $this->calculateTotal();

        if ($this->cart !== NULL)
        {
            foreach ($this->cart as $value)
            {
                if ($value->code === $product->code)
                {
                    $value->items = bcadd($value->items, $object->items);
                    $value->subtotal = bcadd($value->subtotal, $object->subtotal, 2);
                    $this->alert = [
                        'message' => "Added to your shopping cart successfully",
                        'type' => "success",
                    ];

                    if (isset($_POST["shoppingcart"]))
                    {
                        header('Location: /shoppingcart');
                        return;
                    }
                    else
                    {
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
        return;
    }

    public function remove(): void
    {
        $code = $_POST["code"];

        foreach ($this->cart as $key => $value)
        {
            if ($value->code === $code)
            {
                if (isset($_POST["shoppingcart"]))
                {
                    if ($value->items === "1")
                    {
                        $this->items = bcsub($this->items, "1");
                        $this->subtotal = bcsub($this->subtotal, $value->price, 2);

                        unset($this->cart[$key]);
                        break;
                    }
                    else
                    {
                        $value->items = bcsub($value->items, "1");
                        $value->subtotal = bcsub($value->subtotal, $value->price, 2);

                        $this->items = bcsub($this->items, "1");
                        $this->subtotal = bcsub($this->subtotal, $value->price, 2);
                        break;
                    }
                }
                else
                {
                    $this->items = bcsub($this->items, $value->items);
                    $this->subtotal = bcsub($this->subtotal, $value->subtotal, 2);

                    unset($this->cart[$key]);
                    break;
                }
            }
        }

        if (empty($this->cart))
        {
            Session::clear();
            $this->alert = [
                'message' => "Removed from your shopping cart successfully",
                'type' => "success",
            ];
            header('Location: /shoppingcart');
            return;
        }

        $this->discount = bcmul($this->subtotal, "5", 2);
        $this->discount = bcdiv($this->discount, "100", 2);
        $this->calculateTotal();

        $this->alert = [
            'message' => "Removed from your shopping cart successfully",
            'type' => "success",
        ];

        header('Location: /shoppingcart');
        return;
    }

    public function clear(): void
    {
        Session::clear();
        $this->alert = [
            'message' => "Your shopping cart was successfully cleared",
            'type' => "success",
        ];
        header('Location: /shoppingcart');
        return;
    }

    public function checkout(): ?View
    {
        return View::show("checkout", ['cart' => $this->cart, 'title' => 'Checkout']);
    }

    public function shippingOption(): void
    {
        $this->shippingOption = $_POST["shippingOption"];
        $this->calculateTotal();

        $data = [
            'shippingOption' => "$this->shippingOption",
            'totalBeforeTax' => $this->totalBeforeTax,
            'tax' => $this->tax,
            'total' => $this->total
        ];

        echo json_encode($data);
        return;
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: /');
        return;
    }

    public function calculateTotal(): ?ExceptionHandler
    {
        switch ($this->shippingOption)
        {
        case "Pick up":
            $this->totalBeforeTax = bcsub($this->subtotal, $this->discount, 2);
            break;
        case "UPS":
            $this->totalBeforeTax = bcsub($this->subtotal, $this->discount, 2);
            $this->totalBeforeTax = bcadd($this->totalBeforeTax, "5", 2);
            break;
        default:
            ExceptionHandler::defaultRequestHandler("Shipping option \"$this->shippingOption\" is not allowed", "405 Shipping Option Not Allowed");
            break;
        }

        $this->tax = bcmul($this->totalBeforeTax, "3", 2);
        $this->tax = bcdiv($this->tax, "100", 2);
        $this->total = bcadd($this->totalBeforeTax, $this->tax, 2);
        return null;
    }
}
