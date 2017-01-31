<?php

namespace Kisphp\Db;

use Doctrine\DBAL\Driver\Connection;

class Database
{
    /**
     * @var Connection
     */
    protected $pdo;

    /**
     * @param Connection $pdo
     */
    public function __construct(Connection $pdo)
    {
        $this->pdo = $pdo;
    }

    public function insert($tableName, array $keyValues, $forceIgnore = false)
    {
        $parameters = [];
        foreach ($keyValues as $column => $value) {
            $parameters[] = sprintf('%s = \'%s\'', $column, $value);
        }

        $query = sprintf(
            'INSERT%sINTO %s SET %s',
            ($forceIgnore === true) ? ' IGNORE ' : ' ',
            $tableName,
            implode(', ', $parameters)
        );

        $this->execute($query);
    }

    protected function execute($query)
    {
        dump($query);die;
    }
}
