<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\DBConnection;
use App\Core\ExceptionHandler;

class Product extends DBConnection
{
    private ?\PDO $conn;
    private \PDOStatement $stmt;
    private string $code;
    private string $name;
    private string $image;
    private float $price;
    private string $description;
    private \DateTimeInterface $createdAt;
    private \DateTimeInterface $updatedAt;

    public function __construct()
    {
        $this->conn = (new DBConnection)->connect();
    }

    protected function get(): array
    {
        $this->stmt = $this->conn->prepare("SELECT * FROM product");
        $this->stmt->execute();

        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    protected function getByCode(string $code): ?object
    {
        $this->stmt = $this->conn->prepare("SELECT * FROM product WHERE code = '$code'");
        $this->stmt->execute();
        $result = $this->stmt->fetch(\PDO::FETCH_OBJ);

        if ($result === false)
        {
            return NULL;
        }
        return $result;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
        return;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
        return;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
        return;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
        return;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
        return;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
        return;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
        return;
    }

    protected function addProduct(): void
    {
        $createdAt = $this->createdAt->format('Y-m-d H:i:s');
        $updatedAt = $this->updatedAt->format('Y-m-d H:i:s');

        $this->stmt = $this->conn->prepare("INSERT INTO product (code, name, image, price, description, created_at, updated_at) VALUES (:code, :name, :image, :price, :description, :created_at, :updated_at)");
        $this->stmt->bindParam(':code', $this->code);
        $this->stmt->bindParam(':name', $this->name);
        $this->stmt->bindParam(':image', $this->image);
        $this->stmt->bindParam(':price', $this->price);
        $this->stmt->bindParam(':description', $this->description);
        $this->stmt->bindParam(':created_at', $createdAt);
        $this->stmt->bindParam(':updated_at', $updatedAt);

        $this->stmt->execute();
    }
}
