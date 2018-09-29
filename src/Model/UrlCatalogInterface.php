<?php

declare(strict_types=1);

namespace Autowp\Mitrofanova\Model;

use PDO;

/**
 * Just for demonstration of possible change of storage engine without
 * rebuild whole code
 */
interface UrlCatalogInterface
{
    const ID_LENGTH = 5;

    const ALPHABET = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public function getIDByURL(string $url): string;

    public function getURLByID(string $id): string;
}
