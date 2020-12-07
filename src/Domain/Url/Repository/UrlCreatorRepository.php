<?php

namespace App\Domain\Url\Repository;

use PDO;

/**
 * Repository.
 */
class UrlCreatorRepository
{
    /**
     * @var PDO The database connection
     */
    private $connection;

    /**
     * Constructor.
     *
     * @param PDO $connection The database connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Insert url row.
     *
     * @param array $url The url
     *
     * @return int The new ID
     */
    public function insertUrl(array $url): int
    {
        $row = [
            'urlname' => $url['urlname'],
            'first_name' => $url['first_name'],
            'last_name' => $url['last_name'],
            'email' => $url['email'],
        ];

        $sql = "INSERT INTO urls SET 
                urlname=:urlname, 
                first_name=:first_name, 
                last_name=:last_name, 
                email=:email;";

        $this->connection->prepare($sql)->execute($row);

        return (int)$this->connection->lastInsertId();
    }
}