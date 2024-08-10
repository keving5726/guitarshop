<?php
declare(strict_types=1);

namespace Database\Fixtures;

use App\Model\Product;
use App\Model\AverageRating;

class AverageRatingFixtures extends Product
{
    public function __construct()
    {
        $this->load();
    }

    public function load(): void
    {
        $products = (new Product())->get();

        foreach ($products as $value)
        {
            $averageRating = new AverageRating();
            $averageRating->setCode($value->code);
            for ($i = 1; $i <= 5; $i++)
            {
                $vote = "setVote$i";
                $averageRating->$vote(rand(1, 5));
            }
            $averageRating->setAverage();
            $averageRating->addAverageRating();
        }
    }
}
