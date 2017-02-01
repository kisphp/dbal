# Simple MySQL Database Access Layer

[![Build Status](https://travis-ci.org/kisphp/dbal.svg?branch=master)](https://travis-ci.org/kisphp/dbal)
[![codecov](https://codecov.io/gh/kisphp/dbal/branch/master/graph/badge.svg)](https://codecov.io/gh/kisphp/dbal)

## Installation

Run in terminal

```sh
composer require kisphp/dbal: *
```

## Connect to database

```php
<?php

require_once 'path/to/vendor/autoload.php';

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Kisphp\Db\Database;
use Kisphp\Db\DatabaseLog;

$config = new Configuration();
$connectionParams = [
    'driver' => 'pdo_mysql',
    'host' => 'localhost',
    'dbname' => 'test',
    'user' => 'root',
    'password' => '',
];

$connection = DriverManager::getConnection($connectionParams, $config);

$db = new Database($connection, new DatabaseLog());
```

## Database Insert

> `$db->insert('table_name', 'data array');`

If you need `INSERT IGNORE` syntax, then pass `true` for the third parameter

```php
$db->insert('test_table', [
    'column_1' => 'value_1',
    'column_2' => 'value_2',
]);

// will return last_insert_id

$insertIgnore = true;
$db->insert(
    'test_table',
    [
        'column_1' => 'value_1',
        'column_2' => 'value_2',
    ],
    $insertIgnore
);
// will execute INSERT IGNORE ...

```

## Database update

> `$db->update('table_name', 'data array', 'condition value', 'column name (default=id)');`

```php
$db->update('test_table', [
    'column_1' => 'value_1',
    'column_2' => 'value_2',
], 20);

// will return affected_rows
```


## Get single value

```php
$value = $db->getValue("SELECT column_1 FROM test_table");
```

## Get pairs 

```php
$pairs = $db->getPairs("SELECT id, column_1 FROM test_table");

/*
will result
$pairs = [
     '1' => 'c1.1',
     '2' => 'c2.1',
     '3' => 'c3.1',
];
*/
```

## Get Custom query
 

```php
$query = $db->query("SELECT * FROM test_table ");

while ($item = $query->fetch(\PDO::FETCH_ASSOC)) {
    var_dump($item);
}
```
