<?php

declare(strict_types=1);

use Autowp\Mitrofanova;

include __DIR__ . '/../vendor/autoload.php';

$config = include(__DIR__ . '/../config.php');

$app = new Mitrofanova\Application($config);
$app->dispatch();
