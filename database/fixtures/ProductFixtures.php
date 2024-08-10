<?php
declare(strict_types=1);

namespace Database\Fixtures;

use App\Model\Product;

class ProductFixtures extends Product
{
    public function __construct()
    {
        $this->load();
    }

    public function load(): void
    {
        $product = new Product();
        $product->setCode("ex-200");
        $product->setName("EX-200");
        $product->setImage("/build/images/EX-200.png");
        $product->setPrice(300);
        $product->setDescription("LTD EXP-200");
        $product->setCreatedAt(new \DateTime());
        $product->setUpdatedAt(new \DateTime());
        $product->addProduct();

        $product->setCode("ex-401");
        $product->setName("EX-401");
        $product->setImage("/build/images/EX-401.png");
        $product->setPrice(350);
        $product->setDescription("LTD EXP-401");
        $product->setCreatedAt(new \DateTime());
        $product->setUpdatedAt(new \DateTime());
        $product->addProduct();

        $product->setCode("f-200");
        $product->setName("F-200");
        $product->setImage("/build/images/F-200.png");
        $product->setPrice(400);
        $product->setDescription("LTD F-200");
        $product->setCreatedAt(new \DateTime());
        $product->setUpdatedAt(new \DateTime());
        $product->addProduct();

        $product->setCode("sn-1000fr");
        $product->setName("SN-1000FR");
        $product->setImage("/build/images/SN-1000FR.png");
        $product->setPrice(300);
        $product->setDescription("LTD 1000");
        $product->setCreatedAt(new \DateTime());
        $product->setUpdatedAt(new \DateTime());
        $product->addProduct();

        $product->setCode("m-blackmetal");
        $product->setName("M-BLACK METAL");
        $product->setImage("/build/images/M-BLACK_METAL.png");
        $product->setPrice(475);
        $product->setDescription("LTD BLACK METAL");
        $product->setCreatedAt(new \DateTime());
        $product->setUpdatedAt(new \DateTime());
        $product->addProduct();

        $product->setCode("espmystiquectm");
        $product->setName("ESP MYSTIQUE CTM");
        $product->setImage("/build/images/ESP_MYSTIQUE_CTM.png");
        $product->setPrice(500);
        $product->setDescription("ESP Original Mystique");
        $product->setCreatedAt(new \DateTime());
        $product->setUpdatedAt(new \DateTime());
        $product->addProduct();

        $product->setCode("espfrxctm");
        $product->setName("ESP FRX CTM");
        $product->setImage("/build/images/ESP_FRX_CTM.png");
        $product->setPrice(550);
        $product->setDescription("ESP Original FRX");
        $product->setCreatedAt(new \DateTime());
        $product->setUpdatedAt(new \DateTime());
        $product->addProduct();

        $product->setCode("e-iiviper");
        $product->setName("E-II VIPER");
        $product->setImage("/build/images/E-II_VIPER.png");
        $product->setPrice(300);
        $product->setDescription("ESP Viper");
        $product->setCreatedAt(new \DateTime());
        $product->setUpdatedAt(new \DateTime());
        $product->addProduct();

        $product->setCode("e-iit-b7baritone");
        $product->setName("E-II T-B7 BARITONE");
        $product->setImage("/build/images/E-II_T-B7_BARITONE.png");
        $product->setPrice(450);
        $product->setDescription("ESP E-II T-B7");
        $product->setCreatedAt(new \DateTime());
        $product->setUpdatedAt(new \DateTime());
        $product->addProduct();

        $product->setCode("e-iiecplisedb");
        $product->setName("E-II ECLIPSE DB");
        $product->setImage("/build/images/E-II_ECLIPSE_DB.png");
        $product->setPrice(500);
        $product->setDescription("ESP E-II ECLIPSE DB");
        $product->setCreatedAt(new \DateTime());
        $product->setUpdatedAt(new \DateTime());
        $product->addProduct();
    }
}
