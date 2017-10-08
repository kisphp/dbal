<?php

namespace Test\Kisphp;

use Kisphp\Db\Database;
use Kisphp\Db\DatabaseLog;

class DatabaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Database
     */
    protected $db;

    public function setUp()
    {
        parent::setUp();

        $dbal = Connection::create();

        $this->db = new Database($dbal, new DatabaseLog());

        $this->importSchema();
    }

    public function tearDown()
    {
        $this->db->query("DROP TABLE test_table");
    }

    public function test_insert()
    {
        $insertedId = $this->db->insert('test_table', [
            'column_1' => 'value_1',
            'column_2' => 'value_2',
        ]);

        $lastQuery = $this->db->getLastQuery();

        $this->assertGreaterThan(0, $insertedId);
        $this->assertSame(
            'INSERT INTO test_table SET column_1 = :column_1, column_2 = :column_2',
            $lastQuery['sql']
        );

        $this->assertEquals(0, $lastQuery['error_code']);

        $res = $this->db->getRow("SELECT * FROM text_table WHERE id = " . $insertedId);

        $this->asseerSame([
            'id' => $insertedId,
            'column_1' => 'value_1',
            'column_2' => 'value_2',
            'column_3' => '',
        ], $res);
    }

    public function _test_insert_ignore()
    {
        $this->db->insert(
            'test_table',
            [
                'column_1' => 'value_1',
                'column_2' => 'value_2',
            ],
            true
        );

        $lastQuery = $this->db->getLastQuery();

        $this->assertSame(
            'INSERT IGNORE INTO test_table SET column_1 = :column_1, column_2 = :column_2',
            $lastQuery['sql']
        );

        $this->assertEquals(0, $lastQuery['error_code']);
    }

    public function _test_update_simple_value()
    {
        $this->db->update('test_table', [
            'column_1' => 'value_1',
            'column_2' => 'value_2',
        ], [
            'id' => 1,
        ]);

        $lastQuery = $this->db->getLastQuery();

        $this->assertSame(
            'UPDATE test_table SET column_1 = :column_1, column_2 = :column_2 WHERE id = :id',
            $lastQuery['sql']
        );

        $this->assertEquals(0, $lastQuery['error_code']);
    }

    public function _test_update_simple_value_on_other_column()
    {
        $this->db->update('test_table', [
            'column_1' => 'value_1',
            'column_2' => 'value_2',
        ], [
            'column_3' => 'c3.1',
        ]);

        $lastQuery = $this->db->getLastQuery();

        $this->assertSame(
            'UPDATE test_table SET column_1 = :column_1, column_2 = :column_2 WHERE column_3 = :column_3',
            $lastQuery['sql']
        );

        $this->assertEquals(0, $lastQuery['error_code']);
    }

    public function _test_get_value()
    {
        $value = $this->db->getValue("SELECT column_1 FROM test_table");

        $this->assertSame('c1.1', $value);
    }

    public function _test_get_pairs()
    {
        $pairs = $this->db->getPairs("SELECT id, column_1 FROM test_table");

        $this->assertSame([
            1 => 'c1.1',
            2 => 'c2.1',
            3 => 'c3.1',
        ], $pairs);
    }

    public function _test_select()
    {
        $a = $this->db->query("SELECT * FROM test_table ");

        while ($b = $a->fetch(\PDO::FETCH_ASSOC)) {
            $this->assertGreaterThan(2, count($b));
        }
    }

    private function importSchema()
    {
        $sqlFile = dirname(__DIR__) . '/fixtures/import.sql';
        $queries = explode(';', file_get_contents($sqlFile));

        foreach ($queries as $query) {
            $sql = trim($query);

            if (empty($sql)) {
                continue;
            }

            $this->db->query($sql);
        }
    }
}
