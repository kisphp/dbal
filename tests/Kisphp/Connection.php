<?php

namespace Test\Kisphp;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

abstract class Connection
{
    protected function createConfigParams()
    {
        $content = file_get_contents(__DIR__ . '/../config.php');

        return json_decode($content);
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public static function create()
    {
        $config = new Configuration();
        $connectionParams = [
            'dbname' => 'copiatoare',
            'user' => 'developer',
            'password' => 'develop123',
            'host' => 'localhost',
            'driver' => 'pdo_mysql',
        ];

        return DriverManager::getConnection($connectionParams, $config);
    }
}
