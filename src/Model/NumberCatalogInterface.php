<?php

declare(strict_types=1);

namespace Autowp\Mitrofanova\Model;

use PDO;

/**
 * Just for demonstration of possible change of storage engine without
 * rebuild whole code
 */
interface NumberCatalogInterface
{
    public function generate(): int;

    public function retreive(int $id): int;
}
