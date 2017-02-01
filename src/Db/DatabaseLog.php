<?php

namespace Kisphp\Db;

use Doctrine\DBAL\Driver\PDOStatement;
use Doctrine\DBAL\Statement;

class DatabaseLog
{
    /**
     * @var array
     */
    protected $history = [];

    /**
     * @return array
     */
    public function getHistory(): array
    {
        return $this->history;
    }

    /**
     * @param array $history
     */
    public function setHistory(array $history)
    {
        $this->history = $history;
    }

    /**
     * @param Statement $statement
     */
    public function add(Statement $statement)
    {
        /** @var PDOStatement $pdoStatement */
        $pdoStatement = $statement->getIterator();

        $this->history[] = [
            'sql' => $pdoStatement->queryString,
            'error_code' => $pdoStatement->errorCode(),
            'error_message' => $pdoStatement->errorInfo()[2],
        ];
    }

    /**
     * @return array
     */
    public function getLastQuery()
    {
        return end($this->history);
    }
}
