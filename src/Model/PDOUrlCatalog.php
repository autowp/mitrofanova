<?php

declare(strict_types=1);

namespace Autowp\Mitrofanova\Model;

use PDO;

class PDOUrlCatalog implements UrlCatalogInterface
{
    const MAX_ATTEMPTS = 10;

    /**
     * @var PDO
     */
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function normalizeURL(string $url): string
    {
        $parsed = parse_url($url);

        if ($parsed === false) {
            throw new \InvalidArgumentException("URL `$url` malformed");
        }

        $scheme   = isset($parsed['scheme']) ? strtolower($parsed['scheme']) . '://' : '';
        $host     = isset($parsed['host']) ? $parsed['host'] : '';
        $port     = isset($parsed['port']) ? ':' . $parsed['port'] : '';
        $user     = isset($parsed['user']) ? $parsed['user'] : '';
        $pass     = isset($parsed['pass']) ? ':' . $parsed['pass'] : '';
        $pass     = ($user || $pass) ? $pass . '@' : '';
        $path     = isset($parsed['path']) ? $parsed['path'] : '';
        $query    = isset($parsed['query']) ? '?' . $parsed['query'] : '';
        $fragment = isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '';

        return $scheme . $user . $pass . $host . $port . $path . $query . $fragment;
    }

    /**
     * @todo Improve validaion: prevent too long values, invalid schemes etc
     */
    public function getIDByURL(string $url): string
    {
        $url = $this->normalizeURL($url);

        $stmt = $this->pdo->prepare('SELECT id FROM url_catalog_item WHERE url = :url');
        if ($stmt === false) {
            $info = $this->pdo->errorInfo();
            throw new \RuntimeException('Failed to prepare statement: ' . $info[2]);
        }
        $stmt->execute([':url' => $url]);
        $row = $stmt->fetch();

        if ($row) {
            return $row['id'];
        }

        $stmt = $this->pdo->prepare('INSERT INTO url_catalog_item (id, url) VALUES (:id, :url)');
        if ($stmt === false) {
            $info = $this->pdo->errorInfo();
            throw new \RuntimeException('Failed to prepare statement: ' . $info[2]);
        }

        $id = null;
        $attempt = 0;

        do {
            $id = $this->generateRandomID();

            $success = $stmt->execute([
                ':id'  => $id,
                ':url' => $url
            ]);
            $attempt++;
        } while (! $success && $attempt < self::MAX_ATTEMPTS);

        if (! $success) {
            throw new \RuntimeException("Failed to generate unique id");
        }

        return $id;
    }

    /**
     * @todo Add blacklist to prevent use of possible reserved prefixes
     */
    private function generateRandomID(): string
    {
        $length = strlen(self::ALPHABET);
        $result = '';
        for ($i = 0; $i < self::ID_LENGTH; $i++) {
            $result .= self::ALPHABET[random_int(0, $length - 1)];
        }
        return $result;
    }

    public function getURLByID(string $id): string
    {
        $stmt = $this->pdo->prepare('SELECT url FROM url_catalog_item WHERE id = :id');
        if ($stmt === false) {
            $info = $this->pdo->errorInfo();
            throw new \RuntimeException('Failed to prepare statement: ' . $info[2]);
        }
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row ? $row['url'] : '';
    }
}
