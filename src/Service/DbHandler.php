<?php

namespace App\Service;

use App\Entity\DTO\DbDataDTO;
use PDO;

class DbHandler
{
    const DB_FILENAME = 'db.json';
    private $connection;
    private $query;
    private DataHandler $dataHandler;

    public function __construct()
    {
        $this->dataHandler = new DataHandler();
    }

    private function connect(): PDO
    {
        if (!$this->connection) {
            /** @var DbDataDTO $dbData */
            $dbData = $this->dataHandler->readJson(self::DB_FILENAME);
            $pdoRequest = "mysql:host={$dbData->host};dbname={$dbData->dbName};charset=utf8";
            try {
                $this->connection = new \PDO($pdoRequest, $dbData->userName, $dbData->password);
            } catch (\PDOException $e) {
                die ($e->getMessage());
            }
        }
        return $this->connection;
    }

    public function query(string $sql, array $parameters, string $class = null)
    {
        $this->query = ($this->connect())->prepare($sql);
        if ($class) $this->query->setFetchMode(PDO::FETCH_CLASS, $class);
        $this->query->execute($parameters);

        return $this->query->fetchAll();
    }

    public function getError(): ?string
    {
        if (!$this->query) return null;
        $error = $this->query->errorInfo();

        return $error[0] === PDO::ERR_NONE ? null : $error[2];
    }

}