<?php

declare(strict_types=1);

namespace Autowp\Mitrofanova\Model;

use PDO;

class PDONumberCatalog implements NumberCatalogInterface
{
    /**
     * @var PDO
     */
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function generate(): int
    {
        $number = random_int(PHP_INT_MIN, PHP_INT_MAX);

        $stmt = $this->pdo->prepare('INSERT INTO number_catalog_item (number) VALUES (:number)');
        if ($stmt === false) {
            $info = $this->pdo->errorInfo();
            throw new \RuntimeException('Failed to prepare statement: ' . $info[2]);
        }

        $success = $stmt->execute([
            ':number' => $number
        ]);

        if (! $success) {
            throw new \RuntimeException("Failed to generate unique id");
        }

        return (int) $this->pdo->lastInsertId();
    }

    public function retreive(int $id): int
    {
        $stmt = $this->pdo->prepare('SELECT number FROM number_catalog_item WHERE id = :id');
        if ($stmt === false) {
            $info = $this->pdo->errorInfo();
            throw new \RuntimeException('Failed to prepare statement: ' . $info[2]);
        }
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row ? (int) $row['number'] : 0;
    }
}
