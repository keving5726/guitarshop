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

        if ($this->driver === "pgsql")
        {
            $this->conn = (new DBConnection)->connect();
            $this->pgsqlMigration();
        }
        elseif ($this->driver === "mysql")
        {
            $this->conn = (new DBConnection)->connect();
            $this->mysqlMigration();
        }
        else
        {
            echo "Migration can only be executed safely on 'mysql' or 'pgsql'.";
            echo "\n";
        }
    }

    public function pgsqlMigration(): void
    {
        try{
            $this->sql = "SET statement_timeout = 0;
            SET lock_timeout = 0;
            SET idle_in_transaction_session_timeout = 0;
            SET client_encoding = 'UTF8';
            SET standard_conforming_strings = on;
            SELECT pg_catalog.set_config('search_path', '', false);
            SET check_function_bodies = false;
            SET xmloption = content;
            SET client_min_messages = warning;
            SET row_security = off;
            SET default_tablespace = '';
            SET default_table_access_method = heap;

            CREATE TABLE public.average_rating (
                id integer NOT NULL,
                code character varying(45) NOT NULL,
                vote_1 integer DEFAULT 0 NOT NULL,
                vote_2 integer DEFAULT 0 NOT NULL,
                vote_3 integer DEFAULT 0 NOT NULL,
                vote_4 integer DEFAULT 0 NOT NULL,
                vote_5 integer DEFAULT 0 NOT NULL,
                average real DEFAULT 0 NOT NULL
            );

            ALTER TABLE public.average_rating OWNER TO $this->username;

            CREATE SEQUENCE public.average_rating_id_seq
                AS integer
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;

            ALTER TABLE public.average_rating_id_seq OWNER TO $this->username;
            ALTER SEQUENCE public.average_rating_id_seq OWNED BY public.average_rating.id;

            CREATE TABLE public.product (
                id integer NOT NULL,
                code character varying(45) NOT NULL,
                name character varying(45) NOT NULL,
                image character varying(100) NOT NULL,
                price numeric NOT NULL,
                description character varying(45) NOT NULL,
                created_at timestamp without time zone NOT NULL,
                updated_at timestamp without time zone NOT NULL
            );

            ALTER TABLE public.product OWNER TO $this->username;

            CREATE SEQUENCE public.products_id_seq
                AS integer
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;

            ALTER TABLE public.products_id_seq OWNER TO $this->username;
            ALTER SEQUENCE public.products_id_seq OWNED BY public.product.id;
            ALTER TABLE ONLY public.average_rating ALTER COLUMN id SET DEFAULT nextval('public.average_rating_id_seq'::regclass);
            ALTER TABLE ONLY public.product ALTER COLUMN id SET DEFAULT nextval('public.products_id_seq'::regclass);
            ALTER TABLE ONLY public.average_rating
                ADD CONSTRAINT average_rating_pkey PRIMARY KEY (id);
            ALTER TABLE ONLY public.product
                ADD CONSTRAINT code_unique UNIQUE (code);
            ALTER TABLE ONLY public.product
                ADD CONSTRAINT products_pkey PRIMARY KEY (id);

            CREATE INDEX fki_code_fkey ON public.average_rating USING btree (code);

            ALTER TABLE ONLY public.average_rating
                ADD CONSTRAINT code_fkey FOREIGN KEY (code) REFERENCES public.product(code) ON UPDATE RESTRICT ON DELETE RESTRICT;";

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
        try{
            $this->sql = "DROP TABLE IF EXISTS `product`;
            CREATE TABLE `product` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(45) NOT NULL,
                `name` varchar(45) NOT NULL,
                `image` varchar(45) NOT NULL,
                `price` float NOT NULL,
                `description` varchar(45) NOT NULL,
                `created_at` datetime NOT NULL,
                `updated_at` datetime NOT NULL,
                PRIMARY KEY (`id`,`code`),
                UNIQUE KEY `id_UNIQUE` (`id`),
                UNIQUE KEY `code_UNIQUE` (`code`)
            ) AUTO_INCREMENT = 1;

            DROP TABLE IF EXISTS `average_rating`;
            CREATE TABLE `average_rating` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `code` varchar(45) NOT NULL,
                `vote_1` int(11) NOT NULL DEFAULT '0',
                `vote_2` int(11) NOT NULL DEFAULT '0',
                `vote_3` int(11) NOT NULL DEFAULT '0',
                `vote_4` int(11) NOT NULL DEFAULT '0',
                `vote_5` int(11) NOT NULL DEFAULT '0',
                `average` float NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`),
                UNIQUE KEY `id_UNIQUE` (`id`),
                KEY `fk_code_idx` (`code`),
                KEY `average_idx` (`average`),
                CONSTRAINT `fk_code` FOREIGN KEY (`code`) REFERENCES `product` (`code`) ON DELETE RESTRICT ON UPDATE RESTRICT
            ) AUTO_INCREMENT = 1;";


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
