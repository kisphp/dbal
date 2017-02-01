<?php

namespace Test\Kisphp;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

abstract class Connection
{
    /**
     * @return array
     */
    protected static function createConfigParams()
    {
        $content = file_get_contents(__DIR__ . '/../config.json');

        return json_decode($content, true);
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public static function create()
    {
        $config = new Configuration();
        $connectionParams = static::createConfigParams();

        return DriverManager::getConnection($connectionParams, $config);
    }
}
