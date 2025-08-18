<?php
declare(strict_types=1);

namespace App\Core;

class DBConnection
{
    private array $config;
    private string $driver;
    private string $path;
    private string $host;
    private int $port;
    private string $database;
    private string $username;
    private string $password;
    private string $dsn;
    private array $options;
    private \PDO $dbh;

    public function __construct()
    {
        $this->config = require __DIR__.'/../../config/database.php';
        $this->driver = $this->config["driver"];
        $this->path = $this->config["path"];
        $this->host = $this->config["host"];
        $this->port = $this->config["port"];
        $this->database = $this->config["database"];
        $this->username = $this->config["username"];
        $this->password = $this->config["password"];

        if ($this->driver === "sqlite")
        {
            $this->dsn = "$this->driver:$this->path";
        }
        else
        {
            $this->dsn = "$this->driver:host=$this->host;port=$this->port;dbname=$this->database";
        }

        $this->options = 
            [
                \PDO::ATTR_PERSISTENT => false,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ];
    }

    protected function connect(): object
    {
        try
        {
            if ($this->driver === "sqlite")
            {
                $this->dbh = new \PDO($this->dsn, null, null, $this->options);
            }
            else
            {
                $this->dbh = new \PDO($this->dsn, $this->username, $this->password, $this->options);
            }
        }
        catch (\PDOException $e)
        {
            echo $e->getMessage();
            exit;
        }
        return $this->dbh;
    }
}
