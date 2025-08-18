<?php
declare(strict_types=1);

namespace Database\Migrations;

use App\Core\DBConnection;

class DBMigrations extends DBConnection
{
    private ?\PDO $conn;
    private array $config;
    private string $driver;
    private string $username;
    private string $sql;

    public function __construct()
    {
        $this->config = require __DIR__.'/../../config/database.php';
        $this->driver = $this->config["driver"];
        $this->username = $this->config["username"];
        $this->conn = (new DBConnection)->connect();

        if ($this->driver === "pgsql")
        {
            $this->pgsqlMigration();
        }
        elseif ($this->driver === "mysql")
        {
            $this->mysqlMigration();
        }
        elseif ($this->driver === "sqlite")
        {
            $this->sqliteMigration();
        }
        else
        {
            echo "Migration can only be executed safely on 'mysql', 'pgsql' or 'sqlite'.";
            echo "\n";
        }
    }

    public function pgsqlMigration(): void
    {
        try{
            $this->sql = "DROP TABLE IF EXISTS public.average_rating;
            DROP SEQUENCE IF EXISTS public.average_rating_id_seq;
            DROP TABLE IF EXISTS public.product;
            DROP SEQUENCE IF EXISTS public.products_id_seq;

            CREATE TABLE public.product (
                id serial PRIMARY KEY,
                code character varying(50) NOT NULL UNIQUE,
                name character varying(100) NOT NULL,
                image character varying(255) NOT NULL,
                price decimal(10,2) NOT NULL,
                description text NOT NULL,
                created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
                updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE public.average_rating (
                id serial PRIMARY KEY,
                code character varying(50) NOT NULL,
                vote_1 integer DEFAULT 0,
                vote_2 integer DEFAULT 0,
                vote_3 integer DEFAULT 0,
                vote_4 integer DEFAULT 0,
                vote_5 integer DEFAULT 0,
                average numeric(3,2) DEFAULT 0.00,
                FOREIGN KEY (code) REFERENCES public.product (code)
            );";

            $this->conn->exec($this->sql);
            echo "Migration executed successfully";
            echo "\n";
        }
        catch(\PDOException $e)
        {
            echo $e->getMessage();
            echo "\n";
            echo "The migration has failed";
            echo "\n";
        }
        $this->conn = NULL;
    }

    public function mysqlMigration(): void
    {
        try
        {
            $this->sql = "DROP TABLE IF EXISTS `average_rating`;
            DROP TABLE IF EXISTS `product`;

            CREATE TABLE `product` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(50) NOT NULL,
                `name` varchar(100) NOT NULL,
                `image` varchar(255) NOT NULL,
                `price` decimal(10,2) NOT NULL,
                `description` text NOT NULL,
                `created_at` timestamp NULL DEFAULT current_timestamp(),
                `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                PRIMARY KEY (`id`),
                UNIQUE KEY `code` (`code`)
            );

            CREATE TABLE `average_rating` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(50) NOT NULL,
                `vote_1` int(11) DEFAULT '0',
                `vote_2` int(11) DEFAULT '0',
                `vote_3` int(11) DEFAULT '0',
                `vote_4` int(11) DEFAULT '0',
                `vote_5` int(11) DEFAULT '0',
                `average` decimal(3,2) DEFAULT '0.00',
                PRIMARY KEY (`id`),
                KEY `code` (`code`),
                CONSTRAINT `average_rating_ibfk_1` FOREIGN KEY (`code`) REFERENCES `product` (`code`)
            );";

            $this->conn->exec($this->sql);
            echo "Migration executed successfully";
            echo "\n";
        }
        catch(\PDOException $e)
        {
            echo $e->getMessage();
            echo "\n";
            echo "The migration has failed";
            echo "\n";
        }
        $this->conn = NULL;
    }

    public function sqliteMigration(): void
    {
        try
        {
            $this->sql = "DROP TABLE IF EXISTS `average_rating`;
            DROP TABLE IF EXISTS `product`;

            CREATE TABLE product (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                code TEXT NOT NULL UNIQUE,
                name TEXT NOT NULL,
                image TEXT NOT NULL,
                price TEXT NOT NULL,
                description TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE average_rating (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                code TEXT NOT NULL,
                vote_1 INTEGER DEFAULT 0,
                vote_2 INTEGER DEFAULT 0,
                vote_3 INTEGER DEFAULT 0,
                vote_4 INTEGER DEFAULT 0,
                vote_5 INTEGER DEFAULT 0,
                average REAL DEFAULT 0.00,
                FOREIGN KEY (code) REFERENCES product (code)
            );";

            $this->conn->exec($this->sql);
            echo "Migration executed successfully";
            echo "\n";
        }
        catch(\PDOException $e)
        {
            echo $e->getMessage();
            echo "\n";
            echo "The migration has failed";
            echo "\n";
        }
        $this->conn = NULL;
    }
}
