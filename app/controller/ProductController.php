<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Product;
use App\Model\AverageRating;
use App\Core\View;
use App\Core\ExceptionHandler;

class ProductController extends Product implements iController
{
    public function index()
    {
        $products = (new Product())->get();
        return View::show("products", ['products' => $products, 'title' => 'Products']);
    }

    public function new()
    {
    }

    public function show(string $code)
    {
        $product = (new Product())->getByCode($code);
        if ($product === NULL)
        {
            ExceptionHandler::defaultRequestHandler("The product does not exist");
        }
        $averageRating = (new AverageRating())->getByCode($code);
        return View::show("products.show", ['product' => $product, 'average' => $averageRating, 'title' => "Product Details"]);
    }

    public function edit()
    {
    }

    public function delete()
    {
    }

    public function rating(string $code)
    {
        if (!empty($_SESSION["rating"]))
        {
            foreach ($_SESSION["rating"] as $value)
            {
                if ($value === $code)
                {
                    $alert = [
                        'message' => "You have already voted for this product",
                        'type' => "warning",
                    ];
                    echo json_encode($alert);
                    return;
                }
            }
        }

        $vote = (int) $_POST["vote"];
        $getAverageRating = (new AverageRating())->getByCode($code);

        if ($getAverageRating === NULL)
        {
            ExceptionHandler::defaultRequestHandler("The product does not exist");
        }

        $averageRating = new AverageRating();

        for ($i = 1; $i <= 5; $i++)
        {
            $set = "setVote$i";
            $result = "vote_$i";
            if ($vote === $i)
            {
                $averageRating->$set($getAverageRating->$result + 1);
            }
            else
            {
                $averageRating->$set($getAverageRating->$result);
            }
        }
        $averageRating->setAverage();
        $averageRating->updateAverageRating($code, $vote);

        $_SESSION["rating"][] = $code;

        echo json_encode((new AverageRating())->getByCode($code));
        return;
    }
}
