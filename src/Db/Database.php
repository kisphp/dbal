<?php

namespace Kisphp\Db;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Driver\PDOStatement;

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

    /**
     * @param array $tableFields
     *
     * @return array
     */
    protected function buildStatementParameters(array $tableFields)
    {
        $parameters = array_reduce(array_keys($tableFields), function($parameters, $b){
            $parameters[] = $b . ' = :' . $b;

            return $parameters;
        });

        return $parameters;
    }

    /**
     * @param string $tableName
     * @param array $tableFields
     * @param bool|false $forceIgnore
     *
     * @return string last insert id
     */
    public function insert($tableName, array $tableFields, $forceIgnore = false)
    {
        $tableColumns = implode(', ', $this->buildStatementParameters($tableFields));

        $query = sprintf(
            "INSERT %s INTO %s SET %s",
            ($forceIgnore === true) ? 'IGNORE' : '',
            $tableName,
            $tableColumns
        );

        $stmt = $this->pdo->prepare($query);
        foreach ($tableFields as $key => $value) {
            $stmt->bindParam(':' . $key, $value);
        }

        $stmt->execute();

        return $this->pdo->lastInsertId();
    }

    /**
     * @param string $tableName
     * @param array $tableFields
     * @param array $conditions
     *
     * @return int affected rows
     */
    public function update($tableName, array $tableFields, array $conditions)
    {
        $tableColumns = implode(', ', $this->buildStatementParameters($tableFields));
        $tableConditions = implode(' AND ', $this->buildStatementParameters($conditions));

        $query = sprintf(
            "UPDATE %s SET %s WHERE %s",
            $tableName,
            $tableColumns,
            $tableConditions
        );

        $stmt = $this->pdo->prepare($query);

        $parameters = [];
        foreach ($tableFields as $key => $value) {
            $parameters[':' . $key] = $value;
        }
        foreach ($conditions as $parameterName => $parameterValue) {
            $parameters[':' . $parameterName] = $parameterValue;
        }

        $stmt->execute($parameters);

        return $stmt->rowCount();
    }

    /**
     * @param string $query
     * @param array $conditions
     *
     * @return array
     */
    public function getPairs($query, array $conditions = [])
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($conditions);

        $output = [];
        foreach ($stmt->fetchAll(\PDO::FETCH_NUM) as $row) {
            $output[$row[0]] = $row[1];
        }

        return $output;
    }

    /**
     * @param string $query
     * @param array $conditions
     *
     * @return mixed
     */
    public function getValue($query, array $conditions = [])
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($conditions);

        $row = $stmt->fetch(\PDO::FETCH_NUM);

        return $row[0];
    }

    /**
     * @param string $query
     * @param array $conditions
     *
     * @return array
     */
    public function getRow($query, array $conditions = [])
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($conditions);

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row;
    }

    /**
     * @param string $query
     * @param array $conditions
     *
     * @return \Doctrine\DBAL\Driver\Statement|PDOStatement
     */
    public function query($query, array $conditions = [])
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($conditions);

        return $stmt;
    }

    /**
     * @param string $query
     *
     * @return \Doctrine\DBAL\Driver\Statement
     */
    protected function execute($query)
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt;
    }
}
