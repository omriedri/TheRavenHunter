<?php

$driver = new \Aternos\Model\Driver\Mysqli\Mysqli(
    $_ENV['DB_HOST'],
    $_ENV['DB_PORT'] ?? 3306,
    $_ENV['DB_USER'],
    $_ENV['DB_PASSWORD'],
    null,
    $_ENV['DB_NAME']
);
\Aternos\Model\Driver\DriverRegistry::getInstance()->registerDriver($driver);
