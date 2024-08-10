<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\DBConnection;
use App\Core\ExceptionHandler;

class AverageRating extends DBConnection
{
    private ?\PDO $conn;
    private \PDOStatement $stmt;
    private string $code;
    private int $vote1;
    private int $vote2;
    private int $vote3;
    private int $vote4;
    private int $vote5;
    private float $average;

    public function __construct()
    {
        $this->conn = (new DBConnection)->connect();
    }

    public function get(): array
    {
        $this->stmt = $this->conn->prepare("SELECT * FROM average_rating");
        $this->stmt->execute();

        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getByCode(string $code): ?object
    {
        $this->stmt = $this->conn->prepare("SELECT * FROM average_rating WHERE code = '$code'");
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

    public function setVote1(int $vote): void
    {
        $this->vote1 = $vote;
        return;
    }

    public function setVote2(int $vote): void
    {
        $this->vote2 = $vote;
        return;
    }

    public function setVote3(int $vote): void
    {
        $this->vote3 = $vote;
        return;
    }

    public function setVote4(int $vote): void
    {
        $this->vote4 = $vote;
        return;
    }

    public function setVote5(int $vote): void
    {
        $this->vote5 = $vote;
        return;
    }

    public function setAverage(): void
    {
        $multp = 0;
        $sum = 0;
        for ($i = 1; $i <= 5; $i++)
        {
            $vote = "vote" . $i;
            $multp += $this->$vote * $i;
            $sum += $this->$vote;
        }

        $this->average = $multp / $sum;
        $this->average = round($this->average, 2);
        return;
    }

    public function addAverageRating(): void
    {
        $this->stmt = $this->conn->prepare("INSERT INTO average_rating (code, vote_1, vote_2, vote_3, vote_4, vote_5, average) VALUES (:code, :vote1, :vote2, :vote3, :vote4, :vote5, :average)");
        $this->stmt->bindParam(':code', $this->code);
        $this->stmt->bindParam(':vote1', $this->vote1);
        $this->stmt->bindParam(':vote2', $this->vote2);
        $this->stmt->bindParam(':vote3', $this->vote3);
        $this->stmt->bindParam(':vote4', $this->vote4);
        $this->stmt->bindParam(':vote5', $this->vote5);
        $this->stmt->bindParam(':average', $this->average);

        $this->stmt->execute();
    }

    public function updateAverageRating(string $code, int $vote): void
    {
        $v = "vote$vote";
        $this->stmt = $this->conn->prepare("UPDATE average_rating SET vote_$vote = :vote, average = :average WHERE code = :code");
        $this->stmt->bindParam(':code', $code);
        $this->stmt->bindParam(':vote', $this->$v);
        $this->stmt->bindParam(':average', $this->average);

        $this->stmt->execute();
    }
}
